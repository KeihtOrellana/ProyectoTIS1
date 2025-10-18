# BasedeDatosTis1
Base de datos para el sistema "HabilProf", que gestiona las habilitaciones profesionales (Pring, Prinv, PrTut) del DINF. Implementado con migraciones de Laravel.

# paso a paso
    1 git clone https://github.com/KeihtOrellana/ProyectoTIS1
    2 composer install
    3 copy .env.example .env
    4 php artisan key:generate
    5 descomentar DB_CONNECTION=mysql
                  DB_HOST=127.0.0.1
                  DB_PORT=3306
                  DB_DATABASE=habilprof_db
                  DB_USERNAME=root
                  DB_PASSWORD=
    6 php artisan migrate:fresh