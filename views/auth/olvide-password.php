<h2 class="nombre-pagina">Olvide Password</h2>
<p class="descripcion-pagina">Reestablece tu password escribiendo tu email a continuación</p>

<!-- traeos las alertas y como es un arreglo se puede iterar con un forEach -->
<?php 
    // DIR sirve para indicar que desde ese archivo vamos a partir
    include_once __DIR__. "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">

    <div class="campo">
        <label class="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu E-mail">
    </div>

    <input type="submit" value="Enviar Instrucciones" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
</div>