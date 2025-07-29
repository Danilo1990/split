<?php session_start(); ?>
<?php require_once 'functions.php'; ?>
<?php injectScripts(); ?>
<?php head('Registrati'); ?>

<body>
  <div class="container">
    <div class="register-card">
      <h4 class="text-center mb-4"><i class="bi bi-person-plus-fill"></i> Registrati</h4>
      
      <form action="functions/register_process.php" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" required class="form-control form-control-lg">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" required class="form-control form-control-lg">
        </div>
        <div class="mb-3">
          <label for="nome" class="form-label">Come ti chiami?</label>
          <input type="text" id="nome" name="nome" required class="form-control form-control-lg">
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">Registrati</button>
        <div class="text-center mt-3">
          <a href="login.php" class="text-decoration-none">Hai già un account? Accedi</a>
        </div>
      </form>
    </div>
  </div>
  <script>
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('functions/register_process.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Registrazione completata!',
                text: data.message,
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'login.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Errore di rete',
            text: error.message
        });
    });
});
</script>

<?php footer() ?>

