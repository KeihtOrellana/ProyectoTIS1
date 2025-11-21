import 'tom-select/dist/css/tom-select.bootstrap5.css';
import TomSelect from 'tom-select';


document.addEventListener('DOMContentLoaded', function () {

/*Esto era para arreglar la visual de los campos de seleccion de texto
era necesario crear este tomselect */
    const tomSelectConfig = {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };

    /*Lo mismo aqui, era necesario inizializar los tomselect para mejorar los
    campos de texto seleccionable -nombre alumno y profes- */
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

    const tipoHabilitacion = document.getElementById('tipo-habilitacion');
    
    /*contenedores */
    const tituloContainer = document.getElementById('titulo-container');
    const practicaContainer = document.getElementById('practica-container');
    const profesorContainer = document.getElementById('profesor-container');
    const coguiaContainer = document.getElementById('coguia-container');
    const labelProfesor = document.getElementById('label-profesor');
    const toggleCoguia = document.getElementById('toggle-coguia');

    const tituloProyecto = document.getElementById('titulo-proyecto');
    const profesorComision = document.getElementById('select-profesor-comision');
    const nombreEmpresa = document.getElementById('nombre-empresa');
    const nombreSupervisor = document.getElementById('nombre-supervisor');
    const profesorGuia = document.getElementById('select-profesor-guia');
    const descripcionPractica = document.getElementById('descripcion-practica');
    const descripcionProyecto = document.getElementById('descripcion');


    /*Todo esto es para ocultar los campos de profesor coguia, titulo, etc. */
    if (tipoHabilitacion) {
        tipoHabilitacion.addEventListener('change', function () {
            const selectedValue = this.value;
            /*Los oculta */
            tituloContainer.classList.add('d-none');
            practicaContainer.classList.add('d-none');
            profesorContainer.classList.add('d-none');
            
            /* aqui es necesario como "reiniciar" la inicializacion de los contenedores*/
            tituloProyecto.required = false;
            profesorComision.required = false;
            nombreEmpresa.required = false;
            nombreSupervisor.required = false;
            profesorGuia.required = false;
            descripcionPractica.required = false;
            descripcionProyecto.required = false;
            
            /*Si se selecciona PRINV o PRING aparecen los  campos */
            if (selectedValue === 'PrInv' || selectedValue === 'PrIng') {
                tituloContainer.classList.remove('d-none');
                profesorContainer.classList.remove('d-none');
                labelProfesor.innerText = 'Profesor Guía';
                
                /*obligatoriedad de campos */
                tituloProyecto.required = true;
                profesorComision.required = true;
                profesorGuia.required = true;
                descripcionProyecto.required = true;

            } else if (selectedValue === 'PrTut') {
                practicaContainer.classList.remove('d-none');
                profesorContainer.classList.remove('d-none');
                labelProfesor.innerText = 'Profesor Tutor';
                nombreEmpresa.required = true;
                nombreSupervisor.required = true;
                profesorGuia.required = true;
                descripcionPractica.required = true;
            } else {
                labelProfesor.innerText = 'Profesor Guía/Tutor';
            }
        });
    }

    /** esto es necesario para que aparezca el profesor coguia segun corresponda */
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
            /**agregue esto para que el mensaje de exito se vaya despues de un rato */
            successAlert.style.transition = 'opacity 0.3s ease-out';
            successAlert.style.opacity = '0';
            setTimeout(() => {
                successAlert.remove();
            }, 300); // 300ms = 0.3s
            
        }, 3000); // 3 segundos de espera
    }

    if (errorAlert) {
        /**lo mismo de sacar el mensaje pero para los mensajes de error */
        setTimeout(() => {
            errorAlert.style.transition = 'opacity 0.3s ease-out';
            errorAlert.style.opacity = '0';
            setTimeout(() => {
                errorAlert.remove();
            }, 300);
            
        }, 30000);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    /**esto es para configurar el tomselect. NECESARIO */
    const tomSelectConfig = {
        create: false,
        sortField: { field: "text" }
    };

    /**aqui se logra mejorar el campo alumno. ya no se muestran todos los alumnos
     * en un lateral del campo
     */
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
});

