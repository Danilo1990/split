<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

try {
    $pdo = getPDO();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $plainPassword = $_POST['password'] ?? '';
        $nome = trim($_POST['nome'] ?? '');

        if (empty($email) || empty($plainPassword) || empty($nome)) {
            echo json_encode(['success' => false, 'message' => 'Tutti i campi sono obbligatori.']);
            exit;
        }

        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (email, passwrd, nome) VALUES (:email, :passwrd, :nome)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':passwrd' => $hashedPassword,
            ':nome' => $nome
        ]);

        echo json_encode(['success' => true, 'message' => 'Registrazione avvenuta con successo!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore: ' . $e->getMessage()]);
}
