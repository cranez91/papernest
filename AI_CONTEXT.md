# AI_CONTEXT.md — Papernest / Papelería Andy

> Documento de contexto operativo para asistentes de programación como OpenCode.
>
> Este archivo describe la arquitectura actual, las reglas del proyecto y la forma esperada de
> trabajar. Debe consultarse antes de proponer o realizar cambios.

---

## 1. Instrucciones prioritarias para el agente

### 1.1 Objetivo

Trabaja como desarrollador senior dentro del proyecto **Papernest**, una aplicación e-commerce
B2C para la papelería mexicana **Papelería Andy**.

Tu responsabilidad es implementar cambios compatibles con la arquitectura existente, evitando
reescrituras innecesarias, regresiones y modificaciones fuera del alcance solicitado.

### 1.2 Antes de modificar código

Antes de editar cualquier archivo:

1. Lee este documento completo.
2. Inspecciona los archivos directamente relacionados con la tarea.
3. Busca implementaciones similares dentro del proyecto.
4. Revisa modelos, migraciones, rutas, controladores, requests, componentes y tests relacionados.
5. Confirma las versiones instaladas en `composer.json` y `package.json` cuando una solución
   dependa de una API específica.
6. Identifica efectos secundarios sobre:
   - stock;
   - carrito;
   - órdenes;
   - cupones;
   - fotografías de productos;
   - ventas físicas;
   - panel Filament;
   - tienda pública;
   - despliegue.

No asumas que una clase, columna, ruta, relación, paquete o comando existe sin comprobarlo en el
repositorio.

### 1.3 Durante la implementación

- Haz solamente los cambios necesarios para cumplir la tarea.
- Conserva el estilo, estructura y patrones existentes.
- Prefiere extender código existente antes que duplicarlo.
- No cambies nombres públicos, rutas, respuestas JSON o comportamientos existentes sin que la
  tarea lo requiera expresamente.
- Mantén compatibilidad con:
  - PHP 8.2+;
  - Laravel 12;
  - Filament 3.3;
  - Vue 3;
  - Inertia.js 2;
  - Tailwind CSS 4;
  - Vite 6;
  - SQLite en desarrollo.
- Toda nueva interfaz, validación visible, notificación y texto del panel debe estar en
  **español**.
- Usa nombres de clases, métodos, variables, propiedades y archivos en inglés, siguiendo las
  convenciones actuales de Laravel y Vue.
- No introduzcas jQuery en código nuevo. Solo conserva o modifica jQuery donde ya exista y sea
  indispensable.
- No agregues dependencias nuevas cuando el framework o los paquetes instalados ya resuelvan el
  problema.
- No edites archivos compilados de `public/build` manualmente.
- No incluyas secretos, credenciales, tokens ni valores reales de `.env`.

### 1.4 Cambios de base de datos

Cuando una tarea requiera modificar el esquema:

- Crea una migración nueva.
- No alteres migraciones históricas que ya puedan haberse ejecutado.
- Define claves foráneas y comportamiento de borrado explícitamente.
- Evalúa compatibilidad con SQLite.
- Actualiza `$fillable`, `$casts`, relaciones, validaciones y recursos relacionados.
- No elimines datos ni columnas sin autorización explícita.
- Si existe riesgo de pérdida de información, indícalo antes de aplicar el cambio.

### 1.5 Validación obligatoria

Después de implementar:

1. Revisa el diff completo.
2. Ejecuta las comprobaciones aplicables:
   ```bash
   ./vendor/bin/pint
   php artisan test
   npm run build
   ```
3. Si la tarea afecta rutas o configuración, usa también:
   ```bash
   php artisan route:list
   php artisan config:clear
   ```
4. Si no puedes ejecutar un comando, explica:
   - cuál no se ejecutó;
   - por qué;
   - qué riesgo queda pendiente.
5. No afirmes que una prueba pasó si no fue ejecutada.

### 1.6 Formato de la respuesta final de OpenCode

Al terminar una tarea, reporta:

1. Resumen breve de la solución.
2. Archivos creados o modificados.
3. Decisiones técnicas relevantes.
4. Validaciones ejecutadas y resultado.
5. Riesgos, supuestos o pasos manuales pendientes.

No respondas únicamente con “listo” o “terminado”.

---

## 2. Visión general

