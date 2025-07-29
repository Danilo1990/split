<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Verifica login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_nome'])) {
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato.']);
    exit;
}

try {
    $pdo = getPDO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome_spesa = trim($_POST['nome_spesa'] ?? '');
        $costo = trim($_POST['costo'] ?? '');
        $user_id = $_SESSION['user_id'];
        $nome_utente = $_SESSION['user_nome'];
        $tipologia = trim($_POST['tipologia'] ?? '');

        $sql = "INSERT INTO spese (nome_spesa, prezzo, id_user, tipologia) VALUES (:nome_spesa, :prezzo, :id_user, :tipologia)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome_spesa' => $nome_spesa,
            ':prezzo' => $costo,
            ':id_user' => $user_id,
            ':tipologia' => $tipologia
        ]);

        // ✅ Crea la notifica per gli altri utenti
        $messaggio = "$nome_utente ha aggiunto una spesa \"$nome_spesa\" di " . number_format($costo, 2, ',', '') . " €";

        $stmt = $pdo->prepare("INSERT INTO notifiche (messaggio) VALUES (:msg)");
        $stmt->execute([':msg' => $messaggio]);
        $id_notifica = $pdo->lastInsertId();

        // Associa la notifica a tutti gli altri utenti
        $stmtUsers = $pdo->prepare("SELECT id FROM user WHERE id != :id_mittente");
        $stmtUsers->execute([':id_mittente' => $user_id]);
        foreach ($stmtUsers->fetchAll() as $utente) {
            $pdo->prepare("INSERT INTO notifiche_utenti (id_notifica, id_utente) VALUES (:nid, :uid)")
                ->execute([':nid' => $id_notifica, ':uid' => $utente['id']]);
        }

        echo json_encode(['success' => true, 'message' => 'Spesa aggiunta con successo!']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Richiesta non valida.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
}
