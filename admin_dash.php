<?php
session_start();
require_once 'functions.php';
injectScripts();

// Controlla se l'utente è loggato
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

// Email abilitata ad accedere a questa pagina
$email_autorizzata = 'danicala23@gmail.com';

// Verifica se l'utente loggato è l'utente autorizzato
if ($_SESSION['user_email'] !== $email_autorizzata) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Accesso negato. Non hai i permessi per visualizzare questa pagina.</div></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Area Riservata Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>
    body {
      background-color: #f8f9fa;
    }

  </style>
</head>
<body>

<div class="container-fluid mb-4">
  <?php navBar(); ?>
</div>

<section class="container">
  <div class="admin-card">
    <h1 class="mb-4 text-center"><i class="bi bi-shield-lock-fill text-primary"></i> Area Amministratore</h1>
    <h4 class="mb-3">Utenti salvati</h4>
    <?php mostraUtenti(); ?>
  </div>
</section>

</body>
</html>
