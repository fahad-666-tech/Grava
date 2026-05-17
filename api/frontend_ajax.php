<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/helpers.php';
require_once __DIR__.'/../includes/mailer.php';

session_start();

$action = $_REQUEST['action'] ?? '';

/* ── AJAX: real-time email validation ── */
if ($action === 'validate_email') {
    header('Content-Type: application/json');
    $email = strtolower(trim($_POST['email'] ?? ''));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['ok'=>false,'msg'=>'Invalid email format.']); exit;
    }
    $domain = substr(strrchr($email,'@'),1);
    if (isDisposable($email)) {
        echo json_encode(['ok'=>false,'msg'=>'Disposable emails are not allowed. Please use your real email.']); exit;
    }
    // MX record check — real-time DNS validation like big companies do
    $hasMx = checkdnsrr($domain, 'MX');
    if (!$hasMx) {
        echo json_encode(['ok'=>false,'msg'=>'This email domain does not appear to exist. Please check it.']); exit;
    }
    // Duplicate check
    $conn = db();
    $chk = $conn->prepare("SELECT id FROM applications WHERE email=? LIMIT 1");
    $chk->bind_param('s',$email); $chk->execute(); $chk->store_result();
    if ($chk->num_rows > 0) {
        echo json_encode(['ok'=>false,'msg'=>'This email already has a submission.']); exit;
    }
    echo json_encode(['ok'=>true,'msg'=>'Looks good ✓']); exit;
}

/* ── AJAX: send referral invite ── */
if ($action === 'ref_invite' && $_SERVER['REQUEST_METHOD']==='POST') {
    header('Content-Type: application/json');
    $toEmail  = filter_var(trim($_POST['to_email']??''), FILTER_VALIDATE_EMAIL);
    $refCode  = preg_replace('/[^a-f0-9]/','', $_POST['ref_code']??'');
    $fromName = htmlspecialchars(trim($_POST['from_name']??'A Grava member'));
    $fromIp   = $_SERVER['REMOTE_ADDR']??'0.0.0.0';

    if (!$toEmail||strlen($refCode)!==16) {
        echo json_encode(['ok'=>false,'msg'=>'Invalid input.']); exit;
    }

    // Rate limit: max 10 invites/hour per IP
    $conn = db();
    $rlKey = 'invite_'.md5($fromIp);
    $rl = $conn->query("SELECT attempts, TIMESTAMPDIFF(SECOND,window_start,NOW()) as age FROM rate_limits WHERE action_key='$rlKey' LIMIT 1")->fetch_assoc();
    if ($rl && $rl['age'] < 3600 && $rl['attempts'] >= 10) {
        echo json_encode(['ok'=>false,'msg'=>'You have sent too many invites this hour. Please try later.']); exit;
    }

    // Prevent sending an invite to someone already in the queue
    $appChk = $conn->prepare("SELECT id FROM applications WHERE email=? LIMIT 1");
    $appChk->bind_param('s', $toEmail);
    $appChk->execute();
    $appChk->store_result();
    if ($appChk->num_rows > 0) {
        echo json_encode(['ok'=>false,'msg'=>'This email is already in the queue.']); exit;
    }

    // Self-invite guard (prevent sending an invite to the same email as the referrer)
    $stmt = $conn->prepare("SELECT email FROM applications WHERE ref_code=? LIMIT 1");
    $stmt->bind_param('s', $refCode);
    $stmt->execute();
    $ref = $stmt->get_result()->fetch_assoc();
    if (!$ref) {
        echo json_encode(['ok'=>false,'msg'=>'Invalid referral code.']); exit;
    }
    if ($ref['email'] === $toEmail) {
        echo json_encode(['ok'=>false,'msg'=>'You cannot invite your own email.']); exit;
    }

    // Duplicate invite guard
    $di = $conn->prepare("SELECT id FROM referral_invites WHERE invited_email=? AND referrer_code=? LIMIT 1");
    $di->bind_param('ss',$toEmail,$refCode); $di->execute(); $di->store_result();
    if ($di->num_rows>0) {
        echo json_encode(['ok'=>false,'msg'=>'Invite already sent to that email.']); exit;
    }

    // Save invite
    $si = $conn->prepare("INSERT INTO referral_invites (referrer_code,invited_email) VALUES (?,?)");
    $si->bind_param('ss',$refCode,$toEmail); $si->execute();

    // Update rate limit
    $conn->query("INSERT INTO rate_limits (action_key,attempts) VALUES ('$rlKey',1)
                  ON DUPLICATE KEY UPDATE attempts=IF(TIMESTAMPDIFF(SECOND,window_start,NOW())>3600,1,attempts+1),
                  window_start=IF(TIMESTAMPDIFF(SECOND,window_start,NOW())>3600,NOW(),window_start),
                  last_at=NOW()");

    $sent = sendEmail($toEmail, "$fromName referred you to Grava — priority access", buildInviteEmail($fromName, $refCode));
    echo json_encode(['ok'=>true,'sent'=>$sent]); exit;
}

if ($action === 'get_position') {
    header('Content-Type: application/json');
    $rc = preg_replace('/[^a-f0-9]/','', $_REQUEST['rc'] ?? '');
    if (strlen($rc) !== 16) {
        echo json_encode(['pos' => null]); exit;
    }
    $conn = db();
    $stmt = $conn->prepare("SELECT created_at FROM applications WHERE ref_code=? LIMIT 1");
    $stmt->bind_param('s', $rc);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    if (!$row) {
        echo json_encode(['pos' => null]); exit;
    }
    $createdAt = $row['created_at'];
    $stmt2 = $conn->prepare("SELECT COUNT(*) as c FROM applications WHERE created_at < ?");
    $stmt2->bind_param('s', $createdAt);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $count = $res2 ? (int)($res2->fetch_assoc()['c'] ?? 0) : 0;
    echo json_encode(['pos' => $count + 1]); exit;
}
