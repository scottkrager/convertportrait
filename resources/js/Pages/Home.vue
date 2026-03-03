<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// State
const step = ref('upload'); // upload | template | processing | done
const videoFile = ref(null);
const videoUrl = ref(null);
const videoDimensions = ref({ width: 0, height: 0 });
const selectedTemplate = ref('blurred');
const gradientVariant = ref('sunset');
const solidColor = ref('#000000');
const patternType = ref('dots');
const patternColor = ref('#7c3aed');
const progress = ref(0);
const progressMessage = ref('');
const outputUrl = ref(null);
const outputFilename = ref('');
const isDragging = ref(false);
const ffmpegLoaded = ref(false);
const ffmpegLoading = ref(false);
const errorMessage = ref('');

// FFmpeg instance
let ffmpeg = null;

const templates = [
    {
        id: 'blurred',
        name: 'Blurred Mirror',
        description: 'Your video blurred & stretched as the background',
        preview: 'blur',
    },
    {
        id: 'gradient',
        name: 'Gradient Wash',
        description: 'Beautiful color gradients on the sides',
        preview: 'gradient',
    },
    {
        id: 'solid',
        name: 'Solid + Border',
        description: 'Clean solid color with subtle shadow',
        preview: 'solid',
    },
    {
        id: 'pattern',
        name: 'Pattern Fill',
        description: 'Geometric patterns in custom colors',
        preview: 'pattern',
    },
];

const gradients = [
    { id: 'sunset', name: 'Sunset', from: '#f97316', to: '#9333ea' },
    { id: 'ocean', name: 'Ocean', from: '#06b6d4', to: '#1e40af' },
    { id: 'neon', name: 'Neon Night', from: '#ec4899', to: '#6366f1' },
    { id: 'forest', name: 'Forest', from: '#10b981', to: '#064e3b' },
    { id: 'mono', name: 'Monochrome', from: '#9ca3af', to: '#1f2937' },
];

const patterns = [
    { id: 'dots', name: 'Dots' },
    { id: 'lines', name: 'Lines' },
    { id: 'chevrons', name: 'Chevrons' },
];

const isPortrait = computed(() => {
    return videoDimensions.value.height > videoDimensions.value.width;
});

async function loadFFmpeg() {
    if (ffmpegLoaded.value || ffmpegLoading.value) return;
    ffmpegLoading.value = true;
    errorMessage.value = '';

    try {
        const { FFmpeg } = await import('@ffmpeg/ffmpeg');
        const { toBlobURL } = await import('@ffmpeg/util');

        ffmpeg = new FFmpeg();

        ffmpeg.on('progress', ({ progress: p, time }) => {
            progress.value = Math.round(p * 100);
        });

        ffmpeg.on('log', ({ message }) => {
            console.log('[ffmpeg]', message);
        });

        const baseURL = 'https://unpkg.com/@ffmpeg/core@0.12.6/dist/esm';
        await ffmpeg.load({
            coreURL: await toBlobURL(`${baseURL}/ffmpeg-core.js`, 'text/javascript'),
            wasmURL: await toBlobURL(`${baseURL}/ffmpeg-core.wasm`, 'application/wasm'),
        });

        ffmpegLoaded.value = true;
    } catch (err) {
        console.error('FFmpeg load error:', err);
        errorMessage.value = 'Failed to load video processor. Make sure your browser supports SharedArrayBuffer (try Chrome or Edge).';
    } finally {
        ffmpegLoading.value = false;
    }
}

function handleDragOver(e) {
    e.preventDefault();
    isDragging.value = true;
}

function handleDragLeave() {
    isDragging.value = false;
}

function handleDrop(e) {
    e.preventDefault();
    isDragging.value = false;
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('video/')) {
        handleFile(file);
    }
}

function handleFileInput(e) {
    const file = e.target.files[0];
    if (file) handleFile(file);
}

function handleFile(file) {
    videoFile.value = file;
    videoUrl.value = URL.createObjectURL(file);

    const video = document.createElement('video');
    video.preload = 'metadata';
    video.onloadedmetadata = () => {
        videoDimensions.value = {
            width: video.videoWidth,
            height: video.videoHeight,
        };
        URL.revokeObjectURL(video.src);
        step.value = 'template';
    };
    video.src = videoUrl.value;
}

function selectTemplate(id) {
    selectedTemplate.value = id;
}

