document.addEventListener('DOMContentLoaded', function () {
    
    // Logica de Habilitacion
    const tipoHabilitacion = document.getElementById('tipo-habilitacion');
    const tituloContainer = document.getElementById('titulo-container');
    const practicaContainer = document.getElementById('practica-container');
    
    const labelProfesor = document.getElementById('label-profesor');
    const profesorContainer = document.getElementById('profesor-container'); 

    tipoHabilitacion.addEventListener('change', function () {
        const selectedValue = this.value;
        
        tituloContainer.classList.add('d-none');
        practicaContainer.classList.add('d-none');
        profesorContainer.classList.add('d-none'); 
        
        if (selectedValue === 'PrInv' || selectedValue === 'PrIng') {
            tituloContainer.classList.remove('d-none');
            profesorContainer.classList.remove('d-none'); 
            labelProfesor.innerText = 'Profesor Guía';
        } else if (selectedValue === 'PrTut') {
            practicaContainer.classList.remove('d-none');
            profesorContainer.classList.remove('d-none');
            labelProfesor.innerText = 'Profesor Tutor';
        } else {
            labelProfesor.innerText = 'Profesor Guía/Tutor'; 
        }
    });

    // Muestra y oculta el profesro co-guia
    const toggleCoguia = document.getElementById('toggle-coguia');
    const coguiaContainer = document.getElementById('coguia-container');

    if (toggleCoguia && coguiaContainer) {
        
        toggleCoguia.addEventListener('change', function() {
            if (this.value === 'si') {
                coguiaContainer.classList.remove('d-none');
            } else {
                coguiaContainer.classList.add('d-none');
            }
        });
    }

});