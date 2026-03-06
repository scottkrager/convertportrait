<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({ stripeKey: String });

// Pro state
const isPro = ref(false);
const showUpgradeModal = ref(false);
const upgradeReason = ref('');
const checkoutLoading = ref(false);
const showRestoreModal = ref(false);
const restoreEmail = ref('');
const restoreLoading = ref(false);
const restoreMessage = ref('');
const processingMode = ref('browser'); // 'browser' or 'server'

// State
const step = ref('upload');
const videoFile = ref(null);
const videoUrl = ref(null);
const videoDimensions = ref({ width: 0, height: 0 });
const videoFrame = ref(null); // captured ImageBitmap from video
const previewCanvas = ref(null); // template ref for canvas element
const videoDuration = ref(0);
const selectedTemplate = ref('blurred');
const gradientVariant = ref('sunset');
const solidColor = ref('#000000');
const patternType = ref('dots');
const patternColor = ref('#2dd4bf');
const progress = ref(0);
const progressMessage = ref('');
const outputUrl = ref(null);
const outputFilename = ref('');
const isDragging = ref(false);
const ffmpegLoaded = ref(false);
const ffmpegLoading = ref(false);
const errorMessage = ref('');
const estimatedTimeLeft = ref('');
const conversionStartTime = ref(0);

let ffmpeg = null;

const templates = [
    {
        id: 'blurred',
        name: 'Blurred Mirror',
        description: 'Video blurred as background fill',
        pro: false,
        icon: `<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="2" width="24" height="28" rx="2" opacity="0.3"/><rect x="11" y="4" width="10" height="24" rx="1"/><path d="M6 8h3M6 14h3M6 20h3M23 8h3M23 14h3M23 20h3" opacity="0.3"/></svg>`,
    },
    {
        id: 'gradient',
        name: 'Gradient Wash',
        description: 'Color gradients on the sides',
        pro: true,
        icon: `<svg viewBox="0 0 32 32" fill="none"><defs><linearGradient id="gw" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="currentColor" stop-opacity="0.6"/><stop offset="100%" stop-color="currentColor" stop-opacity="0.1"/></linearGradient></defs><rect x="4" y="2" width="24" height="28" rx="2" fill="url(#gw)"/><rect x="11" y="4" width="10" height="24" rx="1" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>`,
    },
    {
        id: 'solid',
        name: 'Solid Color',
        description: 'Clean single-color backdrop',
        pro: false,
        icon: `<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="2" width="24" height="28" rx="2"/><rect x="11" y="4" width="10" height="24" rx="1"/></svg>`,
    },
    {
        id: 'pattern',
        name: 'Pattern Fill',
        description: 'Geometric patterns, your colors',
        pro: true,
        icon: `<svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="2" width="24" height="28" rx="2"/><rect x="11" y="4" width="10" height="24" rx="1"/><circle cx="7" cy="8" r="1" fill="currentColor" opacity="0.3"/><circle cx="7" cy="14" r="1" fill="currentColor" opacity="0.3"/><circle cx="7" cy="20" r="1" fill="currentColor" opacity="0.3"/><circle cx="25" cy="8" r="1" fill="currentColor" opacity="0.3"/><circle cx="25" cy="14" r="1" fill="currentColor" opacity="0.3"/><circle cx="25" cy="20" r="1" fill="currentColor" opacity="0.3"/></svg>`,
    },
];

const estimatedBrowserTime = computed(() => {
    // Browser WASM runs at ~0.05x speed based on real data
    const secs = Math.round(videoDuration.value / 0.05);
    const m = Math.floor(secs / 60);
    return m > 0 ? `~${m} min` : `~${secs}s`;
});
const estimatedServerTime = computed(() => {
    // Server native FFmpeg runs at roughly 5-10x speed
    const secs = Math.max(5, Math.round(videoDuration.value / 7));
    return secs > 60 ? `~${Math.ceil(secs / 60)} min` : `~${secs}s`;
});

function checkProStatus() {
    const stored = localStorage.getItem('convertportrait_pro');
    if (stored) {
        isPro.value = true;
        processingMode.value = 'server';
    }

    // Check URL params for Stripe success — prompt for email to verify
    const params = new URLSearchParams(window.location.search);
    if (params.get('pro') === 'activated') {
        window.history.replaceState({}, '', '/');
        showRestoreModal.value = true;
        restoreMessage.value = 'Payment received! Enter the email you used at checkout to activate Pro.';
    }
}

async function restorePurchase() {
    if (!restoreEmail.value) return;
    restoreLoading.value = true;
    restoreMessage.value = '';

    try {
        const res = await fetch('/api/restore', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ email: restoreEmail.value }),
        });
        const data = await res.json();

        if (data.pro) {
            isPro.value = true;
            processingMode.value = 'server';
            localStorage.setItem('convertportrait_pro', restoreEmail.value);
            showRestoreModal.value = false;
            restoreEmail.value = '';
        } else {
            restoreMessage.value = 'No Pro purchase found for that email. It may take a minute after payment — try again shortly.';
        }
    } catch {
        restoreMessage.value = 'Something went wrong. Please try again.';
    } finally {
        restoreLoading.value = false;
    }
}

function triggerUpgrade(reason) {
    upgradeReason.value = reason;
    showUpgradeModal.value = true;
}

async function startCheckout() {
    checkoutLoading.value = true;
    try {
        const res = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });
        const data = await res.json();
        if (data.url) window.location.href = data.url;
    } catch (err) {
        console.error('Checkout error:', err);
        errorMessage.value = 'Could not start checkout. Please try again.';
    } finally {
        checkoutLoading.value = false;
    }
}

const gradients = [
    { id: 'sunset', name: 'Sunset', from: '#f97316', to: '#9333ea' },
    { id: 'ocean', name: 'Ocean', from: '#06b6d4', to: '#1e40af' },
    { id: 'neon', name: 'Neon', from: '#ec4899', to: '#6366f1' },
    { id: 'forest', name: 'Forest', from: '#10b981', to: '#064e3b' },
    { id: 'mono', name: 'Mono', from: '#9ca3af', to: '#1f2937' },
];

const patterns = [
    { id: 'dots', name: 'Dots' },
    { id: 'lines', name: 'Lines' },
    { id: 'chevrons', name: 'Chevrons' },
];

const isPortrait = computed(() => videoDimensions.value.height > videoDimensions.value.width);

async function loadFFmpeg() {
    if (ffmpegLoaded.value || ffmpegLoading.value) return;
    ffmpegLoading.value = true;
    errorMessage.value = '';

    try {
        const { FFmpeg } = await import('@ffmpeg/ffmpeg');
        const { toBlobURL } = await import('@ffmpeg/util');

        ffmpeg = new FFmpeg();
        ffmpeg.on('progress', ({ progress: p }) => {
            progress.value = Math.round(p * 100);
            if (p > 0.05 && conversionStartTime.value) {
                const elapsed = (Date.now() - conversionStartTime.value) / 1000;
                const totalEstimate = elapsed / p;
                const remaining = Math.max(0, Math.round(totalEstimate - elapsed));
                const m = Math.floor(remaining / 60);
                const s = remaining % 60;
                estimatedTimeLeft.value = m > 0 ? `${m}m ${s}s left` : `${s}s left`;
            }
        });
        ffmpeg.on('log', ({ message }) => console.log('[ffmpeg]', message));

        const baseURL = 'https://unpkg.com/@ffmpeg/core@0.12.6/dist/esm';
        await ffmpeg.load({
            coreURL: await toBlobURL(`${baseURL}/ffmpeg-core.js`, 'text/javascript'),
            wasmURL: await toBlobURL(`${baseURL}/ffmpeg-core.wasm`, 'application/wasm'),
        });

        ffmpegLoaded.value = true;
    } catch (err) {
        console.error('FFmpeg load error:', err);
        errorMessage.value = 'Could not load the video processor. Please try Chrome, Edge, or Firefox on desktop.';
    } finally {
        ffmpegLoading.value = false;
    }
}

