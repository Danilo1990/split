<?php
require_once 'functions/db.php'; // usa getPDO()

function head($title) { 
    echo '<!DOCTYPE html>
    <html lang="it">
    <head>
      <meta charset="UTF-8">
      <title>'.$title.'</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>';
}
function footer() {
    echo '
    </body>
    </html>';
}
function injectScripts() {
    echo '
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/main.js" defer></script>';
}
function navBar() {
    $user_id = $_SESSION['user_id'] ?? null;
    $notifiche_non_lette = $user_id ? getNumeroNotificheNonViste($user_id) : 0;

    echo '
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="/logo.png" alt="Logo" width="180" height="50" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Menu a sinistra -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link fs-5" href="/">Aggiungi spesa</a></li>
                    <li class="nav-item"><a class="nav-link fs-5" href="/spese.php">Totale spese</a></li>
                    <li class="nav-item"><a class="nav-link fs-5" href="/classifica.php">Classifica</a></li>
                    <li class="nav-item"><a class="nav-link fs-5" href="/debiti.php">Debiti</a></li>
                    <li class="nav-item"><a class="nav-link fs-5" href="/lista_spesa.php">Lista spesa</a></li>';
    
    if (isset($_SESSION['user_email']) && $_SESSION['user_email'] === 'danicala23@gmail.com') {
        echo '<li class="nav-item"><a class="nav-link fs-5" href="/admin_dash.php">Amministratore</a></li>';
    }

    echo '<li class="nav-item"><a class="nav-link fs-5" href="/logout.php">Esci</a></li>
                </ul>';

    // Menu a destra: Notifiche
    echo '<ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link position-relative" href="/notifiche.php">
                <i class="bi bi-bell fs-5"></i>';
    if ($notifiche_non_lette > 0) {
        echo '<span class=" start-100 translate-middle badge rounded-pill bg-danger">'
           . $notifiche_non_lette . '</span>';
    }
    echo '</a></li>
        </ul>';

    echo '</div></div></nav>';
}
function mostraUtenti() {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT email,id,nome FROM user ORDER BY id ASC");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$utenti) {
        echo "<p>Nessun utente trovato.</p>";
        return;
    }

    echo "<table class='table table-striped table-bordered centered'>";
    echo "<tr>
        <th>Nome</th>
        <th>Email</th>
        <th></th>
    </tr>";

    foreach ($utenti as $utente) {
        echo "<tr>
            <td>" . htmlspecialchars($utente['nome']) . "</td>
            <td>" . htmlspecialchars($utente['email']) . "</td>
            <td class='text-center'>
                <button class='btn delete-user-btn text-danger fs-5' data-id='" . $utente['id'] . "'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
    }

    echo "</table>";
}
function calcolaBilancioSpese() {
    $pdo = getPDO();

    // 1. Recupera TUTTI gli utenti e le loro spese (anche se 0)
    $stmt = $pdo->query("
        SELECT user.id, user.nome, 
               COALESCE(SUM(spese.prezzo), 0) AS totale_speso
        FROM user
        LEFT JOIN spese ON spese.id_user = user.id
        GROUP BY user.id, user.nome
    ");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$utenti) {
        echo "<p>Nessun utente trovato.</p>";
        return;
    }

    // 2. Calcola il totale generale delle spese
    $totale_generale = 0;
    foreach ($utenti as $u) {
        $totale_generale += $u['totale_speso'];
    }

    // 3. Quota divisa per tutti gli utenti
    $numero_utenti = count($utenti);
    $quota_media = $numero_utenti > 0 ? $totale_generale / $numero_utenti : 0;

    // 4. Stampa la tabella con i bilanci
    echo "<h2>Bilancio spese</h2>";
    echo "<table class='table table-striped table-bordered centered'>";
    echo "<tr>
        <th>Utente</th>
        <th>Ha speso</th>
        <th>Bilancio</th>
    </tr>";

    foreach ($utenti as $u) {
        $differenza = $u['totale_speso'] - $quota_media;
        $colore = $differenza >= 0 ? '#d4f4d4' : '#f4d4d4'; // verde o rosso

        echo "<tr>
            <td class='fs-3' style='background-color:$colore !important;'>" . htmlspecialchars($u['nome']) . "</td>
            <td class='fs-3' style='background-color:$colore !important;'>" . number_format($u['totale_speso'], 2, ',', '') . " €</td>
            <td class='fs-3' style='background-color:$colore !important;'>" . number_format($differenza, 2, ',', '') . " € </td>
        </tr>";
    }
    echo "</table>";
}
function calcolaRimborsiSpese() {
    $pdo = getPDO();

    // Recupera tutti gli utenti con le loro spese (anche 0)
    $stmt = $pdo->query("
        SELECT user.id, user.nome, 
               COALESCE(SUM(spese.prezzo), 0) AS totale_speso
        FROM user
        LEFT JOIN spese ON spese.id_user = user.id
        GROUP BY user.id, user.nome
    ");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$utenti) {
        echo "<p>Nessun utente trovato.</p>";
        return;
    }

    // Totale spese e quota media
    $totale_generale = 0;
    foreach ($utenti as $u) {
        $totale_generale += $u['totale_speso'];
    }

    $numero_utenti = count($utenti);
    $quota_media = $numero_utenti > 0 ? $totale_generale / $numero_utenti : 0;

    // Calcola bilanci
    $creditori = [];
    $debitori = [];

    foreach ($utenti as $u) {
        $saldo = round($u['totale_speso'] - $quota_media, 2); // positivo = credito
        $utente = [
            'nome' => $u['nome'],
            'saldo' => $saldo
        ];
        if ($saldo > 0) {
            $creditori[] = $utente;
        } elseif ($saldo < 0) {
            $utente['saldo'] = abs($saldo);
            $debitori[] = $utente;
        }
    }

    // Calcola i rimborsi
    echo "<h2 class='mb-3'>Chi deve dare soldi a chi</h2>";
    if (empty($creditori) || empty($debitori)) {
        echo "<p>Nessun rimborso da calcolare.</p>";
        return;
    }

    echo "<ul class='list-group'>";
    foreach ($debitori as &$debitore) {
        foreach ($creditori as &$creditore) {
            if ($debitore['saldo'] == 0) break;
            if ($creditore['saldo'] == 0) continue;

            $importo = min($debitore['saldo'], $creditore['saldo']);
            $debitore['saldo'] -= $importo;
            $creditore['saldo'] -= $importo;

            echo "<li class='fs-5 list-group-item list-group-item-action'><strong>" . htmlspecialchars($debitore['nome']) . " </strong> <i class='bi bi-arrow-right'></i><strong> " . number_format($importo, 2, ',', '') . " €</strong> a <strong>" . htmlspecialchars($creditore['nome']) . "</strong></li>";
        }
    }
    echo "</ul>";
}
function classificaSpese() {
    $pdo = getPDO();

    // Recupera tutti gli utenti e quanto hanno speso (anche se 0)
    $stmt = $pdo->query("
        SELECT user.nome, 
               COALESCE(SUM(spese.prezzo), 0) AS totale_speso
        FROM user
        LEFT JOIN spese ON spese.id_user = user.id
        GROUP BY user.id, user.nome
        ORDER BY totale_speso DESC
    ");
    $utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$utenti) {
        echo "<p>Nessun utente trovato.</p>";
        return;
    }

    echo "<table class='table table-striped table-bordered centered'>";
    echo "<tr>
        <th>Posizione</th>
        <th>Nome</th>
        <th>Totale speso</th>
    </tr>";

    $posizione = 1;
    foreach ($utenti as $u) {
        echo "<tr>
            <td class=' " . ($posizione == 1 ? 'bg-success text-light' : '') . "'>$posizione</td>
            <td class=' " . ($posizione == 1 ? 'bg-success text-light' : '') . "'>" . htmlspecialchars($u['nome']) . "</td>
            <td class=' " . ($posizione == 1 ? 'bg-success text-light' : '') . "'>" . number_format($u['totale_speso'], 2, ',', '') . " €</td>
        </tr>";
        $posizione++;
    }

    echo "</table>";
}
function mostraNovita() {
    if (!isset($_SESSION['user_id'])) return;

    $pdo = getPDO();

    // Recupera le notifiche non viste
    $stmt = $pdo->prepare("
        SELECT n.id, n.messaggio, n.creata_il
        FROM notifiche n
        INNER JOIN notifiche_utenti nu ON nu.id_notifica = n.id
        WHERE nu.id_utente = :uid AND nu.vista = 0
        ORDER BY n.creata_il DESC
    ");
    $stmt->execute([':uid' => $_SESSION['user_id']]);
    $notifiche = $stmt->fetchAll();

    if ($notifiche) {
        echo "<div class='container my-3'><div class='alert alert-info p-1'><strong>Novità recenti:</strong><ul>";
        foreach ($notifiche as $n) {
            echo "<li>" . htmlspecialchars($n['messaggio']) . "</li>";
        }
        echo "</ul></div></div>";

        $stmt = $pdo->prepare("
            UPDATE notifiche_utenti 
            SET vista = 1 
            WHERE id_utente = :uid AND vista = 0
        ");
        $stmt->execute([':uid' => $_SESSION['user_id']]);
    } else {
        echo "<div class='my-3'><div class='alert alert-secondary'>Nessuna novità non letta.</div></div>";
    }
}
function getNumeroNotificheNonViste($user_id) {
    $pdo = getPDO();

    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM notifiche_utenti 
        WHERE id_utente = :uid AND vista = 0
    ");
    $stmt->execute([':uid' => $user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return (int) $row['total'];
}
function allSpese() {
    $pdo = getPDO();
    $stmt = $pdo->query("
        SELECT spese.id, spese.nome_spesa, spese.prezzo, user.nome AS nome_utente
        FROM spese
        INNER JOIN user ON spese.id_user = user.id
        ORDER BY spese.id ASC
    ");
    $spese = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$spese) {
        echo "<p class='text-muted'>Nessuna spesa trovata.</p>";
        return;
    }

    $totale = 0;

    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered table-striped align-middle text-center'>";
    echo "<thead class='table-dark'>
        <tr>
            <th>Nome spesa</th>
            <th>Costo</th>
            <th>Inserita da</th>
            <th>Azioni</th>
        </tr>
    </thead><tbody>";

    foreach ($spese as $spesa) {
        $totale += (float)$spesa['prezzo'];
        echo "<tr id='spesa-{$spesa['id']}'>
            <td>" . htmlspecialchars($spesa['nome_spesa']) . "</td>
            <td>" . number_format($spesa['prezzo'], 2, ',', '') . " €</td>
            <td>" . htmlspecialchars($spesa['nome_utente']) . "</td>
            <td>
                <button class='btn delete-btn text-danger fs-5' data-id='" . $spesa['id'] . "'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
    }

    echo "</tbody></table>";
    echo "</div>";

    echo "<div class='alert alert-secondary text-end fs-4'>
            Totale spese: <strong>" . number_format($totale, 2, ',', '') . " €</strong>
          </div>";
}
function mostraLista() {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT quantita,oggetto, id_oggetto FROM lista_spesa ORDER BY oggetto ASC");
    $oggetti = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$oggetti) {
        echo "<p>Nessun oggetto trovato.</p>";
        return;
    }

    echo "<table class='table table-striped table-bordered centered'>";
    echo "<tr>
        <th>N°</th>
        <th>Nome</th>
        <th></th>
    </tr>";

    foreach ($oggetti as $oggetto) {
        echo "<tr>
            <td>" . htmlspecialchars($oggetto['quantita']) . "</td>
            <td>" . htmlspecialchars($oggetto['oggetto']) . "</td>
            <td class='text-center'>
                <button class='btn delete-user-btn text-danger fs-5' data-id='" . $oggetto['id_oggetto'] . "'>
                    <i class='bi bi-trash'></i>
                </button>
            </td>
        </tr>";
    }

    echo "</table>";
}