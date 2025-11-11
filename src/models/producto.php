<?php

    namespace models;

    use Ramsey\Uuid\Uuid;
    
    class Producto{
        public $nombre;
        public $precio;
        public $stock;
        public $genero_id;
        public $uuid;
        public $imagen;
        public $num_jugadores;
        public $tipo;

        public function __construct($nombre = null, $precio = null, $stock = null, $genero_id = null, $uuid = null,
         $imagen = null, $num_jugadores = null, $tipo = null){
            $this->nombre = $nombre;
            $this->precio = $precio;
            $this->stock = $stock;
            $this->genero_id = $genero_id;
            $this->uuid = $uuid;
            $this->imagen = $imagen;
            $this->num_jugadores = $num_jugadores;
            $this->tipo = $tipo;
        }

        public function getId()
        {
            return $this->id;
        }

        public function __get($nombre)
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