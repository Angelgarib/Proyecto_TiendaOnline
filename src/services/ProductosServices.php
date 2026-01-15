<?php

namespace services;

use models\Producto;
use PDO;
use Ramsey\Uuid\Uuid;
use PDOException;

require_once __DIR__ . '/../models/producto.php';

class ProductosServices
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function save($producto)
    {
        try {
            $sql = "INSERT INTO productos (nombre, precio, stock, genero_id, uuid, imagen, 
            num_jugadores, tipo) VALUES (:nombre, :precio, :stock, :genero_id, :uuid,
            :imagen, :num_jugadores, :tipo)";;

            $stmt = $this->pdo->prepare($sql);

            //$randomuuid = Uuid::uuid4();

            $stmt->bindParam(':nombre', $producto->nombre);
            $stmt->bindParam(':precio', $producto->precio);
            $stmt->bindParam(':stock', $producto->stock);
            $stmt->bindParam(':genero_id', $producto->genero_id);
            $stmt->bindParam(':uuid', $producto->uuid);
            $stmt->bindParam(':imagen', $producto->imagen);
            $stmt->bindParam(':num_jugadores', $producto->num_jugadores);
            $stmt->bindParam(':tipo', $producto->tipo);

            $stmt->execute();

            return [
                'success' => true,
                'message' => 'Producto creado correctamente',
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al crear producto: ' . $e->getMessage()
            ];
        }
    }

    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM productos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $producto;
        } catch (PDOException $e) {
            echo 'Error al encontrar el producto: ' . $e->getMessage();
        }
    }

    public function findAllWithGenreName($genero)
    {
        try {
            $sql = "SELECT p.nombre, p.precio, p.stock, p.id, p.genero_id, p.imagen, 
            p.num_jugadores, p.tipo FROM productos p, generos g WHERE p.genero_id = g.id AND g.nombre = :genero ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':genero', strtoupper($genero));
            $stmt->execute();

            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $productos;
        } catch (PDOException $e) {
            echo 'Error al obtener los productos por género: ' . $e->getMessage();
        }
    }

    public function findGenreName($id)
    {
        try {
            $sql = "SELECT g.nombre FROM productos p, generos g WHERE p.genero_id = g.id AND p.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $genero = $stmt->fetch(PDO::FETCH_ASSOC);
            return $genero;
        } catch (PDOException $e) {
            echo 'Error al obtener los productos por género: ' . $e->getMessage();
        }
    }

    public function findAllWithTypeName($tipo)
    {
        try {
            $sql = "SELECT * FROM productos WHERE tipo = :tipo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();

            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $productos;
        } catch (PDOException $e) {
            echo 'Error al obtener los productos por tipo: ' . $e->getMessage();
        }
    }

    public function update($producto, $id)
    {
        try {
            $sql = "UPDATE productos SET nombre = :nombre, precio = :precio, stock = :stock, 
            genero_id = :genero_id, imagen = :imagen, num_jugadores = :num_jugadores, tipo = :tipo 
            WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':nombre', $producto->nombre);
            $stmt->bindParam(':precio', $producto->precio);
            $stmt->bindParam(':stock', $producto->stock);
            $stmt->bindParam(':genero_id', $producto->genero_id);
            $stmt->bindParam(':imagen', $producto->imagen);
            $stmt->bindParam(':num_jugadores', $producto->num_jugadores);
            $stmt->bindParam(':tipo', $producto->tipo);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return [
                    'success' => true,
                    'message' => 'Producto actualizado correctamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se pudo actualizar el producto'
                ];
            }
        } catch (PDOEXception $e) {
            return [
                'success' => false,
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ];
        }
    }

    public function deleteById($id)
    {
        try {
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
               return [
                    'success' => true,
                    'message' => 'Producto eliminado correctamente'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se encontró el producto'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ];
        }
    }

    public function findAll()
    {
        try {
            $sql = "SELECT * FROM productos ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $productos;
        } catch (PDOException $e) {
            echo 'Error al obtener los productos: ' . $e->getMessage();
        }
    }

    public function getLastId()
    {
        try {
            $sql = "SELECT id FROM productos ORDER BY id DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $lastId = $stmt->fetch(PDO::FETCH_ASSOC);
            return $lastId;
        } catch (PDOException $e) {
            error_log('Error al obtener último ID: ' . $e->getMessage());
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}

?>