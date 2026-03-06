<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: { type: String, default: '' },
    metaDescription: { type: String, default: '' },
    canonical: { type: String, default: '' },
});
</script>

<template>
    <Head>
        <title>{{ title }}</title>
        <meta v-if="metaDescription" name="description" :content="metaDescription" />
        <link v-if="canonical" rel="canonical" :href="canonical" />
        <meta v-if="title" property="og:title" :content="title" />
        <meta v-if="metaDescription" property="og:description" :content="metaDescription" />
        <meta v-if="canonical" property="og:url" :content="canonical" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="ConvertPortrait" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta v-if="title" name="twitter:title" :content="title" />
        <meta v-if="metaDescription" name="twitter:description" :content="metaDescription" />
    </Head>

    <div class="min-h-screen bg-midnight text-white noise-bg mesh-gradient flex flex-col">
        <!-- Header -->
        <header class="border-b border-white/[0.04]">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="/" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-midnight" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zM11 4a1 1 0 011-1h4a1 1 0 011 1v12a1 1 0 01-1 1h-4a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <span class="text-[15px] font-bold tracking-tight text-white/90">ConvertPortrait</span>
                    </a>
                    <nav class="hidden sm:flex items-center gap-4 text-sm text-white/40">
                        <a href="/" class="hover:text-white/70 transition">Tool</a>
                        <a href="/blog" class="hover:text-white/70 transition">Blog</a>
                    </nav>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/" class="text-xs font-semibold text-midnight bg-gradient-to-r from-pink-400 to-purple-400 px-4 py-2 rounded-full hover:opacity-90 transition">
                        Convert Now
                    </a>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-white/[0.04] mt-20">
            <div class="max-w-6xl mx-auto px-6 py-12">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 mb-10">
                    <div>
                        <h4 class="text-xs font-bold text-white/50 uppercase tracking-wider mb-4">Tool</h4>
                        <ul class="space-y-2.5">
                            <li><a href="/" class="text-sm text-white/30 hover:text-white/60 transition">Convert Video</a></li>
                            <li><a href="/portrait-to-landscape-video-converter" class="text-sm text-white/30 hover:text-white/60 transition">Portrait to Landscape</a></li>
                            <li><a href="/video-aspect-ratio-converter" class="text-sm text-white/30 hover:text-white/60 transition">Aspect Ratio Converter</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white/50 uppercase tracking-wider mb-4">Convert</h4>
                        <ul class="space-y-2.5">
                            <li><a href="/convert-tiktok-to-youtube" class="text-sm text-white/30 hover:text-white/60 transition">TikTok to YouTube</a></li>
                            <li><a href="/convert-reels-to-landscape" class="text-sm text-white/30 hover:text-white/60 transition">Reels to Landscape</a></li>
                            <li><a href="/vertical-to-horizontal-video" class="text-sm text-white/30 hover:text-white/60 transition">Vertical to Horizontal</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white/50 uppercase tracking-wider mb-4">Resources</h4>
                        <ul class="space-y-2.5">
                            <li><a href="/blog" class="text-sm text-white/30 hover:text-white/60 transition">Blog</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white/50 uppercase tracking-wider mb-4">Product</h4>
                        <ul class="space-y-2.5">
                            <li><a href="/" class="text-sm text-white/30 hover:text-white/60 transition">Free Converter</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-white/[0.04] pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-white/20">&copy; {{ new Date().getFullYear() }} ConvertPortrait. All rights reserved.</p>
                    <p class="text-xs text-white/20">Your video never leaves your device in free mode.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
