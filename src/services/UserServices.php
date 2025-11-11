<?php

namespace services;

use models\User;
use PDO;
use PDOException;

require_once __DIR__ . '/../models/user.php';

class UsersServices
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function authenticate($username, $password)
    {
        try{
            $user = $this->findUserByUsername($username);
            if ($user) {
                // Si la contraseña está en texto plano
                if ($password === $user->password) {
                    return [
                        'success' => true,
                        'user' => $user
                    ];
                }
            }else if($user && password_verify($password, $user->password)){
                return [
                    'success' => true,
                    'user' => $user
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ];
            }
        } catch (PDOException $e) {
            error_log('Error al autenticar el usuario: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error del sistema'
            ];
        }
    }


    public function findUserByUsername($username){
        try {
            // Buscar por username
            $sql = "SELECT * FROM usuarios WHERE (username = :username)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!$userRow){
                return null;
            }

            $sql = "SELECT * FROM user_roles WHERE (user_id = :user_id)";
            $stmtRoles = $this->pdo->prepare($sql);
            $stmtRoles->bindParam(':user_id', $userRow['id']);
            $stmtRoles->execute();
            $roles = $stmtRoles->fetch(PDO::FETCH_ASSOC);

            return new User(
                $userRow['id'],
                $userRow['apellidos'],
                $userRow['email'],
                $userRow['nombre'],
                $userRow['contrasena'],
                $userRow['username'],
                $roles
            );
            
        } catch (PDOException $e) {
           error_log('Error en findUserByUsername: ' . $e->getMessage());
            return null;
        }
    }

    public function findAll()
    {
        try {
            $sql = "SELECT * FROM usuarios ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $productos;
        } catch (PDOException $e) {
            echo 'Error al obtener los usuarios: ' . $e->getMessage();
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}

?>