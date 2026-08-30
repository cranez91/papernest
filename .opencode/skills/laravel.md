# Skill: Laravel

## Propósito

Usa esta skill cuando la tarea afecte código backend de **Papernest** basado en:

- PHP `^8.2`
- Laravel `^12.0`
- Inertia Laravel `^2.0`
- Ziggy `^2.5`
- PHPUnit `^11.5`
- Laravel Pint
- Laravel Pail
- Laravel Sail
- SQLite en desarrollo

Esta skill complementa:

- `AI_CONTEXT.md`, para arquitectura y reglas de negocio.
- `AGENTS.md`, para reglas generales del agente.
- `SKILLS.md`, como índice de procedimientos.

No sustituye la lectura del código real.

---

## Cuándo usar esta skill

Úsala al trabajar con:

- modelos Eloquent;
- controladores;
- Form Requests;
- middleware;
- rutas web o API;
- migraciones;
- factories y seeders;
- validación;
- sesiones;
- colas;
- caché;
- respuestas JSON;
- Inertia desde Laravel;
- comandos Artisan;
- pruebas PHPUnit;
- rendimiento de consultas;
- transacciones;
- almacenamiento de archivos;
- sitemap;
- Ziggy del lado Laravel.

No la uses como referencia principal para:

- componentes Vue;
- estilos Tailwind;
- formularios y tablas de Filament;
- configuración interna de OpenCode.

---

## Principios obligatorios

1. Inspecciona primero el código existente.
2. Mantén compatibilidad con Laravel 12 y PHP 8.2.
3. Haz el cambio mínimo necesario.
4. No agregues paquetes salvo solicitud explícita.
5. No modifiques migraciones antiguas.
6. No ejecutes comandos destructivos.
7. Conserva las reglas de negocio descritas en `AI_CONTEXT.md`.
8. No confíes en datos monetarios, stock o descuentos enviados por el frontend.
9. Usa transacciones cuando una operación afecte varias tablas.
10. Añade pruebas de regresión cuando cambies una regla crítica.

---

## Flujo de trabajo

### 1. Descubrimiento

Antes de editar:

- localiza la ruta;
- localiza el controlador;
- identifica el Form Request;
- revisa el modelo;
- revisa relaciones y scopes;
- revisa migraciones;
- revisa tests existentes;
- busca implementaciones similares.

Comandos útiles:

```bash
php artisan route:list
php artisan migrate:status
php artisan about
```

No asumas que una ruta, tabla, columna, relación o helper existe sin comprobarlo.

### 2. Diseño del cambio

Define:

- archivos a modificar;
- impacto en base de datos;
- impacto en rutas;
- impacto en respuestas;
- impacto en frontend o Filament;
- impacto en stock, órdenes, cupones o archivos;
- pruebas necesarias.

### 3. Implementación

Respeta patrones vecinos.

Evita:

- lógica de negocio extensa en controladores;
- duplicación;
- consultas N+1;
- acceso directo a `env()` fuera de configuración;
- respuestas JSON inconsistentes;
- SQL manual si Eloquent o Query Builder son suficientes;
- refactors no solicitados.

### 4. Validación

Ejecuta según corresponda:

```bash
vendor/bin/pint
composer test
```

Para un test específico:

```bash
php artisan test --filter=NombreDelTest
```

Si cambiaste rutas:

```bash
php artisan route:list
```

Si cambiaste configuración:

```bash
php artisan config:clear
```

---

## Convenciones PHP y Laravel

### Código PHP

- Sigue PSR-12.
- Usa tipos de parámetros y retorno cuando encajen con el estilo existente.
- Usa nombres en inglés para clases, métodos, propiedades y variables.
- Mantén textos visibles y mensajes de validación en español.
- No agregues comentarios, `TODO`, `FIXME` ni código comentado salvo petición expresa.
- Usa `vendor/bin/pint` antes de finalizar.

### Controladores

Los controladores deben coordinar:

1. entrada;
2. validación;
3. autorización;
4. llamada a modelos o servicios existentes;
5. respuesta.

Evita controladores con:

- consultas repetidas;
- reglas de dominio duplicadas;
- transformación manual extensa;
- escritura en varias tablas sin transacción.

### Validación

Para operaciones no triviales:

- usa clases dentro de `app/Http/Requests`;
- define reglas y mensajes coherentes;
- no dupliques validaciones en controlador y request;
- valida IDs y relaciones;
- valida archivos con tipo, tamaño y reglas compatibles con el proyecto.

