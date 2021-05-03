<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController{
    public static function index(Router $router){
        
        $propiedades = Propiedad::get(3);
        $inicio = true;
        
        $router->render('paginas/index', [
            'propiedades' => $propiedades,
            'inicio' => $inicio
        ]);
    }
    public static function nosotros(Router $router){
        $router->render('paginas/nosotros', []);
    }
    public static function propiedades(Router $router){
        
        $propiedades = Propiedad::all();
        
        $router->render('paginas/propiedades', [
            'propiedades' => $propiedades
        ]);
    }
    public static function propiedad(Router $router){
        
        $id = validarORedireccionar('/propiedades');

        $propiedades = Propiedad::find($id);
        $router->render('paginas/propiedad', [
            'propiedad' => $propiedades
        ]);
    }
    public static function blog(Router $router){
        $router->render('paginas/blog');
    }
    public static function entrada(Router $router){
        $router->render('paginas/entrada');
    }
    public static function contacto(Router $router){

        $mensaje = null;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $respuesta = $_POST['contacto'];

            

            //crear instancia de phpMailer
            $mail = new PHPMailer();
            //Conf SMTP

            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '94bdd330515a1a';
            $mail->Password = 'e2c8b8b8630513';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 2525;

            //Cof Contenido del Email
            $mail->SetFrom('admin@bienesracices.com');
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
            $mail->Subject = 'Tienes un Nuevo Mensaje';

            //Habilitar HTML

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            //Definir Contenido
            $contenido = '<html>';
            $contenido .= '<p>Tienes un Mensaje Nuevo</p>';
            $contenido .= '<p>Nombre: ' . $respuesta['nombre'] . ' </p>';
            
            //Enviar de forma condicional
            if($respuesta['contacto'] === 'telefono'){
                
                //eligio por telefono
                $contenido .= '<p>Eligió ser Contactado por Telefono</p>';
                $contenido .= '<p>Telefono: ' . $respuesta['telefono'] . ' </p>';
                $contenido .= '<p>Fecha Contacto: ' . $respuesta['fecha'] . ' </p>';
                $contenido .= '<p>Hora: ' . $respuesta['hora']  . ' </p>';
                
                
            }else{
                //eligio por email
                $contenido .= '<p>Eligió ser Contactado por Email</p>';
                $contenido .= '<p>Email: ' . $respuesta['email']  . ' </p>';
                
            }


            $contenido .= '<p>Mensaje: ' . $respuesta['mensaje'] . ' </p>';
            $contenido .= '<p>Vende o Compra: ' . $respuesta['tipo'] . ' </p>';
            $contenido .= '<p>Precio o Presupuesto: $' . $respuesta['precio'] . ' </p>';
            $contenido .= '<p>Prefiere ser Contactado por: ' . $respuesta['contacto'] . ' </p>';
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin HTML';

            //Enviar Email

            if($mail->send()){
                $mensaje = "MENSAJE ENVIADO CORRECTAMENTE";
            }else{
                $mensaje = "El MENSAJE NO SE PUDO ENVIAR";
             }

        }
        
        $router->render('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
        
}