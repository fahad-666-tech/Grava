<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/mailer.php';

session_start();

/* ══ TRACK PAGE VIEW ═══════════════════════════════════════════ */
trackView();

/* ══ REFERRAL CODE FROM URL ════════════════════════════════════ */
if (isset($_GET['ref']) && preg_match('/^[a-f0-9]{16}$/', $_GET['ref'])) {
    $_SESSION['grava_ref_from'] = $_GET['ref'];
}

/* ══ FORM PROCESSING ════════════════════════════════════════════ */
$submitSuccess = false;
$submitError   = '';
$fieldErrors   = [];
$newRefCode    = '';

if (isset($_GET['ok']) && $_GET['ok'] === '1') {
    $submitSuccess = true;
    $newRefCode = preg_replace('/[^a-f0-9]/', '', $_GET['rc'] ?? '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['grava_filter'])) {

    /* ── honeypot ── */
    if (!empty($_POST['website_url'])) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?ok=1&rc=' . genCode());
        exit;
    }

    /* ── rate limit: 3 form submits/hour/IP ── */
    $ip    = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $rlKey = 'form_' . md5($ip);
    $conn  = db();
    $rl    = $conn->query("SELECT attempts, TIMESTAMPDIFF(SECOND,window_start,NOW()) as age FROM rate_limits WHERE action_key='$rlKey' LIMIT 1")->fetch_assoc();
    if ($rl && $rl['age'] < 3600 && $rl['attempts'] >= 3) {
        $submitError = 'Too many submissions from this connection. Please try again later.';
        goto render;
    }

    /* ── collect ── */
    $d = [
        'name'              => clean($_POST['name']              ?? ''),
        'phone'             => clean($_POST['phone']             ?? ''),
        'current_role'      => clean($_POST['current_role']      ?? ''),
        'q_search_engine'   => clean($_POST['q_search_engine']   ?? ''),
        'q_ai_limitation'   => clean($_POST['q_ai_limitation']   ?? ''),
        'q_changed_mind'    => clean($_POST['q_changed_mind']    ?? ''),
        'q_company_idea'    => clean($_POST['q_company_idea']    ?? ''),
        'q_opinion'         => clean($_POST['q_opinion']         ?? ''),
        'brand_1'           => clean($_POST['brand_1']           ?? ''),
        'brand_2'           => clean($_POST['brand_2']           ?? ''),
        'brand_3'           => clean($_POST['brand_3']           ?? ''),
        'brand_4'           => clean($_POST['brand_4']           ?? ''),
        'brand_5'           => clean($_POST['brand_5']           ?? ''),
        'made_link'         => cleanUrl($_POST['made_link']      ?? ''),
        'q_niche'           => clean($_POST['q_niche']           ?? ''),
        'q_grava_theory'    => clean($_POST['q_grava_theory']    ?? ''),
        'function_interest' => clean($_POST['function_interest'] ?? ''),
        'notice_period'     => clean($_POST['notice_period']     ?? ''),
        'email'             => strtolower(trim(clean($_POST['email'] ?? ''))),
        'linkedin'          => cleanUrl($_POST['linkedin']       ?? ''),
        'resume_link'       => cleanUrl($_POST['resume_link']    ?? ''),
        'fb_pricing'        => clean($_POST['fb_pricing']        ?? ''),
        'fb_features'       => clean($_POST['fb_features']       ?? ''),
        'fb_general'        => clean($_POST['fb_general']        ?? ''),
        'ip_address'        => $ip,
        'user_agent'        => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 512),
        'device_type'       => deviceType($_SERVER['HTTP_USER_AGENT'] ?? ''),
        'fingerprint'       => clean($_POST['fp'] ?? ''),
        'referred_by'       => $_SESSION['grava_ref_from'] ?? null,
    ];

    /* ── validate ── */
    if (empty($d['name']))  $fieldErrors['name']  = 'Name is required.';
    if (empty($d['email'])) $fieldErrors['email'] = 'Email is required.';
    elseif (!filter_var($d['email'], FILTER_VALIDATE_EMAIL))
        $fieldErrors['email'] = 'Enter a valid email address.';
    elseif (isDisposable($d['email']))
        $fieldErrors['email'] = 'Disposable/temporary emails are not accepted. Use your real email.';
    else {
        $domain = substr(strrchr($d['email'], '@'), 1);
        if (!checkdnsrr($domain, 'MX'))
            $fieldErrors['email'] = 'This email domain doesn\'t appear to exist. Please double-check.';
    }
    if (empty($d['function_interest'])) $fieldErrors['function_interest'] = 'Please select your function.';

    if (empty($fieldErrors)) {

        /* ── duplicate email ── */
        $chk = $conn->prepare("SELECT id FROM applications WHERE email=? LIMIT 1");
        $chk->bind_param('s', $d['email']);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $fieldErrors['email'] = 'This email already has a submission.';
        } else {

            /* ── fraud: self-referral (same IP as referrer) ── */
            $isFlagged   = 0;
            $flagReason  = null;
            if ($d['referred_by']) {
                $ref = $conn->query("SELECT ip_address, fingerprint FROM applications WHERE ref_code='" . $conn->real_escape_string($d['referred_by']) . "' LIMIT 1")->fetch_assoc();
                if ($ref) {
                    if ($ref['ip_address'] === $ip) {
                        $isFlagged  = 1;
                        $flagReason = 'same_ip_referral';
                    } elseif ($d['fingerprint'] && $d['fingerprint'] === $ref['fingerprint']) {
                        $isFlagged  = 1;
                        $flagReason = 'same_fingerprint_referral';
                    }
                }
            }
            /* ── flag duplicate IP submissions ── */
            $ipCount = $conn->query("SELECT COUNT(*) as c FROM applications WHERE ip_address='" . $conn->real_escape_string($ip) . "'")->fetch_assoc()['c'];
            if ($ipCount >= 3) {
                $isFlagged = 1;
                $flagReason = ($flagReason ?: 'duplicate_ip');
            }

            $ref   = genCode();
            $stmt  = $conn->prepare("
                INSERT INTO `applications`
                    (`name`,`phone`,`current_role`,`q_search_engine`,`q_ai_limitation`,`q_changed_mind`,
                     `q_company_idea`,`q_opinion`,`brand_1`,`brand_2`,`brand_3`,`brand_4`,`brand_5`,
                     `made_link`,`q_niche`,`q_grava_theory`,`function_interest`,`notice_period`,
                     `email`,`linkedin`,`resume_link`,`fb_pricing`,`fb_features`,`fb_general`,
                     `ref_code`,`referred_by`,`fingerprint`,`ip_address`,`user_agent`,`device_type`,
                     `is_flagged`,`flag_reason`)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");
            $stmt->bind_param(
                'sssssssssssssssssssssssssssssssi',
                $d['name'],
                $d['phone'],
                $d['current_role'],
                $d['q_search_engine'],
                $d['q_ai_limitation'],
                $d['q_changed_mind'],
                $d['q_company_idea'],
                $d['q_opinion'],
                $d['brand_1'],
                $d['brand_2'],
                $d['brand_3'],
                $d['brand_4'],
                $d['brand_5'],
                $d['made_link'],
                $d['q_niche'],
                $d['q_grava_theory'],
                $d['function_interest'],
                $d['notice_period'],
                $d['email'],
                $d['linkedin'],
                $d['resume_link'],
                $d['fb_pricing'],
                $d['fb_features'],
                $d['fb_general'],
                $ref,
                $d['referred_by'],
                $d['fingerprint'],
                $d['ip_address'],
                $d['user_agent'],
                $d['device_type'],
                $isFlagged,
                $flagReason
            );

            if ($stmt->execute()) {
                $appId = $conn->insert_id;

                // Track referral code
                $ri = $conn->prepare("INSERT INTO referrals (code,applicant_id) VALUES (?,?)");
                $ri->bind_param('si', $ref, $appId);
                $ri->execute();

                // Increment referrer's use count
                if ($d['referred_by'])
                    $conn->query("UPDATE referrals SET uses=uses+1 WHERE code='" . $conn->real_escape_string($d['referred_by']) . "'");

                // Rate limit increment
                $conn->query("INSERT INTO rate_limits (action_key,attempts) VALUES ('$rlKey',1)
                              ON DUPLICATE KEY UPDATE attempts=IF(TIMESTAMPDIFF(SECOND,window_start,NOW())>3600,1,attempts+1),
                              window_start=IF(TIMESTAMPDIFF(SECOND,window_start,NOW())>3600,NOW(),window_start),last_at=NOW()");

                // Milestone check
                $total = (int)$conn->query("SELECT COUNT(*) as c FROM applications")->fetch_assoc()['c'];
                $milestones = [10, 25, 50, 100, 250, 500, 1000, 2500, 5000];
                if (in_array($total, $milestones)) {
                    // Send admin email
                    sendEmail(ADMIN_EMAIL, "🎯 Grava Milestone: $total Users!", buildMilestoneEmail($total));
                    // Send all users email
                    $res = $conn->query("SELECT email, name FROM applications WHERE status != 'rejected' LIMIT 2000");
                    while ($row = $res->fetch_assoc()) {
                        sendEmail(
                            $row['email'],
                            "Grava just hit $total members — you're part of it.",
                            buildMilestoneEmail($total)
                        );
                    }
                }

                // Send welcome email
                sendEmail($d['email'], 'Welcome to Grava, ' . $d['name'] . '.', buildWelcomeEmail($d, $ref));

                unset($_SESSION['grava_old'], $_SESSION['grava_ref_from']);
                header('Location: ' . $_SERVER['PHP_SELF'] . '?ok=1&rc=' . $ref);
                exit;
            } else {
                $submitError = 'Something went wrong. Please try again.';
                $_SESSION['grava_old'] = $d;
            }
        }
    }
    if (!empty($fieldErrors) || $submitError) $_SESSION['grava_old'] = $d ?? [];
}

render:
$showFormPage = (!empty($fieldErrors) || $submitError || isset($_GET['apply'])) ? 'true' : 'false';
$phpErrors    = json_encode($fieldErrors);
$siteUrl      = SITE_URL;
$refCodeJs    = json_encode($newRefCode);

require __DIR__ . '/views/frontend/home.php';