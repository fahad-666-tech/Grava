<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Grava — The work doesn't lie.</title>
<meta name="description" content="A new era of the freelance ecosystem. Talent proven by real output, not certificates."/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=JetBrains+Mono:wght@300;400;500;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="assets/css/site.css"/>
</head>
<body>

<!-- fingerprint placeholder -->
<input type="hidden" id="deviceFP" value=""/>

<!-- NAV -->
<nav id="mainNav">
  <button class="nav-logo" onclick="showPage('main')">Grava<span>.</span></button>
  <div class="nav-center">
    <button class="nav-link" onclick="gotoSection('problem')">The Problem</button>
    <button class="nav-link" onclick="gotoSection('movement')">The System</button>
    <button class="nav-link" onclick="gotoSection('beginners')">The Promise</button>
  </div>
  <div style="display:flex;align-items:center;gap:18px;">
    <button class="nav-cta" onclick="showPage('form')">Explore <span>→</span></button>
    <span class="nav-tag">Private Beta — 2025</span>
  </div>
</nav>

<!-- ════ PAGE: MAIN ════ -->
<div id="page-main">

<section id="hero">
  <div class="hero-left">
    <div class="hero-eyebrow">Early Access — 2025</div>
    <h1>You're already<br>doing <em>the work.</em><br>The system just<br>doesn't <span class="hi">count</span> it.</h1>
    <p class="hero-sub">Your GitHub is active. Your skills are growing.<br>But when it matters… <strong>you still look like a beginner.</strong></p>
    <div>
      <p class="hero-cta-label">If you're this curious about what we do,<br>imagine what it's like building it.</p>
      <button class="cta-btn" onclick="showPage('form')">Come find out for real <span class="arr">→</span></button>
    </div>
  </div>
  <div class="hero-right">
    <div class="activity-log">
      <div class="log-head"><div class="d r"></div><div class="d y"></div><div class="d g"></div><span class="log-title">activity.log</span></div>
      <div class="log-line"><span class="log-time">09:14:22</span><span class="log-ev"><span class="ok">✓ PUSH</span> feat/auth-flow — <span class="dim">3 files</span></span></div>
      <div class="log-line"><span class="log-time">09:47:01</span><span class="log-ev"><span class="ok">✓ MERGE</span> PR #47 — <span class="dim">review passed</span></span></div>
      <div class="log-line"><span class="log-time">11:02:38</span><span class="log-ev"><span class="gold">✦ BUILD</span> pipeline <span class="dim">running…</span></span></div>
      <div class="log-line"><span class="log-time">11:04:15</span><span class="log-ev"><span class="bad">✗ TRUST</span> no verified history</span></div>
      <div class="log-line"><span class="log-time">11:04:16</span><span class="log-ev"><span class="bad">✗ PROOF</span> portfolio still empty</span></div>
      <div class="log-line"><span class="log-time">11:04:17</span><span class="log-ev"><span class="bad">✗ SEEN</span>&nbsp; application ignored</span></div>
      <div class="log-line"><span class="log-time">now</span><span class="log-ev"><span class="dim">$ waiting for something to change</span> <span class="cursor-blink"></span></span></div>
    </div>
    <div class="commit-graph" id="commitGraph"></div>
  </div>
  <div class="scroll-hint"><span class="scroll-line"></span> scroll</div>
</section>

<div id="manifesto">
  <div class="manifesto-inner">
    <div class="manifesto-prefix">// manifesto</div>
    <div class="manifesto-type-line"><span class="typed-text" id="manifestoText"></span><span class="typed-cursor"></span></div>
    <div class="manifesto-sub-line" id="manifestoSub"></div>
  </div>
</div>

