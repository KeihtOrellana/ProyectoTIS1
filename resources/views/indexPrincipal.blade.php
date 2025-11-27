<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Habilitación Profesional</title>
    <link rel="icon" href="{{ asset('logo.ico') }}">
    <!-- Carga los estilos y scripts mejor y mas rapido -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="card shadow-sm custom-card">
                    <div class="card-body p-4 p-md-5">

                        <h1 class="text-center mb-5 custom-card-title">Gestión de Habilitación Profesional</h1>

                        <nav class="row gy-4">
                            <div class="col-md-4">
                                <a href="/formulario" class="menu-button" id="btn-ingreso">
                                    <div class="icon-wrapper"><i class="fa-solid fa-file-circle-plus"></i></div>
                                    <span>Ingreso de datos</span>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/editar_eliminar" class="menu-button" id="btn-editar">
                                    <div class="icon-wrapper"><i class="fa-solid fa-file-pen"></i></div>
                                    <span>Editar datos</span>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="{{ route('habilitacion.listado') }}" class="menu-button" id="btn-listado">
                                    <div class="icon-wrapper"><i class="fa-solid fa-table-list"></i></div>
                                    <span>Crear listado</span>
                                </a>
                            </div>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </main>

</html>