**Papernest** es una aplicación de comercio electrónico B2C para la papelería mexicana
**Papelería Andy**.

El sistema incluye:

- **Tienda pública:** catálogo, búsqueda, detalle de productos, carrito y checkout con pago contra
  entrega, construida con Inertia.js y Vue 3.
- **Panel de administración:** catálogo, órdenes, ventas físicas, cupones, ajustes del negocio,
  etiquetas, estadísticas y chat administrativo, construido con Filament en `/admin`.
- **Chatbot público:** ayuda a buscar productos por nombre o etiquetas.
- **Chatbot administrativo:** guía la creación conversacional de productos.

Toda la interfaz y los textos visibles deben permanecer en español.

---

## 3. Stack tecnológico

### 3.1 Backend

- PHP 8.2+
- Laravel 12
- Eloquent ORM
- Filament 3.3
- SQLite en desarrollo (`DB_CONNECTION=sqlite`)
- Laravel Pint
- PHPUnit 11
- Laravel Pail
- Laravel Sail
- Laravel Tinker

### 3.2 Frontend

- Vue 3
- Inertia.js 2
- Pinia
- Vue Router
- Tailwind CSS 4 mediante `@tailwindcss/vite`
- Vite 6
- Axios
- Ziggy
- SweetAlert2
- Lucide Vue Next
- `mobile-device-detect`
- `@tailwindplus/elements`
- `uuid`
- jQuery únicamente como dependencia heredada

### 3.3 Paquetes relevantes

- `spatie/laravel-sitemap`: generación de `/sitemap.xml`.
- `flowframe/laravel-trend`: agregaciones de datos para widgets y gráficas.
- `tightenco/ziggy` / `ziggy-js`: acceso a rutas de Laravel desde JavaScript.

Las versiones declaradas en `composer.json` y `package.json` son la fuente de verdad.

---

## 4. Arquitectura y estructura

```text
app/
  Filament/
    Resources/
      <Resource>/
        Pages/
        Widgets/
    Widgets/
  Http/
    Controllers/
      Api/
    Middleware/
    Requests/
    Resources/
  Models/
  Providers/
    Filament/
      AdminPanelProvider.php

config/
database/
  migrations/
  seeders/
    DatabaseSeeder.php

resources/
  css/
    app.css
  js/
    Pages/
    components/
      layout/
      products/
    stores/
      cartStore.js
    Composables/
      useSeo.js
    app.js
  views/
    app.blade.php
    chat/
    errors/
    layouts/

routes/
  web.php
  api.php

tests/
  Feature/
  Unit/

.github/
  workflows/
    deploy.yml
```

### 4.1 Responsabilidades por capa

- **Models:** relaciones, casts, scopes y reglas de dominio que ya pertenecen al modelo.
- **Controllers:** coordinación de solicitudes y respuestas; evitar lógica extensa cuando pueda
  encapsularse.
- **Form Requests:** autorización y validación de entrada.
- **API Resources:** transformación consistente de respuestas JSON.
- **Filament Resources:** formularios, tablas, filtros, acciones y reglas específicas del panel.
- **Vue Pages:** páginas completas renderizadas por Inertia.
- **Vue components:** comportamiento visual reutilizable.
- **Pinia stores:** estado compartido del cliente.
- **Migrations:** evolución incremental del esquema.
- **Tests:** protección de flujos de negocio y regresiones.

### 4.2 Alias de Vite

Definidos en `vite.config.js`:

- `@` → `resources/js`
- `~public` → `public`

Entradas principales:

- `resources/css/app.css`
- `resources/js/app.js`

---

## 5. Convenciones de implementación

### 5.1 Laravel y PHP

- Sigue PSR-12 y el formato aplicado por Laravel Pint.
- Usa type hints y tipos de retorno cuando sean consistentes con el código existente.
- Prefiere inyección de dependencias sobre llamadas globales innecesarias.
- Usa Form Requests para validaciones de operaciones no triviales.
- Usa transacciones para operaciones que modifican varias tablas o afectan stock y totales.
- Evita consultas N+1 mediante `with()`, `load()` o proyecciones específicas.
- No uses `env()` fuera de archivos de configuración.
- No uses consultas SQL directas si Eloquent o Query Builder resuelven el caso claramente.
- No conviertas excepciones en respuestas exitosas.
- Conserva los códigos HTTP y formatos JSON ya usados por el módulo correspondiente.

