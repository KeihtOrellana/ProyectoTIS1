# 🧩 Configuración del Proyecto Laravel

Sigue estos pasos para preparar y ejecutar el proyecto correctamente en tu entorno local.

---

## ⚙️ 1. Configurar el archivo `.env`

Abre el archivo `.env` en la raíz del proyecto y reemplaza (o descomenta) las siguientes líneas según tu configuración local:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyectotis
DB_USERNAME=root
DB_PASSWORD=
```

> 💡 Asegúrate de que tu servidor MySQL esté en ejecución y la base de datos `proyectotis` exista.

---

## 🧱 2. Instalar dependencias

Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
composer install
```

---

## 🗄️ 3. Ejecutar las migraciones

Aplica las migraciones de la base de datos con:

```bash
php artisan migrate
```

> Si Laravel solicita crear la base de datos o confirma alguna acción, acepta (escribe `yes` o `y`).

---

## 🎨 4. Instalar Bootstrap y Popper.js

Ejecuta el siguiente comando para instalar las dependencias de frontend necesarias:

```bash
npm install bootstrap @popperjs/core
```

---

## 🔑 5. Generar la clave de aplicación

Crea la clave única para tu aplicación con:

```bash
php artisan key:generate
```

---

## 🚀 6. Iniciar el servidor de desarrollo

Finalmente, levanta el servidor local con:

```bash
php artisan serve
```

Luego abre tu navegador en la dirección que se indique (por defecto [http://127.0.0.1:8000](http://127.0.0.1:8000)).

---

## 🧩 Notas adicionales (opcional)

- Si aparece un error de migración, asegúrate de haber creado la base de datos en **phpMyAdmin** o mediante MySQL.
- Si el comando `composer install` no funciona, asegúrate de tener **Composer** correctamente instalado y agregado al PATH.
- Si `npm install` muestra errores, elimina la carpeta `node_modules` y ejecuta de nuevo:
  ```bash
  npm install
  ```
- Si `php artisan serve` no inicia, verifica que no haya otro servidor ejecutándose en el puerto 8000.
