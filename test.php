<?php
require_once 'functions.php';

$testo_pulito = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['testo'])) {
    $input = $_POST['testo'];

    // Rimuove <span style="font-weight: 400;"> e </span>
    $input = preg_replace('/<span[^>]*style="font-weight:\s*400;"[^>]*>/i', '', $input);
    $input = preg_replace('/<\/span>/i', '', $input);

    // Rimuove tutti gli attributi style="font-weight: 400;" da qualsiasi tag
    $input = preg_replace('/\s*style="font-weight:\s*400;"/i', '', $input);

    // Rimuove aria-level="1"
    $input = preg_replace('/\s*aria-level="1"/i', '', $input);

    // Rimuove tutti i &nbsp;
    $input = str_replace('&nbsp;', ' ', $input);

    $testo_pulito = $input;
}

injectScripts();
head('Accedi');
?>

<div class="container">
  <div class="-card">
    <form action="" method="post">
      <div class="mb-3 mt-5">
        <textarea class="form-control form-control-lg" id="testo" name="testo" rows="20"><?php echo htmlspecialchars($testo_pulito); ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-lg w-100">Ripulisci</button>
    </form>
  </div>
</div>

<?php footer(); ?>
