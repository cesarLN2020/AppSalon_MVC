<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Funcion que revisa si el usuario esta autenticado
// si no retorna nada se pone un void 
function isAuth() : void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function esUltimo($actual, $proximo) : bool{
    // cuando sean diferentes ya es el ultimo registro de servicios 
    if($actual !== $proximo){
        return true;
    }
    return false;
}

function isAdmin() : void{
    // en login controller verificamos el tipo de user en la linea 47
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}