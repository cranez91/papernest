import idleUrl from '@/assets/games/dino-runner/sprites/player/idle.png'
import run1Url from '@/assets/games/dino-runner/sprites/player/run_1.png'
import run2Url from '@/assets/games/dino-runner/sprites/player/run_2.png'
import run3Url from '@/assets/games/dino-runner/sprites/player/run_3.png'
import jumpUrl from '@/assets/games/dino-runner/sprites/player/jump.png'
import fallUrl from '@/assets/games/dino-runner/sprites/player/fall.png'
import landUrl from '@/assets/games/dino-runner/sprites/player/land.png'
import duckUrl from '@/assets/games/dino-runner/sprites/player/duck.png'
import duckWalkUrl from '@/assets/games/dino-runner/sprites/player/duck_walk.png'
import hitUrl from '@/assets/games/dino-runner/sprites/player/hit.png'
import lowBorradorUrl from '@/assets/games/dino-runner/sprites/obstacles/low/borrador.png'
import lowCorrectorUrl from '@/assets/games/dino-runner/sprites/obstacles/low/corrector.png'
import lowCuadernoUrl from '@/assets/games/dino-runner/sprites/obstacles/low/cuaderno.png'
import lowGrapadoraUrl from '@/assets/games/dino-runner/sprites/obstacles/low/grapadora.png'
import lowLapiceraUrl from '@/assets/games/dino-runner/sprites/obstacles/low/lapicera.png'
import lowPegamentoUrl from '@/assets/games/dino-runner/sprites/obstacles/low/pegamento.png'
import lowReglaUrl from '@/assets/games/dino-runner/sprites/obstacles/low/regla.png'
import lowSacapuntasUrl from '@/assets/games/dino-runner/sprites/obstacles/low/sacapuntas.png'
import highLapizUrl from '@/assets/games/dino-runner/sprites/obstacles/high/lapiz.png'
import highMarcadorRojoUrl from '@/assets/games/dino-runner/sprites/obstacles/high/marcador_rojo.png'
import highMarcadorUrl from '@/assets/games/dino-runner/sprites/obstacles/high/marcador.png'
import highPegamentoBarraUrl from '@/assets/games/dino-runner/sprites/obstacles/high/pegamento_barra.png'
import highReglaUrl from '@/assets/games/dino-runner/sprites/obstacles/high/regla.png'
import highTijerasUrl from '@/assets/games/dino-runner/sprites/obstacles/high/tijeras.png'
import backgroundUrl from '@/assets/games/dino-runner/backgrounds/day_background.png'

const WIDTH = 1487
const HEIGHT = 362

const GRAVITY = 1.04
const JUMP_VELOCITY = -20.15
const MAX_FALL_SPEED = 23.4
const BASE_SPEED = 4.6
const MAX_SPEED = 9.5
const SPEED_ACCELERATION = 0.0009

const PLAYER_X = 130
const PLAYER_HEIGHT = 120
const DUCK_HEIGHT = 68
const DUCK_RENDER_HEIGHT = 88

const LOW_OBSTACLE_HEIGHT = 68
const LOW_OBSTACLE_MAX_WIDTH = 180
const TALL_OBSTACLE_HEIGHT = 112
const TALL_OBSTACLE_MAX_WIDTH = 176
const TALL_CLEARANCE = 96

const SPRITE_PADDING = 4
const LAND_DURATION = 120
const RUN_FRAME_DURATION = 100
const DUCK_FRAME_DURATION = 200

const HIGH_SCORE_KEY = 'dinoRunnerHighScore'

const GROUND_SOURCE_Y = 348

const TALL_SPAWN_AT_SCORE = 150

const CHROMA_KEY_THRESHOLD = 240

async function loadImage(src) {
    const response = await fetch(src)
    if (!response.ok) {
        throw new Error(`No se pudo cargar la imagen: ${src} (HTTP ${response.status})`)
    }
    const blob = await response.blob()
    return await createImageBitmap(blob)
}

