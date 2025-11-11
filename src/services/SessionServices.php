<?php

namespace services;

class SessionServices{

    private static $instance;
    private $expireAfterSeconds = 3600; //Una hora en segundos

    private function __construct()
    {
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        $this->checkSessionValidity();
        $this->initSession();
    }

    private function checkSessionValidity(){
        if(isset($_SESSION['last_activity'])){
            $secondsInactive = time() - $_SESSION['last_activity'];
            if($secondsInactive >= $this->expireAfterSeconds){
                $this->clear();
            }
        }
    }

    public function clear(){
        session_unset();
        session_destroy();
    }

    private function initSession(){
        if(!isset($_SESSION['visits'])){
            $_SESSION['visits'] = 0;
        }

        if(!isset($_SESSION['logged_in'])){
            $_SESSION['logged_in'] = false;
        }

        if(!isset($_SESSION['isAdmin'])){
            $_SESSION['isAdmin'] = false;
        }

        if(!isset($_SESSION['username'])){
            $_SESSION['username'] = null;
        }

        if (!isset($_SESSION['user_id'])) {  
            $_SESSION['user_id'] = null;
        }

        if (!isset($_SESSION['user_nombre'])) { 
            $_SESSION['user_nombre'] = null;
        }

        if(!isset($_SESSION['lastLoginDate'])){
            $_SESSION['lastLoginDate'] = null;
        }

        if (!$_SESSION['logged_in']) {
            $_SESSION['visits']++;
        }

        $this->refreshLastActivity();
    }

    public function refreshLastActivity(){
        $_SESSION['last_activity'] = time();
    }

    public static function getInstance(): SessionServices
    {
        if(!isset(self::$instance)){
            self::$instance = new SessionServices();
        }
        return self::$instance;
    }

    public function isLoggedIn(){
        return $_SESSION['logged_in'] === true;
    }

    public function isAdmin(){
        return $_SESSION['isAdmin'] === true;
    }

    public function getVisitCount(){
        return $_SESSION['visits'];
    }

    public function login($user) {
        $_SESSION['logged_in'] = true;
        $_SESSION['isAdmin'] = in_array('ADMIN', $user->roles);
        $_SESSION['username'] = $user->username;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_nombre'] = $user->nombre;
        $_SESSION['lastLoginDate'] = date('Y-m-d H:i:s');
        $this->refreshLastActivity();
    }

    public function logout() {
        $_SESSION['logged_in'] = false;
        $_SESSION['isAdmin'] = false;
        $_SESSION['username'] = null;
        $_SESSION['user_id'] = null;
        $_SESSION['user_nombre'] = null;
        $_SESSION['lastLoginDate'] = null;
    }

    public function getWelcomeMessage(){
        return "Listado de Productos Tienda Online";
    }

    public function getUsername(){
        return $_SESSION['username'];
    }

    public function getLastLoginDate(){
        return $_SESSION['lastLoginDate'];
    }

    /**
     * Redirigir si no está logueado
     */
    public function requireLogin($redirectTo = 'login.php') {
        if (!$this->isLoggedIn()) {
            header("Location: $redirectTo");
            exit;
        }
    }

    /**
     * Redirigir si no es administrador
     */
    public function requireAdmin($redirectTo = 'index.php') {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            header("Location: $redirectTo");
            exit;
        }
    }
}

?>