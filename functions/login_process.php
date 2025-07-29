<?php
session_start();
require_once 'db.php'; // importa la funzione getPDO()

$pdo = getPDO(); // connessione al database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Recupera l'utente dal DB
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utente && password_verify($password, $utente['passwrd'])) {
        // Autenticazione riuscita, salva dati in sessione
        $_SESSION['user_email'] = $utente['email'];
        $_SESSION['user_nome']  = $utente['nome'];
        $_SESSION['user_id'] = $utente['id'];


        header('Location: ../index.php');
        exit;
    } else {
        // Credenziali errate
        $_SESSION['login_error'] = "Email o password errati.";
        header('Location: login.php');
        exit;
    }
}