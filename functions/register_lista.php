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
        $oggetto = trim($_POST['oggetto'] ?? '');
        $quantita = trim($_POST['quantita'] ?? '');

        $sql = "INSERT INTO lista_spesa (oggetto, quantita) VALUES (:oggetto, :quantita)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':oggetto' => $oggetto,
            ':quantita' => $quantita,
        ]);

        echo json_encode(['success' => true, 'message' => 'Spesa aggiunta con successo!']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Richiesta non valida.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
}