<section id="problem">
  <div class="section-label">// the gap</div>
  <div class="problem-grid">
    <div class="problem-left">
      <ul class="problem-lines">
        <li class="lit">You push code.</li>
        <li>You build projects.</li>
        <li>You learn every day.</li>
        <li style="padding-top:28px;border-top:1px solid #1e1e1c;margin-top:12px;">Still… no clients.</li>
        <li class="red">No trust.</li>
        <li class="red">No real proof.</li>
      </ul>
    </div>
    <div class="problem-right">
      <div class="problem-counter" id="problemCounter">0</div>
      <div class="problem-note">developers building <strong>every day</strong> —<br>invisible to the people who matter.<br><br><span style="color:#2e2e2c;">The work <span class="hi">exists.</span><br>The system just doesn't see it.</span></div>
    </div>
  </div>
</section>

<section id="broke">
  <div class="broke-ghost">ENOUGH</div>
  <div class="broke-content">
    <div class="broke-eyebrow">// the declaration</div>
    <div class="broke-headline">The era of the<br><span class="dim">'Broke Graduate'</span><br>ends <span class="gold-word">here.</span></div>
    <p class="broke-sub">We are building a new era of the freelance ecosystem — where talent is not judged by certificates alone, but by <strong>real output, visible progress,</strong> and trusted collaboration.<br><br>A system where beginners do not stay beginners forever.</p>
  </div>
</section>

<section id="rootcause">
  <div class="section-label">// root cause</div>
  <div class="cause-layout">
    <div class="cause-terminal">
      <div class="terminal-header"><div class="d r"></div><div class="d y"></div><div class="d g"></div><span class="t-title">grava — diagnose</span></div>
      <div class="t-line"><span class="p">$</span><span class="cmd">run diagnosis --dev @you</span></div>
      <div class="t-line">&nbsp;</div>
      <div class="t-line"><span class="dim">scanning portfolio…</span></div>
      <div class="t-line"><span class="out">→ static. last updated: 8mo ago</span></div>
      <div class="t-line">&nbsp;</div>
      <div class="t-line"><span class="dim">checking credentials…</span></div>
      <div class="t-line"><span class="out">→ 3 certs. 0 proof of execution</span></div>
      <div class="t-line">&nbsp;</div>
      <div class="t-line"><span class="dim">reading github signal…</span></div>
      <div class="t-line"><span class="ok">→ activity: high</span></div>
      <div class="t-line"><span class="out">→ trust signal: none</span></div>
      <div class="t-line">&nbsp;</div>
      <div class="t-line"><span class="dim">checking potential…</span></div>
      <div class="t-line"><span class="pot">→ potential: unread by the system</span></div>
      <div class="t-line">&nbsp;</div>
      <div class="t-line"><span class="dim">conclusion:</span></div>
      <div class="t-line"><span class="out">→ the system doesn't see you.</span></div>
      <div class="t-line"><span class="dim">yet.</span> <span class="cursor-blink"></span></div>
    </div>
    <div class="cause-statements">
      <div class="cause-item"><span class="cause-num">01</span><div class="cause-text">Portfolios are <span class="hi">static.</span><em>A snapshot of who you were. Not who you are.</em></div></div>
      <div class="cause-item"><span class="cause-num">02</span><div class="cause-text">Certificates don't prove <span class="hi">execution.</span><em>They prove you passed a quiz.</em></div></div>
      <div class="cause-item"><span class="cause-num">03</span><div class="cause-text">Platforms reward history, not <span class="hi">potential.</span><em>No track record, no credibility. Circular by design.</em></div></div>
      <div class="cause-item"><span class="cause-num">04</span><div class="cause-text">Even your GitHub shows activity…<em>but not <span class="hi">trust.</span></em></div></div>
    </div>
  </div>
</section>

<section id="shift">
  <div class="shift-bg-text">PROOF</div>
  <p class="shift-q">What if your work<br>could <span class="typing-target"></span></p>
</section>

