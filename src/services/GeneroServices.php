<?php

namespace services;
use models\Producto;
use PDO;
use Ramsey\Uuid\Uuid;
use PDOException;

require_once __DIR__ . '/../models/producto.php';

class GeneroServices
{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function save($genero){
        try {
            $sql = "INSERT INTO generos (id, nombre) VALUES (:id, :nombre)";

            $stmt = $this->pdo->prepare($sql);

            //$randomuuid = Uuid::uuid4();

            $stmt->bindParam(':id', $genero['id']);
            $stmt->bindParam(':nombre', $genero['nombre']);

            $stmt->execute();

            echo 'Género guardado correctamente';

        } catch (PDOException $e) {
                echo 'Error al guardar el género: ' . $e->getMessage();
        }
    }

    public function findByUuId($uuid){
        try {
            $sql = "SELECT nombre FROM generos WHERE id = :uuid";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->execute();

            $genero = $stmt->fetch(PDO::FETCH_ASSOC);
            return $genero;
        } catch (PDOException $e) {
                echo 'Error al encontrar el producto: ' . $e->getMessage();
        }
    }

    public function findByGenero($genero){
        try {
            $sql = "SELECT id FROM generos WHERE nombre = :nombre";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $genero, PDO::PARAM_STR);
            $stmt->execute();

            $uuid = $stmt->fetch(PDO::FETCH_ASSOC);
            return $uuid;
        } catch (PDOException $e) {
                error_log('Error al buscar género: ' . $e->getMessage());
        }
    }

     public function findAllGeneros(){
        try {
            $sql = "SELECT * FROM generos ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $generos;
        } catch (PDOException $e) {
            echo 'Error al obtener los géneros: ' . $e->getMessage();
        }
    }

    public function __destruct() {
        $this->pdo = null;
    }
    
}