/* ── PHP → JS ─────────────────────────────────────────────────── */
var SHOW_FORM = false;
var SHOW_SUCCESS = false;
var PHP_ERRORS = {};
var REF_CODE = '';
var SITE_URL = '';

/* ── DEVICE FINGERPRINT (lightweight) ────────────────────────── */
(function(){
  var fp = [
    navigator.language||'',
    navigator.platform||'',
    screen.width+'x'+screen.height,
    new Date().getTimezoneOffset(),
    navigator.hardwareConcurrency||0,
    !!window.sessionStorage
  ].join('|');
  var hash=0;
  for(var i=0;i<fp.length;i++){hash=((hash<<5)-hash)+fp.charCodeAt(i);hash|=0;}
  var val=Math.abs(hash).toString(16).padStart(8,'0');
  var fpField=document.getElementById('fpField');
  if(fpField) fpField.value=val;
  var deviceFP=document.getElementById('deviceFP');
  if(deviceFP) deviceFP.value=val;
})();

/* ── PAGE ROUTER ──────────────────────────────────────────────── */
function showPage(page){
  var main=document.getElementById('page-main');
  var form=document.getElementById('page-form');
  var nav=document.getElementById('mainNav');
  if(page==='form'){
    main.classList.add('hidden');
    form.style.display='block';
    setTimeout(function(){form.classList.add('active');},10);
    nav.classList.add('dark-nav');
    window.scrollTo({top:0,behavior:'smooth'});
  } else {
    form.classList.remove('active');
    setTimeout(function(){form.style.display='none';},360);
    main.classList.remove('hidden');
    nav.classList.remove('dark-nav');
    window.scrollTo({top:0,behavior:'smooth'});
  }
}

function gotoSection(id){
  showPage('main');
  setTimeout(function(){var el=document.getElementById(id);if(el)el.scrollIntoView({behavior:'smooth'});},130);
}

/* ── INIT ─────────────────────────────────────────────────────── */
(function(){
  if(SHOW_FORM||Object.keys(PHP_ERRORS).length)showPage('form');
  if(SHOW_SUCCESS&&REF_CODE){
    initReferralDashboard(REF_CODE);
  }
})();

/* ── REAL-TIME EMAIL VALIDATION ───────────────────────────────── */
var emailTimer=null;
var emailInput=document.getElementById('emailField');
var emailStatus=document.getElementById('emailStatus');
if(emailInput){
  emailInput.addEventListener('blur',function(){
    var val=this.value.trim();
    if(!val||val.length<5)return;
    clearTimeout(emailTimer);
    if(emailStatus) emailStatus.innerHTML='<span class="email-checking"><span class="spin"></span>Checking email…</span>';
    emailInput.classList.remove('input-error','input-valid');
    emailTimer=setTimeout(function(){
      var fd=new FormData();
      fd.append('action','validate_email');
      fd.append('email',emailInput.value.trim());
      fd.append('fp',document.getElementById('deviceFP').value);
      fetch('api/frontend_ajax.php',{method:'POST',body:fd})
        .then(function(r){return r.json();})
        .then(function(data){
          if(data.ok){
            if(emailStatus) emailStatus.innerHTML='<span class="form-success-hint">'+data.msg+'</span>';
            emailInput.classList.add('input-valid');
          } else {
            if(emailStatus) emailStatus.innerHTML='<span class="form-error-msg">'+data.msg+'</span>';
            emailInput.classList.add('input-error');
          }
        })
        .catch(function(){ if(emailStatus) emailStatus.innerHTML=''; });
    },600);
  });
  emailInput.addEventListener('input',function(){
    if(emailStatus) emailStatus.innerHTML='';
    emailInput.classList.remove('input-error','input-valid');
  });
}

/* ── REFERRAL DASHBOARD INIT ──────────────────────────────────── */
function initReferralDashboard(code){
  var overlay=document.getElementById('successOverlay');
  if(!overlay) return;
  overlay.classList.add('show');
  document.body.style.overflow='hidden';

  var refLink=SITE_URL+'?ref='+code;
  var disp=document.getElementById('refLinkDisplay');
  if(disp)disp.textContent=refLink;

  var wa=document.getElementById('waLink');
  if(wa)wa.href='https://wa.me/?text='+encodeURIComponent('I just applied to Grava — something new is being built in the freelance world. You should check it out: '+refLink);

  var tw=document.getElementById('twLink');
  if(tw)tw.href='https://twitter.com/intent/tweet?text='+encodeURIComponent('Something is being built that changes how developers get trusted and hired. Get early access: '+refLink+' #grava');

  var li=document.getElementById('liLink');
  if(li)li.href='https://www.linkedin.com/sharing/share-offsite/?url='+encodeURIComponent(refLink);

  fetch('api/frontend_ajax.php?action=get_position&rc='+encodeURIComponent(code))
    .then(function(r){return r.json();})
    .catch(function(){return {pos:null};})
    .then(function(d){
      var el=document.getElementById('posNum');
      if(el&&d.pos)el.textContent='#'+d.pos;
    });
}

