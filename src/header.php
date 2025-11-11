<?php
    use config\Config;
    use services\ProductosServices;
    use services\SessionServices;

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/config/config.php';
    require_once __DIR__ . '/services/SessionServices.php';
    require_once __DIR__ . '/services/GeneroServices.php';

    $sessionService = services\SessionServices::getInstance();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            margin: 0px auto;
            font-family: 'Arial', sans-serif;
            padding: 0px;
            width: 70%;
            margin-bottom: 150px;
        }
        header{
            display: flex;
            height: 90px;
            width: 100%;
            top: 5px;
            align-items: center;
            background-color:#2c3e50;
            background-image: url('/uploads/favicon.png');
            background-repeat: no-repeat;
            background-position: 15px center;
            background-size: 70px;
            font-weight: bold;
        }
        h2{
            margin-left: 100px;
            color: white;
        }
        ul{
            padding: 10px;
        }
        li{
            display: inline;
            margin-left: 15px;
            color: white;
        }
        a{
            text-decoration: none;
            color: white;
        }
        a:hover{
            color: #6ECC7C;
            text-decoration: underline;
            cursor: pointer;
        }
        span a button{
            margin-left: 7px;
            width: 100px;
            height: 30px;
            border-radius: 10px;
            font-size: 0.8em;
            color: white;
            background-color: #ff3053ff;
            box-shadow: none;
        }
        .titulo:hover{
            text-decoration: none;
        }
        #perfilUsuario{
            margin-left: 50px;
            color: white;
        }
    </style>
    <link href="./uploads/favicon.png" rel="icon" type="image/png">
</head>
<body>
    <header>
        <a href="./index.php" class="titulo"><h2>Board This Way</h2></a>
        <div id="menu">
            <ul>
                <li><a href="./index.php">Lista de productos</a></li>
                <li><a href="./create.php">Añadir producto</a></li>
                <li><a href="./login.php">Login</a></li>
            </ul>
        </div>
       <?php
        
        if ($sessionService->isLoggedIn()): 
            $rolTexto = $sessionService->isAdmin() ? 'Admin' : 'User';
        ?>
            <span id="perfilUsuario">
                <?php echo htmlspecialchars($rolTexto); ?>
                <a href="logout.php" class="logout-btn"><button>Cerrar Sesión</button></a>
            </span>
        <?php endif; ?>
    </header>
</body>
</html>