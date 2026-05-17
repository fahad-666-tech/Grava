<?php
/* ═══════════════════════════════════════════════════════════════════
   GRAVA — admin.php
   Waitlist Management Dashboard
   Login: username = Tanoli  /  password = FahadKhan786
═══════════════════════════════════════════════════════════════════ */

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/helpers.php';

session_start();

/* ══ AUTH ════════════════════════════════════════════════════════ */
$loginError = '';

if (isset($_POST['admin_login'])) {
    if ($_POST['username']===ADMIN_USER && $_POST['password']===ADMIN_PASS) {
        $_SESSION['grava_admin'] = true;
        $_SESSION['admin_name']  = ADMIN_USER;
        header('Location: '.$_SERVER['PHP_SELF']); exit;
    }
    $loginError = 'Invalid credentials.';
}
if (isset($_POST['admin_logout'])) {
    unset($_SESSION['grava_admin'],$_SESSION['admin_name']);
    header('Location: '.$_SERVER['PHP_SELF']); exit;
}
$authed = !empty($_SESSION['grava_admin']);

/* ══ PAGE DATA (auth required) ══════════════════════════════════ */
if ($authed) {
    $conn = db();

    /* search + filter */
    $search = trim(db()->real_escape_string($_GET['q']??''));
    $filter = db()->real_escape_string($_GET['status']??'');
    $page   = max(1,(int)($_GET['p']??1));
    $perPage= 20;
    $offset = ($page-1)*$perPage;
    $where  = [];
    if ($search) $where[] = "(name LIKE '%$search%' OR email LIKE '%$search%' OR current_role LIKE '%$search%')";
    if ($filter) $where[] = "status='$filter'";
    $whereStr = $where ? 'WHERE '.implode(' AND ',$where) : '';
    $totalRows = (int)(qq("SELECT COUNT(*) as c FROM applications $whereStr")['c']??0);
    $totalPages = max(1,ceil($totalRows/$perPage));
    $users = q("SELECT id,name,email,function_interest,status,device_type,ip_address,is_flagged,ref_code,referred_by,created_at
                FROM applications $whereStr
                ORDER BY is_flagged DESC, created_at DESC
                LIMIT $perPage OFFSET $offset");

    /* segmentation */
    $byRole = q("SELECT current_role as seg, COUNT(*) as cnt FROM applications WHERE current_role!='' GROUP BY current_role ORDER BY cnt DESC LIMIT 10");
    $byFunc = q("SELECT function_interest as seg, COUNT(*) as cnt FROM applications WHERE function_interest!='' GROUP BY function_interest ORDER BY cnt DESC LIMIT 12");

    /* leaderboard */
    $leaderboard = q("SELECT a.name, a.email, r.uses, a.status
                      FROM applications a
                      JOIN referrals r ON r.code=a.ref_code
                      WHERE r.uses > 0
                      ORDER BY r.uses DESC LIMIT 10");

    /* duplicate IPs (potential fraud) */
    $dupIps = q("SELECT ip_address, COUNT(*) as cnt, GROUP_CONCAT(name ORDER BY created_at SEPARATOR ', ') as names
                 FROM applications
                 GROUP BY ip_address HAVING cnt > 1
                 ORDER BY cnt DESC LIMIT 10");
}

require __DIR__ . '/views/admin/layout.php';