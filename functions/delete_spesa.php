<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Accesso negato.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $user_id = $_SESSION['user_id'];

    try {
        $pdo = getPDO();
        $stmt = $pdo->prepare("DELETE FROM spese WHERE id = :id AND id_user = :user_id");
        $stmt->execute([':id' => $id, ':user_id' => $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Spesa eliminata.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nessuna spesa eliminata (forse non sei il proprietario).']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Errore DB: ' . $e->getMessage()]);
    }
}
