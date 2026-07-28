# Gekko

Aplicacion PHP para administrar y publicar contenido multimedia. El proyecto
esta organizado con un front controller publico, codigo privado fuera del
document root y almacenamiento de runtime separado.

## Estructura

- El punto de entrada publico ahora es `public/index.php`.
- Nginx debe apuntar el `root` a `public/`; `private/`, `storage/` y `doctos/`
  no deben exponerse por HTTP.
- Las rutas internas se centralizaron en `private/etc/paths.php` mediante
  constantes como `APP_ROOT`, `PRIVATE_PATH`, `PUBLIC_PATH`, `MEDIA_PATH`,
  `STORAGE_PATH`, `LOGS_PATH`, `SESSIONS_PATH` y `TMP_PATH`.
- Las vistas y controladores usan referencias de assets con `{Asset:/...}` para
  resolver archivos dentro de `public/`.
- Los archivos multimedia se sirven desde `public/media`.
- Los logs, sesiones, temporales y documentos privados se guardan en `storage/`.
- Se agrego soporte Docker para PHP-FPM 8.2 y Nginx con SSL de desarrollo.
- El router de aplicación se activa mediante `APP_ROUTER_MODE=custom` y
  `APP_ROUTER_PATH`, mediante el hook opt-in disponible en `private/core`.

## Requisitos

- Docker y Docker Compose para el entorno incluido.
- PHP 8.2 o superior si se ejecuta fuera de Docker.
- Extensiones PHP: `mysqli`, `pdo_mysql`, `mbstring`, `gd`, `zip`, `xml`,
  `sqlite3`, `pdo_sqlite`, `exif` y `opcache`.
- Submódulos Git inicializados en `private/core` y `public/assets/core-web-kit`.

## Configuracion

1. Copiar la plantilla de variables:

   ```bash
   cp private/.env.dist private/.env
   ```

2. Ajustar `private/.env` segun el entorno:

   ```dotenv
   BD_DBNAME=
   DEBUG_SQL=0
   DEBUG=0
   TIEMPO_LOG_OFF=30
   TIEMPO_REVALIDA_BD_SEGUNDOS=60
   URL_LOGIN=/
   URL_HOME=/
   MINIMIZA_LAYOUT=0
   MIAPP=NombreDeMiApp
   URL_LOGS=''
   ```

   `URL_LOGS` es opcional. Si queda vacio, los logs SQLite se escriben en
   `storage/logs`.

3. Verificar permisos de escritura para:

   - `storage/logs`
   - `storage/sessions`
   - `storage/tmp`
   - `storage/doctos`
   - `public/media`

## Ejecucion con Docker

El entorno Docker esta en `docker/`:

```bash
git submodule update --init --recursive
cd docker
cp .env_dist .env
docker compose up -d --build
```

Las dependencias compartidas forman parte del árbol de la aplicación como
submódulos y quedan disponibles dentro del volumen raíz del proyecto.

Si no existen certificados locales, generarlos antes de levantar Nginx:

```bash
cd docker/nginx/certs
sh generador_certificados
```

Servicios incluidos:

- `php`: PHP-FPM 8.2 con las extensiones requeridas.
- `nginx`: servidor web con `root /var/www/app/public`.

Puertos expuestos:

- HTTP: `8011`
- HTTPS: `8446`

URL local:

```text
https://localhost:8446
```

El `docker-compose.yml` monta el proyecto como solo lectura y habilita escritura
solo en `storage/`.

## Estructura principal

```text
public/
  index.php        Front controller
  assets/
    css/           Estilos publicos
    js/            Scripts publicos
    img/           Iconos e imagenes de UI
  media/           Multimedia publica

private/
  router.php       Registro y despacho de rutas de Gekko
  router/          Middleware y handlers web/API
  apps/            Logica de aplicacion
  etc/paths.php    Constantes de rutas
  libs/            Librerias locales
  vistas/          Layouts HTML
  .env             Configuracion local

storage/
  doctos/          Documentos privados
  logs/            Logs de runtime
  sessions/        Sesiones PHP
  tmp/             Temporales

docker/
  docker-compose.yml
  nginx/default.conf
  php/Dockerfile
```

## Rutas y APIs

El registro activo se encuentra en `private/router.php`:

| Ruta | Handler | Método |
|---|---|---|
| `/` y `/main` | `gekko_handler_main` | GET |
| `/admin` | `gekko_handler_admin` | GET |
| `/salir` | `Salir()` del core | GET |
| `/api/admin` | `gekko_manejar_api('admin')` | POST |
| `/api/instantaneas` | `gekko_manejar_api('instantaneas')` | POST |

Las APIs mantienen el campo `funcion`, las respuestas existentes y la
validación CSRF del core. La autenticación sigue desactivada en las rutas que
ya la tenían comentada; activarla debe tratarse como un cambio de seguridad
separado.

La guía general para repetir esta migración está disponible en
`private/core/MIGRACION_ROUTER_APLICACIONES.md`.

## Notas de seguridad

- No publicar `private/` ni `storage/` como document root.
- No versionar `private/.env`, `docker/.env`, logs, sesiones ni temporales.
- No versionar claves privadas ni certificados generados localmente.
- Los uploads multimedia se guardan bajo `public/media`; validar permisos y
  politicas de carga antes de usar en produccion.

## Verificaciones utiles

Antes de actualizar el repositorio:

```bash
git status --short
git diff --check
git add --dry-run .
```

`git diff --check` valida errores de espacios en blanco en el diff y
`git add --dry-run .` muestra que se agregaria al indice sin modificarlo.