function keyOutAndCrop(img, sx, sy, sw, sh) {
    const tmp = document.createElement('canvas')
    tmp.width = sw
    tmp.height = sh
    const tmpCtx = tmp.getContext('2d')
    tmpCtx.drawImage(img, sx, sy, sw, sh, 0, 0, sw, sh)

    const imageData = tmpCtx.getImageData(0, 0, sw, sh)
    const pixels = imageData.data
    for (let i = 0; i < pixels.length; i += 4) {
        if (pixels[i] >= CHROMA_KEY_THRESHOLD &&
            pixels[i + 1] >= CHROMA_KEY_THRESHOLD &&
            pixels[i + 2] >= CHROMA_KEY_THRESHOLD) {
            pixels[i + 3] = 0
        }
    }
    tmpCtx.putImageData(imageData, 0, 0)

    let minX = sw
    let minY = sh
    let maxX = -1
    let maxY = -1
    for (let y = 0; y < sh; y += 1) {
        for (let x = 0; x < sw; x += 1) {
            if (pixels[(y * sw + x) * 4 + 3] > 0) {
                if (x < minX) minX = x
                if (x > maxX) maxX = x
                if (y < minY) minY = y
                if (y > maxY) maxY = y
            }
        }
    }

    if (maxX < 0) return null

    const padding = 2
    minX = Math.max(0, minX - padding)
    minY = Math.max(0, minY - padding)
    maxX = Math.min(sw - 1, maxX + padding)
    maxY = Math.min(sh - 1, maxY + padding)

    const out = document.createElement('canvas')
    out.width = maxX - minX + 1
    out.height = maxY - minY + 1
    out.getContext('2d').drawImage(tmp, minX, minY, out.width, out.height, 0, 0, out.width, out.height)
    return out
}

export default class DinoRunnerEngine {
    constructor(canvas, { onHud }) {
        this.canvas = canvas
        this.ctx = canvas.getContext('2d')
        this.onHud = onHud || (() => {})

        this.state = 'ready'
        this.highScore = Number(localStorage.getItem(HIGH_SCORE_KEY)) || 0

        this.rafId = null
        this.lastTime = 0
        this.assetsLoaded = false

        this.resetWorld()
    }

    resetWorld() {
        this.distance = 0
        this.score = 0
        this.speed = BASE_SPEED
        this.speedMult = 1

        this.playerY = 0
        this.playerVy = 0
        this.grounded = true
        this.doubleJumpUsed = false
        this.ducking = false
        this.duckKeyHeld = false

        this.obstacles = []
        this.nextSpawnDistance = 560
        this.lastObstacleType = null
        this.runTime = 0
        this.runAnimIndex = 0
        this.landTimer = null
        this.pose = 'idle'
    }

    async load() {
        const assets = await Promise.all([
            loadImage(idleUrl),
            loadImage(run1Url),
            loadImage(run2Url),
            loadImage(run3Url),
            loadImage(jumpUrl),
            loadImage(fallUrl),
            loadImage(landUrl),
            loadImage(duckUrl),
            loadImage(duckWalkUrl),
            loadImage(hitUrl),
            loadImage(lowBorradorUrl),
            loadImage(lowCorrectorUrl),
            loadImage(lowCuadernoUrl),
            loadImage(lowGrapadoraUrl),
            loadImage(lowLapiceraUrl),
            loadImage(lowPegamentoUrl),
            loadImage(lowReglaUrl),
            loadImage(lowSacapuntasUrl),
            loadImage(highLapizUrl),
            loadImage(highMarcadorRojoUrl),
            loadImage(highMarcadorUrl),
            loadImage(highPegamentoBarraUrl),
            loadImage(highReglaUrl),
            loadImage(highTijerasUrl),
            loadImage(backgroundUrl),
        ])
        const [idleImg, run1Img, run2Img, run3Img, jumpImg, fallImg, landImg, duckImg, duckWalkImg, hitImg] = assets
        const lowObstacleImgs = assets.slice(10, 18)
        const tallObstacleImgs = assets.slice(18, 24)
        const backgroundImg = assets[24]

        this.buildBackground(backgroundImg)
        this.buildPlayer({
            idle: idleImg,
            run1: run1Img,
            run2: run2Img,
            run3: run3Img,
            jump: jumpImg,
            fall: fallImg,
            land: landImg,
            duck: duckImg,
            duckWalk: duckWalkImg,
            hit: hitImg,
        })
        this.buildObstacles(lowObstacleImgs, tallObstacleImgs)

        this.assetsLoaded = true
        this.playerY = this.groundLine - this.standH
        this.emitHud()
        this.render()
    }

    buildBackground(img) {
        this.bgImage = img
        this.bgScale = HEIGHT / img.height
        this.bgTileW = Math.round(img.width * this.bgScale)
        this.bgTileH = HEIGHT
        this.bgScroll = 0
        this.groundLine = Math.round(GROUND_SOURCE_Y * this.bgScale)
        this.dirtStart = this.groundLine
    }

