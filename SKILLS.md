# SKILLS.md

# Papernest Skills Library

Este documento contiene procedimientos reutilizables ("playbooks") para implementar tareas frecuentes.

NO describe reglas del proyecto.

Las reglas están en:

- AGENTS.md

La arquitectura está en:

- AI_CONTEXT.md

Si una tarea coincide con uno de estos playbooks, síguelo antes de improvisar una solución.

---

# Skills del entorno

## customize-opencode

Utilizar cuando la tarea implique:

- AGENTS.md
- AI_CONTEXT.md
- SKILLS.md
- .opencode/
- opencode.json
- opencode.jsonc
- configuración del agente
- MCP
- plugins
- permissions
- subagents

Nunca utilizar para modificar la aplicación.

---

## find-skills

Utilizar cuando el usuario pregunte:

- ¿Existe un skill para...?
- ¿Hay una forma mejor de...?
- ¿Qué capability puedo instalar?

---

# Playbooks

---

# P01 — Crear una migración

Objetivo

Modificar el esquema sin romper instalaciones existentes.

Procedimiento

1. Crear una migración nueva.
2. Nunca modificar migraciones antiguas.
3. Agregar índices cuando sean necesarios.
4. Definir claves foráneas.
5. Actualizar:

- Modelo
- Fillable
- Casts
- Relaciones
- FormRequest
- Resource
- Factory
- Seeder
- Tests

6. Verificar compatibilidad SQLite.
7. Ejecutar:

php artisan migrate

Nunca utilizar

migrate:fresh

---

# P02 — Crear un modelo

Checklist

- Modelo
- Factory
- Migration
- Relaciones
- Fillable
- Casts
- SoftDeletes si aplica
- Policies si aplica

---

# P03 — Crear Resource Filament

1.

Revisar:

app/Models

2.

Crear:

app/Filament/Resources

3.

Crear Pages

- List
- Create
- Edit
- View (si aplica)

4.

Crear formulario

5.

Crear tabla

6.

Agregar filtros

7.

Agregar acciones

8.

Agregar bulk actions

9.

Validar navegación

10.

Probar CRUD completo

---

# P04 — Crear página pública

Checklist

- Ruta
- Controlador
- Inertia::render
- Vue
- SEO
- Ziggy
- Responsive
- Build

---

# P05 — Crear endpoint

Checklist

Ruta

↓

Controller

↓

FormRequest

↓

Model

↓

Resource

↓

Test

↓

Documentación

---

# P06 — Crear API JSON

Debe:

✔ usar Resources

✔ códigos HTTP correctos

✔ validaciones

✔ relaciones eager loading

✔ evitar N+1

✔ respuestas consistentes

---

# P07 — Checkout

Nunca romper

- UUID
- idempotencia
- stock
- cupones

Siempre

DB::transaction()

Siempre

recalcular totales

Nunca

confiar en datos enviados por Vue

---

# P08 — Productos

Al modificar Product verificar:

- slug
- sku
- photo
- attachment_file_name
- tags
- category
- stock

Nunca borrar fotografías manualmente.

---

# P09 — Carrito

Cada cambio debe validar:

- stock

- carrito usuario

- carrito sesión

- localStorage

- Pinia

- totales

---

# P10 — Cupones

Verificar:

status

↓

fechas

↓

subtotal

↓

descuento

↓

total

---

# P11 — Setting

Siempre utilizar

Setting::getSettings()

Nunca crear otra fila.

---

# P12 — Chatbot

Mantener:

step

↓

state machine

↓

confirmación

↓

creación Product

Nunca romper conversaciones existentes.

---

# P13 — SEO

Cada página nueva debe revisar:

- SeoHead
- useSeo
- sitemap
- canonical
- title
- description

---

# P14 — Widgets

Utilizar

flowframe/laravel-trend

Evitar consultas N+1.

---

# P15 — Deploy

No modificar

.github/workflows

Sin autorización.

Siempre ejecutar

npm run build

antes de asumir que el deploy funcionará.

---

# P16 — Tests

Cuando una tarea modifica:

Backend

↓

Feature Test

↓

Unit Test

↓

Factories

↓

Seeder si aplica

Nunca eliminar tests existentes.

---

# P17 — Performance

Antes de finalizar revisar:

- N+1
- eager loading
- índices
- consultas repetidas
- selects innecesarios
- paginación
- cache existente

---

# P18 — Frontend

Checklist

Responsive

↓

Desktop

↓

Tablet

↓

Mobile

↓

Dark mode (si existe)

↓

SEO

↓

Accesibilidad

↓

Build

---

# P19 — Refactor

Antes de refactorizar verificar:

- mismo comportamiento
- mismos endpoints
- mismos eventos
- mismos tests

No hacer refactors por gusto.

---

# P20 — Bugfix

Siempre seguir este flujo

1.

Encontrar causa raíz.

2.

No aplicar parches superficiales.

3.

Corregir la causa.

4.

Buscar efectos secundarios.

5.

Agregar test si aplica.

6.

Verificar que no aparezcan regresiones.

---

# Actualizar este archivo

Agregar un nuevo playbook cuando:

- una tarea se repita varias veces;
- exista un procedimiento estándar;
- un error frecuente pueda prevenirse mediante una checklist.