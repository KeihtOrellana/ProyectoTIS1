// resources/js/filtro_tablas.js
const sanitizarTextoFiltro = (input) => {
    input.value = input.value
        .replace(/[^A-Za-z0-9\- ]/g, '')  // solo letras, números, espacio y -
        .slice(0, 50);                     // máx 50
};

const sanitizarSemestreFiltro = (input) => {
    input.value = input.value
        .replace(/[^0-9\-]/g, '')  // solo dígitos y -
        .slice(0, 6);              // máx 6
};
document.addEventListener('DOMContentLoaded', function () {
    console.log('filtro_tablas.js cargado en reporte');

    // Función común para normalizar texto
    const normalizar = (texto) =>
        (texto || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

    // =====================================================
    // 1) FILTRO GENÉRICO → Proyectos y Prácticas (Semestral)
    // =====================================================

    const inputsFiltroGenericos = document.querySelectorAll('[data-table-filter]');

    inputsFiltroGenericos.forEach((input) => {
        const selectorTablas = input.dataset.tableFilter; // "#tabla-proyectos" o "#tabla-practicas"

        input.addEventListener('input', function () {
        // 1) limpiar el valor permitido
        sanitizarTextoFiltro(this);

        // 2) usar SIEMPRE el valor ya limpio
        const query = normalizar(this.value.trim());
        const tablas = document.querySelectorAll(selectorTablas);

        tablas.forEach((table) => {
            const filas = table.querySelectorAll('tbody tr');

            filas.forEach((fila) => {
                const textoFila = normalizar(fila.textContent);
                const coincide = !query || textoFila.includes(query);
                fila.style.display = coincide ? '' : 'none';
            });
        });
    });
    });

    // =====================================================
    // 2) FILTRO AVANZADO → Listado Histórico
    //    (Profesor / Alumno / Semestre / Rol)
    // =====================================================

    const inputHistorico   = document.getElementById('filtro-historico');
    const selectCampo      = document.getElementById('filtro-historico-campo');
    const inputSemestre    = document.getElementById('filtro-historico-semestre');
    const selectRol        = document.getElementById('filtro-historico-rol');

    if (inputHistorico) {
        const tablasHistorico = document.querySelectorAll('.tabla-historico');

        const filtrarHistorico = () => {
            
            const texto     = normalizar(inputHistorico.value.trim());
            const campo     = selectCampo ? selectCampo.value : 'todos'; // 'profesor' | 'alumno' | 'todos'
            const semestreQ = normalizar(inputSemestre?.value.trim() || '');
            const rolQ      = normalizar(selectRol?.value.trim() || '');

            tablasHistorico.forEach((table) => {
                const filas = table.querySelectorAll('tbody tr');

                filas.forEach((fila) => {
                    const celdas = fila.querySelectorAll('td');

                    // columnas: 0 = semestre, 1 = rol, 2 = alumno, 3 = tipo
                    const valorSemestre = normalizar(celdas[0]?.textContent || '');
                    const valorRol      = normalizar(celdas[1]?.textContent || '');
                    const valorAlumno   = normalizar(celdas[2]?.textContent || '');
                    const valorTipo     = normalizar(celdas[3]?.textContent || '');

                    // Profesor viene del header de la card
                    const card          = fila.closest('.card-historico');
                    const headerTexto   = card?.querySelector('.card-header')?.textContent || '';
                    const valorProfesor = normalizar(headerTexto);

                    // --- filtro por texto (Profesor / Alumno / Ambos) ---
                    let coincideTexto = true;
                    if (texto) {
                        if (campo === 'alumno') {
                            coincideTexto = valorAlumno.includes(texto);
                        } else if (campo === 'profesor') {
                            coincideTexto = valorProfesor.includes(texto);
                        } else { // 'todos'
                            coincideTexto =
                                valorProfesor.includes(texto) || valorAlumno.includes(texto);
                        }
                    }

                    // --- filtro por semestre (ej: "2025-2") ---
                    const coincideSemestre = !semestreQ || valorSemestre.includes(semestreQ);

                    // --- filtro por rol (guía, co-guía, comisión, tutor) ---
                    const coincideRol = !rolQ || valorRol === rolQ;

                    const visible = coincideTexto && coincideSemestre && coincideRol;
                    fila.style.display = visible ? '' : 'none';
                });
            });

            // Ocultar cards completas sin filas visibles
            document.querySelectorAll('.card-historico').forEach((card) => {
                const visibleFila = card.querySelector(
                    'tbody tr:not([style*="display: none"])'
                );
                card.style.display = visibleFila ? '' : 'none';
            });
        };

        // 👉 Aquí es donde limpiamos ANTES y luego filtramos

        // Texto (profesor/alumno/todos)
        inputHistorico.addEventListener('input', () => {
            sanitizarTextoFiltro(inputHistorico);   // limpia caracteres raros
            filtrarHistorico();                     // filtra con el valor limpio
        });

        // Campo (select de profesor/alumno/todos)
        selectCampo?.addEventListener('change', filtrarHistorico);

        // Semestre (solo números y guion, máx 6)
        inputSemestre?.addEventListener('input', () => {
            sanitizarSemestreFiltro(inputSemestre); // limpia caracteres raros
            filtrarHistorico();                     // filtra con el valor limpio
        });

        // Rol (select)
        selectRol?.addEventListener('change', filtrarHistorico);
    }
});


document.addEventListener('DOMContentLoaded', () => {
    // Ejemplos: adapta los IDs a los tuyos reales

    const txtHistorico = document.getElementById('filtro-historico');
    if (txtHistorico) {
        txtHistorico.addEventListener('input', () => {
            sanitizarTextoFiltro(txtHistorico);
            // aquí ya llamas a tu función de filtrar si quieres
            // filtrarHistorico();
        });
    }

    const semHistorico = document.getElementById('filtro-historico-semestre');
    if (semHistorico) {
        semHistorico.addEventListener('input', () => {
            sanitizarSemestreFiltro(semHistorico);
            // filtrarHistorico();
        });
    }

    // Lo mismo para prácticas / proyectos si quieres:
    const txtPrac = document.getElementById('filtro-prac-texto');
    if (txtPrac) {
        txtPrac.addEventListener('input', () => {
            sanitizarTextoFiltro(txtPrac);
            // filtrarPracticas();
        });
    }
});
