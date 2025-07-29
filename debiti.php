<?php session_start(); ?>
<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Debiti'); ?>

<div class="container-fluid mb-3">
  <?php navBar(); ?>
</div>

<section class="container my-4">
  <div class="card-debiti">
    <h4 class="mb-4 text-center"><i class="bi bi-arrow-left-right"></i> Riepilogo debiti</h4>
    <?php calcolaRimborsiSpese(); ?>
  </div>
</section>
<?php footer() ?>