<section id="movement">
  <div class="movement-left">
    <p class="move-statement">// we're not building another platform</p>
    <h2 class="move-h">We're building<br>a system.</h2>
    <p class="move-body">Every platform today asks you to <strong>prove your past.</strong><br>We're building one that lets your <strong>present speak for itself.</strong><br><br>Real work. Tracked with transparency. Trusted by everyone.</p>
    <div class="move-system">
      <div class="move-system-line">work becomes proof</div>
      <div class="move-system-line">proof becomes trust</div>
      <div class="move-system-line gold-line">trust becomes opportunity</div>
    </div>
  </div>
  <div class="movement-right">
    <div class="pipeline">
      <div class="pipe-node"><div class="pipe-dot active"><div class="inner"></div></div><div class="pipe-content"><div class="pipe-label">Input</div><div class="pipe-text">Your visible work</div></div></div>
      <div class="pipe-node"><div class="pipe-dot active"><div class="inner"></div></div><div class="pipe-content"><div class="pipe-label">Transform</div><div class="pipe-text">Becomes verifiable proof</div></div></div>
      <div class="pipe-node"><div class="pipe-dot active"><div class="inner"></div></div><div class="pipe-content"><div class="pipe-label">Signal</div><div class="pipe-text">Builds real trust</div></div></div>
      <div class="pipe-node"><div class="pipe-dot gold-dot"><div class="inner"></div></div><div class="pipe-content"><div class="pipe-label">Output</div><div class="pipe-text gold-text">Opportunity finds you <span class="pipe-arrow">— soon</span></div></div></div>
    </div>
  </div>
</section>

<section id="duality" style="padding:0;">
  <div class="dual-side reveal">
    <div class="dual-tag">For Clients</div>
    <h3 class="dual-h">Track with Transparency.<br><em>Hide nothing.</em></h3>
    <p class="dual-body">Know exactly who you're hiring — not based on what they claim, but on <strong>what they've built, when they built it,</strong> and how they performed.</p>
    <ul class="dual-list"><li>See verified work history, not curated portfolios</li><li>Track project progress in real time</li><li>Hire on proof, not promises</li><li>Transparency that builds trust before the first contract</li></ul>
  </div>
  <div class="dual-side reveal">
    <div class="dual-tag">For Developers</div>
    <h3 class="dual-h">Transparency that builds Trust.<br><em>And your Career.</em></h3>
    <p class="dual-body">Every commit, every deliverable, every sprint — counted. Your growth becomes <strong>visible, verifiable, and valuable.</strong></p>
    <ul class="dual-list"><li>Your work history builds itself, automatically</li><li>Potential outweighs pedigree</li><li>No more "prove you've done it before"</li><li>Beginners don't stay beginners here</li></ul>
  </div>
</section>

<section id="beginners">
  <div class="section-label">// the promise</div>
  <div class="beginners-layout">
    <div class="beginners-left">
      <div class="beg-eyebrow">// for every developer who started from nothing</div>
      <h2 class="beg-headline">A system where<br>beginners don't<br>stay <em>beginners</em><br><span class="gold-word">forever.</span></h2>
      <p class="beg-body">The first project is always the hardest to sell. The first client is always the hardest to find. Not because you can't do the work — because no one can see that you can.<br><br>We're changing that infrastructure.</p>
      <button class="beg-cta" onclick="showPage('form')">Apply before it opens →</button>
    </div>
    <div class="beginners-right">
      <div class="progress-widget">
        <div class="pw-header"><span class="pw-title">dev_progress.json</span><span class="pw-badge">● live tracking</span></div>
        <div class="pw-track" id="progressBars">
          <div class="pw-row"><div class="pw-row-head"><span class="pw-skill">Output Quality</span><span class="pw-pct">84%</span></div><div class="pw-bar"><div class="pw-bar-fill" data-width="84"></div></div></div>
          <div class="pw-row"><div class="pw-row-head"><span class="pw-skill">Trust Signal</span><span class="pw-pct">71%</span></div><div class="pw-bar"><div class="pw-bar-fill" data-width="71"></div></div></div>
          <div class="pw-row"><div class="pw-row-head"><span class="pw-skill">Verified Execution</span><span class="pw-pct">92%</span></div><div class="pw-bar"><div class="pw-bar-fill" data-width="92"></div></div></div>
          <div class="pw-row"><div class="pw-row-head"><span class="pw-skill">Visibility Score</span><span class="pw-pct">63%</span></div><div class="pw-bar"><div class="pw-bar-fill" data-width="63"></div></div></div>
          <div class="pw-row"><div class="pw-row-head"><span class="pw-skill">Opportunity Index</span><span class="pw-pct" style="color:var(--gold);">↑ rising</span></div><div class="pw-bar"><div class="pw-bar-fill gold" data-width="48"></div></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="closing">
  <div class="closing-orbit"></div><div class="closing-orbit closing-orbit-2"></div>
  <div class="closing-content">
    <p class="closing-label">// the question you should be asking</p>
    <h2 class="closing-h">What exactly<br>is <em>Grava</em><br>building?</h2>
    <p class="closing-sub">We won't explain it here.<br>Some things are better understood by being inside them.<br><br>Come find out for real.</p>
    <div class="closing-btns">
      <button class="btn-primary" onclick="showPage('form')">Apply to The Filter →</button>
      <a href="#hero" class="btn-secondary">Start from the top ↑</a>
    </div>
  </div>
