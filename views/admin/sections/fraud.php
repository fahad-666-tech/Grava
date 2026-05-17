<section id="sec-fraud" class="page-section">
  <div class="section-head">
    <h2 class="section-title">Fraud <span>Detection</span></h2>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="seg-panel">
      <div class="seg-title">// duplicate IP addresses (potential sock-puppets)</div>
      <?php foreach($dupIps as $d): ?>
      <div class="dup-row">
        <span class="dup-ip"><?= esc($d['ip_address']) ?></span>
        <span class="dup-count"><?= $d['cnt'] ?>×</span>
        <span class="dup-names"><?= esc(substr($d['names'],0,60)) ?>…</span>
      </div>
      <?php endforeach; ?>
      <?php if(empty($dupIps)): ?><p style="color:var(--dim);font-size:11px;">No duplicate IPs detected.</p><?php endif; ?>
    </div>
    <div class="seg-panel">
      <div class="seg-title">// flagged applications</div>
      <?php $flagged=q("SELECT name,email,ip_address,flag_reason,created_at FROM applications WHERE is_flagged=1 ORDER BY created_at DESC LIMIT 20");
      foreach($flagged as $f): ?>
      <div class="dup-row">
        <span class="dup-ip" style="color:var(--red);"><?= esc($f['name']) ?></span>
        <span class="dup-names"><?= esc($f['flag_reason']??'—') ?></span>
      </div>
      <?php endforeach; ?>
      <?php if(empty($flagged)): ?><p style="color:var(--dim);font-size:11px;">No flagged users.</p><?php endif; ?>
    </div>
  </div>
</section>
