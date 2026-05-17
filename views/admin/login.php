<div class="login-wrap">
  <div class="login-box">
    <div class="login-logo">Grava.</div>
    <div class="login-tag">// admin access</div>
    <?php if($loginError): ?>
    <p class="login-error">✗ <?= esc($loginError) ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="hidden" name="admin_login" value="1"/>
      <label class="login-label">Username</label>
      <input class="login-input" type="text" name="username" autocomplete="username" required/>
      <label class="login-label">Password</label>
      <input class="login-input" type="password" name="password" autocomplete="current-password" required/>
      <button class="login-btn" type="submit">Access Dashboard →</button>
    </form>
  </div>
</div>
