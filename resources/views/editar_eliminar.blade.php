<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Habilitación (Prototipo)</title>
    <link rel="icon" href="{{ asset('logo.ico') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/editar_eliminar.css', 'resources/js/editar_eliminar.js'])
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm custom-card">
                    <div class="card-body p-4 p-md-5">

                        <div id="vista-listado">
                            <h1 class="h3 custom-card-title text-center mb-4">Actualizar o Eliminar Habilitación</h1>
                            <p class="text-muted text-center mb-4">Seleccione una habilitación (Datos Simulados).</p>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID Habilitación</th>
                                            <th>Nombre Alumno</th>
                                            <th>Tipo</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-habilitaciones">
                                        </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="vista-formulario" class="d-none">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2 class="h4 custom-card-title mb-0">Actualizar Datos</h2>
                                <span class="badge bg-secondary" id="badge-id-hab">ID: --</span>
                            </div>

                            <form id="form-actualizar" novalidate>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ID Habilitación</label>
                                    <input type="text" class="form-control" id="input-id" readonly disabled>
                                </div>

                                <div id="campos-proyecto" class="d-none">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Título (R2.5)</label>
                                        <input type="text" class="form-control" id="input-titulo">
                                        <div class="invalid-feedback">Modificación no es válida (10-80 letras y espacios).</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Profesor Guía</label>
                                        <select class="form-select" id="select-prof-guia"></select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Profesor Comisión</label>
                                        <select class="form-select" id="select-prof-comision"></select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Profesor Co-Guía</label>
                                        <select class="form-select" id="select-prof-coguia">
                                            <option value="">-- Ninguno --</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="campos-practica" class="d-none">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nombre Empresa (R2.7)</label>
                                        <input type="text" class="form-control" id="input-empresa">
                                        <div class="invalid-feedback">Modificación no es válida (1-50 letras).</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nombre Supervisor (R2.8)</label>
                                        <input type="text" class="form-control" id="input-supervisor">
                                        <div class="invalid-feedback">Modificación no es válida (Formato nombre incorrecto).</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Profesor Tutor</label>
                                        <select class="form-select" id="select-prof-tutor"></select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Descripción (R2.2)</label>
                                    <textarea class="form-control" id="input-descripcion" rows="4"></textarea>
                                    <div class="form-text">Mínimo 100 caracteres, máx 1000.</div>
                                    <div class="invalid-feedback">Modificación no es válida (Largo o caracteres incorrectos).</div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-secondary" id="btn-cancelar-edicion">Cancelar operación</button>
                                    <button type="button" class="btn btn-custom-red" id="btn-terminar-actualizacion">Terminar actualización</button>
                                </div>
                            </form>
                        </div>

                        <div id="vista-final" class="d-none text-center py-5">
                            <h2 class="text-success mb-3"><i class="fa-solid fa-check-circle"></i> Operación finalizada</h2>
                            <p>El sistema ha terminado de eliminar o actualizar los datos.</p>
                            <a href="/" class="btn btn-primary mt-3">Ir al Inicio</a>
                        </div>

                    </div>
                </div>
                                            <div class="text-center mt-4">
                                <a href="/" class="back-link">Volver al menú principal</a>
                            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminar" tabindex="-1" data-bs-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-danger text-white"><h5 class="modal-title">Confirmar Eliminación</h5></div><div class="modal-body"><p>¿Está seguro que desea eliminar esta Habilitación?</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-danger" id="btn-confirmar-eliminar">Confirmar</button></div></div></div></div>
    <div class="modal fade" id="modalGuardarCambios" tabindex="-1" data-bs-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-custom-red text-white"><h5 class="modal-title">Guardar Cambios</h5></div><div class="modal-body"><p>¿Desea guardar los cambios realizados?</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-custom-red" id="btn-confirmar-guardado">Confirmar</button></div></div></div></div>
    <div class="modal fade" id="modalOtraOperacion" tabindex="-1" data-bs-backdrop="static"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Operación Exitosa</h5></div><div class="modal-body"><p id="mensaje-exito-modal" class="fw-bold text-success"></p><hr><p>¿Necesita realizar otra operación?</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" id="btn-salir">Salir</button><button type="button" class="btn btn-primary" id="btn-si-otra">Si</button></div></div></div></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    </body>
</html>