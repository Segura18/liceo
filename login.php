<?php
// ------------------------------------------------------------
// Login del administrador (PHP nativo)
// ------------------------------------------------------------
require_once __DIR__ . '/auth.php';

start_session();
bootstrap_admin_if_empty();

$error = false;

// Procesar credenciales cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        $stmt = db()->prepare('SELECT id, password_hash FROM admin_users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login OK -> guardar sesión y redirigir
            $_SESSION['admin_id'] = $user['id'];
            header('Location: admin.php');
            exit;
        }
    }

    $error = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrativo | Liceo Pedro Lucas Urribarri</title>
    <style>
        :root {
            --primary: #1E3A5F;
            --primary-dark: #102A43;
            --bg-body: #F8FAFC;
            --text-main: #2D3748;
            --accent: #E63946;
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: #FFFFFF;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: var(--radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #E2E8F0;
            text-align: center;
        }

        .login-header h2 {
            color: var(--primary);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .login-header p {
            font-size: 0.85rem;
            color: #718096;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            text-align: left;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background-color: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            background-color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.15);
        }

        .password-field {
            position: relative;
        }

        .password-input {
            padding-right: 2.5rem;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            color: #718096;
        }

        .password-toggle:active {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary);
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: background 0.2s;
        }

        .btn-login:hover { background-color: var(--primary-dark); }

        .error-message {
            color: var(--accent);
            font-size: 0.85rem;
            margin-top: 1rem;
            display: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h2>Control de Acceso</h2>
            <p>C.N. Educativo Pedro Lucas Urribarri</p>
        </div>

        <form id="loginForm" method="post" action="login.php">
            <div class="form-group">
                <label for="username">Usuario Administrador</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Ej. admin_liceo" autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" class="form-control password-input" placeholder="••••••••" autocomplete="current-password" required>
                    <button type="button" id="toggle-password" class="password-toggle" aria-label="Mostrar contraseña" title="Mantén presionado para ver">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M2 12C4.5 7 8 4 12 4C16 4 19.5 7 22 12C19.5 17 16 20 12 20C8 20 4.5 17 2 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-login">Ingresar al Panel</button>
            <div id="errorMsg" class="error-message" style="display: <?php echo $error ? 'block' : 'none'; ?>;">⚠️ Usuario o contraseña incorrectos</div>
        </form>
    </div>

    <script>
        // Mostrar contraseña mientras el botón está presionado
        (function setupPasswordPeek() {
            const input = document.getElementById('password');
            const btn = document.getElementById('toggle-password');
            if (!input || !btn) return;

            const show = () => { input.type = 'text'; };
            const hide = () => { input.type = 'password'; };

            btn.addEventListener('mousedown', show);
            btn.addEventListener('touchstart', show, { passive: true });
            btn.addEventListener('mouseup', hide);
            btn.addEventListener('mouseleave', hide);
            btn.addEventListener('touchend', hide);
            btn.addEventListener('touchcancel', hide);
        })();
    </script>
</body>
</html>