async function startConversion() {
    if (!ffmpegLoaded.value) {
        await loadFFmpeg();
        if (!ffmpegLoaded.value) return;
    }

    step.value = 'processing';
    progress.value = 0;
    progressMessage.value = 'Reading video file...';
    errorMessage.value = '';

    try {
        const { fetchFile } = await import('@ffmpeg/util');
        const inputData = await fetchFile(videoFile.value);
        await ffmpeg.writeFile('input.mp4', inputData);

        progressMessage.value = 'Converting to landscape...';

        const filter = buildFilter();
        const outputName = 'output.mp4';

        await ffmpeg.exec([
            '-i', 'input.mp4',
            '-vf', filter,
            '-c:v', 'libx264',
            '-preset', 'fast',
            '-crf', '23',
            '-c:a', 'aac',
            '-b:a', '128k',
            '-movflags', '+faststart',
            outputName,
        ]);

        progressMessage.value = 'Finalizing...';

        const data = await ffmpeg.readFile(outputName);
        const blob = new Blob([data.buffer], { type: 'video/mp4' });
        outputUrl.value = URL.createObjectURL(blob);

        const baseName = videoFile.value.name.replace(/\.[^.]+$/, '');
        outputFilename.value = `${baseName}-landscape.mp4`;

        step.value = 'done';

        // Cleanup
        await ffmpeg.deleteFile('input.mp4');
        await ffmpeg.deleteFile(outputName);
    } catch (err) {
        console.error('Conversion error:', err);
        errorMessage.value = `Conversion failed: ${err.message}. Try a smaller file or different format.`;
        step.value = 'template';
    }
}

