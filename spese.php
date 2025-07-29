<?php session_start(); ?>
<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Spese'); ?>

<div class="container-fluid mb-3">
  <?php navBar(); ?>
</div>

<div class="container container-spese">
  <div class="card p-4 mb-2 shadow-sm">
    <h4 class="mb-4 text-center"><i class="bi bi-receipt-cutoff"></i> Lista delle spese</h4>
    <?php allSpese() ?>
    <?php 
      $pdo = getPDO();
      $stmt = $pdo->query("
          SELECT tipologia, SUM(prezzo) AS totale
          FROM spese
          GROUP BY tipologia
      ");
      
      $dati = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // Prepara i dati per JS
      $labels = [];
      $values = [];
      
      foreach ($dati as $riga) {
          $labels[] = $riga['tipologia'];
          $values[] = (float)$riga['totale'];
      }
    ?>
  </div>
  <!-- GRAFICO SPESE -->
  <div class="card p-4 mb-5 shadow-sm">
    <canvas id="graficoSpese" width="150" height="150"></canvas>
  </div>
</div>
<!-- SCRIPT GRAFICO SPESE -->
<script>
  const ctx = document.getElementById('graficoSpese').getContext('2d');
  const chart = new Chart(ctx, {
      type: 'doughnut',
      data: {
          labels: <?php echo json_encode($labels); ?>,
          datasets: [{
              label: 'Spese per tipologia',
              data: <?php echo json_encode($values); ?>,
              backgroundColor: [
                  '#800080', '#f3722c', '#f9c74f', '#90be6d', '#43aa8b', '#577590'
              ],
              borderColor: '#fff',
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'bottom'
              }
          }
      }
  });
</script>

<?php footer() ?>