<?php 

// debuguear($alertas);
    // este foreach accedera a la variable alertas y con 'AS $KEY' accedera a su llave y el resultado se almacenara en => mensajes
    foreach($alertas as $key =>$mensajes):
        // esto biene siendo la llave del arreglo
        // debuguear($key);
        foreach($mensajes as $mensaje):
?>
    <div class="alerta <?php echo $key; ?>">
            <?php echo $mensaje; ?>
    </div>
<?php
        endforeach;
    endforeach;
?>