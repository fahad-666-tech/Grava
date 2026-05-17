/* ── SECTION NAVIGATION ───────────────────────────────── */
function showSection(evt,id){
  if (typeof id === 'undefined') {
    id = evt;
    evt = null;
  }
  document.querySelectorAll('.page-section').forEach(function(s){s.classList.remove('active');});
  document.querySelectorAll('.nav-item').forEach(function(a){a.classList.remove('active');});
  var sec=document.getElementById('sec-'+id);
  if(sec){sec.classList.add('active');}
  if(evt){
    evt.preventDefault();
    if(evt.currentTarget) evt.currentTarget.classList.add('active');
  } else {
    // Find the nav item that corresponds to this id
    document.querySelectorAll('.nav-item').forEach(function(a) {
      if (a.getAttribute('onclick') && a.getAttribute('onclick').indexOf("'" + id + "'") !== -1) {
        a.classList.add('active');
      }
    });
  }
  if(id==='trend')setTimeout(renderTrendFull,100);
  if(id==='overview')setTimeout(renderTrendMini,100);
  animateBars();
  
  // Clean URL update
  if(window.history && window.history.pushState) {
    window.history.pushState(null, null, window.location.pathname + '#' + id);
  } else {
    window.location.hash = id;
  }
  
  return false;
}

/* ── SEGMENT BARS ─────────────────────────────────────── */
function animateBars(){
  document.querySelectorAll('.seg-fill').forEach(function(el){
    el.style.width=(el.getAttribute('data-w')||0)+'%';
  });
}
document.addEventListener('DOMContentLoaded',function(){
  var hash = window.location.hash.substring(1);
  if (hash) {
    var validSections = ['overview', 'trend', 'members', 'segmentation', 'leaderboard', 'fraud'];
    if (validSections.indexOf(hash) !== -1) {
      showSection(null, hash);
    }
  } else {
    animateBars();
    renderTrendMini();
  }
});

/* ── TREND CHART (mini on overview) ──────────────────── */
var trendMiniChart = null;
function renderTrendMini(){
  var canvas=document.getElementById('trendChart');
  if(!canvas)return;
  fetch('api/admin_ajax.php?act=trend')
    .then(function(r){return r.json();})
    .then(function(data){
      var labels=data.map(function(d){return d.day;});
      var values=data.map(function(d){return parseInt(d.cnt);});
      if(trendMiniChart)trendMiniChart.destroy();
      trendMiniChart=new Chart(canvas.getContext('2d'),{
        type:'line',
        data:{labels:labels,datasets:[{
          label:'Signups',data:values,
          borderColor:'#F7C94A',backgroundColor:'rgba(247,201,74,.08)',
          stepped:true,tension:0,fill:true,pointRadius:3,
          pointBackgroundColor:'#F7C94A'
        }]},
        options:{
          responsive:true,maintainAspectRatio:false,
          plugins:{legend:{display:false}},
          scales:{
            x:{ticks:{color:'#6a6b65',font:{family:'JetBrains Mono',size:10}},grid:{color:'#d0d1c9'}},
            y:{ticks:{color:'#6a6b65',font:{family:'JetBrains Mono',size:10}},grid:{color:'#d0d1c9'},beginAtZero:true}
          }
        }
      });
    }).catch(function(){});
}

/* ── TREND CHART (full on trend page) ────────────────── */
var trendFullChart=null;
function renderTrendFull(){
  var canvas=document.getElementById('trendChartFull');
  if(!canvas)return;
  fetch('api/admin_ajax.php?act=trend')
    .then(function(r){return r.json();})
    .then(function(data){
      var labels=data.map(function(d){return d.day;});
      var values=data.map(function(d){return parseInt(d.cnt);});
      var cum=[],sum=0;
      values.forEach(function(v){sum+=v;cum.push(sum);});
      if(trendFullChart)trendFullChart.destroy();
      trendFullChart=new Chart(canvas.getContext('2d'),{
        type:'line',
        data:{labels:labels,datasets:[{
          label:'Daily Signups',data:values,
          borderColor:'#F7C94A',backgroundColor:'rgba(247,201,74,.06)',
          stepped:true,tension:0,fill:true,pointRadius:4,
          pointBackgroundColor:'#F7C94A',yAxisID:'y'
        }]},
        options:{
          responsive:true,maintainAspectRatio:false,
          plugins:{legend:{labels:{color:'#8a8b84',font:{family:'JetBrains Mono',size:11}}}},
          scales:{
            x:{ticks:{color:'#6a6b65',font:{family:'JetBrains Mono',size:10}},grid:{color:'#d0d1c9'}},
            y:{ticks:{color:'#6a6b65',font:{family:'JetBrains Mono',size:10}},grid:{color:'#d0d1c9'},beginAtZero:true}
          }
        }
      });
      var cumCanvas=document.getElementById('cumChart');
      if(cumCanvas){
        new Chart(cumCanvas.getContext('2d'),{
          type:'line',
          data:{labels:labels,datasets:[{
            label:'Total Members',data:cum,
            borderColor:'#800000',backgroundColor:'rgba(128,0,0,.06)',
            tension:.4,fill:true,pointRadius:2,pointBackgroundColor:'#800000'
          }]},
          options:{
            responsive:true,maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{
              x:{ticks:{color:'#6a6b65',font:{family:'JetBrains Mono',size:10}},grid:{color:'#d0d1c9'}},
              y:{ticks:{color:'#6a6b65',font:{family:'JetBrains Mono',size:10}},grid:{color:'#d0d1c9'},beginAtZero:true}
            }
          }
        });
      }
    }).catch(function(){});
}

/* ── BATCH SELECT ─────────────────────────────────────── */
function toggleAll(cb){
  document.querySelectorAll('.row-cb').forEach(function(c){c.checked=cb.checked;});
  updateBatch();
}
function updateBatch(){
  var checked=document.querySelectorAll('.row-cb:checked');
  var bar=document.getElementById('batchBar');
  document.getElementById('batchCount').textContent=checked.length;
  bar.classList.toggle('show',checked.length>0);
}
function clearBatch(){
  document.querySelectorAll('.row-cb,#checkAll').forEach(function(c){c.checked=false;});
  document.getElementById('batchBar').classList.remove('show');
}
function selectAll(){
  document.querySelectorAll('.row-cb').forEach(function(c){c.checked=true;});
  updateBatch();
}
function applyBatch(){
  var ids=Array.from(document.querySelectorAll('.row-cb:checked')).map(function(c){return c.value;});
  var st=document.getElementById('batchStatus').value;
  if(!ids.length||!st){alert('Select status and at least one member.');return;}
  if(!confirm('Set '+ids.length+' members to "'+st+'"?'))return;
  var fd=new FormData();
  fd.append('act','batch_status');
  fd.append('ids',ids.join(','));
  fd.append('status',st);
  fetch('api/admin_ajax.php',{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(d){if(d.ok){clearBatch();location.reload();}});
}

/* ── MODAL ────────────────────────────────────────────── */
function openModal(id){
  var overlay=document.getElementById('modalOverlay');
  var body=document.getElementById('modalBody');
  body.innerHTML='<div style="text-align:center;padding:32px;"><span class="spin"></span></div>';
  overlay.classList.add('show');
  fetch('api/admin_ajax.php?act=user_detail&id='+encodeURIComponent(id))
    .then(function(r){return r.text();})
    .then(function(html){body.innerHTML=html;});
}
function closeModal(e){
  if(e.target===document.getElementById('modalOverlay'))
    document.getElementById('modalOverlay').classList.remove('show');
}
