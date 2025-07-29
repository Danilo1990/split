<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Accedi'); ?>
  <div class="container">
    <div class="login-card">
      <h4 class="text-center mb-4"><i class="bi bi-person-circle"></i> Accedi</h4>
      <form action="functions/login_process.php" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control form-control-lg" id="email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control form-control-lg" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Accedi</button>
        <div class="text-center mt-3">
          <a href="register.php" class="text-decoration-none">Non hai un account? Registrati</a>
        </div>
      </form>
    </div>
  </div>
<?php footer() ?>