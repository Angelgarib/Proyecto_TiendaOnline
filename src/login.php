<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/../src/services/SessionServices.php';
require_once __DIR__ . '/../src/services/UserServices.php';

use config\Config;
use services\SessionServices;
use services\UsersServices;

$session = SessionServices::getInstance();

// Si ya está logueado, redirigir al index
if ($session->isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Por favor, completa todos los campos';
    } else {
        try {
            $config = Config::getInstance();
            $pdo = $config->db;
            $userService = new UsersServices($pdo);

            $resultado = $userService->authenticate($username, $password);

            if ($resultado['success']) {
                $session->login($resultado['user']);
                header('Location: index.php'); //Redirige a Index.php
                exit;
            } else {
                $error = $resultado['message'];
            }
        } catch (Exception $e) {
            $error = 'Error del sistema: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link href="./uploads/favicon.png" rel="icon" type="image/png">
    <style>
        .login-container {
            max-width: 400px;
            margin: 4rem auto;
            padding: 2.5rem;
            background: #ffffff;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .login-container h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 2rem;
            font-weight: 600;
            font-size: 1.8rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 1rem;
            letter-spacing: -0.5px;
        }

        /* Formulario de login */
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .login-form .form-group {
            display: flex;
            flex-direction: column;
        }

        .login-form label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-form input[type="text"],
        .login-form input[type="email"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease-in-out;
            box-sizing: border-box;
            background: #ffffff;
            color: #374151;
        }

        .login-form input[type="text"]:focus,
        .login-form input[type="email"]:focus,
        .login-form input[type="password"]:focus {
            outline: none;
            border-color: #6ECC7C;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        /* Botón de login */
        .login-form input[type="submit"],
        .login-form button[type="submit"] {
            background: #6ECC7C;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            margin-top: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .login-form input[type="submit"]:hover,
        .login-form button[type="submit"]:hover {
            opacity: 80%;
            transform: translateY(-1px);
        }

        /* Enlaces adicionales */
        .login-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e1e5e9;
        }

        .login-links a {
            color: #6ECC7C;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .login-links a:hover {
            color: black;
            text-decoration: underline;
        }

        /* Mensajes de error/éxito */
        .login-message {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 14px;
            text-align: center;
        }

        .login-message.error {
            background: #fee;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .login-message.success {
            background: #efe;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
    </style>
</head>

<body>
    <?php
    require_once("./header.php")
    ?>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form class="login-form" action="login.php" method="POST">
            <div class="form-group">
                <label for="user">User:</label>
                <input type="text" id="user" name="user" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <input type="submit" value="Iniciar Sesión">
        </form>

    </div>
    <?php
    require_once("./footer.php")
    ?>
</body>

</html>