### 5.2 Vue e Inertia

- Usa Composition API si el archivo existente ya la utiliza.
- Mantén el estilo del componente antes de migrarlo a otro patrón.
- Usa Ziggy para rutas nombradas cuando corresponda.
- No dupliques datos derivados que puedan calcularse con `computed`.
- Mantén estado global en Pinia solo cuando realmente sea compartido.
- No accedas directamente al DOM salvo necesidad justificada.
- Conserva accesibilidad básica: etiquetas, botones semánticos, foco y mensajes comprensibles.
- No uses valores visuales arbitrarios si ya existe una convención Tailwind en la aplicación.

### 5.3 Filament

- Mantén compatibilidad estricta con Filament 3.3.
- Antes de usar una API de Filament, confirma que existe en la versión instalada.
- Conserva los grupos de navegación actuales.
- Usa componentes y acciones nativas de Filament cuando sean suficientes.
- Mantén moneda MXN en campos monetarios.
- No cambies el path `/admin` ni el identificador del panel.
- Considera permisos, validaciones, relaciones y soft deletes en cada Resource.

### 5.4 Idioma y nomenclatura

- Código y nombres técnicos: inglés.
- Textos visibles para usuarios y administradores: español.
- Nombres de rutas: conservar los existentes; los nuevos deben ser coherentes con el módulo.
- Base de datos: `snake_case`.
- Clases: `PascalCase`.
- Métodos y variables PHP/JS: `camelCase`.
- Componentes Vue: seguir el patrón existente del directorio.

---

## 6. Modelo de datos confirmado

> Las migraciones y modelos del repositorio son la fuente de verdad. Esta sección sirve como mapa
> rápido y debe actualizarse cuando cambie el esquema.

### 6.1 `users`

- `id`
- `name`
- `email` único
- `email_verified_at`
- `password`
- `deleted_at`
- timestamps

Usa soft deletes.

### 6.2 `categories`

- `id`
- `name`
- `sku` nullable y único
- `deleted_at`
- timestamps

Relaciones:

- `hasMany(Product)`

Usa soft deletes.

### 6.3 `products`

- `id`
- `sku` nullable y único
- `name`
- `slug` nullable y único
- `photo` único
- `attachment_file_name` único
- `brand`
- `cost` decimal `(10,2)`
- `price` decimal `(10,2)`
- `status`: `paused`, `active`, `inactive`
- `category_id`
- `stock`
- `description`
- `deleted_at`
- timestamps

Relaciones:

- `belongsTo(Category)`
- `belongsToMany(Tag)`

Usa soft deletes.

### 6.4 `tags`

- `id`
- `name`
- timestamps

### 6.5 `product_tag`

- `id`
- `product_id`
- `tag_id`
- timestamps

Ambas claves foráneas usan eliminación en cascada.

### 6.6 `orders`

La llave primaria es UUID.

- `id`
- `customer_name`
- `whatsapp`
- `email`
- `address`
- `status`: `pending`, `confirmed`, `sent`, `delivered`, `cancelled`, `paid`
- `payment_type`, por defecto `cash_on_delivery`
- `subtotal` decimal `(10,2)`
- `shipping_price` decimal `(10,2)`
- `total` decimal `(10,2)`
- `source`: `web`, `admin`
- `created_by`
- `coupon_id`
- `source_shopping_cart`
- timestamps

Configuración del modelo:

```php
public $incrementing = false;
protected $keyType = 'string';
```

### 6.7 `order_items`

- `id`
- `order_id` UUID
- `product_id`
- `quantity`
- `cost` decimal `(10,2)`
- `price` decimal `(10,2)` nullable
- timestamps

### 6.8 `shopping_carts`

- `id` UUID
- `user_id` nullable
- `session_id` nullable
- timestamps

### 6.9 `shopping_cart_items`

- `id`
- `shopping_cart_id` UUID
- `product_id`
- `quantity`
- timestamps

### 6.10 `coupons`

- `id`
- `code` string de longitud 10, único
- `description` string de longitud 80
- `status`: `active`, `inactive`
- `min_total` decimal `(10,7)`
- `discount_percentage` unsigned tiny integer
- `start_date`
- `end_date`
- timestamps

