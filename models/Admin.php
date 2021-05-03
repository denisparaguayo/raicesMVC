<?php

namespace Model;

class Admin extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'email', 'password'];

    public $id;
    public $email;
    public $password;

    public function __construct($argc = []){
        $this->id = $argc['id'] ?? null;
        $this->email = $argc['email'] ?? '';
        $this->password = $argc['password'] ?? '';
    }

    public function validar(){
        if(!$this->email){
            self::$errores [] = 'El Email es Obligatorio';
        }
        if(!$this->password){
            self::$errores [] = 'El Password es Obligatorio';
        }

        return self::$errores;
    }

    public function existeUsuario(){

        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$bd->query($query);

        
        if(!$resultado->num_rows){
            self::$errores [] = 'El Usuario no Existe';
            return;
        }
        return $resultado;
    }

    public function comprobarPassword($resultado){
        $usuario = $resultado->fetch_object();

        $autenticado = password_verify($this->password, $usuario->password);

        if(!$autenticado){
            self::$errores[] = 'El Password es Incorrecto';
        }
        return $autenticado;
    }

    public function autenticar(){
        session_start();

        //llenar el arreglo de session 
        $_SESSION['usuario'] = $this->email;
        $_SESSION['login'] = true;

        header('Location: /admin');
    }
}