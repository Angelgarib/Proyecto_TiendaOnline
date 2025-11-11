<?php

    namespace models;

    use Ramsey\Uuid\Uuid;
    
    class User{
        public $id;
        public $apellidos;
        public $email;
        public $nombre;
        public $password;
        public $username;
        public $roles = [];

        public function __construct($id, $apellidos, $email, $nombre, $password, $username, $roles=[]){
            $this->nombre = $nombre;
            $this->apellidos = $apellidos;
            $this->email = $email;
            $this->id = $id;
            $this->password = $password;
            $this->username = $username;
            $this->roles = $roles;
        }

        public function getId()
        {
            return $this->id;
        }

        public function __getName($nombre)
        {
            return $this->$nombre;
        }

        public function __setName($nombre, $value)
        {
            $this->$nombre = $value;
        }

        public function generateUuid(){
            $myuuid = Uuid::uuid4();
            return $myuuid->toString();
        }
    }
?>