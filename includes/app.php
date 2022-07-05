<?php 

require __DIR__ . '/../vendor/autoload.php';
// le indicamos que las variables estan en la misma carpeta, igual se le puede pasar un segundo valor que seria
// el nombre del archivo si es que estuviera en otra carpeta ''
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//safeLoad si el archivo no existe no nos va a marcar error
$dotenv->safeLoad();
require 'funciones.php';
require 'database.php';

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);