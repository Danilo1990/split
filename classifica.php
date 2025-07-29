<?php session_start(); ?>
<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Classifica'); ?>
<div class="container-fluid mb-3">
  <?php navBar(); ?>
</div>
<section class="container my-4">
  <div class="card-classifica">
    <h4 class="mb-4 text-center"><i class="bi bi-trophy-fill text-warning"></i> Classifica spese</h4>
    <?php classificaSpese(); ?>
  </div>
</section>
<?php footer() ?>