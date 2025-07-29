<?php session_start(); ?>
<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Home'); ?>


<?php if (isset($_SESSION['user_email'])) { navBar(); } ?>

<div class="container">
    <div class="card-home">
      <form id="form-lista">
        <div class="mb-3">
          <label for="oggetto" class="form-label">Cosa comprare?</label>
          <input type="text" id="oggetto" name="oggetto" required class="form-control form-control-lg">
        </div>
        <div class="mb-3">
          <label for="quantita" class="form-label">Quantità</label>
          <input type="number" id="quantita" name="quantita" required class="form-control form-control-lg">
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Aggiungi alla lista</button>
      </form>
      
    </div>
    <div class="card-home">
        <h4>Lista</h4>
        <?php mostraLista() ?>
    </div>
</div>

<?php footer() ?>