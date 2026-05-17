<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Grava Admin — Waitlist Dashboard</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/htmx/1.9.10/htmx.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" defer></script>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,700..900;1,9..144,700..900&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/admin.css"/>
</head>
<body>

<?php if (!$authed): ?>
  <?php require __DIR__ . '/login.php'; ?>
<?php else: ?>
<!-- ════ DASHBOARD ════ -->
<div class="admin-layout">

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="sidebar-logo-text">Grava.</div>
    <div class="sidebar-logo-sub">Admin · <?= esc($_SESSION['admin_name']??'') ?></div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section">Analytics</div>
    <a class="nav-item active" href="#" onclick="showSection(event, 'overview')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Overview
    </a>
    <a class="nav-item" href="#" onclick="showSection(event, 'trend')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      Growth Trend
    </a>
    <div class="nav-section">Members</div>
    <a class="nav-item" href="#" onclick="showSection(event, 'members')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      All Members
    </a>
    <a class="nav-item" href="#" onclick="showSection(event, 'segmentation')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      Segmentation
    </a>
    <div class="nav-section">Referrals</div>
    <a class="nav-item" href="#" onclick="showSection(event, 'leaderboard')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="18 20 18 10"/><polyline points="12 20 12 4"/><polyline points="6 20 6 14"/></svg>
      Leaderboard
    </a>
    <div class="nav-section">Security</div>
    <a class="nav-item" href="#" onclick="showSection(event, 'fraud')">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Fraud Detection
    </a>
    <div class="nav-section">Tools</div>
    <a class="nav-item" href="api/admin_ajax.php?act=csv" target="_blank">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Export CSV
    </a>
  </nav>
  <div class="sidebar-foot">
    <form method="POST">
      <input type="hidden" name="admin_logout" value="1"/>
      <button class="logout-btn" type="submit">Sign Out</button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<main class="main-area">
  <?php require __DIR__ . '/sections/overview.php'; ?>
  <?php require __DIR__ . '/sections/trend.php'; ?>
  <?php require __DIR__ . '/sections/members.php'; ?>
  <?php require __DIR__ . '/sections/segmentation.php'; ?>
  <?php require __DIR__ . '/sections/leaderboard.php'; ?>
  <?php require __DIR__ . '/sections/fraud.php'; ?>
</main>
</div><!-- /admin-layout -->

<!-- MODAL -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
  <div class="modal-box">
    <div class="modal-head">
      <h3 class="modal-title">Application Details</h3>
      <button class="modal-close" onclick="closeModal(null,true)">×</button>
    </div>
    <div class="modal-body" id="modalBody">
      <div style="text-align:center;padding:40px;"><span class="spin"></span> Loading...</div>
    </div>
  </div>
</div>

<!-- BATCH ACTION BAR -->
<div class="batch-bar" id="batchBar">
  <div class="batch-info">Selected <span id="batchCount">0</span> users</div>
  <select class="batch-status-sel" id="batchStatus">
    <option value="">Update status…</option>
    <option value="pending">pending</option>
    <option value="reviewed">reviewed</option>
    <option value="shortlisted">shortlisted</option>
    <option value="rejected">rejected</option>
    <option value="hired">hired</option>
  </select>
  <button class="batch-apply" onclick="applyBatch()">Apply</button>
  <button class="batch-clear" onclick="clearBatch()">Cancel</button>
</div>

<script src="assets/js/admin.js"></script>
<?php endif; ?>
</body>
</html>
