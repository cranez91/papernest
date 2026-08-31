# Skill: Filament

## Propósito

Usa esta skill cuando la tarea afecte el panel administrativo de **Papernest** basado en:

- Filament `^3.3`
- Laravel `^12.0`
- PHP `^8.2`
- Eloquent
- `flowframe/laravel-trend ^0.4`
- panel con path `/admin`
- moneda MXN
- interfaz en español

Esta skill complementa:

- `AI_CONTEXT.md`;
- `AGENTS.md`;
- `SKILLS.md`;
- `skills/laravel.md`.

No sustituye la inspección de Resources, Pages, Widgets y modelos existentes.

---

## Cuándo usar esta skill

Úsala al trabajar con:

- Filament Resources;
- formularios;
- tablas;
- filtros;
- acciones;
- bulk actions;
- relation managers;
- páginas de Resource;
- widgets;
- dashboards;
- navegación;
- validación administrativa;
- uploads;
- soft deletes;
- estadísticas;
- `flowframe/laravel-trend`.

No la uses como referencia principal para:

- páginas Vue públicas;
- rutas API;
- configuración de OpenCode;
- componentes Inertia fuera del panel.

---

## Compatibilidad obligatoria

La versión instalada es:

```text
filament/filament ^3.3
```

Usa únicamente APIs compatibles con Filament 3.

No copies ejemplos de Filament 4.

Antes de usar un método o componente no presente en el proyecto:

1. revisa `composer.lock`;
2. revisa Resources existentes;
3. revisa documentación compatible con v3 si fuera necesario;
4. evita suposiciones sobre APIs nuevas.

No actualices Filament ni paquetes relacionados salvo solicitud explícita.

---

## Configuración confirmada del panel

Provider:

```text
app/Providers/Filament/AdminPanelProvider.php
```

Configuración:

- id: `admin`;
- path: `/admin`;
- login habilitado;
- color primario: amber;
- descubrimiento automático de Resources, Pages y Widgets.

No cambies:

- id;
- path;
- estrategia de descubrimiento;
- color;
- autenticación;

salvo solicitud explícita.

---

## Principios obligatorios

1. Revisa primero el modelo y el Resource existente.
2. Mantén compatibilidad con Filament 3.3.
3. Conserva labels, mensajes y navegación en español.
4. Mantén nombres técnicos en inglés.
5. Usa componentes nativos de Filament cuando sean suficientes.
6. No dupliques reglas de negocio existentes.
7. No borres archivos manualmente si el modelo ya lo hace.
8. Respeta soft deletes.
9. Mantén moneda MXN.
10. Prueba el CRUD completo afectado.

---

## Flujo de trabajo

### 1. Descubrimiento

Antes de editar:

- localiza el modelo;
- revisa `$fillable`;
- revisa `$casts`;
- revisa relaciones;
- revisa scopes;
- revisa eventos del modelo;
- localiza el Resource;
- revisa Pages;
- revisa Widgets;
- revisa migraciones;
- revisa tests;
- busca un Resource similar.

### 2. Diseño

Define:

- campos visibles;
- campos editables;
- reglas de validación;
- relaciones;
- filtros;
- acciones;
- permisos;
- navegación;
- soft deletes;
- impacto de archivos;
- impacto de stock o ventas;
- pruebas necesarias.

### 3. Implementación

Haz el cambio mínimo.

Evita:

- crear un Resource paralelo para el mismo modelo;
- lógica de dominio duplicada en callbacks de formulario;
- consultas repetidas por fila;
- opciones de Select cargadas sin límite cuando el conjunto pueda crecer;
- APIs de Filament 4;
- HTML manual cuando existe un componente nativo;
- acciones destructivas sin confirmación;
- modificar el provider sin necesidad.

### 4. Validación

Ejecuta:

```bash
vendor/bin/pint
composer test
```

Si el cambio afecta frontend o assets del panel:

```bash
npm run build
```

Prueba manualmente:

- listado;
- búsqueda;
- filtros;
- creación;
- edición;
- eliminación;
- restauración si aplica;
- carga de archivos;
- acciones;
- navegación.

---

## Estructura de Resources

Ubicación:

```text
app/Filament/Resources
```

Estructura esperada:

```text
<X>Resource.php
<X>Resource/
  Pages/
    List<X>.php
    Create<X>.php
    Edit<X>.php
    View<X>.php
  Widgets/
```

`View` y `Widgets` son opcionales.

Los Resources se auto-descubren.

No los registres manualmente si el provider ya usa `discoverResources()`.

---

## Creación de un Resource

### Checklist

1. Revisar o crear modelo.
2. Revisar migración.
3. Definir `$fillable`.
4. Definir `$casts`.
5. Definir relaciones.
6. Crear Resource.
7. Crear Pages.
8. Definir `form()`.
9. Definir `table()`.
10. Añadir filtros.
11. Añadir acciones.
12. Añadir bulk actions.
13. Configurar navegación.
14. Revisar permisos.
15. Añadir tests cuando aplique.
16. Ejecutar Pint y tests.

