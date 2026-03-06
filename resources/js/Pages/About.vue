<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout
        title="About — ConvertPortrait"
        meta-description="ConvertPortrait is a free video converter built by Krager Labs. Turn portrait TikToks, Reels, and Shorts into landscape videos instantly."
    >
        <div class="max-w-3xl mx-auto px-6 pt-16 sm:pt-24 pb-20">
            <div class="text-center mb-14">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-gray-900 mb-4">
                    About ConvertPortrait
                </h1>
                <p class="text-[16.5px] text-gray-600 max-w-lg mx-auto leading-relaxed">
                    The simplest way to turn portrait videos into landscape format.
                </p>
            </div>

            <div class="prose prose-gray max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-h2:text-2xl prose-h2:text-gray-900 prose-h2:mt-12 prose-h2:mb-4 prose-p:text-gray-700 prose-p:leading-[1.8] prose-p:mb-5 prose-p:text-[16.5px] prose-strong:text-gray-900 prose-ul:text-gray-700 prose-li:mb-2 prose-li:text-[16.5px]">
                <h2>Why We Built This</h2>
                <p>We noticed creators spending way too much time trying to repurpose their vertical content for YouTube, presentations, and other widescreen platforms. Existing tools were either expensive, required installing desktop software, or uploaded your private videos to unknown servers.</p>
                <p>ConvertPortrait was built to solve this in the simplest way possible: drop your video, pick a background, download the result. The free version runs entirely in your browser — your video never touches a server.</p>

                <h2>How It Works</h2>
                <p>ConvertPortrait uses <strong>FFmpeg</strong>, the industry-standard video processing engine, compiled to <strong>WebAssembly</strong> so it runs right in your browser. Your portrait video is placed at full height in the center of a 16:9 landscape frame, with a styled background filling the sides. No cropping, no stretching, no quality loss.</p>
                <p>For creators who need faster processing, our Pro tier ($19.99/year) runs the same FFmpeg pipeline on dedicated AWS Lambda servers — converting videos up to 140x faster than browser mode.</p>

                <h2>Privacy First</h2>
                <p>In free mode, your video literally never leaves your device. There are no analytics, no cookies, no tracking pixels. We built ConvertPortrait the way we'd want a tool to treat our own content: with zero compromise on privacy.</p>

                <h2>Built by Krager Labs</h2>
                <p>ConvertPortrait is a product of <a href="https://kragerlabs.com" target="_blank" rel="noopener">Krager Labs LLC</a>. We build simple, focused tools that solve real problems for creators.</p>
                <p>Have questions or ideas? Reach us at <a href="mailto:scott@kragerlab.com">scott@kragerlab.com</a>.</p>
            </div>
        </div>
    </AppLayout>
</template>
