<section id="sec-overview" class="page-section active">
  <div class="section-head">
    <h2 class="section-title">Overview <span>//</span> Live Dashboard</h2>
    <span class="spin htmx-indicator" id="statsSpinner"></span>
  </div>

  <!-- Live stats via HTMX polling every 10s -->
  <div class="stats-grid"
       hx-get="api/admin_ajax.php?act=stats"
       hx-trigger="load, every 10s"
       hx-target="this"
       hx-swap="innerHTML"
       hx-indicator="#statsSpinner">
    <div class="stat-card"><span class="stat-label">Loading…</span><span class="stat-value">—</span></div>
  </div>

  <!-- Chart placeholder — loaded by JS after DOMContentLoaded -->
  <div class="chart-wrap">
    <div class="chart-title">// 14-day daily signups (stepped)</div>
    <div class="chart-canvas-wrap">
      <canvas id="trendChart"></canvas>
    </div>
  </div>

  <!-- Quick stats row -->
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="seg-panel">
      <div class="seg-title">// top functions (quick view)</div>
      <?php $max=max(array_column($byFunc,'cnt')+[1]);
      foreach(array_slice($byFunc,0,6) as $r): $pct=round(($r['cnt']/$max)*100); ?>
      <div class="seg-row">
        <span class="seg-name"><?= esc($r['seg']) ?></span>
        <div class="seg-bar"><div class="seg-fill" data-w="<?= $pct ?>"></div></div>
        <span class="seg-count"><?= $r['cnt'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="seg-panel">
      <div class="seg-title">// recent signups</div>
      <?php $recent=q("SELECT name, function_interest, device_type, created_at FROM applications ORDER BY created_at DESC LIMIT 6");
      foreach($recent as $r): ?>
      <div class="seg-row">
        <span class="seg-name"><?= esc($r['name']) ?></span>
        <span class="device-tag <?= $r['device_type']==='mobile'?'mob':'' ?>"><?= esc($r['device_type']) ?></span>
        <span class="seg-count" style="color:var(--muted);font-size:10px;"><?= date('d M',strtotime($r['created_at'])) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
