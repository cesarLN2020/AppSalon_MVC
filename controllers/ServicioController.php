<?php 

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController {

    public static function index( Router $router){
        // echo 'desde index';
        //debemos iniciar la session siempre y mandarla a la vista
        if(!isset($_SESSION)){
            session_start();
        }

        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index',[
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }
    
    public static function crear( Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAdmin();

        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // echo 'desde crear';
            // el objeto que ya tenemos en memoria lo sincroniza con los datos de post 
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        $router->render('servicios/crear',[
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar( Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAdmin();

        // / validamos que el id sea un numero si no que no retorne
        if(!is_numeric($_GET['id'])) return;

        // buscamos el servicio desde el get id con lla url 
        // $servicio = Servicio::find($_GET['id']);
        $servicio = Servicio::find($_GET['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // echo 'desde actualizar';
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
            
            if(empty($alertas)){
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        $router->render('servicios/actualizar',[
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar( Router $router){
        if(!isset($_SESSION)){
            session_start();
        }
        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // echo 'desde eliminar';
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
      
    }
}


?>