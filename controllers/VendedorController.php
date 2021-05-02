<?php

namespace Controllers;

use MVC\Router;
use Model\vendedor;

class VendedorController{
    public static function crear(Router $router){
        $errores = Vendedor::getErrores();
        $vendedor = new Vendedor();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $vendedor = new Vendedor($_POST['vendedor']);
        
           //Validar que no haya Campos Vacíos
           $errores = $vendedor->validar();
        
           //En caso que no hay errores
           if(empty($errores)){
               $vendedor->guardar();
           }
        }
        $router->render('vendedores/crear', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
    }
    public static function actualizar(Router $router){
        $errores = Vendedor::getErrores();
        $id = validarORedireccionar('/admin');
        $vendedor = Vendedor::find($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //asignar los Valores
            $argc = $_POST['vendedor'];
            
            //Sincronizar Objeto en Memoria con los que el usuario escribió
            $vendedor->sincronizar($argc);
            
            //validacion
        
            $errores = $vendedor->validar();
        
            if(empty($errores)){
        
                $vendedor->guardar();
        
                debug($vendedor);
            }
        
        }

        $router->render('vendedores/actualizar', [
            'errores' => $errores,
            'vendedor' => $vendedor
        ]);
        
    }
    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){            
            //Validar Id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id){
                //Validar Tipo a eliminar
            $tipo= $_POST['tipo'];

            if(validarTipoContenido($tipo)){
                $vendedor = Vendedor::find($id);
                $vendedor->eliminar();
            }
            }

            
        }
    }
}