<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/helpers.php';

session_start();

$authed = !empty($_SESSION['grava_admin']);
$act = $_REQUEST['act'] ?? '';

if ($authed && $act) {

    /* ── live stats cards ── */
    if ($act === 'stats') {
        header('Content-Type: text/html');
        $conn = db();
        $total     = qq("SELECT COUNT(*) as c FROM applications")['c'] ?? 0;
        $today     = qq("SELECT COUNT(*) as c FROM applications WHERE DATE(created_at)=CURDATE()")['c'] ?? 0;
        $views     = qq("SELECT COUNT(DISTINCT ip_address) as c FROM page_views WHERE DATE(viewed_at)=CURDATE()")['c'] ?? 1;
        $signToday = qq("SELECT COUNT(*) as c FROM applications WHERE DATE(created_at)=CURDATE()")['c'] ?? 0;
        $convRate  = $views > 0 ? round(($signToday/$views)*100,1) : 0;
        $thisW     = qq("SELECT COUNT(*) as c FROM applications WHERE created_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)")['c'] ?? 0;
        $lastW     = qq("SELECT COUNT(*) as c FROM applications WHERE created_at BETWEEN DATE_SUB(NOW(),INTERVAL 14 DAY) AND DATE_SUB(NOW(),INTERVAL 7 DAY)")['c'] ?? 0;
        $wow       = $lastW > 0 ? round((($thisW-$lastW)/$lastW)*100,1) : ($thisW > 0 ? 100 : 0);
        $wowSign   = $wow >= 0 ? '+' : '';
        $flagged   = qq("SELECT COUNT(*) as c FROM applications WHERE is_flagged=1")['c'] ?? 0;
        $viral     = qq("SELECT ROUND(SUM(uses)/NULLIF(COUNT(*),0),2) as v FROM referrals")['v'] ?? '0.00';
        echo <<<HTML
<div class="stat-card">
  <span class="stat-label">Total Members</span>
  <span class="stat-value">$total</span>
  <span class="stat-sub">all time</span>
</div>
<div class="stat-card">
  <span class="stat-label">New Today</span>
  <span class="stat-value gold">$today</span>
  <span class="stat-sub">signups</span>
</div>
<div class="stat-card">
  <span class="stat-label">Conversion Rate</span>
  <span class="stat-value">$convRate%</span>
  <span class="stat-sub">visits → signups today</span>
</div>
<div class="stat-card">
  <span class="stat-label">WoW Growth</span>
  <span class="stat-value <?= $wow>=0?'green':'red' ?>">$wowSign$wow%</span>
  <span class="stat-sub">vs previous 7 days</span>
</div>
<div class="stat-card">
  <span class="stat-label">Viral Coefficient</span>
  <span class="stat-value gold">$viral</span>
  <span class="stat-sub">referrals per user</span>
</div>
<div class="stat-card">
  <span class="stat-label">Flagged</span>
  <span class="stat-value red">$flagged</span>
  <span class="stat-sub">fraud suspects</span>
</div>
HTML;
        exit;
    }

    /* ── 14-day trend data ── */
    if ($act === 'trend') {
        header('Content-Type: application/json');
        $rows = q("SELECT DATE(created_at) as day, COUNT(*) as cnt
                   FROM applications
                   WHERE created_at >= DATE_SUB(CURDATE(),INTERVAL 14 DAY)
                   GROUP BY DATE(created_at) ORDER BY day ASC");
        echo json_encode($rows); exit;
    }

    /* ── inline status update ── */
    if ($act === 'set_status' && isset($_POST['id'],$_POST['status'])) {
        $id  = (int)$_POST['id'];
        $st  = db()->real_escape_string($_POST['status']);
        $allowed = ['pending','reviewed','shortlisted','rejected','hired'];
        if (in_array($st,$allowed)) {
            db()->query("UPDATE applications SET status='$st' WHERE id=$id");
        }
        // Return the updated select dropdown so it can be changed again
        $badges = ['pending'=>'badge-pending','reviewed'=>'badge-reviewed',
                   'shortlisted'=>'badge-shortlisted','rejected'=>'badge-rejected','hired'=>'badge-hired'];
        $cls = $badges[$st] ?? 'badge-pending';
        
        $html = '<select class="status-sel badge '.$cls.'" hx-post="api/admin_ajax.php?act=set_status" hx-vals=\'{"id":"'.$id.'"}\' hx-target="closest td" hx-swap="innerHTML" name="status" onchange="this.className=\'status-sel badge badge-\'+this.value">';
        foreach ($allowed as $ast) {
            $sel = ($ast === $st) ? 'selected' : '';
            $html .= '<option '.$sel.' value="'.$ast.'">'.$ast.'</option>';
        }
        $html .= '</select>';
        echo $html;
        exit;
    }

    /* ── batch status update ── */
    if ($act === 'batch_status' && !empty($_POST['ids']) && isset($_POST['status'])) {
        $ids = array_map('intval', explode(',', $_POST['ids']));
        $st  = db()->real_escape_string($_POST['status']);
        $allowed = ['pending','reviewed','shortlisted','rejected','hired'];
        if (in_array($st,$allowed) && $ids) {
            $idStr = implode(',',$ids);
            db()->query("UPDATE applications SET status='$st' WHERE id IN ($idStr)");
        }
        header('Content-Type: application/json');
        echo json_encode(['ok'=>true,'updated'=>count($ids)]); exit;
    }

    /* ── CSV export ── */
    if ($act === 'csv') {
        $status = db()->real_escape_string($_GET['status']??'');
        $where  = $status ? "WHERE status='$status'" : '';
        $rows   = q("SELECT id,name,email,phone,current_role,function_interest,status,device_type,
                     ip_address,is_flagged,ref_code,referred_by,created_at
                     FROM applications $where ORDER BY created_at DESC");
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="grava_waitlist_'.date('Y-m-d').'.csv"');
        $out = fopen('php://output','w');
        fputcsv($out, array_keys($rows[0] ?? ['no','data']));
        foreach ($rows as $row) fputcsv($out, $row);
        fclose($out); exit;
    }

    /* ── toggle flag ── */
    if ($act === 'toggle_flag' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $cur = (int)(qq("SELECT is_flagged FROM applications WHERE id=$id")['is_flagged']??0);
        $new = $cur ? 0 : 1;
        db()->query("UPDATE applications SET is_flagged=$new WHERE id=$id");
        echo $new ? '🚩' : '⚑';
        exit;
    }

    /* ── user detail modal ── */
    if ($act === 'user_detail' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $u = qq("SELECT * FROM applications WHERE id=$id");
        if (!$u) { echo '<p>Not found.</p>'; exit; }
        $refs = qq("SELECT uses FROM referrals WHERE code='".db()->real_escape_string($u['ref_code']??'')."'");
        $uses = $refs['uses']??0;
        $milestone = $uses>=10?'Priority Access':($uses>=3?'Founding Member':'—');
        echo "<div class='modal-grid'>";
        foreach (['name','email','phone','current_role','function_interest','status','device_type',
                  'ip_address','is_flagged','flag_reason','ref_code','referred_by','created_at'] as $f) {
            $val = esc($u[$f]??'—');
            echo "<div class='modal-field'><span class='mf-label'>$f</span><span class='mf-val'>$val</span></div>";
        }
        echo "<div class='modal-field'><span class='mf-label'>referral uses</span><span class='mf-val'>$uses (milestone: $milestone)</span></div>";
        // feedback
        foreach(['fb_pricing','fb_features','fb_general'] as $fb) {
            if (!empty($u[$fb])) {
                echo "<div class='modal-feedback'><span class='mf-label'>$fb</span><p>".esc($u[$fb])."</p></div>";
            }
        }
        echo "</div>"; exit;
    }
}