### `getPages()`

Registra únicamente páginas existentes.

Conserva rutas estándar del Resource cuando sea posible.

No inventes rutas administrativas externas si una Page de Filament resuelve la necesidad.

---

## Formularios

Usa componentes de:

```php
Filament\Forms\Components
```

Ejemplos compatibles habituales:

- `TextInput`;
- `Textarea`;
- `Select`;
- `FileUpload`;
- `Toggle`;
- `DatePicker`;
- `DateTimePicker`;
- `Repeater`;
- `Grid`;
- `Section`;
- `Hidden`;
- `TagsInput`.

Antes de usar uno nuevo, verifica su uso en Resources vecinos o compatibilidad con v3.

### Reglas

- labels en español;
- ayuda y placeholders en español;
- validaciones coherentes con el modelo;
- `required()` solo cuando la base de datos lo exige;
- longitudes compatibles con migraciones;
- tipos numéricos adecuados;
- no permitir modificar campos generados automáticamente;
- reutilizar relaciones;
- no confiar solo en validación visual.

### Dinero

Usa:

```php
->money('MXN', true)
```

en tablas cuando corresponda.

En formularios:

- usa campos numéricos;
- conserva precisión decimal;
- evita floats para cálculos de negocio;
- revisa reglas de mínimo y máximo.

### Fechas

Zona de negocio:

```php
now('America/Mexico_City')
```

No mezcles UTC y hora local sin revisar el flujo.

### Relaciones

Para selects relacionados:

- usa `relationship()` si encaja;
- define label correcto;
- permite búsqueda si la colección puede crecer;
- precarga solo cuando el volumen sea razonable;
- evita cargar miles de opciones.

---

## Tablas

Usa:

```php
Filament\Tables\Table
```

Componentes habituales:

- `TextColumn`;
- `ImageColumn`;
- `SelectColumn`;
- `IconColumn`;
- `ToggleColumn`;
- `BadgeColumn`, solo si es compatible con la versión instalada y el patrón existente.

### Columnas

- labels en español;
- activa búsqueda solo en columnas útiles;
- ordenamiento solo donde exista soporte razonable;
- formatea moneda en MXN;
- formatea fechas consistentemente;
- limita texto largo;
- usa badges o colores coherentes con estados existentes;
- evita callbacks que disparen consultas por fila.

### Eager loading

Si una columna usa relaciones:

- revisa la consulta del Resource;
- agrega eager loading cuando sea necesario;
- evita N+1.

### Acciones

Usa acciones nativas.

Para acciones destructivas:

- solicita confirmación;
- muestra texto claro;
- respeta permisos;
- no ejecutes varias escrituras sin transacción;
- muestra resultado comprensible.

### Bulk actions

Incluye solo acciones seguras y justificadas.

No agregues eliminación masiva en módulos sensibles sin autorización.

---

## Filtros

Usa componentes de:

```php
Filament\Tables\Filters
```

Ejemplo:

- `SelectFilter`.

Reglas:

- filtra mediante columnas indexadas cuando sea posible;
- evita joins costosos;
- reutiliza scopes;
- usa labels en español;
- conserva filtros actuales;
- no dupliques filtros equivalentes.

Para fechas:

- valida rango;
- usa timezone correcto;
- evita incluir accidentalmente registros fuera de límites.

---

## Recursos existentes

Según el contexto del proyecto:

- Product;
- Category;
- Tag;
- Coupon;
- Order;
- Sale;
- Setting.

Grupos de navegación actuales:

- `Catalogos`;
- `Ordenes y Ventas`.

No renombres grupos ni muevas Resources sin solicitud explícita.

---

## Productos

Al modificar `ProductResource`, preserva:

- nombre;
- slug automático;
- SKU automático;
- marca;
- categoría;
- costo;
- precio;
- stock;
- estado;
- etiquetas;
- fotografía;
- `attachment_file_name`.

### Fotografía

El disco es:

```text
products
```

La carga usa el flujo existente con:

```php
storeFileNamesIn('attachment_file_name')
```

El modelo `Product` elimina archivos anteriores mediante eventos.

No:

- borres fotos manualmente en Pages o Resource;
- escribas directamente en `public/products`;
- permitas editar slug o SKU si actualmente son automáticos;
- dupliques la lógica de nombre de archivo.

Verifica edición de producto reemplazando una foto y confirma que no queden archivos huérfanos.

---

## Órdenes

Al modificar `OrderResource`:

- conserva UUID;
- conserva estados existentes;
- conserva `source`;
- conserva totales calculados por backend;
- no permitas editar indiscriminadamente valores derivados;
- revisa efectos sobre stock;
- revisa cupones;
- revisa historial operativo.

Estados confirmados:

- `pending`;
- `confirmed`;
- `sent`;
- `delivered`;
- `cancelled`;
- `paid`.

No renombres estados sin migración y autorización.

---

## Ventas físicas

`SaleResource` reutiliza `Order`.

Filtra y conserva:

```text
source = admin
status = paid
```

