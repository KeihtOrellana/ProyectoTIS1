<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte {{ $tipo }} - HabilProf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('logo.ico') }}">
    @vite(['resources/css/filtro_tablas.css', 'resources/js/filtro_tablas.js'])
    <style>
        h1, h4 { color: #8B0000; }
        .table-header { background-color: #8B0000; color: white; }
        /* Alinear las celdas con los títulos */
        /* .card-historico .table td,
        .card-historico .table th {
            text-align: center !important;
        } */

    </style>
    
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-5 shadow rounded border-top border-5 border-danger">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Listado {{ $tipo }} {{ $semestreBuscado ? "($semestreBuscado)" : '' }}</h1>
            <div>
                <a href="{{ route('habilitacion.listado') }}" class="btn btn-primary">Sí, realizar otra operación</a>
                <a href="/" class="btn btn-secondary">Salir</a>
            </div>
        </div>

        @if ($tipo === 'Semestral')
            <h4 class="mt-4">Proyectos (Ingeniería / Investigación)</h4>
            <!-- Filtro -->
            <div class="mb-2">
                <input
                    type="text"
                    id="filtro-proyectos"
                    class="form-control"
                    placeholder="Buscar en proyectos (RUT, alumno o profesor)…"
                    data-table-filter="#tabla-proyectos"
                >
            </div>
            <!--  -->
            <table id="tabla-proyectos" class="table table-bordered table-hover mt-2">
                <thead class="table-header">
                    <tr>
                        <th>📚 Semestre</th><th>🪪 RUT Alumno</th><th>🧑‍🎓 Nombre</th><th>💡 Tipo</th>
                        <th>🧭 Guía</th><th>📝 Comisión</th><th>🤝 Co-Guía</th>
                        <th>📊 Nota</th><th>📅 Fecha Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados['proyectos'] as $p)
                    <tr>
                        <td>{{ $p->semestre_inicio }}</td>
                        <td>{{ $p->alumno_rut }}</td>
                        <td>{{ $p->alumno->nombre_alumno ?? '---' }}</td>
                        <td>{{ $p->tipo_proyecto }}</td>
                        <td>{{ $p->profesorGuia->nombre_profesor ?? '---' }}</td>
                        <td>{{ $p->profesorComision->nombre_profesor ?? '---' }}</td>
                        <td>{{ $p->profesorCoguia->nombre_profesor ?? '---' }}</td> <td>{{ $p->nota_final ?? '---' }}</td>
                        <td>{{ $p->fecha_nota ?? '---' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">No hay proyectos este semestre.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <h4 class="mt-4">Prácticas Tuteladas</h4>
            <!-- Filtro -->
            <div class="mb-2">
                <input
                    type="text"
                    id="filtro-practicas"
                    class="form-control"
                    placeholder="Buscar en proyectos (RUT, alumno o profesor)…"
                    data-table-filter="#tabla-practicas"
                >
            </div>
            <!--  -->
            <table id="tabla-practicas" class="table table-bordered table-hover mt-2">
                <thead class="table-header">
                    <tr>
                        <th>📚 Semestre</th><th>🪪 RUT Alumno</th><th>🧑‍🎓 Nombre</th>
                        <th>🏢 Empresa</th><th>🧑‍💼Supervisor</th><th>🧑‍🏫 Tutor DINF</th>
                        <th>📊 Nota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resultados['practicas'] as $pt)
                    <tr>
                        <td>{{ $pt->semestre_inicio }}</td>
                        <td>{{ $pt->alumno_rut }}</td>
                        <td>{{ $pt->alumno->nombre_alumno ?? '---' }}</td>
                        <td>{{ $pt->nombre_empresa }}</td>
                        <td>{{ $pt->nombre_supervisor }}</td>
                        <td>{{ $pt->profesorTutor->nombre_profesor ?? '---' }}</td>
                        <td>{{ $pt->nota_final ?? '---' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center">No hay prácticas este semestre.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        @if ($tipo === 'Histórico')
            <!-- Filtro -->
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-md-3">
                    <label for="filtro-historico-campo" class="form-label fw-bold">Buscar por</label>
                    <select id="filtro-historico-campo" class="form-select">
                        <option value="todos">Profesor o Alumno</option>
                        <option value="profesor">Profesor</option>
                        <option value="alumno">Alumno</option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="filtro-historico" class="form-label fw-bold">Texto</label>
                    <input
                        type="text"
                        id="filtro-historico"
                        class="form-control"
                        placeholder="Escriba nombre o parte del nombre…"
                        maxlength="50"
                        pattern="[A-Za-z0-9\- ]*"
                    >
                </div>

                <div class="col-md-2">
                    <label for="filtro-historico-semestre" class="form-label fw-bold">Semestre</label>
                    <input
                        type="text"
                        id="filtro-historico-semestre"
                        class="form-control"
                        placeholder="2025-2"
                        maxlength="6"
                        pattern="[0-9\-]*"
                    >
                </div>

                <div class="col-md-2">
                    <label for="filtro-historico-rol" class="form-label fw-bold">Rol</label>
                    <select id="filtro-historico-rol" class="form-select">
                        <option value="">Todos</option>
                        <option value="guía">Guía</option>
                        <option value="co-guía">Co-Guía</option>
                        <option value="comisión">Comisión</option>
                        <option value="tutor">Tutor</option>
                    </select>
                </div>
            </div>
            <div class="alert alert-info">Ordenado por Profesor y Semestre</div>
            
            @foreach($resultados as $profe)
                @if($profe->proyectosComoGuia->isNotEmpty() || $profe->proyectosComoComision->isNotEmpty() || $profe->practicasComoTutor->isNotEmpty() || $profe->proyectosCoguia->isNotEmpty())
                     <!-- Tarjeta por profesor -->
                    
                    <div class="card mb-4 card-historico">
                        <div class="card-header fw-bold">
                           👨‍🏫 {{ $profe->nombre_profesor }} ({{ $profe->rut_profesor }})
                        </div>
                        <div class="card-body">
                            <table class="table table-sm tabla-historico">
                                <thead>
                                    <tr>
                                        <th>📚 Semestre</th>
                                        <th>🤝 Rol</th>
                                        <th>🧑‍🎓 Alumno</th>
                                        <th>💡 Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($profe->proyectosComoGuia as $p)
                                        <tr><td>{{ $p->semestre_inicio }}</td><td><span class="badge bg-primary">Guía</span></td><td>{{ $p->alumno->nombre_alumno }}</td><td>{{ $p->tipo_proyecto }}</td></tr>
                                    @endforeach
                                    @foreach($profe->proyectosCoguia as $p)
                                        <tr><td>{{ $p->semestre_inicio }}</td><td><span class="badge bg-info text-dark">Co-Guía</span></td><td>{{ $p->alumno->nombre_alumno }}</td><td>{{ $p->tipo_proyecto }}</td></tr>
                                    @endforeach
                                    @foreach($profe->proyectosComoComision as $p)
                                        <tr><td>{{ $p->semestre_inicio }}</td><td><span class="badge bg-secondary">Comisión</span></td><td>{{ $p->alumno->nombre_alumno }}</td><td>{{ $p->tipo_proyecto }}</td></tr>
                                    @endforeach
                                    @foreach($profe->practicasComoTutor as $pt)
                                        <tr><td>{{ $pt->semestre_inicio }}</td><td><span class="badge bg-success">Tutor</span></td><td>{{ $pt->alumno->nombre_alumno }}</td><td>Práctica</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

    </div>
</body>
</html>