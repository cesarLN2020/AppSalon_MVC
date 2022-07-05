<?php 

namespace Controllers;

use MVC\Router;

class citaController{
    public static function index( Router $router){
        
        if (!$_SESSION['nombre']) {
            session_start();
        }

        // verificamos si el usario esta autenticado desde funciones  
        isAuth();

        // pasamos los datos al al formulario de cita 
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}

?>