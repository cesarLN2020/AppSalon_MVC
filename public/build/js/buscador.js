document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();

});

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e){
        const fechaSeleccionada = e.target.value;
        // puede ser location o window.location 
        location = `?fecha=${fechaSeleccionada}`;
    });
}