/* ── COPY REFERRAL LINK ───────────────────────────────────────── */
function copyLink(){
  var txt=document.getElementById('refLinkDisplay').textContent;
  var btn=document.getElementById('copyBtn');
  if(navigator.clipboard){
    navigator.clipboard.writeText(txt).then(function(){
      if(btn){btn.textContent='Copied ✓';btn.classList.add('copied');}
      setTimeout(function(){if(btn){btn.textContent='Copy';btn.classList.remove('copied');}},2000);
    });
  } else {
    var ta=document.createElement('textarea');ta.value=txt;ta.style.opacity='0';ta.style.position='fixed';
    document.body.appendChild(ta);ta.select();document.execCommand('copy');document.body.removeChild(ta);
    if(btn){btn.textContent='Copied ✓';btn.classList.add('copied');}
    setTimeout(function(){if(btn){btn.textContent='Copy';btn.classList.remove('copied');}},2000);
  }
}

/* ── SEND INVITE ────────────────────────────────────────────── */
function sendInvite(){
  var inp=document.getElementById('inviteEmailInput');
  var msg=document.getElementById('inviteMsg');
  if(!msg) return;
  var email=inp?inp.value.trim():'';
  msg.className='invite-msg';msg.textContent='';
  if(!email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
    msg.textContent='// enter a valid email address.';msg.classList.add('err');return;
  }
  var fd=new FormData();
  fd.append('action','ref_invite');
  fd.append('to_email',email);
  fd.append('ref_code',REF_CODE);
  fd.append('from_name','A Grava member');
  fd.append('fp',document.getElementById('deviceFP').value);
  fetch('api/frontend_ajax.php',{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(d){
      if(d.ok){msg.textContent='// invite sent. they\'re in the queue.';msg.classList.add('ok');if(inp)inp.value='';}
      else{msg.textContent='// '+(d.msg||'something went wrong.');msg.classList.add('err');}
    })
    .catch(function(){msg.textContent='// network error.';msg.classList.add('err');});
}

/* ── CLOSE SUCCESS ────────────────────────────────────────── */
function closeSuccess(){
  var overlay=document.getElementById('successOverlay');
  if(overlay) overlay.classList.remove('show');
  document.body.style.overflow='';
  history.replaceState(null,'',location.pathname);
  showPage('main');
}

/* ══ MANIFESTO TYPING ════════════════════════════════════════════ */
(function(){
  var textEl=document.getElementById('manifestoText');
  var subEl=document.getElementById('manifestoSub');
  if(!textEl)return;
  var phrases=[
    {text:'Real Output.',sub:'not polished portfolios'},
    {text:'Visible Progress.',sub:'tracked, not claimed'},
    {text:'Trusted Collaboration.',sub:'built on proof'},
    {text:'Not Certificates.',sub:'execution is the proof'},
    {text:'Work Becomes Proof.',sub:'the core idea'},
    {text:'The New Freelance Era.',sub:'starting now'},
    {text:'Beginners No More.',sub:'everyone starts somewhere'},
    {text:'The System Changes.',sub:'because it has to'},
  ];
  var pi=0,ci=0,deleting=false,paused=false;
  function tick(){
    if(paused)return;
    var phrase=phrases[pi].text,sub=phrases[pi].sub;
    if(!deleting){
      ci++;textEl.textContent=phrase.slice(0,ci);
      if(ci===phrase.length){
        subEl.textContent='// '+sub;subEl.style.opacity='1';paused=true;
        setTimeout(function(){subEl.style.opacity='0';setTimeout(function(){subEl.textContent='';deleting=true;paused=false;setTimeout(tick,40);},400);},2200);
        return;
      }
    } else {
      ci--;textEl.textContent=phrase.slice(0,ci);
      if(ci===0){deleting=false;pi=(pi+1)%phrases.length;setTimeout(tick,280);return;}
    }
    setTimeout(tick,deleting?35:68);
  }
  var started=false;
  var obs=new IntersectionObserver(function(e){if(e[0].isIntersecting&&!started){started=true;obs.disconnect();setTimeout(tick,600);}}, {threshold:.3});
  var m=document.getElementById('manifesto');
  if(m) obs.observe(m); else tick();
})();

/* ══ SHIFT TYPING ═══════════════════════════════════════════════ */
(function(){
  var el=document.querySelector('.typing-target');if(!el)return;
  var phrases=['prove itself?','speak for you?','earn real trust?','open doors?','break barriers?','build your legacy?','outlast the hype?','tell the story?','command respect?','change the game?','be undeniable?','show the way?','create believers?','spark a movement?','do the talking?'];
  var pi=0,ci=0,deleting=false;
  function tick(){
    var p=phrases[pi];
    if(!deleting){el.textContent=p.slice(0,ci+1);ci++;if(ci===p.length){deleting=true;setTimeout(tick,2200);return;}}
    else{el.textContent=p.slice(0,ci-1);ci--;if(ci===0){deleting=false;pi=(pi+1)%phrases.length;}}
    setTimeout(tick,deleting?52:88);
  }
  var obs=new IntersectionObserver(function(e){if(e[0].isIntersecting){obs.disconnect();tick();}});
  var s=document.getElementById('shift');if(s)obs.observe(s);
})();

/* ══ COMMIT GRAPH ═══════════════════════════════════════════════ */
(function(){
  var g=document.getElementById('commitGraph');if(!g)return;
  var lvls=[0,0,0,0,1,1,2,2,3,4];
  for(var w=0;w<27;w++){
    var row=document.createElement('div');row.className='commit-row';
    for(var d=0;d<7;d++){
      var c=document.createElement('div');c.className='commit-cell';
      var l=lvls[Math.floor(Math.random()*lvls.length)];if(l>0)c.classList.add('lvl'+l);
      row.appendChild(c);
      (function(x){setTimeout(function(){x.classList.add('show');},Math.random()*1400);})(c);
    }
    g.appendChild(row);
  }
})();

/* ══ COUNTER ═════════════════════════════════════════════════════ */
(function(){
  var el=document.getElementById('problemCounter');if(!el)return;
  var target=847312;
  var obs=new IntersectionObserver(function(e){if(!e[0].isIntersecting)return;obs.disconnect();var cur=0,step=target/60;var t=setInterval(function(){cur=Math.min(cur+step,target);el.textContent=Math.floor(cur).toLocaleString('en-IN');if(cur>=target)clearInterval(t);},16);});
  obs.observe(el);
})();

/* ══ SCROLL REVEALS ══════════════════════════════════════════════ */
(function(){
  var items=document.querySelectorAll('.cause-item,.pipe-node,.reveal');
  var obs=new IntersectionObserver(function(entries){entries.forEach(function(e){if(e.isIntersecting){var d=parseInt(e.target.getAttribute('data-delay')||0);setTimeout(function(){e.target.classList.add('visible');},d);obs.unobserve(e.target);}});},{threshold:.12});
  items.forEach(function(el,i){el.setAttribute('data-delay',i*55);obs.observe(el);});
})();

/* ══ PROGRESS BARS ══════════════════════════════════════════════ */
(function(){
  var w=document.getElementById('progressBars');if(!w)return;
  var obs=new IntersectionObserver(function(e){if(!e[0].isIntersecting)return;obs.disconnect();w.querySelectorAll('.pw-bar-fill').forEach(function(f,i){setTimeout(function(){f.style.width=f.getAttribute('data-width')+'%';},i*165);});},{threshold:.3});
  obs.observe(w);
})();

/* ══ NAV DARK ON SCROLL ══════════════════════════════════════════ */
(function(){
  var nav=document.getElementById('mainNav');
  var ids=['problem','broke','manifesto','duality','closing'];
  function check(){
    if(!document.getElementById('page-main')||document.getElementById('page-main').classList.contains('hidden'))return;
    var y=window.scrollY+44;
    var dark=ids.some(function(id){var el=document.getElementById(id);return el&&y>=el.offsetTop&&y<=el.offsetTop+el.offsetHeight;});
    nav.classList.toggle('dark-nav',dark);
  }
  window.addEventListener('scroll',check,{passive:true});
})();
