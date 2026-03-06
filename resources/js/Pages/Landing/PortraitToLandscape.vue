<script setup>
import { Head, Link } from '@inertiajs/vue3';

const faqs = [
    {
        q: 'What does a portrait to landscape converter do?',
        a: 'A portrait to landscape converter takes a vertical video (taller than wide, typically 9:16 aspect ratio) and places it inside a horizontal 16:9 frame with a styled background filling the empty side areas. This produces a widescreen video suitable for YouTube, TV displays, presentations, and any platform that expects landscape content.',
    },
    {
        q: 'Does the converter crop my video?',
        a: 'No. ConvertPortrait never crops your original video. Your portrait footage is displayed at full height in the center of the landscape frame. The empty areas on the left and right are filled with your chosen background style — blurred mirror, solid color, gradient, or pattern.',
    },
    {
        q: 'Can I convert without uploading my video to a server?',
        a: 'Yes. ConvertPortrait\'s free mode processes your video entirely in your browser using WebAssembly (FFmpeg.wasm). Your video file never leaves your device. Pro users can optionally use server-side processing for much faster conversion — the file is deleted automatically after processing.',
    },
    {
        q: 'What background styles are available?',
        a: 'Free users get Blurred Mirror (your video blurred and scaled as background) and Solid Color (pick any hex color). Pro users unlock Gradient Wash (5 presets: Sunset, Ocean, Neon, Forest, Mono) and Pattern Fill (dots, lines, or chevrons with custom colors).',
    },
    {
        q: 'What output resolution does the converter produce?',
        a: 'All converted videos output at 1920x1080 pixels (Full HD 16:9). This is the standard resolution for YouTube, Vimeo, and most widescreen displays. The original video quality is preserved without re-encoding the source footage.',
    },
];

const jsonLdWebApp = JSON.stringify({
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "ConvertPortrait — Portrait to Landscape Video Converter",
    "url": "https://convertportrait.com/portrait-to-landscape-video-converter",
    "description": "Convert portrait videos to landscape format with professional backgrounds. Free online tool, no upload required.",
    "applicationCategory": "MultimediaApplication",
    "operatingSystem": "Any",
    "offers": [
        { "@type": "Offer", "price": "0", "priceCurrency": "USD", "description": "Free browser-based conversion" },
        { "@type": "Offer", "price": "19.99", "priceCurrency": "USD", "description": "Pro — fast server-side conversion, lifetime access" }
    ],
});

const jsonLdFaq = JSON.stringify({
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": faqs.map(f => ({
        "@type": "Question",
        "name": f.q,
        "acceptedAnswer": { "@type": "Answer", "text": f.a },
    })),
});
</script>