function handleDragOver(e) { e.preventDefault(); isDragging.value = true; }
function handleDragLeave() { isDragging.value = false; }
function handleDrop(e) {
    e.preventDefault();
    isDragging.value = false;
    const file = e.dataTransfer.files[0];
    if (file?.type.startsWith('video/')) handleFile(file);
}
function handleFileInput(e) {
    const file = e.target.files[0];
    if (file) handleFile(file);
}

function handleFile(file) {
    videoFile.value = file;
    videoUrl.value = URL.createObjectURL(file);
    const video = document.createElement('video');
    video.preload = 'auto';
    video.muted = true;
    video.playsInline = true;
    video.onloadedmetadata = () => {
        videoDimensions.value = { width: video.videoWidth, height: video.videoHeight };
        videoDuration.value = video.duration;
        // Seek to 1s to grab a representative frame
        video.currentTime = Math.min(1, video.duration * 0.1);
    };
    video.onseeked = () => {
        // Capture frame to canvas
        const c = document.createElement('canvas');
        c.width = video.videoWidth;
        c.height = video.videoHeight;
        const ctx = c.getContext('2d');
        ctx.drawImage(video, 0, 0);
        videoFrame.value = c;
        step.value = 'template';
        // Draw initial preview after Vue renders the canvas
        setTimeout(() => drawPreview(), 50);
    };
    video.src = videoUrl.value;
}

function drawPreview() {
    const canvas = previewCanvas.value;
    const frame = videoFrame.value;
    if (!canvas || !frame) return;

    const w = videoDimensions.value.width;
    const h = videoDimensions.value.height;

    // 16:9 canvas at reasonable preview size
    const canvasW = 640;
    const canvasH = 360;
    canvas.width = canvasW;
    canvas.height = canvasH;

    const ctx = canvas.getContext('2d');

    // Scale foreground to fit height
    const fgH = canvasH;
    const fgW = Math.round((w * canvasH) / h);
    const fgX = Math.round((canvasW - fgW) / 2);

    const tpl = selectedTemplate.value;

    // Draw background based on template
    if (tpl === 'blurred') {
        // Draw stretched + blurred frame as background
        ctx.filter = 'blur(20px)';
        ctx.drawImage(frame, -20, -20, canvasW + 40, canvasH + 40);
        ctx.filter = 'none';
        // Slight darken overlay
        ctx.fillStyle = 'rgba(0,0,0,0.15)';
        ctx.fillRect(0, 0, canvasW, canvasH);
    } else if (tpl === 'gradient') {
        const g = gradients.find(g => g.id === gradientVariant.value) || gradients[0];
        const grad = ctx.createLinearGradient(0, 0, canvasW, canvasH);
        grad.addColorStop(0, g.from);
        grad.addColorStop(1, g.to);
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, canvasW, canvasH);
    } else if (tpl === 'solid') {
        ctx.fillStyle = solidColor.value;
        ctx.fillRect(0, 0, canvasW, canvasH);
    } else if (tpl === 'pattern') {
        ctx.fillStyle = '#111111';
        ctx.fillRect(0, 0, canvasW, canvasH);
        const pColor = patternColor.value;
        ctx.fillStyle = pColor + '66'; // 40% opacity
        if (patternType.value === 'dots') {
            for (let y = 0; y < canvasH; y += 15) {
                for (let x = 0; x < canvasW; x += 15) {
                    ctx.beginPath();
                    ctx.arc(x, y, 2, 0, Math.PI * 2);
                    ctx.fill();
                }
            }
        } else if (patternType.value === 'lines') {
            ctx.fillStyle = pColor + '4d'; // 30% opacity
            for (let x = 0; x < canvasW; x += 20) {
                ctx.fillRect(x, 0, 1, canvasH);
            }
        } else {
            ctx.fillStyle = pColor + '26'; // 15% opacity
            for (let x = 0; x < canvasW; x += 30) {
                ctx.fillRect(x, 0, 15, canvasH);
            }
        }
    }

    // Draw the portrait video frame centered
    // Add a subtle shadow
    ctx.shadowColor = 'rgba(0,0,0,0.5)';
    ctx.shadowBlur = 15;
    ctx.shadowOffsetX = 0;
    ctx.shadowOffsetY = 4;
    ctx.drawImage(frame, fgX, 0, fgW, fgH);
    ctx.shadowColor = 'transparent';
}

// Redraw preview when template or options change
watch([selectedTemplate, gradientVariant, solidColor, patternType, patternColor], () => {
    nextTick(() => drawPreview());
});

function selectTemplate(id) {
    const tpl = templates.find(t => t.id === id);
    if (tpl?.pro && !isPro.value) {
        triggerUpgrade('template');
        return;
    }
    selectedTemplate.value = id;
}

async function startConversion() {
    if (isPro.value && processingMode.value === 'server') {
        return startServerConversion();
    }

    if (!ffmpegLoaded.value) {
        await loadFFmpeg();
        if (!ffmpegLoaded.value) return;
    }

    step.value = 'processing';
    progress.value = 0;
    progressMessage.value = 'Reading video file...';
    errorMessage.value = '';
    estimatedTimeLeft.value = estimatedBrowserTime.value;
    conversionStartTime.value = Date.now();

    try {
        const { fetchFile } = await import('@ffmpeg/util');
        await ffmpeg.writeFile('input.mp4', await fetchFile(videoFile.value));
        progressMessage.value = 'Converting to landscape...';

        await ffmpeg.exec([
            '-i', 'input.mp4',
            '-vf', buildFilter(),
            '-c:v', 'libx264', '-preset', 'fast', '-crf', '23',
            '-c:a', 'aac', '-b:a', '128k',
            '-movflags', '+faststart',
            'output.mp4',
        ]);

        progressMessage.value = 'Finalizing...';
        const data = await ffmpeg.readFile('output.mp4');
        outputUrl.value = URL.createObjectURL(new Blob([data.buffer], { type: 'video/mp4' }));
        outputFilename.value = `${videoFile.value.name.replace(/\.[^.]+$/, '')}-landscape.mp4`;
        step.value = 'done';
        await ffmpeg.deleteFile('input.mp4');
        await ffmpeg.deleteFile('output.mp4');
    } catch (err) {
        // If cancelled via terminate(), don't show error
        if (step.value !== 'processing') return;
        console.error('Conversion error:', err);
        errorMessage.value = `Conversion failed: ${err.message}. Try a shorter clip or different format.`;
        step.value = 'template';
    }
}

async function cancelBrowserConversion() {
    if (ffmpeg) {
        try { ffmpeg.terminate(); } catch (e) { /* already terminated */ }
        ffmpeg = null;
        ffmpegLoaded.value = false;
    }
    progress.value = 0;
    progressMessage.value = '';
    estimatedTimeLeft.value = '';
    step.value = 'template';
}

async function switchToFastMode() {
    await cancelBrowserConversion();
    processingMode.value = 'server';
    await nextTick();
    startConversion();
}

function buildOptions() {
    const options = {};
    if (selectedTemplate.value === 'gradient') {
        const g = gradients.find(g => g.id === gradientVariant.value) || gradients[0];
        options.gradientFrom = g.from;
        options.gradientTo = g.to;
    } else if (selectedTemplate.value === 'solid') {
        options.solidColor = solidColor.value;
    } else if (selectedTemplate.value === 'pattern') {
        options.patternColor = patternColor.value;
        options.patternType = patternType.value;
    }
    return options;
}

