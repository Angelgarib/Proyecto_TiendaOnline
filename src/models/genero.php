<?php
    namespace models;

    use Ramsey\Uuid\Uuid;

    class Genero{
        public $id;
        public $nombre;

        public function __construct($id, $nombre)
        {
            $this->id = $id;
            $this->nombre = $nombre;
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