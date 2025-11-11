<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/services/SessionServices.php';
require_once __DIR__ . '/services/GeneroServices.php';

use config\Config;
use models\Producto;
use services\ProductosServices;
use Ramsey\Uuid\Uuid;
use services\GeneroServices;
use services\SessionServices;

try {
    $sessionService = SessionServices::getInstance();
    $sessionService->requireLogin();
    $sessionService->requireAdmin();
    // Obtener la instancia de Config y la conexión PDO
    $config = Config::getInstance();
    $pdo = $config->db;

    // Crear instancia del servicio de productos
    $generosService = new GeneroServices($pdo);
    $productosService = new ProductosServices($pdo);

    // Verificar conexión obteniendo todos los productos
    $generos = $generosService->findAllGeneros();

    $errores = [];

    // Si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //filtramos los datos
        $nombre = filter_input(INPUT_POST, 'nombre-producto', FILTER_SANITIZE_STRING);
        $precio = filter_input(INPUT_POST, 'precio-producto', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $stock = filter_input(INPUT_POST, 'stock-producto', FILTER_SANITIZE_NUMBER_INT);
        $genero = filter_input(INPUT_POST, 'generos', FILTER_SANITIZE_STRING);
        $imagen = filter_input(INPUT_POST, 'imagen-producto', FILTER_SANITIZE_STRING);
        $num_jugadores = filter_input(INPUT_POST, 'jugadores-producto', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_POST, 'tipo-producto', FILTER_SANITIZE_STRING);

        //Validaciones
        if (empty($nombre)) {$errores['nombre'] = 'El nombre es obligatorio';}
        if (empty($precio)) {$errores['precio'] = 'El precio es obligatorio';}
        if (empty($stock)) { $errores['stock'] = 'El stock es obligatorio';}
        if (empty($imagen)) {$errores['imagen'] = 'La imagen es obligatorio';}
        if (empty($num_jugadores)) {$errores['num_jugadores'] = 'El número de jugadores es obligatorio';}
        if (empty($tipo)) {$errores['tipo'] = 'El tipo es obligatorio';}

        //Creamos el producto
        if (empty($errores)) {
            $generoUuid = $generosService->findByGenero($genero);

            $producto = new Producto();
            $producto->nombre = $nombre;
            $producto->precio = floatval($precio);
            $producto->stock = intval($stock);
            $producto->genero_id = $generoUuid['id'];
            $producto->uuid = Uuid::uuid4()->toString();
            $producto->imagen = $imagen;
            $producto->num_jugadores = intval($num_jugadores);
            $producto->tipo = $tipo;

            $resultado = $productosService->save($producto);
            
            if ($resultado['success']) {
                echo "<script>
                    alert('✅ Artículo guardado correctamente en el inventario!');
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 100); // Pequeño retraso para que se vea el alert
                </script>";
                exit;
            } else {
                echo "<script>
                    alert('❌ Error al guardar el artículo: " . addslashes($resultado['message']) . "');
                    // No redirigimos en error, se queda en create.php para corregir
                </script>";
            }
        }else{
            echo "<script>
                alert('❌ Error al guardar el artículo:');
            </script>";
        }
    }
} catch (Exception $e) {
    die("Error al añadir producto: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar producto</title>
    <link href="./uploads/favicon.png" rel="icon" type="image/png">
    <style>
        #guardar-producto {
            max-width: 700px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        #guardar-producto h1 {
            text-align: center;
            color: #212529;
            margin-bottom: 2rem;
            font-weight: 700;
            font-size: 2rem;
            border-bottom: 3px solid #007bff;
            padding-bottom: 1rem;
        }

        #guardar-producto form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        #guardar-producto label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        #guardar-producto input[type="text"],
        #guardar-producto input[type="number"],
        #guardar-producto select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.15s ease-in-out;
            box-sizing: border-box;
            background: white;
        }

        #guardar-producto input[type="text"]:focus,
        #guardar-producto input[type="number"]:focus,
        #guardar-producto select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .grupo-btn {
            grid-column: 1 / -1;
            display: flex;
            gap: 40px;
            margin: auto;
            justify-content: center;
        }

        #guardar-producto input[type="submit"],
        .grupo-btn button {
            background: #6ECC7C;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease-in-out;
            justify-self: center;
            min-width: 200px;
            margin-top: 1rem;
        }

        .grupo-btn button {
            background: #2c3e50;
            margin-top: 15px;
        }

        #guardar-producto input[type="submit"]:hover,
        .btn-volver:hover {
            opacity: 80%;
        }
    </style>
</head>

<body>
    <?php
    require_once("./header.php")
    ?>
    <h1>Añadir producto</h1>
    <div id="guardar-producto">
        <form action="create.php" method="POST">
            <label for="nombre-producto">Nombre:</label>
            <input type="text" id="nombre-producto" name="nombre-producto">

            <label for="precio-producto">Precio:</label>
            <input type="number" step="0.01" id="precio-producto" name="precio-producto">

            <label for="stock-producto">Stock:</label>
            <input type="number" id="stock-producto" name="stock-producto">

            <label for="generos">Genero:</label>
            <select name="generos" id="generos">
                <?php for ($i = 0; $i < count($generos); $i++) { ?>
                    <?php $genero = $generos[$i]; ?>
                    <option value="<?php echo htmlspecialchars($genero['nombre']); ?>">
                        <?php echo htmlspecialchars($genero['nombre']); ?></option>
                <?php } ?>
            </select>

            <label for="imagen-producto">Imagen:</label>
            <input type="text" id="imagen-producto" name="imagen-producto">

            <label for="jugadores-producto">Número de jugadores:</label>
            <input type="number" id="jugadores-producto" name="jugadores-producto">

            <label for="tipo-producto">Tipo:</label>
            <input type="text" id="tipo-producto" name="tipo-producto">
            <div class="grupo-btn">
                <input type="submit" value="Guardar producto"></input>
            </div>
        </form>
        <div class="grupo-btn">
            <a href="./index.php"><button class="btn-volver">Volver</button></a>
        </div>
        
    </div>
    <?php
    require_once("./footer.php")
    ?>
</body>

</html>