No crees un modelo `Sale` separado salvo requisito explícito.

Revisa:

- totales;
- ítems;
- stock;
- usuario creador;
- filtros de widgets;
- acciones administrativas.

---

## Cupones

En `CouponResource` valida:

- código único;
- longitud;
- estado;
- fecha inicial;
- fecha final;
- `min_total`;
- porcentaje entre 0 y 100.

La validez final pertenece al backend y a `Coupon::isActive()`.

No asumas que estar marcado como activo basta si las fechas o mínimo no se cumplen.

---

## Ajustes

`Setting` es singleton.

Usa:

```php
Setting::getSettings()
```

El Resource debe editar la fila existente.

No debe:

- listar múltiples registros;
- crear filas nuevas libremente;
- permitir duplicados.

Campos relevantes:

- `business_name`;
- `description`;
- `location`;
- `whatsapp_contact`;
- `shipping_price`;
- `business_hours`;
- `latitude`;
- `longitude`;
- `facebook_id`;
- `maps_link`.

---

## Soft deletes

Antes de agregar acciones de eliminar:

- revisa si el modelo usa `SoftDeletes`;
- revisa filtros de trashed;
- revisa restauración;
- revisa force delete;
- respeta relaciones y archivos.

No agregues force delete por defecto.

En productos, considera el evento de borrado de fotografía y su interacción con soft delete.

---

## Widgets

Widgets conocidos:

- `MonthlyOrdersChart`;
- `MonthlySalesChart`;
- `RevenueStats`;
- `OrderStatsWidget`;
- `SaleStatsWidget`.

Paquete:

```text
flowframe/laravel-trend ^0.4.0
```

### Reglas

- reutiliza widgets existentes;
- define rango de fechas;
- usa timezone del negocio;
- filtra por `source`;
- filtra por `status`;
- evita duplicar consultas;
- no mezcles órdenes web y ventas físicas accidentalmente;
- conserva formato MXN.

### Trend

Patrón esperado:

```php
Trend::query(
    Order::query()->where(...)
)
    ->between(
        start: $start,
        end: $end,
    )
    ->perMonth()
    ->sum('total');
```

Verifica la API exacta utilizada en widgets existentes antes de modificarla.

No copies sintaxis de una versión distinta de `laravel-trend`.

---

## Navegación

Conserva:

- grupos existentes;
- orden;
- iconografía;
- labels en español;
- visibilidad;
- path `/admin`.

No agregues elementos duplicados.

Antes de cambiar navegación, revisa `AdminPanelProvider` y el propio Resource.

---

## Autorización

No asumas que estar autenticado permite todas las acciones.

Revisa:

- Policies;
- métodos `can*`;
- visibilidad de acciones;
- autenticación del panel;
- filtros de datos;
- acciones masivas.

No ocultes un botón como único mecanismo de seguridad. El backend debe autorizar la acción.

---

## Rendimiento

Revisa:

- N+1;
- columnas relacionadas;
- selects con demasiadas opciones;
- widgets con consultas repetidas;
- cálculos por fila;
- filtros sin índices;
- tablas sin paginación;
- eager loading;
- búsquedas en columnas no adecuadas.

No agregues `preload()` indiscriminadamente.

---

## Pruebas

Añade o actualiza pruebas cuando cambies:

- reglas de creación;
- validación;
- acciones;
- estados;
- ventas;
- stock;
- uploads;
- singleton Setting;
- widgets con lógica de negocio.

Prueba al menos:

- acceso;
- creación válida;
- validación inválida;
- edición;
- persistencia;
- efectos secundarios;
- filtros críticos;
- permisos.

Comandos:

```bash
composer test
php artisan test --filter=NombreDelTest
vendor/bin/pint
```

---

## Prohibiciones

No hacer sin autorización:

- actualizar Filament;
- usar APIs de Filament 4;
- cambiar `/admin`;
- registrar Resources manualmente si ya se descubren;
- crear tablas paralelas para ventas;
- eliminar estados;
- borrar archivos manualmente;
- agregar acciones destructivas masivas;
- cambiar moneda;
- duplicar `Setting`;
- modificar `AdminPanelProvider` fuera del alcance;
- instalar plugins de Filament;
- modificar autenticación del panel;
- hacer deploy.

---

## Checklist final

- [ ] Confirmé compatibilidad con Filament 3.3.
- [ ] Revisé el modelo.
- [ ] Revisé Resource y Pages existentes.
- [ ] Labels y mensajes están en español.
- [ ] Moneda permanece en MXN.
- [ ] Fechas usan timezone correcto.
- [ ] No dupliqué lógica del modelo.
- [ ] No borré archivos manualmente.
- [ ] Revisé soft deletes.
- [ ] Revisé permisos.
- [ ] Evité N+1.
- [ ] Probé formulario y tabla.
- [ ] Ejecuté `vendor/bin/pint`.
- [ ] Ejecuté `composer test` o pruebas afectadas.
- [ ] Ejecuté `npm run build` si hubo cambios en assets.
- [ ] Reporté validaciones no ejecutadas.
