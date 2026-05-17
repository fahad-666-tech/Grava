<section id="sec-trend" class="page-section">
  <div class="section-head">
    <h2 class="section-title">Growth <span>Trend</span></h2>
  </div>
  <div class="chart-wrap" style="padding:32px;">
    <div class="chart-title">// 14-day daily signups — stepped line chart</div>
    <div style="height:320px;position:relative;">
      <canvas id="trendChartFull"></canvas>
    </div>
  </div>
  <?php
  $wow14 = q("SELECT DATE(created_at) as day, COUNT(*) as cnt FROM applications WHERE created_at >= DATE_SUB(CURDATE(),INTERVAL 14 DAY) GROUP BY DATE(created_at) ORDER BY day");
  $cumulative=0; ?>
  <div class="chart-wrap">
    <div class="chart-title">// cumulative growth</div>
    <div style="height:200px;position:relative;">
      <canvas id="cumChart"></canvas>
    </div>
  </div>
</section>
