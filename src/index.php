<?php
use config\Config;
use services\SessionServices;
use services\ProductosServices;
use services\GeneroServices;
use Ramsey\Uuid\Uuid;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/services/SessionServices.php';
require_once __DIR__ . '/services/GeneroServices.php';

try {
    $sessionService = SessionServices::getInstance();
    // Obtener la instancia de Config y la conexión PDO
    $config = Config::getInstance();
    $pdo = $config->db;

    // Crear instancia del servicio de productos
    $productosService = new ProductosServices($pdo);

    // Verificar conexión obteniendo todos los productos
    $generos = ["ESTRATEGIA", "ROL", "FIESTA", "PREGUNTAS"];

    // Verificar conexión obteniendo todos los productos
    if (isset($_GET['genero']) && $_GET['genero'] <> "") {
        $genero =  strtoupper($_GET['genero']);
        if (in_array($genero, $generos)) {
            $productos = $productosService->findAllWithGenreName($genero);
        } else {
            $productos = $productosService->findAll();
            echo "<p style='color: red; font-weight: bolder;'>El género introducido no es valido</p>";
        }
    } else {
        $productos = $productosService->findAll();
    }
} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link href="./uploads/favicon.png" rel="icon" type="image/png">
    <style>
        /* Estilos para las tarjetas horizontales */
        .productos-lista {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .producto-card {
            display: flex;
            align-items: center;
            background: white;
            padding: 20px;
            height: 150px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .producto-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .producto-id {
            width: 45px;
            text-align: justify;
            font-weight: bold;
            color: #3498db;
            font-size: 1.2em;
        }

        .producto-imagen {
            width: 200px;
            height: 100%;
            margin-right: 20px;
        }

        .producto-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .producto-info {
            width: 435px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .producto-nombre {
            width: 90px;
            font-size: 1.1em;
            font-weight: bold;
            color: #2c3e50;
        }

        .producto-jugadores {
            width: 100px;
            text-align: center;
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.2em;
        }

        .producto-precio {
            width: 100px;
            text-align: center;
            font-size: 1.3em;
            font-weight: bold;
            color: #e74c3c;
        }

        .producto-stock {
            width: 100px;
            text-align: center;
            border-radius: 20px;
            font-weight: bold;
        }

        .producto-acciones {
            display: flex;
            gap: 10px;
            width: 250px;
            border-radius: 20px;
            font-weight: bold;
        }

        .stock {
            background-color: #d4edda;
            color: #155724;
        }

        /* Encabezados de la "tabla" */
        .lista-header {
            display: flex;
            align-items: center;
            background: #34495e;
            color: white;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
        }

        .header-id {
            width: 45px;
            text-align: justify;
        }

        .header-imagen {
            width: 200px;
            margin-right: 20px;
            text-align: center;
        }

        .header-info {
            width: 435px;
            display: flex;
            justify-content: space-between;
        }

        .header-nombre {
            width: 90px;
        }

        .header-precio {
            width: 100px;
            text-align: center;
        }

        .header-jugadores {
            width: 100px;
            text-align: center;
        }

        .header-stock {
            width: 100px;
            text-align: center;
        }

        .header-acciones {
            width: 250px;
            text-align: justify;
        }

        button {
            width: 70px;
            height: 45px;
            border-radius: 10px;
            border: none;
            color: white;
            background-color: #6ECC7C;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0px 0px 10px 2px rgb(213, 213, 213);
        }

        .btnEliminar {
            background-color: #e1073aff;
        }

        .buscar:hover,
        button:hover {
            opacity: 80%;
        }

        button a:hover {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        input {
            width: 90%;
            height: 35px
        }

        form {
            display: flex;
        }

        .buscar {
            width: 75px;
            margin-left: 10px;
            height: 35px;
            color: white;
            border-radius: 10px;
            background-color: #34495e;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    require_once("./header.php");
    ?>
    <h1>Listado de Productos</h1>
    <div id="busqueda">
        <p>Introduce el género de juegos que deseas buscar:</p>
        <form action="index.php" method="get">
            <input type="search" name="genero" class="busquedaInput" placeholder="Introduce el género de un juego" />
            <input type="submit" value="Buscar" class="buscar" />
        </form>
    </div>
    <p>Total de productos disponibles: <strong><?php echo count($productos); ?></strong></p>
    <div class="lista-header">
        <div class="header-id">ID</div>
        <div class="header-info">
            <div class="header-nombre">Producto</div>
            <div class="header-jugadores">Nº jugadores</div>
            <div class="header-precio">Precio</div>
            <div class="header-stock">Stock</div>
        </div>
        <div class="header-imagen">Imagen</div>
        <div class="header-acciones">Acciones</div>
    </div>

    <div class="productos-lista">
        <?php for ($i = 0; $i < count($productos); $i++) { ?>
            <?php $producto = $productos[$i]; ?>
            <div class="producto-card">
                <!-- ID autoincremental (índice del array + 1) -->
                <div class="producto-id">
                    <?php echo htmlspecialchars($producto['id']); ?>
                </div>

                <!-- Información del producto -->
                <div class="producto-info">
                    <div class="producto-nombre">
                        <?php echo htmlspecialchars($producto['nombre']); ?>
                    </div>

                    <div class="producto-jugadores">
                        <?php echo $producto['num_jugadores']; ?>
                    </div>

                    <div class="producto-precio">
                        €<?php echo number_format($producto['precio'], 2); ?>
                    </div>

                    <div class="producto-stock">
                        <?php echo $producto['stock']; ?>
                    </div>
                </div>
                <!-- Imagen del producto -->
                <div class="producto-imagen">
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>"
                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                </div>
                <div class="producto-acciones">
                    <a href="details.php?id=<?php echo htmlspecialchars($producto['id']); ?>"><button id="btnDetalles">Detalles</button></a>
                    <?php
                                        
                    // Solo mostrar botones de Editar y Eliminar si el usuario es Admin
                    if ($sessionService->isLoggedIn() && $sessionService->isAdmin()):?>
                        <a href="update.php?id=<?php echo htmlspecialchars($producto['id']); ?>"><button id="btnEditar">Editar</button></a>
                        <a href="delete.php?id=<?php echo htmlspecialchars($producto['id']); ?>" 
                        onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?')"><button class="btnEliminar">Eliminar</button></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </div>
    </div>
    <?php
    require_once("./footer.php")
    ?>
</body>

</html>