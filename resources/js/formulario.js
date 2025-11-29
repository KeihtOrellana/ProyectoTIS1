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

        // ================== NOTA FINAL DESDE SIM_NOTAS ==================
    const selectAlumno      = document.getElementById('select-alumno');
    const inputSemAno       = document.getElementById('semestre-ano');
    const inputSemPeriodo   = document.getElementById('semestre-periodo');
    const inputNotaFinal    = document.getElementById('nota-final');

    // Cache para no pedir las notas muchas veces
    let cacheNotas = null;

    async function actualizarNotaFinal() {
        if (!selectAlumno || !inputSemAno || !inputSemPeriodo || !inputNotaFinal) return;

        const rut    = selectAlumno.value;
        const ano    = inputSemAno.value;
        const periodo = inputSemPeriodo.value;
        const tipo   = tipoHabilitacion ? tipoHabilitacion.value : null;

        // Si falta algún dato (excepto la nota), limpiamos y no buscamos nada
        if (!rut || !ano || !periodo || !tipo) {
            inputNotaFinal.value = '';
            return;
        }

        try {
            // Cargar notas simuladas solo la primera vez
            if (!cacheNotas) {
                const resp = await fetch('/simulacion/notas');
                if (!resp.ok) throw new Error('No se pudieron obtener las notas simuladas');
                cacheNotas = await resp.json();
            }

            const semestre = `${ano}-${periodo}`; // formato AAAA-Y

            // Buscar nota para ese alumno y semestre
            const registro = cacheNotas.find(n =>
                String(n.rut_alumno) === String(rut) &&
                String(n.semestre_inscrito) === semestre
            );

            if (registro && registro.nota_final !== null) {
                inputNotaFinal.value = registro.nota_final;
            } else {
                // No hay nota → dejar vacío
                inputNotaFinal.value = '';
            }
        } catch (error) {
            console.error('Error cargando nota simulada:', error);
            inputNotaFinal.value = '';
        }
    }

    // Disparar la actualización cuando cambian los campos relevantes
    if (selectAlumno) {
        selectAlumno.addEventListener('change', actualizarNotaFinal);
    }
    if (tipoHabilitacion) {
        tipoHabilitacion.addEventListener('change', actualizarNotaFinal);
    }
    if (inputSemAno) {
        inputSemAno.addEventListener('change', actualizarNotaFinal);
        inputSemAno.addEventListener('blur', actualizarNotaFinal);
    }
    if (inputSemPeriodo) {
        inputSemPeriodo.addEventListener('change', actualizarNotaFinal);
        inputSemPeriodo.addEventListener('blur', actualizarNotaFinal);
    }
    // ===============================================================


    // LÓGICA DE MOSTRAR/OCULTAR Y REQUIRED 
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
            
        }, 30000); // 3000ms = 3 segundos de espera
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Configuración base para TomSelect
    const tomSelectConfig = {
        create: false,
        sortField: { field: "text" }
    };

    // Inicializar select de Alumno
    // Nota: Corregí el selector de '#rut_alumno' a '#select-alumno' para coincidir con el HTML
    if (document.getElementById('select-alumno')) {
        new TomSelect('#select-alumno', tomSelectConfig);
    }

    // Inicializar select de Profesor Guía
    // Nota: Corregí el selector de '#profesor_guia' a '#select-profesor-guia'
    if (document.getElementById('select-profesor-guia')) {
        new TomSelect('#select-profesor-guia', tomSelectConfig);
    }

    // Inicializar select de Profesor Comisión (Agregado para consistencia)
    if (document.getElementById('select-profesor-comision')) {
        new TomSelect('#select-profesor-comision', tomSelectConfig);
    }

    // Inicializar select de Profesor Co-guía (Agregado para consistencia)
    if (document.getElementById('select-profesor-coguia')) {
        new TomSelect('#select-profesor-coguia', tomSelectConfig);
    }
});

