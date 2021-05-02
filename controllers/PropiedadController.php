<?php

namespace Controllers;
use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;


class PropiedadController{

    public static function index(Router $router){        
        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();
        $resultado = $_GET['resultado'] ?? NULL; 

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
            ]);
    }

    public static function crear(Router $router){
        $propiedad = new Propiedad;
        $vendedores = Vendedor::all();
        //Arreglo con mensaje de errores         
        $errores = Propiedad::getErrores();
       

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

             //*CREA UNA NUEVA INSTANCIA
        $propiedad = new Propiedad($_POST['propiedad']);
        
            
        /* SUBIDA DE ARCHIVO IMAGEN */         
        //!generar un nombre único
        $nombreImagen = md5(uniqid(rand() . true)) . ".jpg";
        
        //*Setea la Imagen
        //*Realiza un rezise a la imagen con Intervention
        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make ($_FILES['propiedad']['tmp_name']['imagen']) -> fit (800,600);
            $propiedad->setImagen($nombreImagen);
        }
    
        //*Validar
        $errores = $propiedad->validar();   
        
        if (empty($errores)) { 
        //Crear la Carpeta para subir imagenes
        if(!is_dir(CARPETA_IMAGENES)){
            mkdir(CARPETA_IMAGENES);
        }
        
        //Guarda la Imagen en el servidor
        $image->save(CARPETA_IMAGENES . $nombreImagen);
        
        //Guarda en la Base de Datos
        
        $propiedad -> guardar();
        }

            
        }


        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router){
      $id = validarORedireccionar('/admin');
      $propiedad = Propiedad::find($id);
      $vendedores = Vendedor::all();
      $errores = Propiedad::getErrores();
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Asignar los Atributos
        $argc = $_POST['propiedad'];
    
        $propiedad->sincronizar($argc);
        
        //Validación 
        $errores = $propiedad->validar();
    
        
        /* SUBIDA DE ARCHIVO IMAGEN */         
        //!generar un nombre único
        $nombreImagen = md5(uniqid(rand() . true)) . ".jpg";    
        //*Setea la Imagen
        //*Realiza un rezise a la imagen con Intervention
        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make ($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }
    
    
        if (empty($errores)) {
    
            //Almacenar la Imagen
            if($_FILES['propiedad']['tmp_name']['imagen']){
            $image->save (CARPETA_IMAGENES . $nombreImagen);
            }
            
            $propiedad->guardar();
        }
    }  

      $router->render('/propiedades/actualizar', [
          'propiedad' => $propiedad,
          'errores' => $errores,
          'vendedores' => $vendedores
      ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if($id){
            $tipo = $_POST ['tipo'];
        if (validarTipoContenido($tipo)){
            $propiedad = Propiedad::find($id);                   
            $propiedad->eliminar();
                }
            }
        }
    }
}