</section>

<footer>
  <div class="footer-logo">Grava<span>.</span></div>
  <div class="footer-right">
    <div class="footer-pulse"><span class="pulse-dot"></span>Building in stealth — check back for updates</div>
    <div class="footer-copy">© 2025 Grava — All rights reserved</div>
  </div>
</footer>
</div><!-- /page-main -->

<!-- ════ PAGE: FORM ════ -->
<div id="page-form">
  <div class="form-page-hero">
    <div>
      <button class="form-page-back" onclick="showPage('main')">← Back to Grava</button>
      <div class="form-page-title">The<br>Grava<br><span>Filter.</span></div>
    </div>
    <div class="form-page-desc">
      <strong>Not a job application.</strong> More of a vibe check.<br><br>
      We're not looking for polished answers or LinkedIn-speak. We're looking for the way you <strong>think.</strong><br><br>
      If a question makes you pause — good. That's the point.<br><br>
      <span style="color:#2e2e2c;font-size:11px;">Questions: 20 &nbsp;·&nbsp; Time: however long it takes &nbsp;·&nbsp; Bullshit tolerance: zero</span>
    </div>
  </div>

  <div class="filter-body">
    <?php if($submitError): ?>
    <div class="form-global-error"><?= htmlspecialchars($submitError) ?></div>
    <?php endif; ?>

    <form id="gravaForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <input type="hidden" name="grava_filter" value="1"/>
      <input type="hidden" name="fp" id="fpField"/>
      <!-- honeypot — invisible to humans -->
      <input type="text" name="website_url" style="position:absolute;left:-9999px;opacity:0;height:0;width:0;" tabindex="-1" autocomplete="off"/>

      <div class="filter-section-heading">01 / The Basics</div>

      <div class="form-row"><span class="form-num">01</span><div class="form-field">
        <label class="form-label">Name</label>
        <input type="text" name="name" placeholder="First and last" value="<?= old('name') ?>" class="<?= isset($fieldErrors['name'])?'input-error':'' ?>" required/>
        <?php if(isset($fieldErrors['name'])): ?><span class="form-error-msg"><?= $fieldErrors['name'] ?></span><?php endif; ?>
      </div></div>

      <div class="form-row"><span class="form-num">02</span><div class="form-field">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" placeholder="+91 00000 00000" value="<?= old('phone') ?>"/>
      </div></div>

      <div class="form-row"><span class="form-num">03</span><div class="form-field">
        <label class="form-label">What do you do right now?</label>
        <span class="form-note">One line. No LinkedIn bios, please.</span>
        <input type="text" name="current_role" placeholder="e.g. building a SaaS in college while pretending to study" value="<?= old('current_role') ?>"/>
      </div></div>

      <div class="filter-section-heading gold-head">02 / The Vibe Check</div>

      <div class="form-row"><span class="form-num">04</span><div class="form-field">
        <label class="form-label">You get Rs. 10 lakh but you can never use Google, ChatGPT, or any search engine again. Do you take it? Why?</label>
        <textarea name="q_search_engine" placeholder="Think before you answer…"><?= old('q_search_engine') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">05</span><div class="form-field">
        <label class="form-label">What's one thing AI will never be able to do better than a human?</label>
        <textarea name="q_ai_limitation" placeholder="Be honest. Be specific."><?= old('q_ai_limitation') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">06</span><div class="form-field">
        <label class="form-label">What's something you believed 2 years ago that you've completely changed your mind about?</label>
        <textarea name="q_changed_mind" placeholder="The more uncomfortable, the better."><?= old('q_changed_mind') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">07</span><div class="form-field">
        <label class="form-label">You're starting a company tomorrow with Rs. 5 lakh. What do you build and what's your first move?</label>
        <textarea name="q_company_idea" placeholder="Go."><?= old('q_company_idea') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">08</span><div class="form-field">
        <label class="form-label">What's one opinion you hold that most people in your industry would disagree with?</label>
        <textarea name="q_opinion" placeholder="Say it plainly."><?= old('q_opinion') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">09</span><div class="form-field">
        <label class="form-label">What are your top 5 favorite mass-premium brands in the world right now? Why?</label>
        <div class="brands-grid">
          <div class="brand-input-wrap"><span>1.</span><input type="text" name="brand_1" placeholder="Brand" value="<?= old('brand_1') ?>"/></div>
          <div class="brand-input-wrap"><span>2.</span><input type="text" name="brand_2" placeholder="Brand" value="<?= old('brand_2') ?>"/></div>
          <div class="brand-input-wrap"><span>3.</span><input type="text" name="brand_3" placeholder="Brand" value="<?= old('brand_3') ?>"/></div>
          <div class="brand-input-wrap"><span>4.</span><input type="text" name="brand_4" placeholder="Brand" value="<?= old('brand_4') ?>"/></div>
          <div class="brand-input-wrap"><span>5.</span><input type="text" name="brand_5" placeholder="Brand" value="<?= old('brand_5') ?>"/></div>
        </div>
      </div></div>

      <div class="form-row"><span class="form-num">10</span><div class="form-field">
        <label class="form-label">Share one thing you've made.</label>
        <span class="form-note">A reel, meme, deck, spreadsheet, product, system, or playlist. Drop a link.</span>
        <input type="url" name="made_link" placeholder="https://" value="<?= old('made_link') ?>"/>
      </div></div>

      <div class="form-row"><span class="form-num">11</span><div class="form-field">
        <label class="form-label">What's something niche that you're way deep into?</label>
        <span class="form-note">A topic or hobby you know way too much about.</span>
        <textarea name="q_niche" placeholder="Go niche. We're curious."><?= old('q_niche') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">12</span><div class="form-field">
        <label class="form-label">Jokes aside, what do you think Grava is actually doing?</label>
        <span class="form-note">Short answer. Go wild.</span>
        <textarea name="q_grava_theory" placeholder="Your theory…" style="min-height:80px;"><?= old('q_grava_theory') ?></textarea>
      </div></div>

      <div class="filter-section-heading">03 / The Fit</div>

      <div class="form-row"><span class="form-num">13</span><div class="form-field">
        <label class="form-label">Which function are you interested in?</label>
        <select name="function_interest" id="fn_select" required>
          <option value="" disabled <?= old('function_interest')?'':'selected' ?>>Select your function →</option>
          <?php $fns=['Design','Brand Strategy','Marketing','Content / Copywriting','Social Media','Motion Design','Videographer / Editor','Writer','3D Art / Animation','Industrial Design','UX Research','Product Management','Project Management','Software Engineering','Data & Analytics','Finance','Operations','Supply Chain','Sales / Business Development','Customer Success','HR & People','Legal','Strategy','Other'];
          $oldFn=old('function_interest');
          foreach($fns as $fn): $sel=($oldFn===htmlspecialchars($fn))?'selected':'';?>
          <option value="<?= htmlspecialchars($fn) ?>" <?= $sel ?>><?= htmlspecialchars($fn) ?></option>
          <?php endforeach; ?>
        </select>
        <?php if(isset($fieldErrors['function_interest'])): ?><span class="form-error-msg"><?= $fieldErrors['function_interest'] ?></span><?php endif; ?>
      </div></div>

      <div class="form-row"><span class="form-num">14</span><div class="form-field">
        <label class="form-label">Do you have a notice period, or can you join immediately?</label>
        <input type="text" name="notice_period" placeholder="e.g. Available immediately / 30-day notice" value="<?= old('notice_period') ?>"/>
      </div></div>

      <div class="form-row"><span class="form-num">15</span><div class="form-field">
        <label class="form-label">Email Address</label>
        <span class="form-note">So we can actually reach you.</span>
        <!-- real-time validation happens on blur via JS -->
        <input type="email" name="email" id="emailField" placeholder="you@domain.com"
          value="<?= old('email') ?>"
          class="<?= isset($fieldErrors['email'])?'input-error':'' ?>"
          required autocomplete="email"/>
        <span id="emailStatus"><?php if(isset($fieldErrors['email'])): ?><span class="form-error-msg"><?= $fieldErrors['email'] ?></span><?php endif; ?></span>
      </div></div>

      <div class="form-row"><span class="form-num">16</span><div class="form-field">
        <label class="form-label">Your LinkedIn Profile</label>
        <input type="url" name="linkedin" placeholder="https://linkedin.com/in/yourhandle" value="<?= old('linkedin') ?>"/>
      </div></div>

      <div class="form-row"><span class="form-num">17</span><div class="form-field">
        <label class="form-label">Resume / Portfolio Link</label>
        <span class="form-note">Optional. But points for having one.</span>
        <input type="url" name="resume_link" placeholder="https://" value="<?= old('resume_link') ?>"/>
      </div></div>

      <!-- ══ COMMUNITY INSIGHTS ══ -->
      <div class="filter-section-heading gold-head">04 / Community Insights</div>
      <p class="insights-intro">
        // We build this with the people inside it.<br>
        // These questions shape what Grava becomes.<br>
        // Be specific. Be honest. We read every single one.
      </p>

      <div class="form-row"><span class="form-num">18</span><div class="form-field">
        <label class="form-label">What pricing model makes sense to you?</label>
        <span class="form-note">Monthly subscription? Per-project fee? Revenue share? What would you actually pay, and how much?</span>
        <textarea name="fb_pricing" placeholder="e.g. Rs. 499/month feels right if it replaces my portfolio and actually gets me clients. Free tier for students."><?= old('fb_pricing') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">19</span><div class="form-field">
        <label class="form-label">What's the ONE must-have feature Grava needs to ship first?</label>
        <span class="form-note">Not a wish list — just the single most important thing.</span>
        <textarea name="fb_features" placeholder="e.g. A live project timeline clients can follow in real time, so they see progress happening instead of waiting for updates."><?= old('fb_features') ?></textarea>
      </div></div>

      <div class="form-row"><span class="form-num">20</span><div class="form-field">
        <label class="form-label">Anything else — product, branding, pricing, the industry?</label>
        <span class="form-note">We will be glad to receive your suggestion. Truly.</span>
        <textarea name="fb_general" placeholder="Go ahead. We're listening."><?= old('fb_general') ?></textarea>
      </div></div>

      <div class="submit-section">
        <p class="submit-note">// a welcome email will arrive immediately after submission.</p>
        <button type="submit" class="submit-btn" id="submitBtn">Submit the filter →</button>
      </div>
    </form>
  </div>

  <footer>
    <div class="footer-logo">Grava<span>.</span></div>
    <button onclick="showPage('main')" style="background:none;border:none;font-family:var(--mono);font-size:10px;color:#282826;letter-spacing:.1em;cursor:pointer;text-transform:uppercase;" onmouseover="this.style.color='#888'" onmouseout="this.style.color='#282826'">← Back to main</button>
  </footer>
