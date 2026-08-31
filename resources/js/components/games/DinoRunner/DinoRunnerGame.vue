<template>
    <div class="w-full">
        <div class="relative flex h-96 w-full items-center overflow-hidden rounded-2xl bg-black shadow-xl sm:h-[400px]">
            <canvas ref="canvasRef"
                    width="1487"
                    height="362"
                    class="block h-auto w-full select-none [image-rendering:pixelated] [image-rendering:crisp-edges]"
                    @pointerdown="onPointerDown"/>

            <div class="absolute top-3 left-3 flex flex-col gap-1 pointer-events-none">
                <div class="rounded-lg bg-black/60 px-3 py-1 text-sm font-bold text-amber-400 backdrop-blur-sm">
                    Puntos: {{ score }}
                </div>
                <div class="rounded-lg bg-black/60 px-3 py-1 text-sm font-semibold text-white backdrop-blur-sm">
                    Récord: {{ highScore }}
                </div>
                <div class="rounded-lg bg-black/60 px-3 py-1 text-sm font-semibold text-white backdrop-blur-sm">
                    Nivel {{ level }} · Velocidad {{ speed }}x{{ speedMult.toFixed(1) }}
                </div>
            </div>

            <div v-if="state === 'ready'"
                 class="absolute inset-0 flex items-center justify-center bg-black/40">
                <div class="mx-4 max-w-md rounded-2xl bg-white/95 p-6 text-center shadow-xl">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Dino Runner
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Corre con el dinosaurio de Papelería Andy y esquiva los útiles escolares.
                    </p>
                    <ul class="mt-4 grid grid-cols-1 gap-1 text-left text-sm text-gray-700">
                        <li>↑ / Espacio — Saltar (dos veces para doble salto)</li>
                        <li>↓ — Agacharse</li>
                        <li>← / → — Reducir / Aumentar velocidad</li>
                        <li>Esc — Pausar</li>
                        <li>Enter — Iniciar / Reiniciar</li>
                    </ul>
                    <button type="button"
                            class="mt-5 rounded-xl bg-amber-500 px-6 py-2.5 font-semibold text-white shadow hover:bg-amber-600"
                            @click="engine?.start()">
                        Jugar
                    </button>
                </div>
            </div>

            <div v-else-if="state === 'paused'"
                 class="absolute inset-0 flex items-center justify-center bg-black/40">
                <div class="rounded-2xl bg-white/95 p-6 text-center shadow-xl">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Pausa
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Presiona Esc para continuar.
                    </p>
                </div>
            </div>

            <div v-else-if="state === 'gameOver'"
                 class="absolute inset-0 flex items-center justify-center bg-black/40">
                <div class="mx-4 max-w-md rounded-2xl bg-white/95 p-6 text-center shadow-xl">
                    <h2 class="text-2xl font-bold text-red-600">
                        ¡Juego terminado!
                    </h2>
                    <p class="mt-2 text-sm text-gray-700">
                        Puntos: <strong>{{ score }}</strong>
                    </p>
                    <p class="mt-1 text-sm text-gray-700">
                        Récord: <strong>{{ highScore }}</strong>
                    </p>
                    <p class="mt-4 text-sm text-gray-600">
                        Presiona Enter para volver a jugar.
                    </p>
                    <button type="button"
                            class="mt-5 rounded-xl bg-amber-500 px-6 py-2.5 font-semibold text-white shadow hover:bg-amber-600"
                            @click="engine?.start()">
                        Reiniciar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { onBeforeUnmount, onMounted, ref } from 'vue';
    import DinoRunnerEngine from '@/components/games/DinoRunner/DinoRunnerEngine.js';

    const canvasRef = ref(null);

    const state = ref('ready');
    const score = ref(0);
    const highScore = ref(0);
    const level = ref(1);
    const speed = ref(0);
    const speedMult = ref(1);

    let engine = null;

    const onHud = (hud) => {
        state.value = hud.state;
        score.value = hud.score;
        highScore.value = hud.highScore;
        level.value = hud.level;
        speed.value = hud.speed;
        speedMult.value = hud.speedMult;
    };

    const onKeyDown = (event) => {
        const key = event.key;
        const handledKeys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', ' ', 'Spacebar', 'Enter', 'Escape'];
        if (handledKeys.includes(key)) {
            event.preventDefault();
        }

        if (key === 'ArrowUp' || key === ' ' || key === 'Spacebar') {
            if (state.value === 'ready' || state.value === 'gameOver') {
                engine?.start();
            } else {
                engine?.jump();
            }
        } else if (key === 'Enter') {
            if (state.value === 'ready' || state.value === 'gameOver') {
                engine?.start();
            } else if (state.value === 'paused') {
                engine?.togglePause();
            }
        } else if (key === 'Escape') {
            engine?.togglePause();
        } else if (key === 'ArrowDown') {
            engine?.setDuck(true);
        } else if (key === 'ArrowLeft') {
            engine?.adjustSpeed(-0.1);
        } else if (key === 'ArrowRight') {
            engine?.adjustSpeed(0.1);
        }
    };

    const onKeyUp = (event) => {
        if (event.key === 'ArrowDown') {
            engine?.setDuck(false);
        }
    };

    const onPointerDown = () => {
        if (state.value === 'ready' || state.value === 'gameOver') {
            engine?.start();
        } else {
            engine?.jump();
        }
    };

    const onVisibilityChange = () => {
        if (document.hidden) {
            engine?.pause();
        }
    };

    onMounted(async () => {
        engine = new DinoRunnerEngine(canvasRef.value, { onHud });
        try {
            await engine.load();
        } catch (error) {
            console.error(error);
        }

        window.addEventListener('keydown', onKeyDown);
        window.addEventListener('keyup', onKeyUp);
        document.addEventListener('visibilitychange', onVisibilityChange);
    });

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', onKeyDown);
        window.removeEventListener('keyup', onKeyUp);
        document.removeEventListener('visibilitychange', onVisibilityChange);
        engine?.destroy();
        engine = null;
    });
</script>
