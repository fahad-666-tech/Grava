<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/db.php';

function sendEmail(string $to, string $subject, string $html): bool {
    $conn = db();
    $status = 'failed';
    $errorMsg = null;
    $autoload = dirname(__DIR__).'/vendor/autoload.php';

    if (file_exists($autoload)) {
        require_once $autoload;
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            if ($mail->send()) {
                $status = 'sent';
            } else {
                $errorMsg = $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            error_log('PHPMailer error: '.$errorMsg);
        }
    } elseif (SMTP_ENABLED && !empty(SMTP_USER)) {
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: '.MAIL_FROM_NAME.' <'.MAIL_FROM.'>',
            'Reply-To: '.MAIL_FROM,
            'X-Mailer: Grava/2.0',
        ]);
        if (@mail($to, $subject, $html, $headers)) {
            $status = 'sent';
        } else {
            $errorMsg = 'PHP mail() failed';
        }
    } else {
        $status = 'logged';
        $errorMsg = 'SMTP not configured - logged for dev';
    }

    $stmt = $conn->prepare('INSERT INTO mail_log (to_email, subject, body, status, error_msg) VALUES (?, ?, ?, ?, ?)');
    if ($stmt) {
        $stmt->bind_param('sssss', $to, $subject, $html, $status, $errorMsg);
        $stmt->execute();
    }

    return $status === 'sent';
}

function buildWelcomeEmail(array $d, string $refCode): string {
    $name = htmlspecialchars($d['name']);
    $fn = htmlspecialchars($d['function_interest'] ?: 'your field');
    $suggestion = $d['fb_general'] ? substr(strip_tags($d['fb_general']), 0, 120) : '';
    $suggLine = $suggestion ? '<p style="color:#8a8b84;font-size:12px;font-style:italic;border-left:3px solid #F7C94A;padding-left:16px;margin:24px 0;">"'.htmlspecialchars($suggestion).'"</p><p style="color:#5c5c5a;font-size:12px;">— We\'ve noted your suggestion. It\'s already in the pile, read by a human.</p>' : '';
    $refLink = SITE_URL.'?ref='.$refCode;
    return <<<HTML
<!DOCTYPE html><html><body style="background:#111110;margin:0;padding:0;font-family:'Courier New',monospace;">
<table width="100%" style="background:#111110;padding:48px 0;"><tr><td align="center">
<table width="580" style="background:#161614;border:1px solid #252523;max-width:580px;">
  <tr><td style="padding:40px 48px;border-bottom:3px solid #F7C94A;">
    <p style="font-family:Georgia,serif;font-size:26px;font-weight:900;color:#F7C94A;letter-spacing:-.03em;margin:0 0 4px;">Grava.</p>
    <p style="font-size:10px;color:#3a3a37;letter-spacing:.2em;text-transform:uppercase;margin:0 0 36px;">filter.submit → status: received</p>
    <h1 style="font-family:Georgia,serif;font-size:34px;font-weight:900;color:#c8c9c2;letter-spacing:-.04em;line-height:1.1;margin:0;">
      Welcome to Grava,<br><span style="color:#F7C94A;">$name.</span>
    </h1>
  </td></tr>
  <tr><td style="padding:32px 48px;">
    <p style="color:#8a8b84;font-size:13px;line-height:1.9;margin:0 0 20px;">
      We noticed you're interested in <strong style="color:#c0c1ba;">$fn</strong>.
      We're building exactly the infrastructure that changes how $fn professionals get discovered, trusted, and hired — based on what they actually build.
    </p>
    $suggLine
    <div style="background:#1e1e1c;border:1px solid #2a2a28;padding:24px;margin:28px 0;">
      <p style="font-size:10px;color:#4a4a47;letter-spacing:.2em;text-transform:uppercase;margin:0 0 10px;">// your referral code</p>
      <p style="font-size:22px;color:#F7C94A;letter-spacing:.12em;font-weight:700;font-family:monospace;margin:0 0 10px;">$refCode</p>
      <p style="font-size:11px;color:#4a4a47;margin:0 0 10px;">Every person you refer who passes the filter moves you toward <strong style="color:#c0c1ba;">Founding Member</strong> status.</p>
      <a href="$refLink" style="font-size:12px;color:#F7C94A;word-break:break-all;">$refLink</a>
    </div>
    <p style="color:#3a3a37;font-size:11px;line-height:1.8;margin:0;">No newsletter. No update spam. Just a message when it's time.<br>Go back to building.</p>
  </td></tr>
  <tr><td style="padding:20px 48px;border-top:1px solid #1e1e1c;">
    <p style="font-size:10px;color:#2a2a28;letter-spacing:.1em;">© 2025 Grava — All rights reserved</p>
  </td></tr>
</table></td></tr></table>
</body></html>
HTML;
}