<template>
    <Head>
        <title>Portrait to Landscape Video Converter — Free, No Upload</title>
        <meta name="description" content="Convert portrait video to landscape format online for free. No upload required — runs in your browser. Choose from blurred, gradient, solid, or pattern backgrounds.">
        <link rel="canonical" href="https://convertportrait.com/portrait-to-landscape-video-converter">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://convertportrait.com/portrait-to-landscape-video-converter">
        <meta property="og:title" content="Portrait to Landscape Video Converter — Free, No Upload">
        <meta property="og:description" content="Convert portrait video to landscape format online for free. No upload required — runs in your browser.">
        <meta property="og:site_name" content="ConvertPortrait">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Portrait to Landscape Video Converter — Free, No Upload">
        <meta name="twitter:description" content="Convert portrait video to landscape format online for free. No upload required — runs in your browser.">
        <component is="script" type="application/ld+json" v-html="jsonLdWebApp" />
        <component is="script" type="application/ld+json" v-html="jsonLdFaq" />
    </Head>

    <div class="min-h-screen bg-midnight text-gray-900 noise-bg mesh-gradient">
        <!-- Header -->
        <header class="border-b border-gray-200">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-pink-400 to-purple-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zM11 4a1 1 0 011-1h4a1 1 0 011 1v12a1 1 0 01-1 1h-4a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                    <span class="text-[15px] font-bold tracking-tight text-gray-900">ConvertPortrait</span>
                </Link>
                <Link href="/" class="text-xs font-semibold text-teal bg-teal/[0.08] hover:bg-teal/[0.14] px-4 py-2 rounded-full border border-teal/15 transition-all">
                    Open Tool
                </Link>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-6">
            <!-- Hero -->
            <section class="pt-16 sm:pt-24 pb-16 text-center step-enter">
                <div class="inline-flex items-center gap-1.5 text-xs text-teal bg-teal/[0.08] px-3 py-1 rounded-full mb-6 border border-teal/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    100% private — video never leaves your device
                </div>

                <h1 class="text-4xl sm:text-[3.25rem] font-extrabold tracking-tight leading-[1.1] mb-5">
                    Portrait to Landscape
                    <br>
                    <span class="font-editorial font-normal text-teal">Video Converter</span>
                </h1>

                <p class="text-[15px] sm:text-base text-gray-400 max-w-xl mx-auto leading-relaxed mb-8">
                    Transform any vertical video into a professional widescreen format. Your portrait footage stays centered
                    while stylish backgrounds fill the sides — no cropping, no black bars, no quality loss.
                    Free and completely private.
                </p>

                <Link href="/" class="inline-flex items-center gap-2 font-bold text-base px-8 py-4 rounded-xl bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]">
                    Convert Your Video Now — Free
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </Link>
            </section>

            <!-- Visual Diagram: Portrait to Landscape -->
            <section class="pb-16">
                <div class="bg-surface/60 rounded-2xl border border-gray-200 p-8 sm:p-10">
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-8">How the Conversion Works</h2>
                    <div class="flex items-center justify-center gap-4 sm:gap-8">
                        <!-- Portrait -->
                        <div class="text-center">
                            <div class="w-16 sm:w-20 h-28 sm:h-36 mx-auto rounded-lg border-2 border-gray-300 bg-gray-100/70 flex items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-b from-teal/20 to-emerald/10"></div>
                                <span class="relative text-[10px] text-gray-400 font-bold">9:16</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-2 font-semibold">Portrait</p>
                        </div>

                        <!-- Arrow -->
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-teal/40" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-[9px] text-teal/40 mt-1">Convert</span>
                        </div>

                        <!-- Landscape result -->
                        <div class="text-center">
                            <div class="w-36 sm:w-48 h-20 sm:h-28 mx-auto rounded-lg border-2 border-teal/20 bg-gray-100/60 flex items-center justify-center relative overflow-hidden">
                                <!-- Blurred sides -->
                                <div class="absolute inset-y-0 left-0 w-1/4 bg-gradient-to-r from-teal/10 to-teal/5 blur-sm"></div>
                                <div class="absolute inset-y-0 right-0 w-1/4 bg-gradient-to-l from-teal/10 to-teal/5 blur-sm"></div>
                                <!-- Center video -->
                                <div class="relative w-8 sm:w-11 h-14 sm:h-20 rounded border border-gray-300 bg-gradient-to-b from-teal/20 to-emerald/10 flex items-center justify-center">
                                    <span class="text-[8px] text-gray-400 font-bold">Video</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-teal/60 mt-2 font-semibold">Landscape (16:9)</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-300 text-center mt-6 max-w-lg mx-auto leading-relaxed">
                        Your original portrait video is placed at full height in the center of a 1920x1080 landscape frame.
                        The side panels are filled with your chosen background style — blurred mirror, solid color, gradient, or pattern.
                    </p>
                </div>
            </section>

            <!-- Background Templates Showcase -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-3">Background Templates</h2>
                <p class="text-sm text-gray-400 text-center mb-8 max-w-md mx-auto">Choose the perfect background to complement your video content.</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-4 text-center">
                        <div class="w-full aspect-video rounded-lg mb-3 overflow-hidden relative border border-gray-200">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/5 via-transparent to-white/5 blur-md"></div>
                            <div class="absolute inset-y-1 left-1/2 -translate-x-1/2 w-1/3 bg-white/10 rounded"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500">Blurred Mirror</p>
                        <p class="text-[10px] text-teal/60 mt-0.5">Free</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-4 text-center">
                        <div class="w-full aspect-video rounded-lg mb-3 overflow-hidden relative border border-gray-200 bg-slate-800">
                            <div class="absolute inset-y-1 left-1/2 -translate-x-1/2 w-1/3 bg-white/10 rounded"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500">Solid Color</p>
                        <p class="text-[10px] text-teal/60 mt-0.5">Free</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-amber-400/10 p-4 text-center">
                        <div class="w-full aspect-video rounded-lg mb-3 overflow-hidden relative border border-gray-200">
                            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/30 to-purple-500/20"></div>
                            <div class="absolute inset-y-1 left-1/2 -translate-x-1/2 w-1/3 bg-white/10 rounded"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500">Gradient Wash</p>
                        <p class="text-[10px] text-amber-400/60 mt-0.5">Pro</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-amber-400/10 p-4 text-center">
                        <div class="w-full aspect-video rounded-lg mb-3 overflow-hidden relative border border-gray-200">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 8px 8px;"></div>
                            <div class="absolute inset-y-1 left-1/2 -translate-x-1/2 w-1/3 bg-white/10 rounded"></div>
                        </div>
                        <p class="text-xs font-semibold text-gray-500">Pattern Fill</p>
                        <p class="text-[10px] text-amber-400/60 mt-0.5">Pro</p>
                    </div>
                </div>
            </section>

            <!-- Why ConvertPortrait -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-10">Why ConvertPortrait</h2>
                <div class="grid sm:grid-cols-3 gap-6">
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-2">Privacy First</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">Your video is processed locally in your browser using WebAssembly. No server upload, no data collection, no account required. Your content stays yours.</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-2">No Software to Install</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">Works entirely in your web browser on any device. No downloads, no plugins, no app installations. Just open the page and convert.</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-2">Professional Results</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">Four background styles with customizable colors and patterns. Output at Full HD 1920x1080 — ready for YouTube, presentations, TV screens, and more.</p>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="pb-16 text-center">
                <div class="bg-gradient-to-br from-teal/[0.06] to-emerald/[0.03] rounded-2xl border border-teal/10 p-10">
                    <h2 class="text-2xl font-bold tracking-tight mb-3">Start Converting Now</h2>
                    <p class="text-sm text-gray-400 mb-6 max-w-md mx-auto">No signup needed. Drop your portrait video and get a landscape version in minutes.</p>
                    <Link href="/" class="inline-flex items-center gap-2 font-bold px-8 py-4 rounded-xl bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]">
                        Convert Your Video Now — Free
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </div>
            </section>

            <!-- FAQ -->
            <section class="pb-20">
                <h2 class="text-lg font-bold tracking-tight text-center mb-8 text-gray-600">Frequently Asked Questions</h2>
                <div class="space-y-3 max-w-xl mx-auto">
                    <details v-for="(faq, i) in faqs" :key="i" class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                        <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-sm font-semibold text-gray-600 hover:text-gray-700 transition">
                            {{ faq.q }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 transition-transform group-open:rotate-180 shrink-0 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </summary>
                        <p class="px-5 pb-4 text-xs text-gray-400 leading-relaxed">{{ faq.a }}</p>
                    </details>
                </div>
            </section>

            <!-- Internal Links -->
            <section class="pb-16">
                <h2 class="text-sm font-bold text-gray-300 text-center mb-4 uppercase tracking-wider">Related Tools</h2>
                <div class="flex flex-wrap justify-center gap-3">
                    <Link href="/convert-tiktok-to-youtube" class="text-xs text-gray-400 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">TikTok to YouTube Converter</Link>
                    <Link href="/vertical-to-horizontal-video" class="text-xs text-gray-400 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Vertical to Horizontal Video</Link>
                    <Link href="/convert-reels-to-landscape" class="text-xs text-gray-400 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Convert Reels to Landscape</Link>
                    <Link href="/video-aspect-ratio-converter" class="text-xs text-gray-400 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Video Aspect Ratio Converter</Link>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-200 mt-8">
            <div class="max-w-6xl mx-auto px-6 py-10">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded bg-gradient-to-br from-teal/60 to-emerald/60 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zM11 4a1 1 0 011-1h4a1 1 0 011 1v12a1 1 0 01-1 1h-4a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <span class="text-xs text-gray-300">ConvertPortrait.com</span>
                    </div>
                    <p class="text-[11px] text-gray-200 text-center leading-relaxed">
                        Free online portrait to landscape video converter. Browser-based processing — your video never leaves your device.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
