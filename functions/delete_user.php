<?php
require_once 'db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $pdo = getPDO();

    try {
    // Elimina le spese dell'utente
    $pdo->prepare("DELETE FROM spese WHERE id_user = :id")->execute([':id' => $id]);

    // Elimina le notifiche associate all'utente
    $pdo->prepare("DELETE FROM notifiche_utenti WHERE id_utente = :id")->execute([':id' => $id]);

    // Ora elimina l'utente
    $pdo->prepare("DELETE FROM user WHERE id = :id")->execute([':id' => $id]);

        echo json_encode(['success' => true, 'message' => 'Utente eliminato con successo']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida']);
}