    buildPlayer(images) {
        const crop = (name) => keyOutAndCrop(images[name], 0, 0, images[name].width, images[name].height)

        const standNames = ['idle', 'run1', 'run2', 'run3', 'jump', 'fall', 'land', 'hit']
        const duckNames = ['duck', 'duckWalk']

        const normalize = (names, matchWidth = false) => {
            const sprites = names.map((name) => ({ name, canvas: crop(name) }))
            const maxW = Math.max(...sprites.map((s) => s.canvas.width))
            const maxH = Math.ceil(Math.max(...sprites.map((s) => matchWidth
                ? s.canvas.height * maxW / s.canvas.width
                : s.canvas.height)))
            const canvasW = maxW + SPRITE_PADDING * 2
            const canvasH = maxH + SPRITE_PADDING * 2
            return sprites.map(({ name, canvas }) => {
                const out = document.createElement('canvas')
                out.width = canvasW
                out.height = canvasH
                const ctx = out.getContext('2d')
                const scale = matchWidth ? maxW / canvas.width : 1
                const width = canvas.width * scale
                const height = canvas.height * scale
                const x = SPRITE_PADDING + (maxW - width) / 2
                const y = SPRITE_PADDING + (maxH - height)
                ctx.imageSmoothingEnabled = false
                ctx.drawImage(canvas, x, y, width, height)
                return { name, canvas: out }
            })
        }

        const stand = normalize(standNames)
        const duck = normalize(duckNames, true)

        const byName = (list, name) => list.find((s) => s.name === name).canvas

        this.poses = {
            idle: byName(stand, 'idle'),
            run: ['run1', 'run2', 'run3'].map((name) => byName(stand, name)),
            jump: byName(stand, 'jump'),
            fall: byName(stand, 'fall'),
            land: byName(stand, 'land'),
            hit: byName(stand, 'hit'),
            duck: ['duck', 'duckWalk'].map((name) => byName(duck, name)),
        }

        const standFrame = byName(stand, 'run1')
        const duckFrame = byName(duck, 'duck')

        this.standH = PLAYER_HEIGHT
        this.standW = Math.round(this.standH * (standFrame.width / standFrame.height))
        this.duckW = Math.round(DUCK_HEIGHT * (duckFrame.width / duckFrame.height))
        this.duckRenderW = Math.round(DUCK_RENDER_HEIGHT * (duckFrame.width / duckFrame.height))
    }

    buildObstacles(lowImgs, tallImgs) {
        const crop = (img) => keyOutAndCrop(img, 0, 0, img.width, img.height)
        this.lowObstacleSprites = lowImgs.map(crop).filter(Boolean)
        this.tallObstacleSprites = tallImgs.map(crop).filter(Boolean)
    }

    get gameSpeed() {
        return this.speed * this.speedMult
    }

    get level() {
        return Math.max(1, Math.floor((this.speed - BASE_SPEED) / 0.9) + 1)
    }

    start() {
        if (!this.assetsLoaded) return
        this.resetWorld()
        this.setState('running')
        this.lastTime = performance.now()
        if (!this.rafId) {
            this.loop = this.loop.bind(this)
            this.rafId = requestAnimationFrame(this.loop)
        }
    }

    togglePause() {
        if (!this.assetsLoaded) return
        if (this.state === 'running') {
            this.setState('paused')
        } else if (this.state === 'paused') {
            this.setState('running')
            this.lastTime = performance.now()
        }
    }

    pause() {
        if (this.state === 'running') {
            this.setState('paused')
        }
    }

    jump() {
        if (!this.assetsLoaded || this.state !== 'running') return
        if (this.grounded) {
            this.playerVy = JUMP_VELOCITY
            this.grounded = false
            this.doubleJumpUsed = false
        } else if (!this.doubleJumpUsed) {
            this.playerVy = JUMP_VELOCITY
            this.doubleJumpUsed = true
        }
    }

    setDuck(ducking) {
        if (!this.assetsLoaded || this.state === 'gameOver') return
        this.duckKeyHeld = ducking
        if (this.grounded && this.state === 'running') {
            this.ducking = ducking
            this.playerY = this.groundLine - (ducking ? DUCK_HEIGHT : this.standH)
            this.landTimer = null
            this.pose = ducking ? 'duck' : 'running'
        }
    }

    adjustSpeed(delta) {
        this.speedMult = Math.min(1.4, Math.max(0.6, this.speedMult + delta))
    }

    setState(next) {
        this.state = next
        this.emitHud()
    }

    emitHud() {
        this.onHud({
            state: this.state,
            score: Math.floor(this.score),
            highScore: this.highScore,
            level: this.level,
            speed: Number(this.gameSpeed.toFixed(1)),
            speedMult: this.speedMult,
        })
    }

