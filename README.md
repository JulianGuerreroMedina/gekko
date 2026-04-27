# Gekko

Aplicacion PHP para administrar y publicar contenido multimedia. El proyecto
esta organizado con un front controller publico, codigo privado fuera del
document root y almacenamiento de runtime separado.

## Cambios recientes de estructura

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

## Requisitos

- Docker y Docker Compose para el entorno incluido.
- PHP 8.2 o superior si se ejecuta fuera de Docker.
- Extensiones PHP: `mysqli`, `pdo_mysql`, `mbstring`, `gd`, `zip`, `xml`,
  `sqlite3`, `pdo_sqlite`, `exif` y `opcache`.
- Acceso al core compartido montado en `private/core` o mediante el volumen
  configurado en Docker.

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
cd docker
cp .env_dist .env
docker compose up -d --build
```

Despues de crear `docker/.env`, ajustar las rutas de dependencias compartidas
para que apunten a directorios reales del host:

```dotenv
CORE_PATH=/ruta/local/al/core
CORE_JS_PATH=/ruta/local/al/core.js
CORE_ICONS_PATH=/ruta/local/al/core.icons
```

Docker Compose monta esas rutas dentro del contenedor como `private/core`,
`public/core.js` y `public/core.icons`. Si alguna variable queda vacia o apunta
a una ruta inexistente, Docker Compose no podra iniciar correctamente.

Si no existen certificados locales, generarlos antes de levantar Nginx:

```bash
cd docker/ngnix/certs
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

El `docker-compose.yml` monta el proyecto como solo lectura, habilita escritura
solo en `storage/` y usa las variables de `docker/.env` para montar las
dependencias compartidas externas.

## Estructura principal

```text
public/
  index.php        Front controller
  css/             Estilos publicos
  js/              Scripts publicos
  img/             Iconos e imagenes de UI
  media/           Multimedia publica

private/
  Controllers/     Controladores de paginas
  api/             Endpoints internos
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
  ngnix/default.conf
  php/Dockerfile
```

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