</div><!-- /page-form -->

<!-- ════ REFERRAL DASHBOARD (success overlay) ════ -->
<div class="success-overlay" id="successOverlay">
  <div class="ref-dashboard">

    <div class="ref-top">
      <p class="ref-code-tag">// filter.submit — status: received</p>
      <h2 class="ref-headline">You're in<br>the queue.<br><span>We'll find you.</span></h2>
      <p class="ref-sub">A welcome email is on its way. No newsletter, no spam — just a message when it's your time.<br><br>While you wait, your referral code unlocks something better.</p>
    </div>

    <div class="ref-body">

      <!-- LEFT: referral link + share -->
      <div>
        <div class="ref-panel">
          <p class="ref-panel-title">// your referral link</p>

          <div class="ref-incentive">
            <p>Refer <strong>3 people</strong> who pass the filter → <strong>Founding Member</strong> status.<br>
            Refer <strong>10</strong> → <strong>Priority Access</strong> + early feature previews.</p>
          </div>

          <div class="ref-link-wrap">
            <span class="ref-link-txt" id="refLinkDisplay">loading…</span>
            <button class="ref-copy-btn" id="copyBtn" onclick="copyLink()">Copy</button>
          </div>

          <div class="share-row">
            <a class="share-btn wa" id="waLink" href="#" target="_blank" rel="noopener">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              WhatsApp
            </a>
            <a class="share-btn tw" id="twLink" href="#" target="_blank" rel="noopener">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L1.254 2.25H8.08l4.259 5.629L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/></svg>
              X / Twitter
            </a>
            <a class="share-btn li" id="liLink" href="#" target="_blank" rel="noopener">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              LinkedIn
            </a>
          </div>
        </div>
      </div>

      <!-- RIGHT: direct invite + position -->
      <div>
        <div class="ref-panel" style="margin-bottom:16px;">
          <p class="ref-panel-title">// send a personal invite</p>
          <p class="invite-label">Your friend's name will be in the email. They'll know you sent it, and they'll get priority over everyone who isn't referred.</p>
          <div class="invite-row">
            <input class="invite-inp" type="email" id="inviteEmailInput" placeholder="friend@email.com"/>
            <button class="invite-send" onclick="sendInvite()">Send →</button>
          </div>
          <p class="invite-msg" id="inviteMsg"></p>
        </div>

        <div class="ref-panel">
          <p class="ref-panel-title">// your position</p>
          <div class="position-badge">
            <div class="pos-num" id="posNum">—</div>
            <div class="pos-label">in the queue</div>
          </div>
          <p style="font-family:var(--mono);font-size:11px;color:#3a3a37;line-height:1.8;">Every referred signup moves you forward. Every person who uses your link and passes the filter counts toward your Founding Member milestone.</p>
        </div>
      </div>

    </div>

    <button class="ref-dismiss" onclick="closeSuccess()">← done — back to grava</button>
  </div>
</div><!-- /success-overlay -->

<!-- ════ JAVASCRIPT ════ -->
<script>
var SHOW_FORM    = <?= $showFormPage ?>;
var SHOW_SUCCESS = <?= $submitSuccess?'true':'false' ?>;
var PHP_ERRORS   = <?= $phpErrors ?>;
var REF_CODE     = <?= $refCodeJs ?>;
var SITE_URL     = <?= json_encode($siteUrl) ?>;
</script>
<script src="assets/js/site.js" defer></script>
</body>
</html>
