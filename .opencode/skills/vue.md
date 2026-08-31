# Skill: Vue

## Propósito

Usa esta skill cuando la tarea afecte el frontend de **Papernest** basado en:

- Vue `^3.4`
- Inertia Vue `^2.1.2`
- Pinia `^3.0.3`
- Vue Router `^4.5.0`
- Tailwind CSS `^4.0`
- Vite `^6.2.4`
- Ziggy JS `^2.5.3`
- Axios `^1.11`
- Lucide Vue Next
- SweetAlert2
- UUID
- `mobile-device-detect`
- `@tailwindplus/elements`
- jQuery como dependencia heredada

Esta skill complementa:

- `AI_CONTEXT.md`;
- `AGENTS.md`;
- `SKILLS.md`;
- `skills/laravel.md`.

No sustituye la inspección de los componentes reales.

---

## Cuándo usar esta skill

Úsala al trabajar con:

- páginas Inertia;
- componentes Vue;
- Composition API;
- Pinia;
- carrito;
- formularios;
- navegación;
- rutas Ziggy;
- SEO del frontend;
- estilos Tailwind;
- Vite;
- Axios;
- iconos;
- alertas;
- responsive;
- accesibilidad;
- estado local;
- localStorage.

No la uses como referencia principal para:

- modelos Eloquent;
- migraciones;
- recursos Filament;
- configuración de OpenCode.

---

## Compatibilidad obligatoria

El proyecto declara:

```text
vue ^3.4.0
@inertiajs/vue3 ^2.1.2
pinia ^3.0.3
vue-router ^4.5.0
tailwindcss ^4.0.0
vite ^6.2.4
```

Usa APIs compatibles con esas versiones.

Hay duplicados entre `dependencies` y `devDependencies` para:

- `@inertiajs/vue3`;
- `@vitejs/plugin-vue`.

La versión efectiva debe verificarse en el lockfile si una API concreta depende de ello.

No modifiques estas dependencias ni limpies duplicados salvo que la tarea lo solicite.

---

## Principios obligatorios

1. Inspecciona primero componentes similares.
2. Conserva el patrón del archivo existente.
3. No migres componentes completos entre Options API y Composition API sin necesidad.
4. No introduzcas jQuery nuevo.
5. Mantén los textos visibles en español.
6. Usa Ziggy para rutas Laravel cuando exista una ruta nombrada.
7. Mantén el estado global solo en Pinia cuando sea realmente compartido.
8. No confíes en datos monetarios del cliente como fuente de verdad.
9. No edites `public/build` manualmente.
10. Ejecuta `npm run build` antes de finalizar.

---

## Flujo de trabajo

### 1. Descubrimiento

Antes de editar:

- localiza la página en `resources/js/Pages`;
- localiza componentes relacionados;
- revisa `resources/js/app.js`;
- revisa stores de Pinia;
- revisa composables;
- revisa rutas Laravel;
- revisa props entregadas por Inertia;
- revisa estilos existentes;
- revisa llamadas Axios.

Busca primero una solución equivalente dentro del proyecto.

### 2. Diseño

Define:

- qué componente es propietario del estado;
- qué estado es local y cuál es global;
- qué datos vienen del backend;
- qué acciones requieren petición HTTP;
- qué errores deben mostrarse;
- qué rutas se generan con Ziggy;
- qué metadatos SEO aplican;
- qué comportamiento responsive se requiere.

### 3. Implementación

Haz cambios pequeños.

Evita:

- crear stores para estado de una sola página;
- watchers cuando basta `computed`;
- manipulación directa del DOM;
- llamadas HTTP duplicadas;
- URLs hardcodeadas;
- estilos arbitrarios ajenos al proyecto;
- código jQuery nuevo;
- mezclar dos librerías para la misma responsabilidad;
- replicar reglas de negocio sensibles del backend.

### 4. Validación

Ejecuta:

```bash
npm run build
```

Durante desarrollo:

```bash
npm run dev
```

Si la tarea también modifica Laravel:

```bash
composer test
vendor/bin/pint
```

---

## Estructura del frontend

```text
resources/js/
  Pages/
  components/
    layout/
    products/
  stores/
    cartStore.js
  Composables/
    useSeo.js
  app.js
```

Alias:

```text
@        -> resources/js
~public  -> public
```

Usa esos alias cuando el código vecino ya lo haga.

---

## Páginas Inertia

Cada página pública debe vivir en:

```text
resources/js/Pages/<Name>.vue
```

Laravel la renderiza con:

```php
Inertia::render('<Name>', [...])
```

### Reglas