Para validaciones pequeñas y ya consistentes con el código vecino, puede mantenerse `Request::validate()`.

### Autorización

Antes de agregar lógica manual, revisa si existen:

- Policies;
- Gates;
- middleware;
- reglas propias del Resource de Filament.

No expongas datos administrativos en rutas públicas.

---

## Eloquent

### Modelos

Ubicación:

```text
app/Models
```

Revisa y actualiza cuando corresponda:

- `$fillable`;
- `$casts`;
- relaciones;
- scopes;
- accessors;
- mutators;
- eventos;
- `SoftDeletes`;
- configuración de UUID.

No conviertas atributos a tipos incompatibles con SQLite.

### Relaciones

Usa relaciones Eloquent explícitas.

Antes de consultar datos relacionados:

- usa `with()` cuando se necesiten desde el inicio;
- usa `load()` cuando el modelo ya esté obtenido;
- limita columnas cuando sea útil;
- evita ejecutar consultas dentro de bucles.

### Scopes

Usa scopes para filtros reutilizables.

Ejemplos existentes del proyecto:

- `basicInfo`;
- `activeItems`;
- `filterByName`;
- `filterByTags`;
- `filterByNameOrTags`.

No dupliques en controladores filtros que ya existen en el modelo.

### Eventos de modelo

Los efectos secundarios ya centralizados en modelos deben permanecer ahí.

En `Product`, revisa especialmente:

- generación de slug;
- generación de SKU;
- reemplazo de fotografía;
- eliminación física de fotografía.

No dupliques el borrado de archivos en controladores.

---

## Migraciones

### Reglas

- Crea siempre una migración nueva.
- No renombres migraciones aplicadas.
- No modifiques timestamps históricos.
- Define índices y claves foráneas.
- Define comportamiento `cascade`, `restrict`, `nullOnDelete` u otro de forma explícita.
- Considera datos existentes.
- Mantén compatibilidad con SQLite.
- No elimines columnas ni datos sin autorización.

### Flujo

1. Crear migración.
2. Implementar `up()`.
3. Implementar `down()` cuando sea seguro.
4. Actualizar modelo.
5. Actualizar validación.
6. Actualizar factories, seeders y tests.
7. Ejecutar:

```bash
php artisan migrate
php artisan test
```

### Prohibido sin autorización

```bash
php artisan migrate:fresh
php artisan db:wipe
php artisan migrate:reset
php artisan migrate:rollback
```

---

## Transacciones

Usa:

```php
DB::transaction(function () {
    // Operaciones relacionadas.
});
```

cuando el cambio afecte:

- orden y sus ítems;
- stock;
- carrito;
- cupón;
- varias tablas;
- creación dependiente de múltiples registros.

La operación debe quedar completamente aplicada o completamente revertida.

No captures excepciones solo para ocultarlas o devolver éxito.

---

## Reglas críticas de Papernest

### Órdenes UUID

`Order` utiliza UUID como llave primaria.

Debes preservar:

```php
public $incrementing = false;
protected $keyType = 'string';
```

El frontend genera el UUID y llama:

```text
PUT /orders/{order}
```

No cambies este flujo a IDs incrementales.

### Idempotencia

`OrderController@store` debe:

- detectar si la orden ya existe;
- devolver su confirmación;
- evitar duplicados.

Todo cambio al checkout debe probar idempotencia.

### Stock

Validar en:

- carrito;
- checkout.

La disponibilidad considera la lógica actual de:

```text
stock - cantidades presentes en carritos
```

No permitas stock negativo.

### Precios y totales

El servidor es la fuente de verdad para:

- precio;
- costo;
- subtotal;
- descuento;
- envío;
- total.

Nunca aceptes los cálculos del cliente como definitivos.

### Cupones

La aplicación debe verificar:

- `status`;
- `start_date`;
- `end_date`;
- `min_total`;
- `discount_percentage`.

Reutiliza `Coupon::isActive()` cuando corresponda.

Usa la zona horaria de negocio:

```php
now('America/Mexico_City')
```

sin mezclarla accidentalmente con UTC.

### `Setting`

Usa siempre:

```php
Setting::getSettings()
```

No crees filas duplicadas.

### Ventas físicas

Reutilizan `Order`.

Debes conservar:

- `source = admin`;
- `status = paid`.

No crees un modelo o tabla paralela sin solicitud explícita.

---

## Archivos y Storage

El disco de productos es:

```text
products
```

Usa Laravel Storage.

No:

