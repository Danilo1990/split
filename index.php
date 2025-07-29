<?php session_start(); ?>
<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Home'); ?>

<?php if (isset($_SESSION['user_email'])) { navBar(); } ?>

<div class="container">
  <div class="card-home">

    <?php if (!isset($_SESSION['user_email'])) { ?>
      <h3 class="mb-4 text-center">Benvenuto/a!</h3>
      <a class="btn btn-primary btn-lg w-100 mb-2" href="login.php">Accedi</a>
      <a class="btn btn-outline-primary btn-lg w-100" href="register.php">Registrati</a>

    <?php } else { ?>
      <h4 class="mb-4">Ciao <?php echo htmlspecialchars($_SESSION['user_nome']); ?> 👋</h4>

      <form id="form-spesa">
        <div class="mb-3">
          <label for="inputState">Tipologia</label>
          <select id="tipologia" name="tipologia" class="form-control">
            <option value="Casa">🏚️ Casa</option>
            <option value="Cibo">🥘 Cibo</option>
            <option value="Attivita">🏃 Attività</option>
            <option value="Altro">🚀 Altro</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="nome_spesa" class="form-label">Che spesa hai fatto?</label>
          <input type="text" id="nome_spesa" name="nome_spesa" required class="form-control form-control-lg">
        </div>
        <div class="mb-3">
          <label for="costo" class="form-label">Quanto hai speso</label>
          <input type="number" step="0.01" id="costo" name="costo" required class="form-control form-control-lg">
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Aggiungi spesa</button>
      </form>
    <?php } ?>

  </div>
</div>

<?php footer() ?>