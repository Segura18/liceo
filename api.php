<?php
// ------------------------------------------------------------
// API sencilla para CRUD (PHP nativo + MySQL)
// ------------------------------------------------------------
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$entity = $_GET['entity'] ?? '';

// ------------------------------------------------------------
// Configuración de entidades (tabla + campos permitidos)
// ------------------------------------------------------------
function entity_config(string $entity): array
{
    $map = [
        'reuniones' => ['table' => 'reuniones', 'fields' => ['titulo', 'fecha', 'hora', 'descripcion']],
        'horarios' => ['table' => 'horarios', 'fields' => ['dia', 'horario']],
        'notas' => ['table' => 'notas', 'fields' => ['lapso', 'fecha', 'detalles']],
        'efemerides' => ['table' => 'efemerides', 'fields' => ['fecha', 'evento']],
        'actividades' => ['table' => 'actividades', 'fields' => ['nombre', 'fecha', 'descripcion']],
        'festivos' => ['table' => 'festivos', 'fields' => ['fecha', 'motivo']],
    ];

    return $map[$entity] ?? [];
}

// ------------------------------------------------------------
// Inserta efemérides por defecto si no existen (requisito)
// ------------------------------------------------------------
function ensure_default_efemerides(): void
{
    $pdo = db();
    $count = (int) $pdo->query('SELECT COUNT(*) FROM efemerides')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $defaults = [
        ['05 de Julio', 'Firma del Acta de Independencia de Venezuela'],
        ['24 de Junio', 'Batalla de Carabobo'],
        ['19 de Abril', 'Proclamación de la Independencia'],
    ];

    $stmt = $pdo->prepare('INSERT INTO efemerides (fecha, evento) VALUES (?, ?)');
    foreach ($defaults as $row) {
        $stmt->execute($row);
    }
}

// ------------------------------------------------------------
// Helpers de respuesta
// ------------------------------------------------------------
function json_ok($data): void
{
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['ok' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

// ------------------------------------------------------------
// Gestión de usuarios admin (requiere sesión)
// ------------------------------------------------------------
if ($entity === 'admin_users') {
    require_login();
    $pdo = db();

    if ($method === 'GET' && $action === 'list') {
        $stmt = $pdo->query('SELECT id, username, created_at FROM admin_users ORDER BY id DESC');
        json_ok($stmt->fetchAll());
    }

    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            $input = $_POST;
        }

        if ($action === 'create') {
            $username = trim($input['username'] ?? '');
            $password = trim($input['password'] ?? '');

            if ($username === '' || $password === '') {
                json_error('Usuario y contraseña son obligatorios.');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);

            json_ok(['id' => $pdo->lastInsertId()]);
        }

        if ($action === 'update') {
            $id = isset($input['id']) ? (int) $input['id'] : 0;
            $username = trim($input['username'] ?? '');
            $password = trim($input['password'] ?? '');

            if ($id <= 0) {
                json_error('ID inválido.');
            }
            if ($username === '') {
                json_error('El usuario es obligatorio.');
            }

            $fields = ['username = ?'];
            $values = [$username];

            if ($password !== '') {
                $fields[] = 'password_hash = ?';
                $values[] = password_hash($password, PASSWORD_DEFAULT);
            }

            $values[] = $id;
            $set = implode(', ', $fields);
            $stmt = $pdo->prepare("UPDATE admin_users SET {$set} WHERE id = ?");
            $stmt->execute($values);

            json_ok(['id' => $id]);
        }

        if ($action === 'delete') {
            $id = isset($input['id']) ? (int) $input['id'] : 0;
            if ($id <= 0) {
                json_error('ID inválido.');
            }
            if (isset($_SESSION['admin_id']) && (int) $_SESSION['admin_id'] === $id) {
                json_error('No puedes eliminar tu propio usuario.');
            }

            $stmt = $pdo->prepare('DELETE FROM admin_users WHERE id = ?');
            $stmt->execute([$id]);

            json_ok(['id' => $id]);
        }
    }

    json_error('Acción no soportada.');
}

$config = entity_config($entity);
if (empty($config)) {
    json_error('Entidad no válida.');
}

// ------------------------------------------------------------
// Acciones públicas: listar datos para index.php
// ------------------------------------------------------------
if ($method === 'GET' && $action === 'list') {
    if ($entity === 'efemerides') {
        ensure_default_efemerides();
    }

    $pdo = db();
    $table = $config['table'];
    $stmt = $pdo->query("SELECT * FROM {$table} ORDER BY id DESC");
    $data = $stmt->fetchAll();

    json_ok($data);
}

// ------------------------------------------------------------
// Acciones privadas (crear/actualizar/eliminar)
// ------------------------------------------------------------
if ($method === 'POST') {
    require_login();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!is_array($input)) {
        $input = $_POST;
    }

    $table = $config['table'];
    $fields = $config['fields'];

    if ($action === 'create') {
        // Crear registro
        $values = [];
        foreach ($fields as $field) {
            if (!isset($input[$field]) || trim($input[$field]) === '') {
                json_error('Faltan campos obligatorios.');
            }
            $values[$field] = trim($input[$field]);
        }

        $columns = implode(', ', array_keys($values));
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $stmt = db()->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($values));

        json_ok(['id' => db()->lastInsertId()]);
    }

    if ($action === 'update') {
        // Actualizar registro
        $id = isset($input['id']) ? (int) $input['id'] : 0;
        if ($id <= 0) {
            json_error('ID inválido.');
        }

        $values = [];
        foreach ($fields as $field) {
            if (!isset($input[$field]) || trim($input[$field]) === '') {
                json_error('Faltan campos obligatorios.');
            }
            $values[$field] = trim($input[$field]);
        }

        $set = implode(', ', array_map(fn($f) => "$f = ?", array_keys($values)));
        $stmt = db()->prepare("UPDATE {$table} SET {$set} WHERE id = ?");
        $stmt->execute([...array_values($values), $id]);

        json_ok(['id' => $id]);
    }

    if ($action === 'delete') {
        // Eliminar registro
        $id = isset($input['id']) ? (int) $input['id'] : 0;
        if ($id <= 0) {
            json_error('ID inválido.');
        }

        $stmt = db()->prepare("DELETE FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);

        json_ok(['id' => $id]);
    }
}

json_error('Acción no soportada.');
