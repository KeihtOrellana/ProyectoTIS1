<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Listados - HabilProf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --color-titulo: #8B0000; --color-btn: #aa85ed; }
        body { background-color: #f4f4f4; display: flex; align-items: center; min-height: 100vh; }
        .card { border-top: 5px solid var(--color-titulo); }
        .btn-purple { background-color: var(--color-btn); color: white; font-weight: bold; }
        .btn-purple:hover { background-color: #9575cd; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h2 class="text-center mb-4" style="color: var(--color-titulo)">Listados Varios</h2>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('habilitacion.reporte') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Listado</label>
                            <select name="tipo_listado" id="tipo_listado" class="form-select" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Semestral">Semestral</option>
                                <option value="Histórico">Histórico</option>
                            </select>
                        </div>

                        <div id="campo-semestre" class="mb-4 d-none">
                            <label class="form-label fw-bold">Ingrese Semestre (R4.7)</label>
                            <div class="input-group">
                                <input type="number" name="semestre_ano" class="form-control" placeholder="AAAA (2025-2045)" min="2025" max="2045">
                                <span class="input-group-text">-</span>
                                <input type="number" name="semestre_periodo" class="form-control" placeholder="N (1-2)" min="1" max="2">
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-purple">Generar Reporte</button>
                            <a href="/" class="btn btn-outline-secondary">Volver al Menú</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Lógica simple para mostrar/ocultar el campo de semestre
        document.getElementById('tipo_listado').addEventListener('change', function() {
            const campo = document.getElementById('campo-semestre');
            if (this.value === 'Semestral') {
                campo.classList.remove('d-none');
            } else {
                campo.classList.add('d-none');
            }
        });
    </script>
</body>
</html>