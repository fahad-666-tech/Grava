<?php
require_once __DIR__.'/config.php';

function esc(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES|ENT_HTML5, 'UTF-8');
}

function clean(?string $v): string {
    return htmlspecialchars(strip_tags(trim($v ?? '')), ENT_QUOTES|ENT_HTML5, 'UTF-8');
}

function cleanUrl(?string $v): string {
    $u = filter_var(trim($v ?? ''), FILTER_SANITIZE_URL);
    return filter_var($u, FILTER_VALIDATE_URL) ? $u : '';
}

function old(string $k): string {
    return clean($_SESSION['grava_old'][$k] ?? '');
}

function genCode(): string {
    return bin2hex(random_bytes(8));
}

function deviceType(string $ua): string {
    return preg_match('/Mobi|Android|iPhone|iPad/i', $ua) ? 'mobile' : 'desktop';
}

function isDisposable(string $email): bool {
    $disposable_domains = [
        'mailinator.com','guerrillamail.com','guerrillamail.info','guerrillamail.biz','guerrillamail.de',
        'guerrillamail.net','guerrillamail.org','tempmail.com','throwam.com','yopmail.com','sharklasers.com',
        'spam4.me','trashmail.com','trashmail.me','trashmail.net','trashmail.io','trashmail.org','fakeinbox.com',
        'dispostable.com','maildrop.cc','tempinbox.com','spamgourmet.com','mailnull.com','10minutemail.com',
        '10minutemail.net','minutemail.com','tempail.com','tmpmail.net','tmpmail.org','binkmail.com','bobmail.info',
        'discard.email','spambox.us','spamfree24.org','mailscrap.com','emailfake.com','fakemail.fr','discardmail.com',
        'getairmail.com','tempemail.net','mytempemail.com','spamtrap.ro','trash-me.com','filzmail.com','wetrashed.com',
        'zehnminuten.de','zoemail.com','objectmail.com','rcpt.at','devnullmail.com','chammy.info','veryrealemail.com',
    ];
    $domain = strtolower(substr(strrchr($email, '@'), 1));
    return in_array($domain, $disposable_domains, true);
}

function trackView(): void {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $conn = db();
        $stmt = $conn->prepare('INSERT INTO page_views (ip_address) VALUES (?)');
        if ($stmt) {
            $stmt->bind_param('s', $ip);
            $stmt->execute();
        }
    } catch (Throwable $e) {
    }
}
