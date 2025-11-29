<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Habilitación Profesional</title>
    <link rel="icon" href="{{ asset('logo.ico') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/formulario.js'])
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm custom-card">
                    <div class="card-body p-4 p-md-5">

                        <h1 class="h3 mb-2 custom-card-title">Formulario de Habilitación Profesional</h1>
                        <p class="card-text text-muted mb-4">Complete los siguientes campos para registrar la habilitación.</p>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert" id="success-alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert" id="error-alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('proyecto_creado'))
                            <div class="card border-danger mb-4">
                                <div class="card-header bg-danger text-white">
                                    <strong>Se ha ingresado exitosamente la Habilitación Profesional del alumno</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">ID Habilitación:</div>
                                        <div class="col-md-8">{{ session('proyecto_creado')->id_habilitacion }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Título:</div>
                                        <div class="col-md-8">{{ session('proyecto_creado')->titulo }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Descripción:</div>
                                        <div class="col-md-8">{{ session('proyecto_creado')->descripcion }}</div>
                                    </div>
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">RUT Prof. Guía:</div>
                                        <div class="col-md-8">{{ session('proyecto_creado')->profesor_guia_rut }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">RUT Prof. Comisión:</div>
                                        <div class="col-md-8">{{ session('proyecto_creado')->profesor_comision_rut }}</div>
                                    </div>
                                    @if(session('proyecto_creado')->profesor_coguia_rut)
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">RUT Prof. Co-Guía:</div>
                                        <div class="col-md-8">{{ session('proyecto_creado')->profesor_coguia_rut }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if (session('practica_creada'))
                            <div class="card border-danger mb-4">
                                <div class="card-header bg-danger text-white">
                                    <strong>Se ha ingresado exitosamente la Habilitación Profesional del alumno</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">ID Habilitación:</div>
                                        <div class="col-md-8">{{ session('practica_creada')->id_habilitacion }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">RUT Profesor Tutor:</div>
                                        <div class="col-md-8">{{ session('practica_creada')->profesor_tutor_rut }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Nombre Empresa:</div>
                                        <div class="col-md-8">{{ session('practica_creada')->nombre_empresa }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Nombre Supervisor:</div>
                                        <div class="col-md-8">{{ session('practica_creada')->nombre_supervisor }}</div>
                                    </div>
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-md-4 fw-bold">Descripción:</div>
                                        <div class="col-md-8">{{ session('practica_creada')->descripcion }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <form method="POST" action="/registrar-habilitacion">
                            @csrf

                            <div class="mb-3">
                                <label for="select-alumno" class="form-label fw-bold">Alumno</label>
                                <select id="select-alumno" name="rut_alumno" placeholder="Escriba para buscar..." required>
                                    <option value="">Seleccione un alumno...</option>
                                    @if(isset($alumnos))
                                        @foreach($alumnos as $alumno)
                                            <option value="{{ $alumno->rut_alumno }}">{{ $alumno->nombre_alumno }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3 d-none" id="profesor-container">
                                <label for="select-profesor-guia" class="form-label fw-bold" id="label-profesor">Profesor Guía/Tutor</label>
                                <select id="select-profesor-guia" name="profesor_guia_rut" placeholder="Escriba para buscar..." required>
                                    <option value="">Seleccione un profesor...</option>
                                    @if(isset($profesores))
                                        @foreach($profesores as $profesor)
                                            <option value="{{ $profesor->rut_profesor }}">{{ $profesor->nombre_profesor }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tipo-habilitacion" class="form-label fw-bold">Tipo de habilitación</label>
                                <select class="form-select" id="tipo-habilitacion" name="tipo_habilitacion" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    <option value="PrTut">Práctica tutelada (PrTut)</option>
                                    <option value="PrInv">Proyecto de investigación (PrInv)</option>
                                    <option value="PrIng">Proyecto de ingeniería (PrIng)</option>
                                </select>
                            </div>

                            <div id="titulo-container" class="d-none"> 
                                <h5 class="custom-card-title mb-3">Detalles del Proyecto</h5>

                                <div class="mb-3">
                                    <label for="titulo-proyecto" class="form-label fw-bold">Nombre Proyecto</label>
                                    <input type="text" class="form-control" id="titulo-proyecto" name="titulo" maxlength="100" required>
                                </div>

                                <div class="mb-3">
                                    <label for="select-profesor-comision" class="form-label fw-bold">Profesor Comisión</label>
                                    <select id="select-profesor-comision" name="profesor_comision_rut" placeholder="Escriba para buscar..." required>
                                        <option value="">Seleccione un profesor...</option>
                                        @if(isset($profesores))
                                            @foreach($profesores as $profesor)
                                                <option value="{{ $profesor->rut_profesor }}">{{ $profesor->nombre_profesor }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="toggle-coguia" class="form-label fw-bold">¿Desea incluir un profesor co-guía?</label>
                                    <select class="form-select" id="toggle-coguia" name="toggle_coguia">
                                        <option value="no" selected>No</option>
                                        <option value="si">Sí</option>
                                    </select>
                                </div>

                                <div class="mb-3 d-none" id="coguia-container">
                                    <label for="select-profesor-coguia" class="form-label fw-bold">Profesor Co-guía</label>
                                    <select id="select-profesor-coguia" name="profesor_coguia_rut" placeholder="Escriba para buscar...">
                                        <option value="">Seleccione un profesor...</option>
                                        @if(isset($profesores))
                                            @foreach($profesores as $profesor)
                                                <option value="{{ $profesor->rut_profesor }}">{{ $profesor->nombre_profesor }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Añada una breve descripción del proyecto..."></textarea>
                                </div>
                            </div>

                            <div id="practica-container" class="d-none"> 
                                <div class="mb-3">
                                    <label for="nombre-empresa" class="form-label fw-bold">Nombre empresa</label>
                                    <input type="text" class="form-control" id="nombre-empresa" name="nombre-empresa" maxlength="50" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombre-supervisor" class="form-label fw-bold">Nombre Supervisor</label>
                                    <input type="text" class="form-control" id="nombre-supervisor" name="nombre_supervisor" maxlength="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion-practica" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" id="descripcion-practica" name="descripcion_practica" rows="3" placeholder="Añade una breve descripción" required></textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Semestre inicio</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="number" class="form-control" id="semestre-ano" name="semestre-ano" value="2025" min="1990" max="2050" required>
                                        <div class="form-text">Ingrese año</div>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="semestre-periodo" name="semestre-periodo" min="1" max="2" value="1" required>
                                        <div class="form-text">Ingrese semestre (1 - 2)</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="nota-final" class="form-label fw-bold">Nota Final</label>
                                <input type="text" class="form-control" id="nota-final" name="nota-final" value="" readonly>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-custom-red w-100">Registrar Habilitación</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="/" class="back-link">Volver al menú principal</a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
</body>
</html>