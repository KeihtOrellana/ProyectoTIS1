import 'tom-select/dist/css/tom-select.bootstrap5.css';
import TomSelect from 'tom-select';


document.addEventListener('DOMContentLoaded', function () {


    const tomSelectConfig = {
        create: false, // Evita que el usuario cree nuevas opciones
        sortField: {
            field: "text",
            direction: "asc"
        }
    };

    // Inicializa todos los selectores de búsqueda
    if (document.getElementById('select-alumno')) {
        new TomSelect('#select-alumno', tomSelectConfig);
    }
    if (document.getElementById('select-profesor-guia')) {
        new TomSelect('#select-profesor-guia', tomSelectConfig);
    }
    if (document.getElementById('select-profesor-comision')) {
        new TomSelect('#select-profesor-comision', tomSelectConfig);
    }
    if (document.getElementById('select-profesor-coguia')) {
        new TomSelect('#select-profesor-coguia', tomSelectConfig);
    }

    
    // --- OBTENER TODOS LOS ELEMENTOS ---

    const tipoHabilitacion = document.getElementById('tipo-habilitacion');
    
    // Contenedores
    const tituloContainer = document.getElementById('titulo-container');
    const practicaContainer = document.getElementById('practica-container');
    const profesorContainer = document.getElementById('profesor-container');
    const coguiaContainer = document.getElementById('coguia-container');
    
    // Etiquetas y Selects
    const labelProfesor = document.getElementById('label-profesor');
    const toggleCoguia = document.getElementById('toggle-coguia');

    // ==========================================
    const tituloProyecto = document.getElementById('titulo-proyecto');
    const profesorComision = document.getElementById('select-profesor-comision'); // <--- CAMBIO
    const nombreEmpresa = document.getElementById('nombre-empresa');
    const nombreSupervisor = document.getElementById('nombre-supervisor');
    const profesorGuia = document.getElementById('select-profesor-guia'); // <--- CAMBIO
    const descripcionPractica = document.getElementById('descripcion-practica');
    const descripcionProyecto = document.getElementById('descripcion');


    // --- LÓGICA DE MOSTRAR/OCULTAR Y REQUIRED ---
    if (tipoHabilitacion) {
        tipoHabilitacion.addEventListener('change', function () {
            const selectedValue = this.value;
            
            // 1. Ocultar todo
            tituloContainer.classList.add('d-none');
            practicaContainer.classList.add('d-none');
            profesorContainer.classList.add('d-none');
            
            // 2. Resetear 'required' de TODOS los campos
            tituloProyecto.required = false;
            profesorComision.required = false;
            nombreEmpresa.required = false;
            nombreSupervisor.required = false;
            profesorGuia.required = false;
            descripcionPractica.required = false;
            descripcionProyecto.required = false;
            
            // 3. Mostrar contenedor y poner 'required' según la selección
            if (selectedValue === 'PrInv' || selectedValue === 'PrIng') {
                tituloContainer.classList.remove('d-none');
                profesorContainer.classList.remove('d-none');
                labelProfesor.innerText = 'Profesor Guía';
                
                // Hacer obligatorios los campos de Proyecto
                tituloProyecto.required = true;
                profesorComision.required = true;
                profesorGuia.required = true;
                descripcionProyecto.required = true;

            } else if (selectedValue === 'PrTut') {
                practicaContainer.classList.remove('d-none');
                profesorContainer.classList.remove('d-none');
                labelProfesor.innerText = 'Profesor Tutor';

                // Hacer obligatorios los campos de Práctica
                nombreEmpresa.required = true;
                nombreSupervisor.required = true;
                profesorGuia.required = true;
                descripcionPractica.required = true;
            } else {
                labelProfesor.innerText = 'Profesor Guía/Tutor';
            }
        });
    }

    // --- MUESTRA Y OCULTA EL PROFESOR CO-GUIA ---
    if (toggleCoguia && coguiaContainer) {
        toggleCoguia.addEventListener('change', function() {
            if (this.value === 'si') {
                coguiaContainer.classList.remove('d-none');
            } else {
                coguiaContainer.classList.add('d-none');
            }
        });
    }
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    if (successAlert) {
        setTimeout(() => {
            // Inicia la transición de desvanecimiento
            successAlert.style.transition = 'opacity 0.3s ease-out';
            successAlert.style.opacity = '0';
            
            // Espera a que termine la transición (0.3s) y luego la elimina
            setTimeout(() => {
                successAlert.remove();
            }, 300); // 300ms = 0.3s (debe coincidir con la transición)
            
        }, 3000); // 3000ms = 3 segundos de espera
    }

    if (errorAlert) {
        // Espera 3 segundos (3000 milisegundos)
        setTimeout(() => {
            // Inicia la transición de desvanecimiento
            errorAlert.style.transition = 'opacity 0.3s ease-out';
            errorAlert.style.opacity = '0';
            
            // Espera a que termine la transición (0.3s) y luego la elimina
            setTimeout(() => {
                errorAlert.remove();
            }, 300); // 300ms = 0.3s (debe coincidir con la transición)
            
        }, 3000); // 3000ms = 3 segundos de espera
    }
});


