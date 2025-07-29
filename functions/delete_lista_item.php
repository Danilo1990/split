<?php
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID non valido']);
        exit;
    }

    try {
        $pdo = getPDO();
        $stmt = $pdo->prepare("DELETE FROM lista_spesa WHERE id_oggetto = :id");
        $stmt->execute([':id' => $id]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