- recibe datos mediante props;
- no repitas una consulta que ya entregó Laravel;
- no uses Vue Router para sustituir navegación Inertia existente;
- usa el componente `Link` de Inertia para navegación interna;
- conserva estado de página solo cuando la UX lo necesite;
- evita props excesivamente grandes;
- no mutar props directamente.

### Navegación

Usa:

```js
route('route.name')
```

mediante Ziggy cuando exista una ruta nombrada.

Evita:

```js
window.location.href = '/ruta-hardcodeada'
```

salvo que el flujo existente lo requiera y no exista alternativa razonable.

---

## Composition API

Respeta el estilo del archivo.

En componentes nuevos, si el proyecto usa `<script setup>`, prefiérelo.

Usa:

- `ref` para valores mutables simples;
- `reactive` para objetos estrechamente relacionados;
- `computed` para valores derivados;
- `watch` solo para efectos reales;
- `onMounted` para efectos de montaje;
- `defineProps` para props;
- `defineEmits` para eventos.

No:

- dupliques estado derivado;
- uses `watch` para recalcular algo que puede ser `computed`;
- mutar props;
- ocultes efectos secundarios dentro de `computed`;
- conviertas todo en estado global.

---

## Pinia

La versión declarada es:

```text
pinia ^3.0.3
```

El carrito vive en:

```text
resources/js/stores/cartStore.js
```

### Cuándo usar Pinia

Úsalo para estado:

- compartido por varias páginas o componentes;
- que necesite persistencia;
- que represente el carrito global;
- que deba sobrevivir a navegación.

No lo uses para:

- inputs temporales de un formulario;
- modales locales;
- filtros usados por un único componente;
- loading de una sola petición.

### Carrito

Preserva:

- sincronización con backend;
- espejo en `localStorage`;
- getters `totalCartPrice`;
- getter `totalCartItems`;
- manejo de usuario autenticado o sesión anónima;
- validación de stock del servidor.

El frontend puede mostrar cálculos provisionales, pero el backend decide:

- stock;
- precio;
- descuento;
- subtotal;
- envío;
- total.

No consideres exitoso un cambio del carrito hasta procesar la respuesta del servidor.

---

## Formularios

### Reglas

- muestra labels y mensajes en español;
- conserva valores ante errores;
- deshabilita envío mientras se procesa;
- evita doble envío;
- muestra errores de validación cerca del campo cuando sea posible;
- no confíes solo en validación HTML;
- normaliza datos solo cuando el backend espere esa forma;
- usa tipos de input correctos.

### Inertia forms

Si el proyecto ya usa `useForm`, conserva ese patrón.

Maneja:

- `processing`;
- `errors`;
- `reset`;
- callbacks de éxito y error;
- preservación de scroll cuando corresponda.

No agregues una segunda estrategia de formularios dentro del mismo módulo sin necesidad.

---

## Axios y llamadas HTTP

Versión:

```text
axios ^1.11.0
```

Antes de crear una instancia nueva, revisa la configuración existente.

Para cada petición:

- maneja loading;
- maneja errores;
- evita doble click;
- procesa códigos no exitosos;
- actualiza el estado solo tras respuesta válida;
- no expongas detalles internos del servidor.

No dupliques CSRF o headers que Laravel ya configure en el bootstrap del frontend.

---

## Checkout

El frontend genera UUID con:

```text
uuid ^11.1.0
```

Flujo:

```text
PUT /orders/{uuid}
```

Debes preservar:

- UUID generado antes del envío;
- reutilización del mismo UUID ante reintentos;
- idempotencia del backend;
- bloqueo de doble envío;
- confirmación de orden;
- limpieza del carrito únicamente después de éxito.

No generes un UUID nuevo en cada reintento automático, porque rompería la idempotencia.

No aceptes como definitivos:

- subtotal calculado en Vue;
- descuento;
- envío;
- total;
- stock disponible.

El backend debe recalcularlos.

---

## Rutas y Ziggy

Versiones:

```text
tightenco/ziggy ^2.5
ziggy-js ^2.5.3
```

Usa rutas nombradas.

Ejemplo:

```js
route('products.detail', { sku: product.sku })
```

Antes de usar un nombre, verifica:

```bash
php artisan route:list
```

No inventes nombres de rutas.

---

## SEO

Componentes actuales:

```text
SeoHead.vue
useSeo.js
```

Para páginas públicas revisa:

- título;
- descripción;
- URL canónica;
- metadatos sociales si el componente los soporta;
- indexación;
- inclusión en sitemap desde Laravel.

No agregues metadatos distintos en múltiples lugares para una misma página.

---

## Tailwind CSS

Versiones:

