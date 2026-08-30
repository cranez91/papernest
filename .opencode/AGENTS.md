# AGENTS.md

# Papernest

Instrucciones obligatorias para cualquier agente de IA que modifique este repositorio.

Este archivo define **cómo trabajar**.

Para comprender el proyecto consulta:

- AI_CONTEXT.md
- SKILLS.md

Si existe contradicción:

1. El código existente es la fuente de verdad para detalles de implementación.
2. AI_CONTEXT.md es la fuente de verdad para reglas de negocio.
3. AGENTS.md define cómo debes trabajar.

---

# Objetivo

Implementar cambios con el menor impacto posible.

No reescribir módulos.

No cambiar arquitectura.

No introducir nuevas dependencias sin necesidad.

No romper compatibilidad.

Siempre priorizar modificaciones pequeñas, consistentes y fáciles de revisar.

---

# Flujo obligatorio

Antes de escribir código:

1. Leer AI_CONTEXT.md.
2. Leer el código relacionado.
3. Buscar implementaciones similares.
4. Entender el flujo completo.
5. Identificar efectos secundarios.

Nunca asumir.

Verificar siempre.

---

# Antes de editar

Antes de modificar un archivo debes responder internamente:

- ¿Existe ya una implementación parecida?
- ¿Existe un helper que ya resuelva esto?
- ¿Existe un Scope?
- ¿Existe un Trait?
- ¿Existe un Service?
- ¿Existe un FormRequest?
- ¿Existe un componente Vue equivalente?

Si la respuesta es sí, reutilízalo.

---

# Cambios permitidos

Haz solamente lo solicitado.

Evita:

- refactors innecesarios
- cambios de estilo
- renombrar variables
- mover archivos
- cambiar imports
- cambiar formato
- cambiar arquitectura

Si no aporta valor directo a la tarea, no lo modifiques.

---

# Código

## PHP

Utiliza:

- Laravel 12
- PHP 8.2
- Eloquent
- Form Requests
- Policies
- Resources

Evita:

- SQL manual cuando Eloquent sea suficiente
- lógica grande en Controllers
- duplicación
- Helpers globales innecesarios

---

## Base de datos

Nunca:

- modificar migraciones antiguas
- borrar columnas
- renombrar columnas existentes

Siempre:

crear una migración nueva.

---

## Filament

Usa exclusivamente APIs compatibles con Filament v3.

Antes de utilizar una función verifica que exista en la versión instalada.

No utilices ejemplos de Filament v4.

---

## Vue

Mantén el patrón existente.

No mezcles Options API con Composition API dentro del mismo componente.

No uses jQuery nuevo.

---

# Reglas del proyecto

Respetar todas las reglas descritas en AI_CONTEXT.md.

Especialmente:

- UUID de órdenes
- checkout idempotente
- stock
- cupones
- singleton Setting
- disco products
- ventas físicas

---

# Comentarios

No agregar comentarios.

No agregar TODO.

No agregar FIXME.

No agregar código comentado.

---

# Dependencias

No instalar paquetes.

No actualizar paquetes.

No modificar composer.json ni package.json salvo que la tarea lo requiera explícitamente.

---

# Seguridad

Nunca:

- modificar .env
- agregar secretos
- hardcodear rutas
- hardcodear credenciales

---

# Validaciones

Cuando una tarea modifica:

## Backend

Ejecutar

vendor/bin/pint

composer test

## Frontend

Ejecutar

npm run build

---

# Al terminar

Siempre entregar:

## Resumen

...

## Archivos modificados

...

## Validaciones ejecutadas

...

## Riesgos

...

---

# Checklist

Antes de finalizar confirmar:

[ ] No rompí compatibilidad.

[ ] No modifiqué código fuera del alcance.

[ ] No dupliqué lógica.

[ ] No agregué dependencias.

[ ] No dejé código muerto.

[ ] Ejecuté Pint.

[ ] Ejecuté Tests.

[ ] Ejecuté Build si correspondía.

[ ] Revisé efectos secundarios.

---

# Acciones prohibidas

No hacer sin autorización:

- git commit
- git push
- merge
- deploy
- migrate:fresh
- db:wipe
- eliminar archivos
- actualizar Laravel
- actualizar Filament
- actualizar Vue
- cambiar arquitectura
- cambiar despliegue
- cambiar workflow
- eliminar tests
- eliminar soft deletes

---

# Si existe duda

No inventes.

Inspecciona el código.

Si el repositorio no permite resolver la duda, explica el supuesto adoptado.