async function pollForCompletion(jobId, proEmail) {
    while (true) {
        await new Promise(r => setTimeout(r, 2000));
        const resp = await fetch(`/api/process/status/${jobId}`, {
            headers: { 'X-Pro-Email': proEmail },
        });
        if (!resp.ok) throw new Error('Status check failed');
        const data = await resp.json();

        if (data.status === 'failed') {
            throw new Error(data.error || 'Processing failed on server');
        }

        // Map Lambda progress (0-100) to UI progress (42-95)
        progress.value = Math.min(95, 42 + Math.round(data.progress * 0.53));
        progressMessage.value = data.status === 'uploading' ? 'Saving result...' : 'Converting on server...';

        if (data.status === 'completed' && data.downloadUrl) {
            return data.downloadUrl;
        }
    }
}

async function startServerConversion() {
    step.value = 'processing';
    progress.value = 0;
    progressMessage.value = 'Preparing upload...';
    errorMessage.value = '';
    conversionStartTime.value = Date.now();

    const proEmail = localStorage.getItem('convertportrait_pro') || '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    try {
        // Phase 1: Get presigned upload URL
        const initResp = await fetch('/api/process/init', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Pro-Email': proEmail,
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                template: selectedTemplate.value,
                options: JSON.stringify(buildOptions()),
            }),
        });
        if (!initResp.ok) {
            const err = await initResp.json().catch(() => ({}));
            throw new Error(err.error || 'Failed to initialize');
        }
        const { jobId, uploadUrl } = await initResp.json();

        // Phase 2: Upload directly to S3 with real progress
        progressMessage.value = 'Uploading video...';
        await new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('PUT', uploadUrl);
            xhr.setRequestHeader('Content-Type', 'video/mp4');

            xhr.upload.onprogress = (e) => {
                if (e.lengthComputable) {
                    progress.value = Math.round((e.loaded / e.total) * 40);
                }
            };

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) resolve();
                else reject(new Error(`Upload failed (${xhr.status})`));
            };
            xhr.onerror = () => reject(new Error('Upload failed — check your connection'));
            xhr.send(videoFile.value);
        });

        // Phase 3: Trigger Lambda
        progress.value = 42;
        progressMessage.value = 'Starting conversion...';
        const startResp = await fetch('/api/process/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Pro-Email': proEmail,
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ jobId }),
        });
        if (!startResp.ok) throw new Error('Failed to start processing');

        // Phase 4: Poll for real progress
        progressMessage.value = 'Converting on server...';
        const downloadUrl = await pollForCompletion(jobId, proEmail);

        // Phase 5: Download result
        progress.value = 96;
        progressMessage.value = 'Downloading result...';
        const resultResp = await fetch(downloadUrl);
        const blob = await resultResp.blob();

        progress.value = 100;
        outputUrl.value = URL.createObjectURL(blob);
        outputFilename.value = `${videoFile.value.name.replace(/\.[^.]+$/, '')}-landscape.mp4`;
        step.value = 'done';
    } catch (err) {
        console.error('Server conversion error:', err);
        errorMessage.value = `Server processing failed: ${err.message}. Try browser mode or a smaller file.`;
        step.value = 'template';
    }
}

function buildFilter() {
    const w = videoDimensions.value.width;
    const h = videoDimensions.value.height;

    // Target 1920x1080 (or proportional) — always 16:9 landscape
    let outH, outW;
    if (h > 1080) {
        outH = 1080;
        outW = 1920;
    } else {
        outH = Math.ceil(h / 2) * 2;
        outW = Math.ceil((outH * 16) / 9 / 2) * 2;
    }

    // Scale the portrait video to fit within the 16:9 frame height
    const fgH = outH;
    const fgW = Math.ceil((w * outH / h) / 2) * 2;

    const padX = `(${outW}-${fgW})/2`;
    const padY = 0;

    switch (selectedTemplate.value) {
        case 'blurred':
            // Scale input to fill 16:9, blur it, then overlay the sharp original centered
            return [
                `split[fg][bg]`,
                `[bg]scale=${outW}:${outH}:force_original_aspect_ratio=increase,crop=${outW}:${outH},avgblur=sizeX=40:sizeY=40[blurred]`,
                `[fg]scale=${fgW}:${fgH}[sharp]`,
                `[blurred][sharp]overlay=${padX}:${padY}`
            ].join(';');

        case 'gradient': {
            const g = gradients.find(g => g.id === gradientVariant.value) || gradients[0];
            const fromHex = g.from.slice(1);
            const toHex = g.to.slice(1);
            return [
                `color=c=0x${fromHex}:s=${outW}x${outH}:d=1[left]`,
                `color=c=0x${toHex}:s=${outW}x${outH}:d=1[right]`,
                `[left][right]blend=all_mode=addition:all_opacity=0.5[bg]`,
                `[0:v]scale=${fgW}:${fgH}[fg]`,
                `[bg][fg]overlay=${padX}:${padY}:shortest=1`
            ].join(';');
        }

        case 'solid': {
            const hex = solidColor.value.slice(1);
            return [
                `color=c=0x${hex}:s=${outW}x${outH}:d=1[bg]`,
                `[0:v]scale=${fgW}:${fgH}[fg]`,
                `[bg][fg]overlay=${padX}:${padY}:shortest=1`
            ].join(';');
        }

        case 'pattern': {
            const hex = patternColor.value.slice(1);
            let drawboxes = '';
            if (patternType.value === 'lines') {
                for (let i = 0; i < outW; i += 40) drawboxes += `,drawbox=x=${i}:y=0:w=2:h=${outH}:color=0x${hex}@0.3:t=fill`;
            } else if (patternType.value === 'chevrons') {
                for (let i = 0; i < outW; i += 60) drawboxes += `,drawbox=x=${i}:y=0:w=30:h=${outH}:color=0x${hex}@0.15:t=fill`;
            } else {
                for (let y = 0; y < outH; y += 30)
                    for (let x = 0; x < outW; x += 30)
                        drawboxes += `,drawbox=x=${x}:y=${y}:w=4:h=4:color=0x${hex}@0.4:t=fill`;
                drawboxes = drawboxes.substring(0, 5000);
            }
            return [
                `color=c=0x111111:s=${outW}x${outH}:d=1${drawboxes}[bg]`,
                `[0:v]scale=${fgW}:${fgH}[fg]`,
                `[bg][fg]overlay=${padX}:${padY}:shortest=1`
            ].join(';');
        }

        default:
            return [
                `split[fg][bg]`,
                `[bg]scale=${outW}:${outH}:force_original_aspect_ratio=increase,crop=${outW}:${outH},avgblur=sizeX=40:sizeY=40[blurred]`,
                `[fg]scale=${fgW}:${fgH}[sharp]`,
                `[blurred][sharp]overlay=${padX}:${padY}`
            ].join(';');
    }
}

function downloadOutput() {
    const a = document.createElement('a');
    a.href = outputUrl.value;
    a.download = outputFilename.value;
    a.click();
}

function startOver() {
    if (videoUrl.value) URL.revokeObjectURL(videoUrl.value);
    if (outputUrl.value) URL.revokeObjectURL(outputUrl.value);
    videoFile.value = null;
    videoUrl.value = null;
    outputUrl.value = null;
    videoDuration.value = 0;
    step.value = 'upload';
    progress.value = 0;
    errorMessage.value = '';
}

onMounted(() => {
    checkProStatus();
    loadFFmpeg();
});
onUnmounted(() => {
    if (videoUrl.value) URL.revokeObjectURL(videoUrl.value);
    if (outputUrl.value) URL.revokeObjectURL(outputUrl.value);
});
</script>

