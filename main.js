document.addEventListener('DOMContentLoaded', () => {
  initDeleteButtons();
  initSpesaForm();
  initDeleteUsers();
  initListaSpesaForm();
  initDeleteListaSpesa();
});

function initSpesaForm() {
  const form = document.getElementById('form-spesa');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('functions/register_spesa.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          title: "Spesa inserita!",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        this.reset();
      } else {
        Swal.fire({
          icon: "error",
          title: "Errore",
          text: data.message || "Qualcosa è andato storto!"
        });
      }
    })
    .catch(error => {
      Swal.fire({
        icon: "error",
        title: "Errore",
        text: error
      });
    });
  });
}
function initDeleteButtons() {
  const table = document.querySelector('table');
  if (!table) return;

  table.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-btn');
    if (!btn) return;

    const id = btn.getAttribute('data-id');

    Swal.fire({
      title: 'Sei sicuro?',
      text: 'Questa spesa sarà eliminata definitivamente.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sì, elimina!',
      cancelButtonText: 'Annulla'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('functions/delete_spesa.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            const row = document.getElementById('spesa-' + id);
            if (row) row.remove();

            Swal.fire({
              title: 'Eliminata!',
              text: data.message,
              icon: 'success',
              timer: 1500,
              showConfirmButton: false
            });
          } else {
            Swal.fire('Errore', data.message, 'error');
          }
        })
        .catch(error => {
          Swal.fire('Errore', 'Errore nella richiesta AJAX', 'error');
        });
      }
    });
  });
}
function initDeleteUsers() {
  document.querySelector('table')?.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-user-btn');
    if (!btn) return;

    const id = btn.getAttribute('data-id');

    Swal.fire({
      title: 'Sei sicuro?',
      text: 'L\'utente sarà eliminato definitivamente.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonText: 'Annulla',
      confirmButtonText: 'Sì, elimina'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('/functions/delete_user.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            document.querySelector(`button[data-id="${id}"]`).closest('tr').remove();
            Swal.fire('Eliminato!', data.message, 'success');
          } else {
            Swal.fire('Errore', data.message, 'error');
          }
        })
        .catch(err => {
          Swal.fire('Errore', 'Errore nella richiesta AJAX', 'error');
        });
      }
    });
  });
}
function initListaSpesaForm() {
  const form = document.getElementById('form-lista');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('functions/register_lista.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire({
          title: "Oggetto inserita!",
          icon: "success",
          showConfirmButton: false,
          timer: 1500
        });
        this.reset();
      } else {
        Swal.fire({
          icon: "error",
          title: "Errore",
          text: data.message || "Qualcosa è andato storto!"
        });
      }
    })
    .catch(error => {
      Swal.fire({
        icon: "error",
        title: "Errore",
        text: error
      });
    });
  });
}
function initDeleteListaSpesa() {
  document.querySelector('table')?.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-user-btn');
    if (!btn) return;

    const id = btn.getAttribute('data-id');
    fetch('/functions/delete_lista_item.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        // Rimuove la riga dalla tabella
        btn.closest('tr').remove();

        // Mostra il messaggio SweetAlert2
        Swal.fire({
          icon: 'success',
          title: 'Oggetto eliminato',
          showConfirmButton: false,
          timer: 1200
        });
      } else {
        Swal.fire('Errore', data.message || 'Errore durante l\'eliminazione', 'error');
      }
    })
    .catch(err => {
      Swal.fire('Errore', 'Errore nella richiesta AJAX', 'error');
    });
  });
}
