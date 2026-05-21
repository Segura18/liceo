<?php
// ------------------------------------------------------------
// Manejo de sesión y control de acceso
// ------------------------------------------------------------
require_once __DIR__ . '/db.php';

function start_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function is_logged_in(): bool
{
    start_session();
    return isset($_SESSION['admin_id']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// ------------------------------------------------------------
// Inicializar usuario admin si la tabla está vacía (opcional)
// ------------------------------------------------------------
function bootstrap_admin_if_empty(): void
{
    $pdo = db();
    $count = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $user = env('ADMIN_USER');
    $pass = env('ADMIN_PASS');
    if ($user === '' || $pass === '') {
        return;
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$user, $hash]);
}
