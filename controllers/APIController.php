<?php 

namespace Controllers;

use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController{
    // no necesitamos el router por que solo manejamos json 
    public static function index(){
        // importamos el modelo  si no fuera static el all seria = new Servicio
        $servicios = Servicio::all();

        // se trae los registros directos de mysql y para manipularlos con js ocupamos convertirlos
        echo json_encode($servicios);
    }

    public static function guardar(){
        // solo es prueba
        // $respuesta = [
        //     'datos' => $_POST
        // ];

        // Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado['id'];

        //Almacena  los servicios con el id de la cita
        $idServicios = explode(",", $_POST['servicios']);

        // aqui vamos guardando cada uno de los servicios con la referencia de la cita 
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar(){
        // echo "Eliminando...";
        // validamos que solo se ejecute cuando estemos en metodo post 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            
            // ya tenemos el modelo importado de cita y en activerecord tenemos la funcion find()
            $cita = Cita::find($id);
            $cita->eliminar($id);
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}

?>