    loop(now) {
        this.rafId = requestAnimationFrame(this.loop)

        const dt = Math.min(50, now - this.lastTime)
        this.lastTime = now

        if (this.state === 'running') {
            this.update(dt)
        }

        this.render()
    }

    update(dt) {
        const dtFrames = dt / (1000 / 60)
        this.runTime += dt

        this.speed = Math.min(MAX_SPEED, this.speed + SPEED_ACCELERATION * dtFrames)

        const step = this.gameSpeed * dtFrames
        this.distance += step
        this.score += step / 12

        this.updatePlayer(dtFrames)
        this.updatePose(dt)
        this.updateObstacles(step)
        this.spawnObstacles()

        this.checkCollisions()

        if (this.score > this.highScore) {
            this.highScore = Math.floor(this.score)
            localStorage.setItem(HIGH_SCORE_KEY, String(this.highScore))
        }

        this.emitHud()
    }

    updatePlayer(dtFrames) {
        if (this.playerVy < MAX_FALL_SPEED) {
            this.playerVy += GRAVITY * dtFrames
        }

        this.playerY += this.playerVy * dtFrames

        const playerH = this.ducking ? DUCK_HEIGHT : this.standH
        if (this.playerY >= this.groundLine - playerH) {
            this.playerY = this.groundLine - playerH
            if (!this.grounded && !this.duckKeyHeld) {
                this.landTimer = LAND_DURATION
            }
            this.playerVy = 0
            this.grounded = true
            this.doubleJumpUsed = false
            this.ducking = this.duckKeyHeld
            this.playerY = this.groundLine - (this.ducking ? DUCK_HEIGHT : this.standH)
        } else {
            this.grounded = false
        }

        if (!this.grounded && this.ducking) {
            this.ducking = false
        }
    }

    updatePose(dt) {
        if (this.state === 'ready') {
            this.pose = 'idle'
            return
        }
        if (this.state === 'gameOver') {
            this.pose = 'hit'
            return
        }

        if (!this.grounded) {
            this.pose = this.playerVy < 0 ? 'jump' : 'fall'
            this.landTimer = null
            return
        }

        if (this.ducking) {
            this.pose = 'duck'
            this.landTimer = null
            return
        }

        if (this.landTimer !== null) {
            this.landTimer -= dt
            if (this.landTimer <= 0) {
                this.landTimer = null
                this.pose = 'running'
            } else {
                this.pose = 'land'
            }
            return
        }

        this.pose = 'running'
        this.runAnimIndex = Math.floor(this.runTime / RUN_FRAME_DURATION) % this.poses.run.length
    }

    updateObstacles(step) {
        for (const obstacle of this.obstacles) {
            obstacle.x -= step
        }
        this.obstacles = this.obstacles.filter((obstacle) => obstacle.x + obstacle.w > -40)
    }

    spawnObstacles() {
        if (this.distance < this.nextSpawnDistance) return

        const canSpawnTall = this.score > TALL_SPAWN_AT_SCORE
        let type = 'low'
        if (canSpawnTall) {
            type = Math.random() < 0.55 ? 'low' : 'tall'
            if (this.lastObstacleType === type && Math.random() < 0.5) {
                type = type === 'low' ? 'tall' : 'low'
            }
        }

        const pool = type === 'low' ? this.lowObstacleSprites : this.tallObstacleSprites
        const sprite = pool[Math.floor(Math.random() * pool.length)]
        if (!sprite) {
            this.nextSpawnDistance = this.distance + 200
            return
        }

        const maxH = type === 'low' ? LOW_OBSTACLE_HEIGHT : TALL_OBSTACLE_HEIGHT
        const maxW = type === 'low' ? LOW_OBSTACLE_MAX_WIDTH : TALL_OBSTACLE_MAX_WIDTH
        const aspect = sprite.width / sprite.height
        let w = Math.round(maxH * aspect)
        let h = maxH
        if (w > maxW) {
            w = maxW
            h = Math.round(maxW / aspect)
        }

        const y = type === 'low'
            ? this.groundLine - h
            : this.groundLine - TALL_CLEARANCE - h

        this.obstacles.push({ sprite, x: WIDTH + 20, y, w, h, type })
        this.lastObstacleType = type

        const gap = 360 + this.speed * 26 + Math.random() * 320
        this.nextSpawnDistance = this.distance + gap
    }

