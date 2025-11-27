document.addEventListener('DOMContentLoaded', function () {
    
    // aqui puse datos para simular la interaccion real. (es solo el muckup)
    let habilitaciones = [
        { 
            id: "2025-1 12345678", 
            rut_alumno: 12345678, 
            nombre_alumno: "juan  perez", 
            tipo: "PrInv", // Proyecto de Investigación
            titulo: "AAAAAAAA", 
            descripcion: "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA", 
            prof_guia: 11111111, 
            prof_comision: 22222222, 
            prof_coguia: null 
        },

        { 
            id: "2025-1 99887766", 
            rut_alumno: 99887766, 
            nombre_alumno: "Pedro Pascal", 
            tipo: "PrIng", // Proyecto de ingenieria
            titulo: "jkdsahfkdfjdksaf", 
            descripcion: "Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas 'Letraset', las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.", 
            prof_guia: 33333333, 
            prof_comision: 11111111, 
            prof_coguia: null 
        },
        { 
            id: "2025-1 87654321", 
            rut_alumno: 87654321, 
            nombre_alumno: "Maria Lopez", 
            tipo: "PrTut", // Práctica Tutelada
            empresa: "junaeb", 
            supervisor: "Carlos Diaz", 
            descripcion: "gracias maduro", 
            prof_tutor: 33333333 
        }
    ];
    // profesores para el muckup
    const profesores = [
        { rut: 11111111, nombre: "Dr. Alan Turing" },
        { rut: 22222222, nombre: "Matias Toro" },
        { rut: 33333333, nombre: "George" }
    ];

    // --- REFERENCIAS DOM ---
    const vistaListado = document.getElementById('vista-listado');
    const vistaFormulario = document.getElementById('vista-formulario');
    const vistaFinal = document.getElementById('vista-final');
    const tbody = document.getElementById('tbody-habilitaciones');
    
    // Modales
    const modalEliminar = new bootstrap.Modal(document.getElementById('modalEliminar'));
    const modalGuardar = new bootstrap.Modal(document.getElementById('modalGuardarCambios'));
    const modalOtra = new bootstrap.Modal(document.getElementById('modalOtraOperacion'));

    // Variables de estado
    let idSeleccionado = null;
    let tipoSeleccionado = null;

    // RENDERIZAR LISTADO 
    function renderizarTabla() {
        tbody.innerHTML = '';
        habilitaciones.forEach(hab => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${hab.id}</td>
                <td>${hab.nombre_alumno}</td>
                <td><span class="badge ${hab.tipo === 'PrTut' ? 'bg-success' : 'bg-primary'}">${hab.tipo}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary me-2 btn-actualizar" data-id="${hab.id}">
                        <i class="fa-solid fa-pen"></i> Actualizar
                    </button>
                    <button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${hab.id}">
                        <i class="fa-solid fa-trash"></i> Eliminar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
        asignarEventosBotones();
    }

    function asignarEventosBotones() {
        // Evento Actualizar
        document.querySelectorAll('.btn-actualizar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.closest('button').dataset.id;
                iniciarActualizacion(id);
            });
        });

        // Evento Eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                idSeleccionado = e.target.closest('button').dataset.id;
                modalEliminar.show();
            });
        });
    }

    // logica de la eliminacion
    document.getElementById('btn-confirmar-eliminar').addEventListener('click', function() {
        habilitaciones = habilitaciones.filter(h => h.id !== idSeleccionado);
        modalEliminar.hide();
        mostrarMensajeExito("Habilitación Profesional eliminada con éxito");
    });

    // logica de la actualizacion
    function iniciarActualizacion(id) {
        idSeleccionado = id;
        const data = habilitaciones.find(h => h.id === id);
        tipoSeleccionado = data.tipo;

        // Ocultar listado, mostrar formulario
        vistaListado.classList.add('d-none');
        vistaFormulario.classList.remove('d-none');

        // Cargar datos comunes
        document.getElementById('input-id').value = data.id;
        document.getElementById('badge-id-hab').innerText = `ID: ${data.id}`;
        document.getElementById('input-descripcion').value = data.descripcion;

        // Llenar selects de profesores
        llenarSelectsProfesores();

        // Lógica para mostrar campos:
        // Si es PrTut -> Campos de Práctica.
        // Si NO es PrTut (es decir, PrInv o PrIng) -> Campos de Proyecto.
        if (data.tipo === 'PrTut') {
            // Es Práctica
            document.getElementById('campos-proyecto').classList.add('d-none');
            document.getElementById('campos-practica').classList.remove('d-none');
            
            document.getElementById('input-empresa').value = data.empresa;
            document.getElementById('input-supervisor').value = data.supervisor;
            document.getElementById('select-prof-tutor').value = data.prof_tutor;
        } else {
            // Es Proyecto (PrInv O PrIng)
            document.getElementById('campos-practica').classList.add('d-none');
            document.getElementById('campos-proyecto').classList.remove('d-none');

            document.getElementById('input-titulo').value = data.titulo;
            document.getElementById('select-prof-guia').value = data.prof_guia;
            document.getElementById('select-prof-comision').value = data.prof_comision;
            document.getElementById('select-prof-coguia').value = data.prof_coguia || "";
        }
    }

    function llenarSelectsProfesores() {
        const selects = ['select-prof-guia', 'select-prof-comision', 'select-prof-coguia', 'select-prof-tutor'];
        selects.forEach(idSelect => {
            const select = document.getElementById(idSelect);
            const primeraOpcion = select.options[0] ? select.options[0].outerHTML : '';
            select.innerHTML = primeraOpcion; 

            profesores.forEach(prof => {
                const opt = document.createElement('option');
                opt.value = prof.rut;
                opt.text = prof.nombre;
                select.add(opt);
            });
        });
    }

    // validaciones para el mockup
    function validarCampo(input, regex, minLength, maxLength) {
        const valor = input.value;
        const cumpleLargo = valor.length >= minLength && valor.length <= maxLength;
        const cumpleRegex = regex.test(valor);

        if (!cumpleLargo || !cumpleRegex) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            return false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            return true;
        }
    }

    // Botón "Terminar Actualización"
    document.getElementById('btn-terminar-actualizacion').addEventListener('click', function() {
        let esValido = true;

        const regexDesc = /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚüÜ\s.,;]+$/; 
        if (!validarCampo(document.getElementById('input-descripcion'), regexDesc, 100, 1000)) esValido = false;

        if (tipoSeleccionado === 'PrTut') {
            const regexEmpresa = /^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/;
            if (!validarCampo(document.getElementById('input-empresa'), regexEmpresa, 1, 50)) esValido = false;

            const regexSupervisor = /^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s-]+$/; 
            if (!validarCampo(document.getElementById('input-supervisor'), regexSupervisor, 13, 100)) esValido = false;

        } else {
            // (Aplica para PrInv y PrIng)
            const regexTitulo = /^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/;
            if (!validarCampo(document.getElementById('input-titulo'), regexTitulo, 10, 80)) esValido = false;
        }

        if (esValido) {
            modalGuardar.show();
        } 
    });

    // guardar y salir
    document.getElementById('btn-confirmar-guardado').addEventListener('click', function() {
        modalGuardar.hide();
        mostrarMensajeExito("Campos actualizados con éxito");
    });

    document.getElementById('btn-cancelar-edicion').addEventListener('click', function() {
        vistaFormulario.classList.add('d-none');
        vistaFinal.classList.remove('d-none');
    });

    // flujo final
    function mostrarMensajeExito(mensaje) {
        document.getElementById('mensaje-exito-modal').innerText = mensaje;
        vistaListado.classList.add('d-none'); 
        vistaFormulario.classList.add('d-none');
        modalOtra.show();
    }

    document.getElementById('btn-si-otra').addEventListener('click', function() {
        modalOtra.hide();
        renderizarTabla(); 
        vistaListado.classList.remove('d-none');
        document.querySelectorAll('.form-control').forEach(i => i.classList.remove('is-valid', 'is-invalid'));
    });

    document.getElementById('btn-salir').addEventListener('click', function() {
        modalOtra.hide();
        vistaFinal.classList.remove('d-none');
    });

    renderizarTabla();
});