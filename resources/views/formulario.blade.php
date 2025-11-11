<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Habilitación Profesional</title>
    <link rel="icon" href="{{ asset('logo.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST" action="/registrar-habilitacion">
                            @csrf 

                            <div class="mb-3">
                                <label for="rut-alumno-display" class="form-label fw-bold">Alumno</label>
                                <input class="form-control" list="alumnos-list" id="rut-alumno-display" 
                                       placeholder="Escriba para buscar..." required
                                       data-datalist-id="alumnos-list" 
                                       data-hidden-id="rut-alumno-hidden">
                                <input type="hidden" name="rut_alumno" id="rut-alumno-hidden">
                                
                                <datalist id="alumnos-list">
                                    @if(isset($alumnos))
                                        @foreach($alumnos as $alumno)
                                            <option value="{{ $alumno->nombre_alumno }}" data-rut="{{ $alumno->rut_alumno }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>

                            <div class="mb-3 d-none" id="profesor-container">
                                <label for="profesor-guia-display" class="form-label fw-bold" id="label-profesor">Profesor Guía/Tutor</label>
                                <input class="form-control" list="profesores-list" id="profesor-guia-display" 
                                       placeholder="Escriba para buscar..." required
                                       data-datalist-id="profesores-list" 
                                       data-hidden-id="profesor-guia-hidden">
                                <input type="hidden" name="profesor_guia_rut" id="profesor-guia-hidden">
                                
                                <datalist id="profesores-list">
                                    @if(isset($profesores))
                                        @foreach($profesores as $profesor)
                                            <option value="{{ $profesor->nombre_profesor }}" data-rut="{{ $profesor->rut_profesor }}">
                                        @endforeach
                                    @endif
                                </datalist>
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
                                    <label for="profesor-comision-display" class="form-label fw-bold">Profesor Comisión</label>
                                    <input class="form-control" list="profesores-list" id="profesor-comision-display" 
                                           placeholder="Escriba para buscar..."
                                           data-datalist-id="profesores-list" 
                                           data-hidden-id="profesor-comision-hidden"
                                           required>
                                    <input type="hidden" name="profesor_comision_rut" id="profesor-comision-hidden">
                                </div>

                                <div class="mb-3">
                                    <label for="toggle-coguia" class="form-label fw-bold">¿Desea incluir un profesor co-guía?</label>
                                    <select class="form-select" id="toggle-coguia" name="toggle_coguia">
                                        <option value="no" selected>No</option>
                                        <option value="si">Sí</option>
                                    </select>
                                </div>

                                <div class="mb-3 d-none" id="coguia-container">
                                    <label for="profesor-coguia-display" class="form-label fw-bold">Profesor Co-guía</label>
                                    <input class="form-control" list="profesores-list" id="profesor-coguia-display" 
                                           placeholder="Escriba para buscar..."
                                           data-datalist-id="profesores-list" 
                                           data-hidden-id="profesor-coguia-hidden">
                                    <input type="hidden" name="profesor_coguia_rut" id="profesor-coguia-hidden">
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
                                    <textarea class="form-control" id="descripcion-practica" name="descripcion_practica" rows="3"
                                        placeholder="Añade una breve descripción" required></textarea>
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
                                <input type="text" class="form-control" id="nota-final" name="nota-final" value="4.0" readonly>
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

</body>
</html>
        </div>
    </div>

</body>
</html>