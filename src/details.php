<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/services/SessionServices.php';
require_once __DIR__ . '/services/GeneroServices.php';

use config\Config;
use services\SessionServices;
use services\ProductosServices;
use Ramsey\Uuid\Uuid;
use services\GeneroServices;

$sessionService = SessionServices::getInstance();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No se han recibido datos.";
}

try {
    // Obtener la instancia de Config y la conexión PDO
    $config = Config::getInstance();
    $pdo = $config->db;

    // Crear instancia del servicio de productos
    $productoServices = new ProductosServices($pdo);
    $producto = $productoServices->findById($id);

    // Crear instancia del servicio de productos
    $generosService = new GeneroServices($pdo);
    $genero = $generosService->findByUuId($producto['genero_id']);
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles</title>
    <link href="./uploads/favicon.png" rel="icon" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 15px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h1 {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
            margin: 0;
            font-size: 2em;
        }

        .content {
            padding: 40px;
        }

        .bloque-imagen {
            text-align: center;
            margin-bottom: 30px;
        }

        .bloque-imagen img {
            max-width: 300px;
            max-height: 300px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            object-fit: cover;
        }

        .info-producto {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 15px;
            align-items: start;
        }

        .info-producto h4 {
            background-color: #ecf0f1;
            padding: 12px 15px;
            border-radius: 8px;
            color: #2c3e50;
            font-size: 0.95em;
            margin: 0;
            border-left: 4px solid #3498db;
        }

        .info-producto p {
            padding: 12px 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 0;
            color: #555;
            font-size: 1em;
            border: 1px solid #e9ecef;
        }

        .actions {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #ecf0f1;
        }

        /* Tus estilos originales del botón - SIN MODIFICAR */
        button {
            width: 160px;
            height: 55px;
            border-radius: 10px;
            border: none;
            color: white;
            background-color: #2c3e50;
            font-size: 1.3em;
            font-weight: bold;
            box-shadow: 0px 0px 10px 2px rgb(213, 213, 213);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            opacity: 80%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 10px;
            }

            .content {
                padding: 20px;
            }

            .info-producto {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .bloque-imagen img {
                max-width: 250px;
                max-height: 250px;
            }

            h1 {
                font-size: 1.5em;
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .content {
                padding: 15px;
            }

            .bloque-imagen img {
                max-width: 200px;
                max-height: 200px;
            }
        }
    </style>
</head>

<body>
    <?php
    require_once("./header.php");
    ?>
    <div class="container">
        <h1>Información del producto</h1>

        <div class="content">
            <div class="bloque-imagen">
                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>"
                    alt="juego-<?php echo htmlspecialchars($producto['nombre']); ?>">
            </div>

            <div class="info-producto">
                <h4>ID</h4>
                <p><?php echo htmlspecialchars($producto['id']); ?></p>

                <h4>Nombre</h4>
                <p><?php echo htmlspecialchars($producto['nombre']); ?></p>

                <h4>Precio</h4>
                <p>€<?php echo number_format($producto['precio'], 2); ?></p>

                <h4>Unidades</h4>
                <p><?php echo htmlspecialchars($producto['stock']); ?> unidades</p>

                <h4>Género</h4>
                <p><?php echo htmlspecialchars($genero['nombre']); ?></p>

                <h4>Número de jugadores</h4>
                <p><?php echo htmlspecialchars($producto['num_jugadores']); ?></p>

                <h4>Tipo</h4>
                <p><?php echo htmlspecialchars($producto['tipo']); ?></p>
            </div>

            <div class="actions">
                <a href="index.php"><button class="btnVolver" href="index.php">Volver</button></a>
            </div>
        </div>
    </div>
    <?php
    require_once("./footer.php")
    ?>
</body>

</html>