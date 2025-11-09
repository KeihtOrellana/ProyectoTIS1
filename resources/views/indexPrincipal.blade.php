<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Habilitación Profesional</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --color-ingreso: #20c997;
            --color-editar: #ffc107;
            --color-listado: #aa85ed;
            --color-titulo: #8B0000;
        }
        body {
            background-color: #f4f4f4;
            display: flex;
            align-items: center;
            min-height: 100vh;
        }
        /* La caja principal con el borde rojo */
        .custom-card {
            border-top: 5px solid var(--color-titulo);
            border-radius: 8px;
        }
        .custom-card-title {
            color: var(--color-titulo);
            font-weight: 600;
            font-size: 28px;
        }
        /* Botones de menú personalizados */
        .menu-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px 20px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .menu-button:hover {
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }
        .menu-button .icon-wrapper {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        /* Asignar colores de las imágenes */
        #btn-ingreso { background-color: var(--color-ingreso); }
        #btn-editar { background-color: var(--color-editar); color: #333; }
        #btn-editar:hover { color: #333; }
        #btn-listado { background-color: var(--color-listado); }
    </style>
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
                                <a href="/editar" class="menu-button" id="btn-editar">
                                    <div class="icon-wrapper"><i class="fa-solid fa-file-pen"></i></div>
                                    <span>Editar datos</span>
                                </a>
                            </div>

                            <div class="col-md-4">
                                <a href="/listado" class="menu-button" id="btn-listado">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>