<template>
    <div class="min-h-screen bg-midnight text-gray-900 noise-bg mesh-gradient">

        <!-- Header -->
        <header class="border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zM11 4a1 1 0 011-1h4a1 1 0 011 1v12a1 1 0 01-1 1h-4a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <span class="text-[15px] font-bold tracking-tight text-gray-900">ConvertPortrait</span>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Privacy badge -->
                    <nav class="hidden sm:flex items-center gap-4 text-sm text-gray-400">
                        <a href="/blog" class="hover:text-gray-600 transition">Blog</a>
                    </nav>
                    <!-- Pro badge or upgrade button -->
                    <button
                        v-if="isPro"
                        class="text-xs font-bold text-white bg-gradient-to-r from-amber-400 to-orange-400 px-3 py-1.5 rounded-full"
                    >
                        PRO
                    </button>
                    <button
                        v-else
                        @click="triggerUpgrade('header')"
                        class="text-xs font-semibold text-amber-400/90 bg-amber-400/[0.08] hover:bg-amber-400/[0.14] px-3 py-1.5 rounded-full border border-amber-400/15 transition-all"
                    >
                        Upgrade to Pro
                    </button>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="max-w-6xl mx-auto px-6">

            <!-- ==================== UPLOAD STEP ==================== -->
            <div v-if="step === 'upload'" class="step-enter">
                <div class="max-w-2xl mx-auto pt-16 sm:pt-24 pb-20">

                    <!-- Hero -->
                    <div class="text-center mb-12 stagger">
                        <div class="inline-flex items-center gap-1.5 text-xs text-teal bg-teal/[0.08] px-3 py-1 rounded-full mb-6 border border-teal/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Your video never leaves your device
                        </div>

                        <h1 class="text-4xl sm:text-[3.25rem] font-extrabold tracking-tight leading-[1.1] mb-5">
                            Make your videos
                            <br>
                            <span class="font-editorial font-normal text-teal">look amazing everywhere</span>
                        </h1>

                        <p class="text-[15px] sm:text-base text-gray-400 max-w-md mx-auto leading-relaxed">
                            Turn your TikToks, Reels, and Shorts into gorgeous landscape videos.
                            Free, private, and takes literally seconds.
                        </p>
                    </div>

                    <!-- Upload zone -->
                    <div
                        @dragover="handleDragOver"
                        @dragleave="handleDragLeave"
                        @drop="handleDrop"
                        @click="$refs.fileInput.click()"
                        :class="[
                            'group relative rounded-2xl p-12 sm:p-16 text-center cursor-pointer transition-all duration-300 border-2 border-dashed',
                            isDragging
                                ? 'border-teal bg-teal/[0.06] scale-[1.01]'
                                : 'border-gray-300 hover:border-teal/30 bg-surface/50 upload-pulse'
                        ]"
                    >
                        <input ref="fileInput" type="file" accept="video/*" class="hidden" @change="handleFileInput">

                        <!-- Icon -->
                        <div class="mb-5">
                            <div :class="[
                                'w-14 h-14 mx-auto rounded-2xl flex items-center justify-center transition-all duration-300',
                                isDragging ? 'bg-teal/20' : 'bg-gray-100/80 group-hover:bg-teal/10'
                            ]">
                                <svg xmlns="http://www.w3.org/2000/svg" :class="['w-6 h-6 transition-colors duration-300', isDragging ? 'text-teal' : 'text-gray-400 group-hover:text-teal/70']" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                </svg>
                            </div>
                        </div>

                        <p class="text-base font-semibold mb-1.5 text-gray-700">
                            Drop your video here
                        </p>
                        <p class="text-sm text-gray-400">
                            or click to browse &middot; MP4, MOV, WebM
                        </p>
                    </div>

                    <!-- Processor status -->
                    <div v-if="ffmpegLoading" class="mt-5 text-center">
                        <div class="inline-flex items-center gap-2 text-xs text-gray-400 bg-surface/80 px-4 py-2 rounded-full">
                            <svg class="animate-spin w-3 h-3 text-teal/60" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Preparing video engine...
                        </div>
                    </div>

                    <div v-if="ffmpegLoaded && !ffmpegLoading" class="mt-5 text-center">
                        <div class="inline-flex items-center gap-1.5 text-xs text-emerald/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Engine ready
                        </div>
                    </div>

                    <div v-if="errorMessage" class="mt-5 bg-danger/[0.06] border border-danger/10 rounded-xl px-5 py-3.5 text-danger/80 text-sm text-center">
                        {{ errorMessage }}
                    </div>

                    <!-- Trust signals -->
                    <div class="mt-14 grid grid-cols-3 gap-4 stagger">
                        <div class="text-center">
                            <div class="w-9 h-9 mx-auto mb-2.5 rounded-xl bg-gray-100/70 flex items-center justify-center border border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal/60" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-500 mb-0.5">No uploads</p>
                            <p class="text-[11px] text-gray-300 leading-snug">Video stays on your device. Nothing is sent to any server.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-9 h-9 mx-auto mb-2.5 rounded-xl bg-gray-100/70 flex items-center justify-center border border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal/60" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-500 mb-0.5">No tracking</p>
                            <p class="text-[11px] text-gray-300 leading-snug">No cookies, analytics, or data collection of any kind.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-9 h-9 mx-auto mb-2.5 rounded-xl bg-gray-100/70 flex items-center justify-center border border-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal/60" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-gray-500 mb-0.5">Instant results</p>
                            <p class="text-[11px] text-gray-300 leading-snug">Converts in your browser using WebAssembly. Fast and free.</p>
                        </div>
                    </div>

                    <!-- How it works -->
                    <div class="mt-20">
                        <h2 class="text-lg font-bold tracking-tight text-center mb-8 text-gray-600">How it works</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="w-8 h-8 mx-auto mb-3 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-xs font-bold text-teal">1</div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Upload your video</p>
                                <p class="text-xs text-gray-300 leading-relaxed">Drop any portrait video — TikTok, Reel, YouTube Short. MP4, MOV, or WebM.</p>
                            </div>
                            <div class="text-center">
                                <div class="w-8 h-8 mx-auto mb-3 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-xs font-bold text-teal">2</div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Pick a background</p>
                                <p class="text-xs text-gray-300 leading-relaxed">Choose blurred mirror, solid color, gradient, or pattern fill for the sides.</p>
                            </div>
                            <div class="text-center">
                                <div class="w-8 h-8 mx-auto mb-3 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-xs font-bold text-teal">3</div>
                                <p class="text-sm font-semibold text-gray-600 mb-1">Download landscape</p>
                                <p class="text-xs text-gray-300 leading-relaxed">Get a 1920x1080 video ready for YouTube, presentations, or any widescreen display.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Use Cases -->
                    <div class="mt-20">
                        <h2 class="text-lg font-bold tracking-tight text-center mb-3 text-gray-600">Works with all your fave platforms</h2>
                        <p class="text-sm text-gray-300 text-center max-w-lg mx-auto mb-10 leading-relaxed">Repurpose your TikToks for YouTube, turn Reels into widescreen content, or make your Shorts look stunning on any screen.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="/convert-tiktok-to-youtube" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-pink-500/10 border border-pink-500/10 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-800 transition mb-1">TikTok to YouTube</p>
                                        <p class="text-xs text-gray-300 leading-relaxed">Convert 9:16 TikTok videos to 16:9 landscape format ready for YouTube uploads.</p>
                                    </div>
                                </div>
                            </a>
                            <a href="/convert-reels-to-landscape" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/10 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-400" viewBox="0 0 20 20" fill="currentColor"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-800 transition mb-1">Instagram Reels to Landscape</p>
                                        <p class="text-xs text-gray-300 leading-relaxed">Repurpose your Reels for widescreen platforms without cropping or stretching.</p>
                                    </div>
                                </div>
                            </a>
                            <a href="/vertical-to-horizontal-video" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/10 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 110-2h4a1 1 0 011 1v4a1 1 0 11-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 112 0v1.586l2.293-2.293a1 1 0 011.414 1.414L5.414 15H7a1 1 0 110 2H3a1 1 0 01-1-1v-4zm13.657 3.657a1 1 0 01-1.414 0L12.95 13.364a1 1 0 011.414-1.414l2.293 2.293V12.95a1 1 0 112 0v4a1 1 0 01-1 1h-4a1 1 0 110-2h1.586z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-800 transition mb-1">Vertical to Horizontal</p>
                                        <p class="text-xs text-gray-300 leading-relaxed">Convert any vertical video to horizontal for presentations, TV screens, and more.</p>
                                    </div>
                                </div>
                            </a>
                            <a href="/video-aspect-ratio-converter" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm9 4a1 1 0 10-2 0v6a1 1 0 102 0V7zm-3 2a1 1 0 10-2 0v4a1 1 0 102 0V9zM8 11a1 1 0 10-2 0v2a1 1 0 102 0v-2z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-800 transition mb-1">Aspect Ratio Converter</p>
                                        <p class="text-xs text-gray-300 leading-relaxed">Change video aspect ratio from 9:16 to 16:9 — no black bars, no quality loss.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Background Styles Detail -->
                    <div class="mt-20">
                        <h2 class="text-lg font-bold tracking-tight text-center mb-3 text-gray-600">4 background styles to choose from</h2>
                        <p class="text-sm text-gray-300 text-center max-w-lg mx-auto mb-10 leading-relaxed">Your portrait video stays centered and sharp. The background fills the 16:9 frame with the style you choose.</p>

                        <div class="space-y-3">
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-10 rounded-lg bg-gradient-to-r from-surface-light/80 via-surface/60 to-surface-light/80 flex items-center justify-center shrink-0 border border-gray-200 overflow-hidden">
                                        <div class="w-4 h-9 bg-white/10 rounded-sm"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Blurred Mirror <span class="text-[10px] text-emerald/60 font-medium ml-1.5">FREE</span></p>
                                        <p class="text-xs text-gray-400 leading-relaxed">Your video is blurred and scaled to fill the entire background. Creates a cohesive, professional look that extends your content naturally. The most popular choice for YouTube uploads.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-10 rounded-lg bg-[#1a1a2e] flex items-center justify-center shrink-0 border border-gray-200">
                                        <div class="w-4 h-9 bg-white/10 rounded-sm"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Solid Color <span class="text-[10px] text-emerald/60 font-medium ml-1.5">FREE</span></p>
                                        <p class="text-xs text-gray-400 leading-relaxed">Pick any color for a clean, minimal backdrop. Great for branded content where you want the sides to match your brand colors. Includes a color picker and preset options.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-10 rounded-lg bg-gradient-to-r from-orange-500/40 to-purple-500/40 flex items-center justify-center shrink-0 border border-gray-200">
                                        <div class="w-4 h-9 bg-white/10 rounded-sm"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Gradient Wash <span class="text-[10px] text-amber-400/60 font-bold ml-1.5">PRO</span></p>
                                        <p class="text-xs text-gray-400 leading-relaxed">Beautiful color gradients like Sunset, Ocean, Neon, Forest, and Mono. Adds visual energy and depth to your landscape video. Five hand-picked presets to choose from.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-16 h-10 rounded-lg bg-[#111] flex items-center justify-center shrink-0 border border-gray-200 relative overflow-hidden">
                                        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle, rgba(45,212,191,0.4) 1px, transparent 1px); background-size: 6px 6px;"></div>
                                        <div class="w-4 h-9 bg-white/10 rounded-sm relative z-10"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-600 mb-1">Pattern Fill <span class="text-[10px] text-amber-400/60 font-bold ml-1.5">PRO</span></p>
                                        <p class="text-xs text-gray-400 leading-relaxed">Geometric patterns — dots, lines, or chevrons — with your choice of color. Adds texture and style without distracting from your content.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Why ConvertPortrait -->
                    <div class="mt-20">
                        <h2 class="text-lg font-bold tracking-tight text-center mb-3 text-gray-600">Why creators love it</h2>
                        <p class="text-sm text-gray-300 text-center max-w-lg mx-auto mb-10 leading-relaxed">The easiest way to make your vertical content look gorgeous in landscape. No downloads, no sign-ups, just vibes.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <p class="text-sm font-semibold text-gray-600 mb-1.5">No cropping, no stretching</p>
                                <p class="text-xs text-gray-300 leading-relaxed">Your original video is preserved at full resolution. We add a styled background to fill the 16:9 frame — never cut content from your video.</p>
                            </div>
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <p class="text-sm font-semibold text-gray-600 mb-1.5">Works in your browser</p>
                                <p class="text-xs text-gray-300 leading-relaxed">No downloads, no installs. ConvertPortrait uses WebAssembly to process video directly in your browser. Your files never leave your device.</p>
                            </div>
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <p class="text-sm font-semibold text-gray-600 mb-1.5">1920x1080 Full HD output</p>
                                <p class="text-xs text-gray-300 leading-relaxed">Every converted video outputs at 1920x1080 — the standard for YouTube, presentations, and any widescreen display.</p>
                            </div>
                            <div class="bg-gray-100/60 border border-gray-200 rounded-xl p-5">
                                <p class="text-sm font-semibold text-gray-600 mb-1.5">Pro: 140x faster with server processing</p>
                                <p class="text-xs text-gray-300 leading-relaxed">Upgrade to Pro for $19.99 (lifetime) and convert videos in seconds with server-side FFmpeg. Plus all premium templates included.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Teaser -->
                    <div class="mt-20">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold tracking-tight text-gray-600">From the blog</h2>
                            <a href="/blog" class="text-xs text-teal/60 hover:text-teal transition font-medium">View all &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <a href="/blog/convert-9-16-to-16-9-without-black-bars" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-700 transition mb-1.5 leading-snug">Best Ways to Convert 9:16 to 16:9 Without Black Bars</p>
                                <p class="text-xs text-gray-300 leading-relaxed line-clamp-2">Black bars ruin the viewing experience. Here are the best ways to convert vertical video to landscape while keeping it professional.</p>
                            </a>
                            <a href="/blog/convert-tiktok-videos-to-youtube-format" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-700 transition mb-1.5 leading-snug">How to Convert TikTok Videos to YouTube Format</p>
                                <p class="text-xs text-gray-300 leading-relaxed line-clamp-2">TikTok is 9:16 portrait, YouTube is 16:9 landscape. Here's how to convert between them without losing quality.</p>
                            </a>
                            <a href="/blog/portrait-vs-landscape-video-when-to-convert" class="group bg-gray-100/60 border border-gray-200 rounded-xl p-5 hover:border-teal/20 hover:bg-surface-light/30 transition-all duration-300">
                                <p class="text-sm font-semibold text-gray-600 group-hover:text-gray-700 transition mb-1.5 leading-snug">Portrait vs Landscape Video: When to Convert</p>
                                <p class="text-xs text-gray-300 leading-relaxed line-clamp-2">Understanding when to convert between formats can help you reach more viewers and repurpose content across platforms.</p>
                            </a>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div class="mt-20 mb-8">
                        <h2 class="text-lg font-bold tracking-tight text-center mb-8 text-gray-600">Frequently asked questions</h2>
                        <div class="space-y-3 max-w-xl mx-auto">
                            <details class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                                <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-sm font-semibold text-gray-600 hover:text-gray-700 transition">
                                    How do I convert a portrait video to landscape?
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </summary>
                                <p class="px-5 pb-4 text-xs text-gray-400 leading-relaxed">Upload your portrait video, choose a background style, and click Convert. Your video is converted to 16:9 landscape format right in your browser — no software to install.</p>
                            </details>
                            <details class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                                <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-sm font-semibold text-gray-600 hover:text-gray-700 transition">
                                    Is ConvertPortrait free?
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </summary>
                                <p class="px-5 pb-4 text-xs text-gray-400 leading-relaxed">Yes! The free tier includes browser-based conversion with blurred mirror and solid color backgrounds. Pro ($19.99 one-time) adds server-side fast processing, premium templates, and future features.</p>
                            </details>
                            <details class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                                <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-sm font-semibold text-gray-600 hover:text-gray-700 transition">
                                    Is my video uploaded to a server?
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </summary>
                                <p class="px-5 pb-4 text-xs text-gray-400 leading-relaxed">In free mode, no — your video is processed entirely in your browser using WebAssembly and never leaves your device. Pro users can optionally use server-side processing for faster conversion, where the video is deleted immediately after.</p>
                            </details>
                            <details class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                                <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-sm font-semibold text-gray-600 hover:text-gray-700 transition">
                                    What video formats are supported?
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </summary>
                                <p class="px-5 pb-4 text-xs text-gray-400 leading-relaxed">ConvertPortrait supports MP4, MOV, WebM, and AVI files up to 200MB in browser mode or 500MB with Pro server processing.</p>
                            </details>
                            <details class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                                <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-sm font-semibold text-gray-600 hover:text-gray-700 transition">
                                    What background styles are available?
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </summary>
                                <p class="px-5 pb-4 text-xs text-gray-400 leading-relaxed">Free backgrounds include Blurred Mirror (your video blurred as background) and Solid Color (pick any color). Pro backgrounds include Gradient Wash (5 preset gradients like Sunset, Ocean, Neon) and Pattern Fill (dots, lines, or chevrons with custom colors).</p>
                            </details>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==================== TEMPLATE STEP ==================== -->
            <div v-if="step === 'template'" class="step-enter">
                <div class="max-w-4xl mx-auto pt-10 pb-20">

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-bold tracking-tight">Choose a style</h2>
                            <p class="text-gray-400 text-sm mt-1">
                                {{ videoDimensions.width }} &times; {{ videoDimensions.height }}px
                                <span v-if="isPortrait" class="text-teal/60 ml-1.5">&middot; Portrait</span>
                                <span v-else class="text-warning/60 ml-1.5">&middot; Landscape &mdash; will add side panels</span>
                            </p>
                        </div>
                        <button @click="startOver" class="text-sm text-gray-400 hover:text-gray-600 transition flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Change video
                        </button>
                    </div>

                    <!-- Template preview -->
                    <div class="mb-8 bg-surface/60 rounded-xl border border-gray-200 p-3">
                        <div class="relative">
                            <canvas ref="previewCanvas" class="w-full rounded-lg" style="aspect-ratio: 16/9;"></canvas>
                            <div class="absolute bottom-2 right-2 bg-black/50 backdrop-blur-sm text-[10px] text-gray-600 px-2 py-1 rounded">
                                Preview
                            </div>
                        </div>
                    </div>

                    <!-- Templates -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8 stagger">
                        <button
                            v-for="t in templates"
                            :key="t.id"
                            @click="selectTemplate(t.id)"
                            :class="[
                                'group relative rounded-xl p-4 text-left transition-all duration-200 border',
                                selectedTemplate === t.id
                                    ? 'border-teal/40 bg-teal/[0.06] ring-1 ring-teal/20'
                                    : 'border-gray-200 bg-surface/40 hover:border-gray-300 hover:bg-surface/60'
                            ]"
                        >
                            <div :class="['w-8 h-8 mb-3 transition-colors', selectedTemplate === t.id ? 'text-teal' : t.pro && !isPro ? 'text-gray-200' : 'text-gray-300 group-hover:text-gray-500']" v-html="t.icon"></div>
                            <p class="text-sm font-semibold text-gray-700 flex items-center gap-1.5">
                                {{ t.name }}
                                <span v-if="t.pro && !isPro" class="text-[10px] font-bold text-amber-400/80 bg-amber-400/[0.1] px-1.5 py-0.5 rounded">PRO</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ t.description }}</p>
                            <div v-if="selectedTemplate === t.id" class="absolute top-3 right-3 w-5 h-5 bg-teal rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Gradient options -->
                    <div v-if="selectedTemplate === 'gradient'" class="bg-surface/50 rounded-xl border border-gray-200 p-5 mb-6 step-enter">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Gradient</p>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="g in gradients"
                                :key="g.id"
                                @click="gradientVariant = g.id"
                                :class="[
                                    'flex items-center gap-2 px-3.5 py-2 rounded-lg border transition-all text-sm',
                                    gradientVariant === g.id
                                        ? 'border-teal/30 bg-teal/[0.06]'
                                        : 'border-gray-200 hover:border-gray-300'
                                ]"
                            >
                                <div class="w-5 h-5 rounded-full ring-1 ring-gray-300" :style="`background: linear-gradient(135deg, ${g.from}, ${g.to})`"></div>
                                <span class="text-gray-600">{{ g.name }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Solid options -->
                    <div v-if="selectedTemplate === 'solid'" class="bg-surface/50 rounded-xl border border-gray-200 p-5 mb-6 step-enter">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Color</p>
                        <div class="flex items-center gap-3">
                            <input type="color" v-model="solidColor" class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent">
                            <div class="flex gap-1.5">
                                <button
                                    v-for="c in ['#000000', '#ffffff', '#1a1a2e', '#0f172a', '#1c1917']"
                                    :key="c"
                                    @click="solidColor = c"
                                    :class="[
                                        'w-7 h-7 rounded-lg border transition-all',
                                        solidColor === c ? 'border-teal/50 ring-1 ring-teal/20' : 'border-gray-300'
                                    ]"
                                    :style="`background: ${c}`"
                                ></button>
                            </div>
                        </div>
                    </div>

                    <!-- Pattern options -->
                    <div v-if="selectedTemplate === 'pattern'" class="bg-surface/50 rounded-xl border border-gray-200 p-5 mb-6 step-enter">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Pattern</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <button
                                v-for="p in patterns"
                                :key="p.id"
                                @click="patternType = p.id"
                                :class="[
                                    'px-3.5 py-2 rounded-lg border text-sm transition-all',
                                    patternType === p.id
                                        ? 'border-teal/30 bg-teal/[0.06] text-gray-600'
                                        : 'border-gray-200 text-gray-400 hover:border-gray-300'
                                ]"
                            >{{ p.name }}</button>
                        </div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Color</p>
                        <input type="color" v-model="patternColor" class="w-9 h-9 rounded-lg cursor-pointer border-0 bg-transparent">
                    </div>

                    <!-- Processing mode toggle (Pro only) -->
                    <div v-if="isPro" class="mb-4">
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-2">Processing</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                @click="processingMode = 'server'"
                                :class="[
                                    'relative rounded-xl border p-3 text-left transition-all duration-200',
                                    processingMode === 'server'
                                        ? 'bg-amber-400/[0.08] border-amber-400/30'
                                        : 'bg-gray-100/60 border-gray-200 hover:border-gray-300'
                                ]"
                            >
                                <div class="flex items-center gap-2 mb-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" :class="['w-3.5 h-3.5', processingMode === 'server' ? 'text-amber-400' : 'text-gray-400']" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                                    </svg>
                                    <span :class="['text-xs font-bold', processingMode === 'server' ? 'text-amber-400' : 'text-gray-500']">Fast</span>
                                    <span v-if="processingMode === 'server'" class="ml-auto text-[9px] font-bold uppercase tracking-wider text-amber-400/60 bg-amber-400/10 px-1.5 py-0.5 rounded">Active</span>
                                </div>
                                <p class="text-[10px] leading-relaxed" :class="processingMode === 'server' ? 'text-gray-400' : 'text-gray-300'">Server-side — ~140x faster. Video uploaded temporarily, deleted after.</p>
                            </button>
                            <button
                                @click="processingMode = 'browser'"
                                :class="[
                                    'relative rounded-xl border p-3 text-left transition-all duration-200',
                                    processingMode === 'browser'
                                        ? 'bg-teal/[0.08] border-teal/30'
                                        : 'bg-gray-100/60 border-gray-200 hover:border-gray-300'
                                ]"
                            >
                                <div class="flex items-center gap-2 mb-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" :class="['w-3.5 h-3.5', processingMode === 'browser' ? 'text-teal' : 'text-gray-400']" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span :class="['text-xs font-bold', processingMode === 'browser' ? 'text-teal' : 'text-gray-500']">Private</span>
                                    <span v-if="processingMode === 'browser'" class="ml-auto text-[9px] font-bold uppercase tracking-wider text-teal/60 bg-teal/10 px-1.5 py-0.5 rounded">Active</span>
                                </div>
                                <p class="text-[10px] leading-relaxed" :class="processingMode === 'browser' ? 'text-gray-400' : 'text-gray-300'">In-browser — video never leaves your device. Slower processing.</p>
                            </button>
                        </div>
                    </div>

                    <!-- Time estimate (free users) -->
                    <div v-if="!isPro && videoDuration > 10" class="mb-4 bg-gray-100/60 rounded-xl border border-gray-200 p-4">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-300 text-[10px] uppercase tracking-wider">Browser</span>
                                    <span class="text-gray-500 font-semibold">{{ estimatedBrowserTime }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-200" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <div class="flex flex-col items-center">
                                    <span class="text-amber-400/60 text-[10px] uppercase tracking-wider flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                                        </svg>
                                        Pro
                                    </span>
                                    <span class="text-amber-400/80 font-semibold">{{ estimatedServerTime }}</span>
                                </div>
                            </div>
                            <button @click="triggerUpgrade('speed')" class="text-xs font-semibold text-amber-400/80 hover:text-amber-400 transition">
                                Go faster &rarr;
                            </button>
                        </div>
                    </div>

                    <div v-if="errorMessage" class="mb-4 bg-danger/[0.06] border border-danger/10 rounded-xl px-5 py-3.5 text-danger/80 text-sm text-center">
                        {{ errorMessage }}
                    </div>

                    <!-- Convert button -->
                    <button
                        @click="startConversion"
                        :disabled="ffmpegLoading"
                        class="w-full font-bold text-base py-4 rounded-xl transition-all duration-200 active:scale-[0.99] bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white hover:shadow-lg hover:shadow-pink-400/10 disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <span v-if="ffmpegLoading" class="inline-flex items-center gap-2 opacity-70">
                            <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Preparing engine...
                        </span>
                        <span v-else>Convert to Landscape</span>
                    </button>
                </div>
            </div>

            <!-- ==================== PROCESSING STEP ==================== -->
            <div v-if="step === 'processing'" class="step-enter">
                <div class="max-w-md mx-auto pt-24 pb-20 text-center">
                    <div class="w-16 h-16 mx-auto mb-8 relative">
                        <div class="absolute inset-0 rounded-full border-2 border-gray-200"></div>
                        <svg class="absolute inset-0 animate-spin" viewBox="0 0 64 64" fill="none">
                            <circle cx="32" cy="32" r="30" stroke="url(#prog-grad)" stroke-width="2" stroke-linecap="round" stroke-dasharray="140 60" />
                            <defs>
                                <linearGradient id="prog-grad" x1="0" y1="0" x2="64" y2="64">
                                    <stop offset="0%" stop-color="#f472b6" />
                                    <stop offset="100%" stop-color="#c084fc" stop-opacity="0.2" />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <h2 class="text-xl font-bold mb-1.5 tracking-tight">Converting...</h2>
                    <p class="text-sm text-gray-400 mb-2">{{ progressMessage }}</p>
                    <p v-if="estimatedTimeLeft && progress >= 0" class="text-sm text-gray-500 font-semibold mb-6 tabular-nums">{{ estimatedTimeLeft }}</p>
                    <p v-else class="mb-6"></p>

                    <!-- Progress bar -->
                    <div class="relative bg-surface rounded-full h-2.5 overflow-hidden">
                        <div v-if="progress >= 0"
                            class="absolute inset-y-0 left-0 bg-gradient-to-r from-pink-400 to-purple-400 rounded-full transition-all duration-500 ease-out"
                            :style="`width: ${Math.max(progress, 3)}%`"
                        ></div>
                        <div class="absolute inset-0 shimmer rounded-full"></div>
                    </div>
                    <p v-if="progress >= 0" class="mt-3 text-xs text-gray-300 tabular-nums">{{ progress }}%</p>
                    <p v-else class="mt-3 text-xs text-gray-300">Processing on server...</p>

                    <!-- Switch to fast mode (Pro users in browser mode) -->
                    <div v-if="isPro && processingMode === 'browser' && progress > 0 && progress < 95" class="mt-8">
                        <button
                            @click="switchToFastMode"
                            class="w-full bg-amber-400/[0.08] hover:bg-amber-400/[0.12] border border-amber-400/20 hover:border-amber-400/30 rounded-xl p-4 text-left transition-all duration-200 group"
                        >
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-400/15 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-amber-400 group-hover:text-amber-300 transition">Switch to Fast mode</p>
                                    <p class="text-[11px] text-gray-400">Cancel this and re-process on our server in {{ estimatedServerTime }}</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400/50 group-hover:text-amber-400 group-hover:translate-x-0.5 transition-all" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Pro upsell during slow browser conversion (free users) -->
                    <div v-if="!isPro && processingMode === 'browser' && progress > 2 && progress < 90" class="mt-8 bg-amber-400/[0.04] border border-amber-400/10 rounded-xl p-5 text-left">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-400/10 flex items-center justify-center shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 mb-1">Too slow? Pro is {{ estimatedServerTime }} with server processing</p>
                                <p class="text-xs text-gray-400 mb-3">Plus all templates unlocked and future features included.</p>
                                <button @click="triggerUpgrade('speed')" class="text-xs font-bold text-amber-400 hover:text-amber-300 transition">
                                    Upgrade to Pro &rarr;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cancel / context reminder -->
                    <div class="mt-10 flex flex-col items-center gap-3">
                        <div class="inline-flex items-center gap-1.5 text-[11px] text-gray-300">
                            <svg v-if="processingMode === 'server'" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            {{ processingMode === 'server' ? 'Server-side processing — file deleted after conversion' : 'Processing locally on your device' }}
                        </div>
                        <button
                            v-if="processingMode === 'browser'"
                            @click="cancelBrowserConversion"
                            class="text-[11px] text-gray-300 hover:text-gray-500 transition"
                        >
                            Cancel conversion
                        </button>
                    </div>
                </div>
            </div>

            <!-- ==================== DONE STEP ==================== -->
            <div v-if="step === 'done'" class="step-enter">
                <div class="max-w-3xl mx-auto pt-12 pb-20 text-center">
                    <div class="mb-6 stagger">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-success/10 border border-success/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-success" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold tracking-tight mb-1">Ready to download</h2>
                        <p class="text-sm text-gray-400">{{ outputFilename }}</p>
                    </div>

                    <div class="bg-surface/60 rounded-xl border border-gray-200 p-3 mb-8">
                        <video :src="outputUrl" class="max-h-72 mx-auto rounded-lg" controls></video>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button
                            @click="downloadOutput"
                            class="bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white font-bold px-8 py-3.5 rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]"
                        >
                            Download Video
                        </button>
                        <button
                            @click="startOver"
                            class="bg-surface/60 hover:bg-surface-light text-gray-600 hover:text-gray-700 font-semibold px-8 py-3.5 rounded-xl border border-gray-200 hover:border-gray-300 transition-all"
                        >
                            Convert Another
                        </button>
                    </div>
                </div>
            </div>

        </main>

        <!-- Upgrade Modal -->
        <Teleport to="body">
            <div v-if="showUpgradeModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 text-gray-900" @click.self="showUpgradeModal = false">
                <div class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>
                <div class="relative max-w-md w-full rounded-2xl overflow-hidden shadow-2xl shadow-amber-500/10 step-enter">

                    <!-- Glowing top accent -->
                    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-amber-400/60 to-transparent"></div>
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-24 bg-amber-400/10 blur-3xl pointer-events-none"></div>

                    <div class="relative bg-white border border-gray-300 rounded-2xl p-8">

                        <!-- Close -->
                        <button @click="showUpgradeModal = false" class="absolute top-4 right-4 w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Icon -->
                        <div class="flex justify-center mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 bg-amber-400/20 rounded-2xl blur-xl"></div>
                                <div class="relative w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-lg shadow-amber-500/25">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Title -->
                        <h3 class="text-2xl font-extrabold text-center tracking-tight text-gray-900 mb-1.5">Go Pro</h3>
                        <p class="text-center text-gray-400 text-sm mb-8">One-time payment. Yours forever.</p>

                        <!-- Features -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-3.5">
                                <div class="w-8 h-8 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Unlimited video length</p>
                                    <p class="text-xs text-gray-400">No 60-second cap on conversions</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <div class="w-8 h-8 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">All templates unlocked</p>
                                    <p class="text-xs text-gray-400">Gradient Wash, Pattern Fill & future styles</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <div class="w-8 h-8 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Lifetime updates</p>
                                    <p class="text-xs text-gray-400">Every new feature, included automatically</p>
                                </div>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 mb-6"></div>

                        <!-- Price -->
                        <div class="text-center mb-6">
                            <div class="flex items-baseline justify-center gap-0.5">
                                <span class="text-sm font-medium text-gray-400 mr-1">USD</span>
                                <span class="text-4xl font-extrabold text-gray-900">$19</span>
                                <span class="text-xl font-bold text-gray-400">.99</span>
                            </div>
                            <p class="text-xs text-gray-300 mt-1.5">One-time &middot; No subscription &middot; No recurring fees</p>
                        </div>

                        <!-- CTA -->
                        <button
                            @click="startCheckout"
                            :disabled="checkoutLoading"
                            class="group w-full relative bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 disabled:opacity-50 text-white font-bold text-base py-4 rounded-xl transition-all duration-200 hover:shadow-xl hover:shadow-amber-500/20 active:scale-[0.98]"
                        >
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-t from-black/10 to-transparent pointer-events-none"></div>
                            <span v-if="checkoutLoading" class="relative inline-flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Redirecting to checkout...
                            </span>
                            <span v-else class="relative">Get Pro Access</span>
                        </button>

                        <!-- Trust -->
                        <div class="flex items-center justify-center gap-3 mt-5">
                            <div class="flex items-center gap-1.5 text-[11px] text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                Secured by Stripe
                            </div>
                            <span class="text-gray-300">&middot;</span>
                            <div class="text-[11px] text-gray-300">Instant activation</div>
                        </div>
                        <button @click="showUpgradeModal = false; showRestoreModal = true" class="block mx-auto mt-3 text-[11px] text-gray-300 hover:text-gray-500 transition underline underline-offset-2">
                            Already purchased? Restore here
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Restore Modal -->
        <Teleport to="body">
            <div v-if="showRestoreModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 text-gray-900" @click.self="showRestoreModal = false">
                <div class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>
                <div class="relative max-w-sm w-full rounded-2xl overflow-hidden shadow-2xl step-enter">
                    <div class="relative bg-white border border-gray-300 rounded-2xl p-8">

                        <button @click="showRestoreModal = false" class="absolute top-4 right-4 w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div class="flex justify-center mb-5">
                            <div class="w-12 h-12 rounded-2xl bg-teal/10 border border-teal/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-center text-gray-900 mb-1.5">Restore Pro</h3>
                        <p class="text-center text-gray-400 text-sm mb-6">Enter the email you used at checkout.</p>

                        <div v-if="restoreMessage" class="mb-4 bg-teal/[0.06] border border-teal/10 rounded-lg px-4 py-3 text-sm text-teal/80 text-center">
                            {{ restoreMessage }}
                        </div>

                        <input
                            v-model="restoreEmail"
                            type="email"
                            placeholder="you@example.com"
                            class="w-full bg-gray-100/80 border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-teal/30 focus:ring-1 focus:ring-teal/20 transition mb-4"
                            @keydown.enter="restorePurchase"
                        >

                        <button
                            @click="restorePurchase"
                            :disabled="restoreLoading || !restoreEmail"
                            class="w-full bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl transition-all duration-200 active:scale-[0.98]"
                        >
                            <span v-if="restoreLoading" class="inline-flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Checking...
                            </span>
                            <span v-else>Activate Pro</span>
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Footer -->
        <footer class="border-t border-gray-200 mt-8">
            <div class="max-w-6xl mx-auto px-6 py-12">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 mb-10">
                    <div>
                        <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Tool</h4>
                        <ul class="space-y-2">
                            <li><a href="/" class="text-xs text-gray-300 hover:text-gray-500 transition">Convert Video</a></li>
                            <li><a href="/portrait-to-landscape-video-converter" class="text-xs text-gray-300 hover:text-gray-500 transition">Portrait to Landscape</a></li>
                            <li><a href="/video-aspect-ratio-converter" class="text-xs text-gray-300 hover:text-gray-500 transition">Aspect Ratio Converter</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Convert</h4>
                        <ul class="space-y-2">
                            <li><a href="/convert-tiktok-to-youtube" class="text-xs text-gray-300 hover:text-gray-500 transition">TikTok to YouTube</a></li>
                            <li><a href="/convert-reels-to-landscape" class="text-xs text-gray-300 hover:text-gray-500 transition">Reels to Landscape</a></li>
                            <li><a href="/vertical-to-horizontal-video" class="text-xs text-gray-300 hover:text-gray-500 transition">Vertical to Horizontal</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Resources</h4>
                        <ul class="space-y-2">
                            <li><a href="/blog" class="text-xs text-gray-300 hover:text-gray-500 transition">Blog</a></li>
                            <li><a href="/blog/convert-tiktok-videos-to-youtube-format" class="text-xs text-gray-300 hover:text-gray-500 transition">TikTok to YouTube Guide</a></li>
                            <li><a href="/blog/convert-9-16-to-16-9-without-black-bars" class="text-xs text-gray-300 hover:text-gray-500 transition">Remove Black Bars</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Product</h4>
                        <ul class="space-y-2">
                            <li><a href="/" class="text-xs text-gray-300 hover:text-gray-500 transition">Free Converter</a></li>
                            <li><span @click="triggerUpgrade('footer')" class="text-xs text-gray-300 hover:text-gray-500 transition cursor-pointer">Upgrade to Pro</span></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-200 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-gradient-to-br from-pink-400/60 to-purple-400/60 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zM11 4a1 1 0 011-1h4a1 1 0 011 1v12a1 1 0 01-1 1h-4a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <span class="text-xs text-gray-300">&copy; 2026 ConvertPortrait.com</span>
                    </div>
                    <p class="text-[11px] text-gray-200 text-center leading-relaxed">
                        Your video never leaves your device in free mode. 100% browser-based processing via WebAssembly.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