function buildInviteEmail(string $fromName, string $refCode): string {
    $from = htmlspecialchars($fromName);
    $refLink = SITE_URL.'?ref='.$refCode;
    return <<<HTML
<!DOCTYPE html><html><body style="background:#111110;margin:0;padding:0;font-family:'Courier New',monospace;">
<table width="100%" style="background:#111110;padding:48px 0;"><tr><td align="center">
<table width="580" style="background:#161614;border:1px solid #252523;max-width:580px;">
  <tr><td style="padding:40px 48px;border-bottom:3px solid #800000;">
    <p style="font-family:Georgia,serif;font-size:26px;font-weight:900;color:#F7C94A;margin:0 0 36px;">Grava.</p>
    <h1 style="font-family:Georgia,serif;font-size:30px;font-weight:900;color:#c8c9c2;line-height:1.15;margin:0;">
      <span style="color:#F7C94A;">$from</span> referred you<br>for priority access to Grava.
    </h1>
  </td></tr>
  <tr><td style="padding:32px 48px;">
    <p style="color:#8a8b84;font-size:13px;line-height:1.9;margin:0 0 24px;">They think you should be on the waitlist — and with this link, you skip the queue and get <strong style="color:#c0c1ba;">Founding Member priority</strong>.</p>
    <p style="color:#5c5c5a;font-size:12px;margin:0 0 28px;">We still need you to pass the filter. But you're already ahead of everyone who isn't referred.</p>
    <a href="$refLink" style="display:inline-block;background:#800000;color:#e4e5dd;font-size:11px;letter-spacing:.14em;text-transform:uppercase;padding:16px 32px;text-decoration:none;font-weight:700;margin:0 0 24px;">Apply to The Filter →</a>
    <p style="color:#3a3a37;font-size:11px;">// referred by $from · priority queue</p>
  </td></tr>
  <tr><td style="padding:20px 48px;border-top:1px solid #1e1e1c;">
    <p style="font-size:10px;color:#2a2a28;letter-spacing:.1em;">© 2025 Grava — All rights reserved</p>
  </td></tr>
</table></td></tr></table>
</body></html>
HTML;
}

function buildMilestoneEmail(int $count): string {
    return <<<HTML
<!DOCTYPE html><html><body style="background:#111110;font-family:'Courier New',monospace;">
<table width="100%" style="padding:40px 0;background:#111110;"><tr><td align="center">
<table width="540" style="background:#161614;border:1px solid #252523;">
  <tr><td style="padding:36px 40px;border-bottom:3px solid #F7C94A;">
    <p style="font-family:Georgia,serif;font-size:24px;font-weight:900;color:#F7C94A;margin:0 0 28px;">Grava.</p>
    <h1 style="font-family:Georgia,serif;font-size:32px;color:#c8c9c2;margin:0;">🎯 Milestone: <span style="color:#F7C94A;">$count users</span></h1>
  </td></tr>
  <tr><td style="padding:28px 40px;">
    <p style="color:#8a8b84;font-size:13px;line-height:1.9;">The Grava waitlist just hit <strong style="color:#c0c1ba;">$count members</strong>. The filter is working.</p>
    <p style="color:#5c5c5a;font-size:12px;margin-top:16px;">// grava internal alert · auto-generated</p>
  </td></tr>
</table></td></tr></table>
</body></html>
HTML;
}

function fireMilestoneAlert(int $total): void {
    $conn = db();
    sendEmail(ADMIN_EMAIL, "🎯 Grava Milestone: $total Users!", buildMilestoneEmail($total));
    $res = $conn->query("SELECT email, name FROM applications WHERE status != 'rejected' LIMIT 2000");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            sendEmail($row['email'], "Grava just hit $total members — you're part of it.", buildMilestoneEmail($total));
        }
    }
}
