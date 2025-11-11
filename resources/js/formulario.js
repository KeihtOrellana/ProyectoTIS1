document.addEventListener('DOMContentLoaded', function () {
    
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

    // CAMPOS QUE CAMBIARÁN DE REQUIRED
    const tituloProyecto = document.getElementById('titulo-proyecto');
    const profesorComision = document.getElementById('profesor-comision-display');
    const nombreEmpresa = document.getElementById('nombre-empresa');
    const nombreSupervisor = document.getElementById('nombre-supervisor');
    const profesorGuia = document.getElementById('profesor-guia-display');
    const descripcionPractica = document.getElementById('descripcion-practica');
    const descripcionProyecto = document.getElementById('descripcion'); // <- El campo que faltaba


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
            descripcionProyecto.required = false; // <- Resetea la descripción del proyecto
            
            // 3. Mostrar contenedor y poner 'required' según la selección
            if (selectedValue === 'PrInv' || selectedValue === 'PrIng') {
                tituloContainer.classList.remove('d-none');
                profesorContainer.classList.remove('d-none');
                labelProfesor.innerText = 'Profesor Guía';
                
                // Hacer obligatorios los campos de Proyecto
                tituloProyecto.required = true;
                profesorComision.required = true;
                profesorGuia.required = true;
                descripcionProyecto.required = true; // <- Hacer obligatoria

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

    // Muestra y oculta el profesor co-guia
    if (toggleCoguia && coguiaContainer) {
        toggleCoguia.addEventListener('change', function() {
            if (this.value === 'si') {
                coguiaContainer.classList.remove('d-none');
            } else {
                coguiaContainer.classList.add('d-none');
            }
        });
    }
    
    // --- LÓGICA DE SINCRONIZACIÓN DE RUTS (Sin cambios) ---
    function setupDatalistSync(inputId) {
        const displayInput = document.getElementById(inputId);
        if (!displayInput) return; 

        const datalistId = displayInput.dataset.datalistId;
        const hiddenId = displayInput.dataset.hiddenId;
        const datalist = document.getElementById(datalistId);
        const hiddenInput = document.getElementById(hiddenId);

        if (!datalist || !hiddenInput) {
            console.warn('Faltan elementos para ' + inputId);
            return;
        }

        displayInput.addEventListener('input', function (e) {
            const selectedValue = e.target.value;
            let foundRut = '';
            const options = datalist.getElementsByTagName('option');
            
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === selectedValue) {
                    foundRut = options[i].dataset.rut;
                    break;
                }
            }
            hiddenInput.value = foundRut;
        });
    }

    // Inicializar la sincronización
    setupDatalistSync('rut-alumno-display');
    setupDatalistSync('profesor-guia-display');
    setupDatalistSync('profesor-comision-display');
    setupDatalistSync('profesor-coguia-display');
});