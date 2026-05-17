<section id="sec-segmentation" class="page-section">
  <div class="section-head">
    <h2 class="section-title">Audience <span>Segmentation</span></h2>
  </div>
  <div class="seg-grid">
    <div class="seg-panel">
      <div class="seg-title">// by function interest</div>
      <?php $max=max(array_column($byFunc,'cnt')+[1]);
      foreach($byFunc as $r): $pct=round(($r['cnt']/$max)*100); ?>
      <div class="seg-row">
        <span class="seg-name"><?= esc($r['seg']) ?></span>
        <div class="seg-bar"><div class="seg-fill" data-w="<?= $pct ?>"></div></div>
        <span class="seg-count"><?= $r['cnt'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="seg-panel">
      <div class="seg-title">// by current role</div>
      <?php $max2=max(array_column($byRole,'cnt')+[1]);
      foreach($byRole as $r): $pct=round(($r['cnt']/$max2)*100); ?>
      <div class="seg-row">
        <span class="seg-name"><?= esc($r['seg']) ?></span>
        <div class="seg-bar"><div class="seg-fill" data-w="<?= $pct ?>"></div></div>
        <span class="seg-count"><?= $r['cnt'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <!-- Device breakdown -->
  <?php
  $devices = q("SELECT device_type, COUNT(*) as cnt FROM applications GROUP BY device_type");
  $total   = array_sum(array_column($devices,'cnt'))?:1;
  ?>
  <div class="seg-panel" style="max-width:400px;">
    <div class="seg-title">// device breakdown</div>
    <?php foreach($devices as $d): $pct=round(($d['cnt']/$total)*100); ?>
    <div class="seg-row">
      <span class="seg-name <?= $d['device_type']==='mobile'?'mob':'' ?>"><?= esc($d['device_type']) ?></span>
      <div class="seg-bar"><div class="seg-fill" data-w="<?= $pct ?>"></div></div>
      <span class="seg-count"><?= $d['cnt'] ?> (<?= $pct ?>%)</span>
    </div>
    <?php endforeach; ?>
  </div>
</section>
