<?php 

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController{
    // creamos un metodo con su funcion 
    // este si tiene router por que va a consulgar BD y mostrar citas 
    public static function index(Router $router){

        if (!$_SESSION['nombre']) {
            session_start();
        }

        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        // para pasar la fecha en particion para el checkdate usamos explode que convierte en arreglo
        $fechas = explode('-', $fecha);

        // para protejer que los usuarios editen la url utilizamos:
        // checkdate(toma 3 parametros) y retorna true o false
        if(!checkdate($fechas[1], $fechas[2], $fechas[0])){
            header('Location: /404');
        }

        // consultar la BD
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";

        $citas = AdminCita::SQL($consulta);
        // debuguear($citas);

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);

    }
}



?>