### 6.11 `settings`

Tabla singleton:

- `id`
- `business_name`
- `description`
- `location`
- `whatsapp_contact`
- `shipping_price` decimal `(10,2)`
- `business_hours` JSON, casteado a array
- `latitude` decimal `(10,7)`
- `longitude` decimal `(10,7)`
- `facebook_id`
- `maps_link`
- timestamps

### 6.12 `chat_conversations`

- `id`
- `admin_id`
- `step`, por defecto `start`
- `data` JSON nullable
- timestamps

### 6.13 Infraestructura

- `password_reset_tokens`
- `sessions`
- `cache`
- `cache_locks`
- `jobs`
- `job_batches`
- `failed_jobs`

---

## 7. Reglas de dominio críticas

### 7.1 Productos y fotografías

`app/Models/Product.php` contiene eventos con efectos secundarios:

- Al crear, genera `slug` y un SKU aleatorio de 14 caracteres en mayúsculas si faltan.
- Al actualizar el nombre, regenera el slug.
- Al reemplazar la foto, elimina la foto anterior del disco `products`.
- Al eliminar el producto, elimina físicamente su foto.

Reglas:

- No dupliques el borrado de archivos en controladores si el modelo ya lo realiza.
- Revisa soft deletes antes de cambiar el evento `deleting`.
- No cambies la estrategia de SKU o slug sin considerar productos existentes y unicidad.
- El disco `products` debe usarse mediante `Storage`.

Accessors conocidos:

- `category_name`
- `short_name`

Scopes conocidos:

- `basicInfo`
- `activeItems`
- `filterByName`
- `filterByTags`
- `filterByNameOrTags`

### 7.2 Carrito

El carrito se identifica mediante:

- `user_id` para usuarios autenticados;
- `session_id` para usuarios anónimos.

Se obtiene con `firstOrCreate`.

Operaciones soportadas:

- listar;
- agregar;
- actualizar cantidad;
- eliminar ítem;
- vaciar.

Reglas:

- Validar stock en cada modificación.
- El frontend mantiene un espejo en `localStorage` mediante Pinia.
- No confíes en precios, subtotales, stock ni totales enviados por el cliente.
- El servidor debe recalcular datos sensibles.
- Revisa sincronización entre carrito persistido y estado local al modificar este flujo.

Getters del store:

- `totalCartPrice`
- `totalCartItems`

### 7.3 Checkout y órdenes

La creación de órdenes es idempotente:

```text
PUT /orders/{order}
```

`{order}` es un UUID generado en el frontend.

Si la orden ya existe, se devuelve su URL de confirmación en lugar de crear otra.

El flujo actual de `OrderController@store`:

1. Valida mediante `StoreOrderRequest`.
2. Ejecuta la operación dentro de una transacción.
3. Construye la dirección.
4. Localiza el cupón si fue enviado.
5. Crea la orden como:
   - `source = web`;
   - `payment_type = cash_on_delivery`;
   - `status = pending`.
6. Verifica stock disponible.
7. Crea los `OrderItem` usando precios del producto almacenado.
8. Calcula totales.
9. Descuenta stock.

Reglas obligatorias:

- Preservar idempotencia.
- Preservar transacción.
- No confiar en totales enviados por el navegador.
- No permitir stock negativo.
- Considerar cantidades reservadas en carritos según la implementación actual.
- Mantener consistencia entre creación de ítems, cálculo de totales y descuento de stock.
- Cualquier cambio en este flujo debe incluir tests de éxito, stock insuficiente e idempotencia.

Métodos relevantes:

- `Order::updateTotalPrice()`
- `Order::updateProductsStock()`

### 7.4 Cupones

Un cupón aplica únicamente cuando:

- está activo;
- la fecha actual está dentro de su ventana;
- el subtotal alcanza `min_total`.

El descuento es porcentual.

Reglas:

- El backend decide si el cupón es válido.
- No aceptar como autoridad el descuento calculado por el frontend.
- Considerar timezone de negocio y conversión existente a UTC.
- Reutilizar `Coupon::isActive()` cuando corresponda.

### 7.5 Ventas físicas

Las ventas físicas reutilizan `Order`.

Valores esperados:

- `source = admin`
- `status = paid`

El evento `creating` de `Order` asigna esos valores en órdenes administrativas sin datos del
cliente.

