# OpenCode_DinoRunner_v1

## Objetivo

Implementar la primera versión del módulo **Juega** y **Aprende** para
Papernest.

Esta implementación debe respetar completamente:

-   AI_CONTEXT.md
-   AGENTS.md
-   SKILLS.md
-   skills/laravel.md
-   skills/vue.md
-   skills/filament.md

------------------------------------------------------------------------

# Alcance

Implementar las rutas:

-   GET `/juega`
-   GET `/juega/dino-runner`
-   GET `/aprende`

No modificar dependencias.

No instalar librerías.

El juego debe implementarse únicamente con:

-   HTML
-   CSS
-   JavaScript
-   Canvas API
-   requestAnimationFrame

No utilizar:

-   Phaser
-   PixiJS
-   Matter.js
-   Three.js
-   GSAP
-   p5.js
-   Motores externos

------------------------------------------------------------------------

# Navegación

Agregar al Header público:

-   Juega
-   Aprende

Utilizar Inertia + Ziggy.

------------------------------------------------------------------------

# Página /juega

Crear un catálogo inicial de juegos.

Mostrar una tarjeta para:

**Dino Runner**

Debe contener:

-   imagen del dinosaurio
-   descripción
-   botón Jugar

------------------------------------------------------------------------

# Página /aprende

Crear una página informativa indicando que próximamente habrá
actividades educativas.

------------------------------------------------------------------------

# Dino Runner

Inspiración:

-   Chrome Dino
-   Endless Runner
-   Plataformas laterales tipo Mario (sin copiar elementos protegidos)

El protagonista será el dinosaurio de Papelería Andy.

------------------------------------------------------------------------

# Assets

Usar únicamente los sprites y backgrounds existentes del proyecto.

No modificar las imágenes originales.

Aplicar:

``` css
image-rendering: pixelated;
image-rendering: crisp-edges;
```

------------------------------------------------------------------------

# Controles

-   ↑ : Saltar
-   Espacio : Saltar
-   ↓ : Agacharse
-   ← : Reducir velocidad
-   → : Aumentar velocidad
-   Esc : Pausar
-   Enter : Iniciar / Reiniciar

Evitar que las teclas desplacen la página.

------------------------------------------------------------------------

# Estados

Implementar:

-   ready
-   running
-   paused
-   gameOver

------------------------------------------------------------------------

# HUD

Mostrar:

-   Score
-   High Score
-   Nivel / Velocidad

Guardar High Score en localStorage.

------------------------------------------------------------------------

# Obstáculos

Utilizar sprites escolares:

-   borrador
-   tijeras
-   regla
-   pegamento
-   sacapuntas
-   cuaderno
-   grapadora
-   lápiz

No generar obstáculos imposibles.

------------------------------------------------------------------------

# Background

Usar el tileset de selva generado.

Implementar parallax:

-   cielo
-   sol
-   nubes
-   montañas
-   árboles
-   arbustos
-   suelo

Cada capa debe moverse a distinta velocidad.

------------------------------------------------------------------------

# Física

Implementar:

-   gravedad
-   salto
-   agacharse
-   velocidad progresiva
-   generación aleatoria de obstáculos
-   colisiones AABB

No usar librerías.

------------------------------------------------------------------------

# Organización sugerida

``` text
resources/js/

Pages/
    Juega/
        Index.vue
        DinoRunner.vue

    Aprende/
        Index.vue

components/
    games/
        DinoRunner/

assets/
    games/
        dino-runner/
```

------------------------------------------------------------------------

# Calidad

No:

-   refactorizar módulos ajenos
-   instalar paquetes
-   modificar package.json
-   modificar composer.json
-   modificar workflow
-   hacer commit
-   hacer push

------------------------------------------------------------------------

# Validaciones

Ejecutar:

``` bash
vendor/bin/pint
composer test
npm run build
php artisan route:list
```

------------------------------------------------------------------------

# Entrega

Reportar:

-   Resumen
-   Rutas creadas
-   Archivos creados
-   Archivos modificados
-   Mecánicas implementadas
-   Assets utilizados
-   Validaciones ejecutadas
-   Limitaciones
-   Pasos pendientes

------------------------------------------------------------------------

# Notas para OpenCode

-   Inspeccionar primero el proyecto antes de crear archivos.
-   Reutilizar componentes existentes.
-   Mantener compatibilidad con Laravel 12, Inertia y Vue.
-   Mantener textos en español.
-   Mantener nombres técnicos en inglés.
-   Priorizar código limpio y modular.
