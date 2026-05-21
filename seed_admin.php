<?php
// ------------------------------------------------------------
// Seed de usuario administrador (usar UNA sola vez)
// ------------------------------------------------------------
require_once __DIR__ . '/db.php';

// Lee credenciales desde .env
$username = trim(env('ADMIN_USER'));
$password = trim(env('ADMIN_PASS'));

if ($username === '' || $password === '') {
    echo 'Faltan ADMIN_USER o ADMIN_PASS en .env';
    exit;
}

$pdo = db();

// Verifica si el usuario ya existe
$stmt = $pdo->prepare('SELECT id FROM admin_users WHERE username = ?');
$stmt->execute([$username]);
$exists = $stmt->fetchColumn();

if ($exists) {
    echo 'El usuario ya existe. No se hizo ningún cambio.';
    exit;
}

// Inserta el nuevo usuario con contraseña hasheada
$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
$insert->execute([$username, $hash]);

echo 'Usuario administrador creado correctamente.';