No crear una tabla paralela de ventas salvo requerimiento explícito y análisis de migración.

### 7.6 Ajustes

`Setting` funciona como singleton.

Usar:

```php
Setting::getSettings()
```

No asumir que la fila ya existe.

`business_hours` se maneja como JSON y se castea a array.

### 7.7 Chatbot público

Componentes principales:

- `resources/js/components/HomeChatBot.vue`
- `App\Http\Controllers\Api\ProductController@chatbotFind`

Endpoint:

```text
POST /api/chat/find
```

La búsqueda usa nombre o etiquetas mediante `filterByNameOrTags`.

La respuesta debe limitarse a información pública del catálogo.

### 7.8 Chatbot administrativo

Controlador:

- `ChatController`

Rutas:

- `GET /admin/chat`
- `POST /admin/chat/send`

El estado se guarda en:

- `ChatConversation.step`
- `ChatConversation.data`

Secuencia actual:

1. nombre;
2. precio;
3. costo;
4. stock;
5. marca;
6. categoría;
7. confirmación.

El SKU administrativo usa:

- 3 letras del nombre;
- 3 letras de la marca;
- 6 caracteres aleatorios.

Al confirmar, crea el producto sin foto; la foto se agrega después mediante Filament.

No rompas conversaciones activas al cambiar nombres de pasos.

### 7.9 SEO

- `/sitemap.xml` se genera con `spatie/laravel-sitemap`.
- `SeoHead.vue` y `useSeo.js` gestionan metadatos.

Al crear páginas públicas:

- define título y descripción;
- evalúa inclusión en sitemap;
- conserva URLs canónicas y estructura actual.

---

## 8. Panel de administración

Configuración principal:

- Provider: `app/Providers/Filament/AdminPanelProvider.php`
- Id: `admin`
- Path: `/admin`
- Color primario: amber
- Login habilitado

Recursos:

- Productos
- Categorías
- Etiquetas
- Cupones
- Órdenes
- Ventas físicas
- Ajustes

Grupos actuales:

- `Catalogos`
- `Ordenes y Ventas`

Widgets conocidos:

- `MonthlyOrdersChart`
- `MonthlySalesChart`
- `RevenueStats`
- `OrderStatsWidget`
- `SaleStatsWidget`

Al modificar widgets:

- valida rangos de fechas;
- evita consultas N+1;
- conserva timezone;
- distingue ventas físicas de órdenes web;
- usa `flowframe/laravel-trend` conforme a la implementación existente.

---

## 9. Rutas existentes

### 9.1 Tienda pública

| Método | URI | Controlador | Nombre |
|---|---|---|---|
| GET | `/` | `HomeController@index` | `home` |
| GET | `/acerca` | `HomeController@about` | `about` |
| GET | `/terminos` | `HomeController@termsOfService` | `tos` |
| GET | `/privacidad` | `HomeController@privacyPolicy` | `privacy` |
| GET | `/carrito` | `HomeController@cart` | `cart` |
| GET | `/articulo/{sku?}` | `ProductController@show` | `products.detail` |
| GET | `/articulos/{category?}` | `ProductController@index` | `products.list` |
| GET | `/sitemap.xml` | Closure | — |

### 9.2 Carrito y órdenes

| Método | URI | Controlador |
|---|---|---|
| GET | `/cart` | `CartController@index` |
| POST | `/cart/items` | `CartController@store` |
| PATCH | `/cart/items/{id}` | `CartController@update` |
| DELETE | `/cart/items/{id}` | `CartController@destroy` |
| DELETE | `/cart` | `CartController@clear` |
| PUT | `/orders/{order}` | `OrderController@store` |
| GET | `/orders/confirmation/{order}` | `OrderController@confirmation` |

### 9.3 Administración

| Método | URI | Controlador |
|---|---|---|
| GET | `/admin/chat` | `ChatController@index` |
| POST | `/admin/chat/send` | `ChatController@send` |

### 9.4 API pública

| Método | URI | Controlador |
|---|---|---|
| GET | `/api/products` | `Api\ProductController@index` |
| GET | `/api/products/{product}` | `Api\ProductController@show` |
| GET | `/api/categories` | Closure |
| POST | `/api/chat/find` | `Api\ProductController@chatbotFind` |

