<section id="sec-members" class="page-section">
  <div class="section-head">
    <h2 class="section-title">All <span>Members</span></h2>
  </div>

  <div class="table-controls">
    <form method="GET" style="display:contents;">
      <input class="search-input" type="text" name="q" placeholder="Search name or email…" value="<?= esc($search) ?>"/>
      <select class="filter-select" name="status" onchange="this.form.submit()">
        <option value="">All statuses</option>
        <?php foreach(['pending','reviewed','shortlisted','rejected','hired'] as $st): ?>
        <option <?= $filter===$st?'selected':'' ?>><?= $st ?></option>
        <?php endforeach; ?>
      </select>
      <button class="action-btn primary" type="submit">Search</button>
      <?php if($search||$filter): ?>
      <a class="action-btn" href="<?= $_SERVER['PHP_SELF'] ?>?#members" onclick="showSection('members')">Clear</a>
      <?php endif; ?>
      <div class="table-actions">
        <a class="action-btn" href="api/admin_ajax.php?act=csv&status=<?= urlencode($filter) ?>" target="_blank">↓ Export CSV</a>
        <button type="button" class="action-btn" onclick="selectAll()">Select All</button>
      </div>
    </form>
  </div>

  <p style="font-size:11px;color:var(--muted);margin-bottom:12px;">
    Showing <?= count($users) ?> of <?= $totalRows ?> members
  </p>

  <table class="data-table">
    <thead>
      <tr>
        <th><input class="cb" type="checkbox" id="checkAll" onchange="toggleAll(this)"/></th>
        <th>#</th><th>Name</th><th>Email</th><th>Function</th>
        <th>Device</th><th>Status</th><th>Flag</th><th>Joined</th><th>View</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($users as $i=>$u):
      $badgeCls = ['pending'=>'badge-pending','reviewed'=>'badge-reviewed','shortlisted'=>'badge-shortlisted','rejected'=>'badge-rejected','hired'=>'badge-hired'][$u['status']]??'badge-pending';
      $rowCls = $u['is_flagged'] ? 'flagged-row' : '';
    ?>
    <tr class="<?= $rowCls ?>">
      <td><input class="cb row-cb" type="checkbox" value="<?= $u['id'] ?>" onchange="updateBatch()"/></td>
      <td style="color:var(--dim);"><?= ($offset+$i+1) ?></td>
      <td><?= esc($u['name']) ?></td>
      <td style="color:var(--muted);font-size:11px;"><?= esc($u['email']) ?></td>
      <td style="font-size:11px;"><?= esc($u['function_interest']??'—') ?></td>
      <td><span class="device-tag <?= $u['device_type']==='mobile'?'mob':'' ?>"><?= esc($u['device_type']??'—') ?></span></td>
      <td>
        <select class="status-sel badge <?= $badgeCls ?>"
          hx-post="api/admin_ajax.php?act=set_status"
          hx-vals='{"id":"<?= $u['id'] ?>"}'
          hx-target="closest td"
          hx-swap="innerHTML"
          name="status"
          onchange="this.className='status-sel badge badge-'+this.value">
          <?php foreach(['pending','reviewed','shortlisted','rejected','hired'] as $st): ?>
          <option <?= $u['status']===$st?'selected':'' ?> value="<?= $st ?>"><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </td>
      <td>
        <button type="button" class="flag-btn"
          hx-post="api/admin_ajax.php?act=toggle_flag"
          hx-vals='{"id":"<?= $u['id'] ?>"}'
          hx-target="this"
          hx-swap="innerHTML"
          title="<?= $u['is_flagged']?'Unflag':'Flag as suspicious' ?>">
          <?= $u['is_flagged']?'🚩':'⚑' ?>
        </button>
      </td>
      <td style="font-size:10px;color:var(--dim);"><?= date('d M Y',strtotime($u['created_at'])) ?></td>
      <td>
        <button type="button" class="view-btn" onclick="openModal(<?= $u['id'] ?>)">View</button>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <?php if($totalPages>1): ?>
  <div class="pagination">
    <?php for($i=1;$i<=$totalPages;$i++):
      $cls = $i===$page?'cur':'';
      $href = '?q='.urlencode($search).'&status='.urlencode($filter).'&p='.$i.'#members';
    ?>
    <a class="page-btn <?= $cls ?>" href="<?= $href ?>" onclick="showSection('members')"><?= $i ?></a>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</section>
