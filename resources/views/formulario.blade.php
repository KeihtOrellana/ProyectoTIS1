<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Habilitación Profesional</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <style>
        :root {
            --color-titulo: #8B0000;
            --color-focus: #A52A2A;
            --color-focus-shadow: rgba(139, 0, 0, 0.2);
            --color-border: #D3BDBD;
        }
        body {
            background-color: #f4f4f4;
        }
        .custom-card {
            border-top: 5px solid var(--color-titulo);
            border-radius: 8px;
        }
        .custom-card-title {
            color: var(--color-titulo);
            font-weight: 600;
        }
        .form-control, .form-select, .form-control:disabled, .form-control[readonly] {
            border-color: var(--color-border);
            background-color: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--color-focus);
            box-shadow: 0 0 5px var(--color-focus-shadow);
        }
        .form-control:disabled, .form-control[readonly] {
             background-color: #f8f9fa; /* Color gris claro para campos deshabilitados */
        }
        .btn-custom-red {
            background-color: var(--color-titulo);
            border-color: var(--color-titulo);
            color: white;
            font-weight: 600;
            padding: 10px 0;
        }
        .btn-custom-red:hover {
            background-color: var(--color-focus);
            border-color: var(--color-focus);
            color: white;
        }
        .back-link {
            text-decoration: none;
            color: #6c757d;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #333;
        }
        /* Estilo para el contenedor de Proyectos */
        #titulo-container {
            border: 1px solid var(--color-border);
            border-radius: 5px;
            padding: 1.25rem;
            margin-top: 1rem;
            background-color: #fdfdfd;
        }
    </style>
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
                                <input class="form-control" list="alumnos-list" id="rut-alumno" name="rut-alumno" placeholder="Escriba para buscar..." required>
                                <datalist id="alumnos-list">
                                    @if(isset($alumnos))
                                        @foreach($alumnos as $alumno)
                                            <option value="{{ $alumno->rut_alumno }} {{ $alumno->nombre_alumno }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <label for="profesor-guia" class="form-label fw-bold">Profesor Guía/Tutor</label>
                                <input class="form-control" list="profesores-list" id="profesor-guia" name="profesor-guia" placeholder="Escriba para buscar..." required>
                                <datalist id="profesores-list">
                                     @if(isset($profesores))
                                        @foreach($profesores as $profesor)
                                            <option value="{{ $profesor->rut_profesor }} {{ $profesor->nombre_profesor }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>

                            <div class="mb-3">
                                <label for="tipo-habilitacion" class="form-label fw-bold">Tipo de habilitación</label>
                                <select class="form-select" id="tipo-habilitacion" name="tipo-habilitacion" required>
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
                                    <input type="text" class="form-control" id="titulo-proyecto" name="titulo-proyecto" maxlength="100">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="profesor-comision" class="form-label fw-bold">Profesor Comisión</label>
                                    <input class="form-control" list="profesores-list" id="profesor-comision" name="profesor_comision" placeholder="Escriba para buscar...">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="profesor-coguia" class="form-label fw-bold">Profesor Co-guía (opcional)</label>
                                    <input class="form-control" list="profesores-list" id="profesor-coguia" name="profesor_coguia" placeholder="Escriba para buscar...">
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
                                    <input type="text" class="form-control" id="nombre-supervisor" name="nombre-supervisor" maxlength="100">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Semestre inicio</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="number" class="form-control" id="semestre-ano" name="semestre-ano" placeholder="AAAA" min="1990" max="2050" required>
                                        <div class="form-text">Ingrese año</div>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="semestre-periodo" name="semestre-periodo" min="1" max="2" placeholder="N" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoHabilitacion = document.getElementById('tipo-habilitacion');
            const tituloContainer = document.getElementById('titulo-container');
            const practicaContainer = document.getElementById('practica-container');

            tipoHabilitacion.addEventListener('change', function () {
                const selectedValue = this.value;
                // Ocultar ambos
                tituloContainer.classList.add('d-none');
                practicaContainer.classList.add('d-none');
                // Mostrar el correcto
                if (selectedValue === 'PrInv' || selectedValue === 'PrIng') {
                    tituloContainer.classList.remove('d-none');
                } else if (selectedValue === 'PrTut') {
                    practicaContainer.classList.remove('d-none');
                }
            });
        });
    </script>

</body>
</html>