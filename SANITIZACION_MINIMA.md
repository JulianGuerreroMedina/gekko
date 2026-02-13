# Politica Minima de Sanitizacion

## 1. Capa de Control
- No pasar `$_POST`, `$_GET` o `$_REQUEST` directo al modelo.
- IDs numericos: validar con `ctype_digit()` y castear a `(int)`.
- Fechas: normalizar con funciones de fecha del proyecto y validar formato.
- Selects con opcion `nulo`: convertir a `0` o cortar flujo antes de consultar.

## 2. Capa de Modelo
- Nunca interpolar texto sin escapar en SQL.
- Para IDs numericos, castear al inicio del metodo (`(int)$id`) y validar `> 0` cuando aplique.
- Si una entrada no es valida, retornar `array()` o `0` y no ejecutar SQL.
- En consultas complejas, preferir sentencias preparadas cuando sea viable.

## 3. Reglas de Seguridad
- Prohibido usar archivos `temp.php`, `* copy.php` o duplicados como rutas activas.
- No usar `eval()` para datos de usuario.
- Evitar `unserialize()` sobre datos no confiables.

## 4. Checklist de PR
- [ ] Control valida y normaliza entradas.
- [ ] Modelo protege IDs y no ejecuta SQL con valores no validos.
- [ ] No hay rutas legacy/temporales expuestas.
- [ ] `php -l` sin errores en archivos modificados.