```text
tailwindcss ^4.0.0
@tailwindcss/vite ^4.0.0
@tailwindcss/forms ^0.5.3
```

### Reglas

- sigue los patrones de clases existentes;
- evita CSS personalizado si Tailwind ya resuelve el caso;
- no inventes una paleta distinta;
- no uses clases dinámicas imposibles de detectar por el build;
- verifica responsive;
- conserva consistencia visual;
- usa estados `hover`, `focus`, `disabled` y `aria` cuando corresponda.

No asumas configuración de Tailwind 3.

Revisa los archivos de configuración y CSS existentes antes de usar sintaxis dependiente de versión.

---

## Componentes visuales

### Lucide

Paquete:

```text
lucide-vue-next ^0.543.0
```

Antes de importar un icono:

- verifica el nombre exportado;
- usa tamaño consistente;
- añade texto accesible o `aria-hidden` según corresponda;
- evita mezclar otra biblioteca de iconos.

### SweetAlert2

Paquete:

```text
sweetalert2 ^11.22.5
```

Úsalo cuando el proyecto ya lo emplee para:

- confirmaciones;
- alertas relevantes;
- resultados de acciones.

No lo uses para cada error de campo o mensaje menor.

Textos en español.

### Tailwind Plus Elements

Paquete:

```text
@tailwindplus/elements ^1.0.12
```

Antes de usar un elemento:

- revisa cómo se inicializa en `app.js`;
- no dupliques comportamiento con Vue;
- evita que un plugin manipule el mismo DOM controlado por Vue.

---

## jQuery heredado

Paquete:

```text
jquery ^3.7.1
```

Regla:

- no introducir jQuery en componentes nuevos;
- no usar jQuery para estado o renderizado Vue;
- mantenerlo solo en zonas heredadas;
- al tocar código existente, no migrarlo entero salvo solicitud explícita;
- evitar que jQuery y Vue controlen el mismo nodo.

---

## Vue Router

Paquete:

```text
vue-router ^4.5.0
```

La tienda está basada en Inertia.

No crees rutas SPA paralelas para páginas que ya se resuelven con Laravel + Inertia.

Usa Vue Router únicamente donde el proyecto ya lo haya establecido o la tarea lo requiera de forma explícita.

---

## Responsive

Verifica al menos:

- móvil;
- tablet;
- escritorio.

Revisa:

- navegación;
- tablas;
- imágenes;
- botones;
- formularios;
- modales;
- tarjetas de producto;
- carrito;
- checkout.

Evita anchos fijos que provoquen overflow.

---

## Accesibilidad

Requisitos mínimos:

- botones reales para acciones;
- links reales para navegación;
- labels asociados;
- foco visible;
- texto alternativo en imágenes;
- atributos `aria` cuando sean necesarios;
- soporte de teclado en modales y menús;
- estados disabled comprensibles;
- no depender solo del color.

No elimines outline sin reemplazo accesible.

---

## Manejo de errores

Muestra al usuario mensajes en español.

Distingue:

- validación;
- error de red;
- conflicto de stock;
- cupón inválido;
- sesión expirada;
- error inesperado.

No muestres:

- stack traces;
- SQL;
- rutas internas;
- nombres de clases;
- payloads sensibles.

---

## Rendimiento

Antes de finalizar revisa:

- imports innecesarios;
- llamadas duplicadas;
- renders provocados por watchers;
- listas sin `key`;
- imágenes sin dimensiones apropiadas;
- estado global excesivo;
- componentes demasiado grandes;
- datos repetidos en localStorage;
- carga de librerías para una acción trivial.

No optimices prematuramente ni agregues nuevas dependencias.

---

## Build y assets

Comandos:

```bash
npm run dev
npm run build
```

El build genera:

```text
public/build
```

Reglas:

- no editar archivos compilados;
- no borrar `public/build`;
- no excluirlo del repositorio;
- ejecutar build después de tocar Vue, CSS, Vite o imports;
- reportar errores de build completos y no ocultarlos.

---

## Checklist final

- [ ] Leí `AI_CONTEXT.md`.
- [ ] Revisé componentes similares.
- [ ] Conservé el patrón del archivo.
- [ ] No introduje jQuery nuevo.
- [ ] Usé Ziggy cuando correspondía.
- [ ] No dupliqué estado.
- [ ] El backend sigue siendo autoridad de precios y stock.
- [ ] Evité doble envío.
- [ ] Preservé UUID e idempotencia.
- [ ] Textos visibles están en español.
- [ ] Revisé responsive.
- [ ] Revisé accesibilidad básica.
- [ ] Ejecuté `npm run build`.
- [ ] Reporté cualquier validación no ejecutada.