    checkCollisions() {
        if (!this.poses) return

        const playerH = this.ducking ? DUCK_HEIGHT : this.standH
        const playerW = this.ducking ? this.duckW : this.standW
        const bob = this.grounded && this.state === 'running' && !this.ducking
            ? Math.abs(Math.sin(this.runTime * 0.02)) * 4
            : 0

        const px = PLAYER_X + playerW * 0.18
        const py = this.playerY + playerH * 0.12 - bob
        const pw = playerW * 0.64
        const ph = playerH * 0.88

        for (const obstacle of this.obstacles) {
            let ox, oy, ow, oh
            if (obstacle.type === 'tall') {
                ox = obstacle.x + obstacle.w * 0.12
                oy = obstacle.y + obstacle.h * 0.1
                ow = obstacle.w * 0.76
                oh = obstacle.h * 0.9 - 4
            } else {
                ox = obstacle.x + obstacle.w * 0.15
                oy = obstacle.y + obstacle.h * 0.1
                ow = obstacle.w * 0.7
                oh = obstacle.h * 0.9
            }

            if (px < ox + ow && px + pw > ox && py < oy + oh && py + ph > oy) {
                this.gameOver()
                return
            }
        }
    }

    gameOver() {
        if (this.state !== 'running') return

        this.highScore = Math.max(this.highScore, Math.floor(this.score))
        localStorage.setItem(HIGH_SCORE_KEY, String(this.highScore))
        this.ducking = false
        this.duckKeyHeld = false
        if (this.grounded) {
            this.playerY = this.groundLine - this.standH
        }
        this.pose = 'hit'
        this.setState('gameOver')

        if (this.rafId) {
            cancelAnimationFrame(this.rafId)
            this.rafId = null
        }

        this.render()
    }

    render() {
        const ctx = this.ctx

        ctx.fillStyle = '#fdfdfb'
        ctx.fillRect(0, 0, WIDTH, HEIGHT)

        if (!this.assetsLoaded) return

        this.renderBackground(ctx)
        this.renderObstacles(ctx)
        this.renderPlayer(ctx)
    }

    renderBackground(ctx) {
        if (this.state === 'running') {
            this.bgScroll = (this.bgScroll + this.gameSpeed) % this.bgTileW
        }

        const tiles = Math.ceil(WIDTH / this.bgTileW) + 1
        for (let k = -1; k < tiles; k++) {
            ctx.drawImage(this.bgImage, k * this.bgTileW - this.bgScroll, 0, this.bgTileW, this.bgTileH)
        }
    }

    renderObstacles(ctx) {
        for (const obstacle of this.obstacles) {
            ctx.save()
            ctx.fillStyle = 'rgba(0, 0, 0, 0.18)'
            ctx.beginPath()
            ctx.ellipse(
                obstacle.x + obstacle.w / 2,
                this.groundLine + 12,
                obstacle.w * 0.5,
                5,
                0,
                0,
                Math.PI * 2
            )
            ctx.fill()
            ctx.drawImage(obstacle.sprite, obstacle.x, obstacle.y, obstacle.w, obstacle.h)
            ctx.restore()
        }
    }

    renderPlayer(ctx) {
        const sprite = this.getCurrentSprite()
        if (!sprite) return

        const ducking = this.ducking
        const playerH = ducking ? DUCK_RENDER_HEIGHT : this.standH
        const playerW = ducking ? this.duckRenderW : this.standW

        const bob = this.grounded && this.state === 'running' && !ducking
            ? Math.abs(Math.sin(this.runTime * 0.02)) * 4
            : 0

        const y = ducking ? this.groundLine - playerH : this.playerY - bob

        ctx.save()
        ctx.imageSmoothingEnabled = false
        ctx.drawImage(sprite, PLAYER_X, y, playerW, playerH)
        ctx.restore()
    }

    getCurrentSprite() {
        if (!this.poses) return null
        if (this.state === 'gameOver') return this.poses.hit
        if (this.grounded && this.ducking) {
            return this.poses.duck[Math.floor(this.runTime / DUCK_FRAME_DURATION) % this.poses.duck.length]
        }

        switch (this.pose) {
            case 'idle':
                return this.poses.idle
            case 'jump':
                return this.poses.jump
            case 'fall':
                return this.poses.fall
            case 'land':
                return this.poses.land
            case 'duck':
                return this.poses.duck[Math.floor(this.runTime / DUCK_FRAME_DURATION) % this.poses.duck.length]
            case 'hit':
                return this.poses.hit
            case 'running':
            default:
                return this.poses.run[this.runAnimIndex]
        }
    }

    destroy() {
        if (this.rafId) {
            cancelAnimationFrame(this.rafId)
            this.rafId = null
        }
    }
}