Al agregar rutas:

- colócalas en el archivo correcto;
- usa nombres cuando sean consumidas por Inertia o Ziggy;
- añade middleware explícito;
- evita conflictos con rutas parametrizadas;
- revisa el resultado de `php artisan route:list`.

---

## 10. Configuración relevante

### 10.1 Moneda

La moneda es MXN.

En Filament se usa:

```php
->money('MXN', true)
```

No mostrar USD ni convertir moneda salvo requerimiento explícito.

### 10.2 Zona horaria

Zona de negocio:

```text
America/Mexico_City
```

Los cálculos de cupones realizan conversiones a UTC.

No mezcles fechas locales y UTC sin revisar el flujo completo.

### 10.3 Idioma

- Interfaz: español.
- Traducciones: `resources/lang/es`.
- Configuración mediante `APP_LOCALE`.

### 10.4 Archivos de productos

Disco:

```text
products
```

Configuración:

- raíz: `env('PRODUCTS_PATH', public_path('products'))`;
- URL pública: `/products`;
- visibilidad: pública.

No uses rutas absolutas fijas.

### 10.5 Sesiones, colas y caché

Drivers por defecto:

```text
database
```

Antes de introducir jobs o caché, revisa las tablas existentes y el entorno de despliegue.

---

## 11. Pruebas esperadas

Actualmente existen pruebas base en `tests/Feature` y `tests/Unit`. Toda funcionalidad nueva o
corrección de una regla crítica debe incluir pruebas cuando sea viable.

Prioridades de prueba:

1. creación idempotente de órdenes;
2. validación de stock;
3. cálculo de subtotal, descuento, envío y total;
4. aplicación y rechazo de cupones;
5. operaciones del carrito;
6. filtros de productos;
7. autorización del panel;
8. creación de ventas físicas;
9. efectos de actualización y eliminación de fotografías;
10. endpoints de API.

Convenciones:

- Usa factories cuando existan.
- Crea factories si una nueva suite las necesita y su uso será recurrente.
- Evita depender del orden de ejecución.
- No uses datos reales.
- Mantén compatibilidad con SQLite.
- Verifica tanto el resultado HTTP como el estado de la base de datos.

Comandos:

```bash
php artisan test
php artisan test --filter=NombreDelTest
```

---

## 12. Comandos útiles

### Instalación

```bash
composer install
npm install
```

### Desarrollo

```bash
php artisan serve
npm run dev
```

Usa el comando de desarrollo definido por el repositorio cuando exista uno compuesto en
`composer.json`.

### Calidad

```bash
./vendor/bin/pint
php artisan test
npm run build
```

### Diagnóstico

```bash
php artisan about
php artisan route:list
php artisan migrate:status
php artisan config:show filesystems
php artisan pail
```

### Base de datos

```bash
php artisan migrate
php artisan db:seed
```

No ejecutes `migrate:fresh`, `db:wipe` o comandos destructivos sin autorización explícita.

---

## 13. Despliegue

El despliegue se realiza mediante:

```text
.github/workflows/deploy.yml
```

Se ejecuta al hacer push a `main`.

Flujo conocido:

1. instala dependencias Composer sin paquetes de desarrollo;
2. publica assets de Livewire;
3. compila frontend;
4. despliega por FTP a `/papeleria-andy/`.

Los assets compilados se versionan actualmente en:

```text
public/build
```

Reglas:

- No cambies el workflow sin revisar el hosting de destino.
- No migres el despliegue a Docker, SSH u otro proveedor salvo solicitud explícita.
- No elimines el versionado de `public/build` sin ajustar primero el workflow y el servidor.
- No expongas secretos de GitHub Actions.
- Si un cambio requiere migraciones en producción, documenta el paso operativo.
- Comprueba que los assets referenciados por Laravel coincidan con los generados por Vite.

---

## 14. Restricciones y decisiones que deben preservarse

- La aplicación es B2C y está orientada a México.
- La tienda usa pago contra entrega.
- La moneda es MXN.
- La interfaz está en español.
- SQLite se utiliza en desarrollo.
- El panel administrativo permanece en `/admin`.
- Filament permanece en la rama 3.3 instalada.
- Las órdenes web usan UUID generado desde el frontend.
- La creación de órdenes debe conservar idempotencia.
- Las ventas físicas reutilizan el modelo `Order`.
- `Setting` funciona como singleton.
- El stock debe validarse en carrito y checkout.
- El servidor es la autoridad para precios, descuentos, stock y totales.
- Las fotografías se gestionan mediante el disco `products`.
- jQuery es legado y no debe extenderse.
- El despliegue actual usa GitHub Actions y FTP.

