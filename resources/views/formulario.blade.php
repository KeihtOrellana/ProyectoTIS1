<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Habilitación Profesional</title>
    <link rel="icon" href="{{ asset('logo.ico') }}">
    <!-- Carga los estilos y scripts mejor y mas rapido -->
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

                        <form method="POST" action="/registrar-habilitacion">
                            @csrf 

                            <div class="mb-3">
                                <label for="rut-alumno" class="form-label fw-bold">RUT Alumno</label>
                                <input class="form-control" list="alumnos-list" id="rut-alumno" name="rut_alumno" placeholder="Escriba para buscar..." required>
                                <datalist id="alumnos-list">
                                <!-- Lista de alumnos ingresados al sistema -->
                                    @if(isset($alumnos))
                                        @foreach($alumnos as $alumno)
                                            <option value="{{ $alumno->rut_alumno }} {{ $alumno->nombre_alumno }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>

                            <div class="mb-3 d-none" id="profesor-container">
                                <label for="profesor-guia" class="form-label fw-bold" id="label-profesor">Profesor Guía/Tutor</label>
                                <input class="form-control" list="profesores-list" id="profesor-guia" name="profesor_guia_rut" placeholder="Escriba para buscar..." required>
                                <!-- Lista de profesores ingresados al sistema -->
                                <datalist id="profesores-list">
                                    @if(isset($profesores))
                                        @foreach($profesores as $profesor)
                                            <option value="{{ $profesor->nombre_profesor }}">
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
                                    <input type="text" class="form-control" id="titulo-proyecto" name="titulo" maxlength="100">
                                </div>

                                <div class="mb-3">
                                    <label for="profesor-comision" class="form-label fw-bold">Profesor Comisión</label>
                                    <input class="form-control" list="profesores-list" id="profesor-comision" name="profesor_comision" placeholder="Escriba para buscar...">
                                </div>

                                <div class="mb-3">
                                    <label for="toggle-coguia" class="form-label fw-bold">¿Desea incluir un profesor co-guía?</label>
                                    <select class="form-select" id="toggle-coguia" name="toggle_coguia">
                                        <option value="no" selected>No</option>
                                        <option value="si">Sí</option>
                                    </select>
                                </div>

                                <div class="mb-3 d-none" id="coguia-container">
                                    <label for="profesor-coguia" class="form-label fw-bold">Profesor Co-guía</label>
                                    <input class="form-control" list="profesores-list" id="profesor-coguia" name="profesor_coguia_rut" placeholder="Escriba para buscar...">
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Añada una breve descripción del proyecto..."></textarea>
                                </div>

                            </div>
                            <div id="practica-container" class="d-none"> 
                                <div class="mb-3">
                                    <label for="nombre-empresa" class="form-label fw-bold">Nombre empresa</label>
                                    <input type="text" class="form-control" id="nombre-empresa" name="nombre-empresa" maxlength="50">
                                </div>
                                <div class="mb-3">
                                    <label for="nombre-supervisor" class="form-label fw-bold">Nombre Supervisor</label>
                                    <input type="text" class="form-control" id="nombre-supervisor" name="nombre_supervisor" maxlength="100">
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