<section id="sec-leaderboard" class="page-section">
  <div class="section-head">
    <h2 class="section-title">Referral <span>Leaderboard</span></h2>
  </div>
  <table class="lb-table">
    <thead>
      <tr>
        <th>Rank</th><th>Name</th><th>Email</th>
        <th>Referrals</th><th>Status</th><th>Milestone</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($leaderboard as $i=>$lb):
      $rank = $i+1;
      $rankCls = $rank<=3?'top3':'';
      $uses = (int)$lb['uses'];
      $ms = $uses>=10?'Priority Access':($uses>=3?'Founding Member':'');
      $badgeCls = ['pending'=>'badge-pending','reviewed'=>'badge-reviewed','shortlisted'=>'badge-shortlisted','rejected'=>'badge-rejected','hired'=>'badge-hired'][$lb['status']]??'badge-pending';
    ?>
    <tr>
      <td class="lb-rank <?= $rankCls ?>"><?= $rank ?></td>
      <td><?= esc($lb['name']) ?></td>
      <td style="color:var(--muted);font-size:11px;"><?= esc($lb['email']) ?></td>
      <td style="color:var(--gold);font-weight:500;"><?= $uses ?></td>
      <td><span class="badge <?= $badgeCls ?>"><?= esc($lb['status']) ?></span></td>
      <td><?php if($ms): ?><span class="milestone-badge"><?= $ms ?></span><?php else: ?><span class="milestone-none">—</span><?php endif; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if(empty($leaderboard)): ?>
    <tr><td colspan="6" style="color:var(--dim);padding:24px;text-align:center;">No referrals yet.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</section>