- hardcodees rutas absolutas;
- borres fotografías manualmente en controladores si el modelo ya lo hace;
- escribas directamente en `public/products` evitando el filesystem configurado.

Revisa:

```bash
php artisan config:show filesystems
```

si la tarea depende del disco.

---

## Rutas

### Web

Ubicación:

```text
routes/web.php
```

Usa rutas web para:

- tienda pública;
- páginas Inertia;
- carrito;
- checkout;
- confirmación;
- administración fuera del descubrimiento automático de Filament.

### API

Ubicación:

```text
routes/api.php
```

Usa API para respuestas JSON públicas.

Al agregar una ruta:

- usa middleware explícito;
- evita conflictos con parámetros;
- usa nombres si será consumida por Ziggy;
- revisa `php artisan route:list`;
- conserva el formato de respuesta del módulo.

---

## Inertia desde Laravel

El paquete instalado es:

```text
inertiajs/inertia-laravel ^2.0
```

Para páginas:

```php
return Inertia::render('PageName', [
    'key' => $value,
]);
```

La página debe existir en:

```text
resources/js/Pages/PageName.vue
```

Antes de compartir datos globales, revisa:

```text
app/Http/Middleware/HandleInertiaRequests.php
```

No compartas globalmente datos pesados o consultas costosas.

---

## API y respuestas JSON

Al crear o modificar endpoints:

- usa códigos HTTP correctos;
- valida entrada;
- carga relaciones de forma eficiente;
- evita filtrar columnas sensibles;
- usa API Resources cuando el módulo ya siga ese patrón;
- conserva estructuras existentes;
- no rompas consumidores actuales.

Para listados grandes, considera paginación si el patrón del proyecto lo permite.

---

## Ziggy

Versiones instaladas:

- `tightenco/ziggy ^2.5`
- `ziggy-js ^2.5.3`

Usa rutas nombradas cuando el frontend necesite generar URLs.

No hardcodees URLs en Vue si existe una ruta nombrada disponible.

---

## Sitemap

Paquete:

```text
spatie/laravel-sitemap ^7.3
```

Antes de agregar una nueva URL pública:

- revisa la implementación de `/sitemap.xml`;
- verifica si debe indexarse;
- conserva rutas canónicas;
- evita incluir rutas privadas, carrito, checkout o administración.

---

## Colas, logs y entorno de desarrollo

El comando integrado es:

```bash
composer dev
```

Ejecuta:

- servidor Laravel;
- `queue:listen --tries=1`;
- Laravel Pail;
- Vite.

No cambies este script salvo solicitud explícita.

Logs:

```bash
php artisan pail
```

Shell:

```bash
php artisan tinker
```

No uses Tinker para modificar datos reales sin autorización.

---

## Testing

Stack:

- PHPUnit 11.5;
- Mockery;
- Faker;
- Collision.

Comando general:

```bash
composer test
```

Este ejecuta:

```bash
php artisan config:clear
php artisan test
```

### Cuándo agregar pruebas

Incluye pruebas cuando cambies:

- checkout;
- idempotencia;
- stock;
- cupones;
- carrito;
- endpoints;
- permisos;
- efectos secundarios de modelos;
- cálculos;
- correcciones de bugs.

### Qué comprobar

- código HTTP;
- redirección o respuesta Inertia;
- estructura JSON;
- validaciones;
- estado de base de datos;
- relaciones;
- efectos secundarios;
- repetición idempotente;
- casos inválidos.

Mantén compatibilidad con SQLite.

---

## Rendimiento

Antes de finalizar, revisa:

- N+1;
- consultas dentro de loops;
- `get()` cuando basta `exists()`;
- columnas innecesarias;
- falta de índices;
- colecciones grandes cargadas en memoria;
- datos compartidos globalmente por Inertia;
- cálculos duplicados;
- paginación.

No agregues caché sin revisar la estrategia de invalidación.

---

## Checklist final

- [ ] Leí `AI_CONTEXT.md`.
- [ ] Revisé el código relacionado.
- [ ] El cambio es mínimo.
- [ ] No modifiqué migraciones antiguas.
- [ ] No agregué dependencias.
- [ ] No rompí UUID ni checkout idempotente.
- [ ] Validé stock y totales en servidor.
- [ ] Evité N+1.
- [ ] Añadí pruebas cuando aplicaba.
- [ ] Ejecuté `vendor/bin/pint`.
- [ ] Ejecuté `composer test` o el test afectado.
- [ ] Reporté comandos no ejecutados.
