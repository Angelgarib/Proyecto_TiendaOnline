<?php

use config\Config;
use services\ProductosServices;
use services\SessionServices;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/services/SessionServices.php';
require_once __DIR__ . '/services/GeneroServices.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No se han recibido datos.";
}

try {
    $sessionService = SessionServices::getInstance();
    $sessionService->requireLogin();
    $sessionService->requireAdmin();
    $config = Config::getInstance();
    $pdo = $config->db;
    $productosService = new ProductosServices($pdo);
    
    $resultado = $productosService->deleteById($id);
    
    if ($resultado['success']) {
        echo "<script>
            alert('✅ Artículo eliminado correctamente!');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Error al eliminar el artículo!');
            window.location.href = 'index.php';
        </script>";
    }
    
} catch (Exception $e) {
    echo "<script>
        alert('❌ Error inesperado: " . addslashes($e->getMessage()) . "');
        window.location.href = 'index.php';
    </script>";
}
?>