function buildFilter() {
    const w = videoDimensions.value.width;
    const h = videoDimensions.value.height;
    // Target 16:9 output
    const outW = Math.ceil((h * 16) / 9 / 2) * 2;
    const outH = Math.ceil(h / 2) * 2;

    switch (selectedTemplate.value) {
        case 'blurred':
            return `split[original][blur];[blur]scale=${outW}:${outH},boxblur=20:20[bg];[bg][original]overlay=(W-w)/2:(H-h)/2`;

        case 'gradient': {
            const g = gradients.find(g => g.id === gradientVariant.value) || gradients[0];
            const fromHex = g.from.replace('#', '');
            const toHex = g.to.replace('#', '');
            return `color=c=0x${fromHex}:s=${outW}x${outH}:d=1[left];color=c=0x${toHex}:s=${outW}x${outH}:d=1[right];[left][right]blend=all_mode=addition:all_opacity=0.5[bg];[bg][0:v]overlay=(W-w)/2:(H-h)/2:shortest=1`;
        }

        case 'solid': {
            const hex = solidColor.value.replace('#', '');
            return `color=c=0x${hex}:s=${outW}x${outH}:d=1[bg];[bg][0:v]overlay=(W-w)/2:(H-h)/2:shortest=1`;
        }

        case 'pattern': {
            // Use a color background for pattern - actual pattern drawn via drawbox
            const hex = patternColor.value.replace('#', '');
            if (patternType.value === 'lines') {
                let drawboxes = '';
                for (let i = 0; i < outW; i += 40) {
                    drawboxes += `,drawbox=x=${i}:y=0:w=2:h=${outH}:color=0x${hex}@0.3:t=fill`;
                }
                return `color=c=0x111111:s=${outW}x${outH}:d=1${drawboxes}[bg];[bg][0:v]overlay=(W-w)/2:(H-h)/2:shortest=1`;
            } else if (patternType.value === 'chevrons') {
                let drawboxes = '';
                for (let i = 0; i < outW; i += 60) {
                    drawboxes += `,drawbox=x=${i}:y=0:w=30:h=${outH}:color=0x${hex}@0.15:t=fill`;
                }
                return `color=c=0x111111:s=${outW}x${outH}:d=1${drawboxes}[bg];[bg][0:v]overlay=(W-w)/2:(H-h)/2:shortest=1`;
            } else {
                // dots - approximate with small boxes
                let drawboxes = '';
                for (let y = 0; y < outH; y += 30) {
                    for (let x = 0; x < outW; x += 30) {
                        drawboxes += `,drawbox=x=${x}:y=${y}:w=4:h=4:color=0x${hex}@0.4:t=fill`;
                    }
                }
                // Too many drawboxes can be slow, limit pattern
                const limitedBoxes = drawboxes.substring(0, 5000);
                return `color=c=0x111111:s=${outW}x${outH}:d=1${limitedBoxes}[bg];[bg][0:v]overlay=(W-w)/2:(H-h)/2:shortest=1`;
            }
        }

        default:
            return `split[original][blur];[blur]scale=${outW}:${outH},boxblur=20:20[bg];[bg][original]overlay=(W-w)/2:(H-h)/2`;
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
    step.value = 'upload';
    progress.value = 0;
    errorMessage.value = '';
}

onMounted(() => {
    loadFFmpeg();
});

onUnmounted(() => {
    if (videoUrl.value) URL.revokeObjectURL(videoUrl.value);
    if (outputUrl.value) URL.revokeObjectURL(outputUrl.value);
});
</script>

<template>
    <div class="min-h-screen bg-midnight text-white">
        <!-- Header -->
        <header class="border-b border-white/5">
            <div class="max-w-5xl mx-auto px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-accent rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight">ConvertPortrait</span>
                </div>
                <div class="text-sm text-white/40">
                    100% browser-based &middot; No uploads &middot; Free
                </div>
            </div>
        </header>

        <!-- Main content -->
        <main class="max-w-5xl mx-auto px-6 py-12">

            <!-- STEP: Upload -->
            <div v-if="step === 'upload'" class="max-w-2xl mx-auto">
                <div class="text-center mb-10">
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">
                        Portrait to Landscape<br>
                        <span class="text-accent-light">in seconds</span>
                    </h1>
                    <p class="text-lg text-white/50 max-w-lg mx-auto">
                        Convert your TikToks, Reels, and Shorts to landscape format.
                        Everything runs in your browser — your video never leaves your device.
                    </p>
                </div>

                <div
                    @dragover="handleDragOver"
                    @dragleave="handleDragLeave"
                    @drop="handleDrop"
                    :class="[
                        'relative border-2 border-dashed rounded-2xl p-16 text-center cursor-pointer transition-all duration-200',
                        isDragging
                            ? 'border-accent bg-accent/10 scale-[1.02]'
                            : 'border-white/10 hover:border-white/25 bg-surface'
                    ]"
                    @click="$refs.fileInput.click()"
                >
                    <input
                        ref="fileInput"
                        type="file"
                        accept="video/*"
                        class="hidden"
                        @change="handleFileInput"
                    >
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-white/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <p class="text-lg font-semibold mb-1">Drop your video here</p>
                    <p class="text-white/40 text-sm">or click to browse &middot; MP4, MOV, WebM</p>
                </div>

                <div v-if="ffmpegLoading" class="mt-6 text-center text-white/40 text-sm">
                    <div class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Loading video processor...
                    </div>
                </div>

                <div v-if="errorMessage" class="mt-6 bg-red-500/10 border border-red-500/20 rounded-xl p-4 text-red-400 text-sm text-center">
                    {{ errorMessage }}
                </div>
            </div>

            <!-- STEP: Template Selection -->
            <div v-if="step === 'template'" class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold">Choose a template</h2>
                        <p class="text-white/40 text-sm mt-1">
                            {{ videoDimensions.width }}x{{ videoDimensions.height }}
                            <span v-if="isPortrait" class="text-accent-light ml-2">Portrait detected</span>
                            <span v-else class="text-warning ml-2">Already landscape — will still add side panels</span>
                        </p>
                    </div>
                    <button @click="startOver" class="text-sm text-white/40 hover:text-white transition">
                        &larr; Change video
                    </button>
                </div>

                <!-- Video preview -->
                <div class="mb-8 bg-surface rounded-xl p-4">
                    <video
                        :src="videoUrl"
                        class="max-h-48 mx-auto rounded-lg"
                        controls
                        muted
                    ></video>
                </div>

                <!-- Template grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                    <button
                        v-for="t in templates"
                        :key="t.id"
                        @click="selectTemplate(t.id)"
                        :class="[
                            'relative rounded-xl p-4 text-left transition-all duration-200 border-2',
                            selectedTemplate === t.id
                                ? 'border-accent bg-accent/10'
                                : 'border-white/5 bg-surface hover:border-white/15'
                        ]"
                    >
                        <!-- Preview thumbnail -->
                        <div class="h-20 rounded-lg mb-3 overflow-hidden flex items-center justify-center" :class="{
                            'bg-gradient-to-r from-gray-600/40 to-gray-800/40': t.id === 'blurred',
                            'bg-gradient-to-r from-orange-500 to-purple-600': t.id === 'gradient',
                            'bg-black': t.id === 'solid',
                            'bg-gray-900': t.id === 'pattern',
                        }">
                            <div class="w-6 h-10 bg-white/20 rounded-sm border border-white/30"></div>
                        </div>
                        <p class="font-semibold text-sm">{{ t.name }}</p>
                        <p class="text-xs text-white/40 mt-0.5">{{ t.description }}</p>
                        <div v-if="selectedTemplate === t.id" class="absolute top-2 right-2 w-5 h-5 bg-accent rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </button>
                </div>

                <!-- Template options -->
                <div v-if="selectedTemplate === 'gradient'" class="bg-surface rounded-xl p-6 mb-8">
                    <p class="text-sm font-semibold mb-3">Gradient Style</p>
                    <div class="flex flex-wrap gap-3">
                        <button
                            v-for="g in gradients"
                            :key="g.id"
                            @click="gradientVariant = g.id"
                            :class="[
                                'flex items-center gap-2 px-4 py-2 rounded-lg border-2 transition',
                                gradientVariant === g.id
                                    ? 'border-accent'
                                    : 'border-white/5 hover:border-white/15'
                            ]"
                        >
                            <div
                                class="w-6 h-6 rounded-full"
                                :style="`background: linear-gradient(135deg, ${g.from}, ${g.to})`"
                            ></div>
                            <span class="text-sm">{{ g.name }}</span>
                        </button>
                    </div>
                </div>

                <div v-if="selectedTemplate === 'solid'" class="bg-surface rounded-xl p-6 mb-8">
                    <p class="text-sm font-semibold mb-3">Background Color</p>
                    <div class="flex items-center gap-4">
                        <input type="color" v-model="solidColor" class="w-10 h-10 rounded-lg cursor-pointer border-0 bg-transparent">
                        <div class="flex gap-2">
                            <button
                                v-for="c in ['#000000', '#ffffff', '#1a1a2e', '#0f172a', '#1c1917']"
                                :key="c"
                                @click="solidColor = c"
                                :class="[
                                    'w-8 h-8 rounded-lg border-2 transition',
                                    solidColor === c ? 'border-accent' : 'border-white/10'
                                ]"
                                :style="`background: ${c}`"
                            ></button>
                        </div>
                    </div>
                </div>

                <div v-if="selectedTemplate === 'pattern'" class="bg-surface rounded-xl p-6 mb-8">
                    <p class="text-sm font-semibold mb-3">Pattern Style</p>
                    <div class="flex flex-wrap gap-3 mb-4">
                        <button
                            v-for="p in patterns"
                            :key="p.id"
                            @click="patternType = p.id"
                            :class="[
                                'px-4 py-2 rounded-lg border-2 text-sm transition',
                                patternType === p.id
                                    ? 'border-accent bg-accent/10'
                                    : 'border-white/5 hover:border-white/15'
                            ]"
                        >
                            {{ p.name }}
                        </button>
                    </div>
                    <p class="text-sm font-semibold mb-2">Pattern Color</p>
                    <input type="color" v-model="patternColor" class="w-10 h-10 rounded-lg cursor-pointer border-0 bg-transparent">
                </div>

                <div v-if="errorMessage" class="mb-6 bg-red-500/10 border border-red-500/20 rounded-xl p-4 text-red-400 text-sm text-center">
                    {{ errorMessage }}
                </div>

                <!-- Convert button -->
                <button
                    @click="startConversion"
                    :disabled="ffmpegLoading"
                    class="w-full bg-accent hover:bg-accent/80 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-lg py-4 rounded-xl transition-all duration-200 hover:scale-[1.01] active:scale-[0.99]"
                >
                    <span v-if="ffmpegLoading" class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Loading processor...
                    </span>
                    <span v-else>Convert to Landscape</span>
                </button>
            </div>

            <!-- STEP: Processing -->
            <div v-if="step === 'processing'" class="max-w-lg mx-auto text-center">
                <div class="mb-8">
                    <div class="w-20 h-20 mx-auto mb-6 relative">
                        <svg class="animate-spin w-20 h-20 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Converting your video...</h2>
                    <p class="text-white/40">{{ progressMessage }}</p>
                </div>
                <div class="bg-surface rounded-full h-3 overflow-hidden">
                    <div
                        class="h-full bg-accent transition-all duration-300 rounded-full"
                        :style="`width: ${Math.max(progress, 2)}%`"
                    ></div>
                </div>
                <p class="mt-3 text-sm text-white/40">{{ progress }}% complete</p>
                <p class="mt-6 text-xs text-white/30">
                    Processing happens entirely in your browser. Larger files take longer.
                </p>
            </div>

            <!-- STEP: Done -->
            <div v-if="step === 'done'" class="max-w-3xl mx-auto text-center">
                <div class="mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 bg-success/20 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Your video is ready!</h2>
                    <p class="text-white/40">{{ outputFilename }}</p>
                </div>

                <div class="bg-surface rounded-xl p-4 mb-8">
                    <video
                        :src="outputUrl"
                        class="max-h-80 mx-auto rounded-lg"
                        controls
                    ></video>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button
                        @click="downloadOutput"
                        class="bg-accent hover:bg-accent/80 text-white font-bold px-8 py-4 rounded-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]"
                    >
                        Download Video
                    </button>
                    <button
                        @click="startOver"
                        class="bg-surface hover:bg-surface-light text-white font-semibold px-8 py-4 rounded-xl border border-white/5 transition"
                    >
                        Convert Another
                    </button>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="border-t border-white/5 mt-20">
            <div class="max-w-5xl mx-auto px-6 py-8 text-center text-white/25 text-sm">
                <p>ConvertPortrait.com &mdash; Free portrait-to-landscape video converter</p>
                <p class="mt-1">Your videos never leave your browser. 100% private.</p>
            </div>
        </footer>
    </div>
</template>