---

## 15. Acciones prohibidas sin autorización explícita

No realices ninguna de estas acciones por iniciativa propia:

- actualizar versiones mayores de Laravel, Filament, Vue, Inertia, Tailwind o Vite;
- reemplazar Filament;
- migrar de SQLite a otra base en desarrollo;
- cambiar el esquema de autenticación;
- cambiar URLs públicas existentes;
- eliminar o renombrar estados de órdenes o productos;
- modificar la semántica de ventas físicas;
- reemplazar UUID de órdenes por IDs incrementales;
- eliminar idempotencia del checkout;
- eliminar soft deletes;
- cambiar moneda o timezone;
- borrar archivos o datos de producción;
- ejecutar migraciones destructivas;
- ejecutar `migrate:fresh`;
- reescribir módulos completos cuando basta un cambio localizado;
- introducir una arquitectura nueva sin necesidad;
- agregar paquetes sin justificar por qué son necesarios;
- modificar el workflow de despliegue fuera del alcance;
- editar `.env` con credenciales reales;
- hacer commits, push, merge o despliegues salvo que se solicite expresamente.

---

## 16. Manejo de ambigüedades

Cuando la solicitud sea ambigua:

1. Inspecciona el código para resolverla con evidencia.
2. Elige la interpretación que requiera menos cambios y preserve compatibilidad.
3. Documenta el supuesto en el resumen final.
4. Solo solicita aclaración cuando existan dos opciones materialmente distintas y no sea posible
   inferir la correcta del repositorio.

Si el código contradice este documento, informa la discrepancia. Para detalles de implementación,
el código y las migraciones actuales tienen prioridad; para reglas expresamente declaradas como
restricciones del proyecto, no las cambies sin autorización.

---

## 17. Checklist por tipo de tarea

### Backend

- [ ] Revisar ruta y middleware.
- [ ] Usar Form Request cuando corresponda.
- [ ] Revisar autorización.
- [ ] Evitar N+1.
- [ ] Usar transacción si hay múltiples escrituras.
- [ ] Mantener formato de respuesta.
- [ ] Añadir o actualizar tests.
- [ ] Ejecutar Pint y PHPUnit.

### Base de datos

- [ ] Crear migración nueva.
- [ ] Verificar SQLite.
- [ ] Actualizar modelo, casts y relaciones.
- [ ] Revisar datos existentes.
- [ ] Evitar cambios destructivos.
- [ ] Documentar migración de producción.

### Vue / Inertia

- [ ] Mantener patrón del componente.
- [ ] Usar rutas existentes mediante Ziggy.
- [ ] No duplicar estado.
- [ ] Mantener textos en español.
- [ ] Revisar responsive y accesibilidad.
- [ ] Ejecutar build.

### Filament

- [ ] Confirmar API compatible con v3.3.
- [ ] Mantener grupos y navegación.
- [ ] Revisar validaciones y relaciones.
- [ ] Mantener MXN.
- [ ] Verificar permisos y soft deletes.
- [ ] Probar formularios, tablas y acciones afectadas.

### Checkout, carrito o stock

- [ ] No confiar en datos monetarios del cliente.
- [ ] Verificar stock en servidor.
- [ ] Mantener transacción.
- [ ] Mantener idempotencia.
- [ ] Revisar cupones.
- [ ] Revisar ventas físicas.
- [ ] Añadir pruebas de regresión.

---

## 18. Mantenimiento de este documento

Actualiza este archivo cuando cambie cualquiera de los siguientes elementos:

- stack o versiones principales;
- estructura de carpetas;
- esquema de base de datos;
- reglas de dominio;
- rutas públicas o administrativas;
- flujo de checkout;
- configuración de archivos;
- estrategia de despliegue;
- comandos de validación;
- restricciones arquitectónicas.

No agregues funcionalidades futuras como si ya estuvieran implementadas. Distingue claramente
entre estado actual, trabajo pendiente y propuestas.
