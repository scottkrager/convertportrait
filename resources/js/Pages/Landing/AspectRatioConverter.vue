<script setup>
import { Head, Link } from '@inertiajs/vue3';

const faqs = [
    {
        q: 'What aspect ratios can I convert between?',
        a: 'ConvertPortrait specializes in converting 9:16 portrait video to 16:9 landscape. This covers TikTok, Instagram Reels, YouTube Shorts, Snapchat, and any other vertical video format. The output is always 1920x1080 Full HD landscape.',
    },
    {
        q: 'What is the difference between 9:16 and 16:9?',
        a: '9:16 is the portrait (vertical) aspect ratio used by TikTok, Instagram Reels, and YouTube Shorts — taller than wide, optimized for phone screens held upright. 16:9 is the landscape (horizontal) aspect ratio used by YouTube, TV, monitors, and presentations — wider than tall, optimized for widescreen displays.',
    },
    {
        q: 'Does changing the aspect ratio reduce video quality?',
        a: 'No. ConvertPortrait does not crop, stretch, or re-encode your original video. Your footage is placed at its native resolution inside the new frame, and the empty areas are filled with a styled background. The output is always 1920x1080 Full HD.',
    },
    {
        q: 'Can I change the aspect ratio of a video that is already landscape?',
        a: 'ConvertPortrait is designed primarily for portrait-to-landscape conversion. If you upload a landscape video, it will still process it and add side panels if the video is not exactly 16:9. However, for best results, the tool works with vertical or near-vertical videos.',
    },
    {
        q: 'Is there a file size limit?',
        a: 'In free browser mode, files up to 200MB are supported. Pro users with server-side processing can upload files up to 500MB. Supported formats include MP4, MOV, WebM, and AVI.',
    },
];

const conversions = [
    { from: '9:16', to: '16:9', label: 'Portrait to Landscape', desc: 'TikTok, Reels, Shorts to YouTube, TV', primary: true },
    { from: '4:5', to: '16:9', label: 'Instagram Post to Landscape', desc: 'Instagram feed videos to widescreen', primary: false },
    { from: '1:1', to: '16:9', label: 'Square to Landscape', desc: 'Square videos to widescreen format', primary: false },
    { from: '3:4', to: '16:9', label: 'Classic Portrait to Landscape', desc: 'Older portrait formats to widescreen', primary: false },
];

const jsonLdWebApp = JSON.stringify({
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "ConvertPortrait — Video Aspect Ratio Converter",
    "url": "https://convertportrait.com/video-aspect-ratio-converter",
    "description": "Change video aspect ratio from 9:16 to 16:9 online for free. Convert portrait to landscape with professional backgrounds.",
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
        <title>Video Aspect Ratio Converter — 9:16 to 16:9 Free</title>
        <meta name="description" content="Change video aspect ratio from 9:16 portrait to 16:9 landscape online for free. No upload, no software — works in your browser with professional backgrounds.">
        <link rel="canonical" href="https://convertportrait.com/video-aspect-ratio-converter">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://convertportrait.com/video-aspect-ratio-converter">
        <meta property="og:title" content="Video Aspect Ratio Converter — 9:16 to 16:9 Free">
        <meta property="og:description" content="Change video aspect ratio from 9:16 portrait to 16:9 landscape online for free. No upload, no software.">
        <meta property="og:site_name" content="ConvertPortrait">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Video Aspect Ratio Converter — 9:16 to 16:9 Free">
        <meta name="twitter:description" content="Change video aspect ratio from 9:16 portrait to 16:9 landscape online for free. No upload, no software.">
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
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Free online tool — no software to install
                </div>

                <h1 class="text-4xl sm:text-[3.25rem] font-extrabold tracking-tight leading-[1.1] mb-5">
                    Video Aspect Ratio
                    <br>
                    <span class="font-editorial font-normal text-teal">Converter</span>
                </h1>

                <p class="text-[15px] sm:text-base text-gray-600 max-w-xl mx-auto leading-relaxed mb-8">
                    Change your video's aspect ratio from 9:16 portrait to 16:9 landscape — the standard for YouTube,
                    TV, presentations, and widescreen displays. No cropping or stretching. Your video stays centered
                    with professional backgrounds filling the sides. Free and private.
                </p>

                <Link href="/" class="inline-flex items-center gap-2 font-bold text-base px-8 py-4 rounded-xl bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]">
                    Convert Your Video Now — Free
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </Link>
            </section>

            <!-- Supported Conversions -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-3">Supported Aspect Ratio Conversions</h2>
                <p class="text-sm text-gray-600 text-center mb-10 max-w-md mx-auto">Upload any portrait or square video and ConvertPortrait will convert it to 16:9 landscape with a professional background.</p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div v-for="conv in conversions" :key="conv.label" :class="['rounded-xl border p-5', conv.primary ? 'bg-teal/[0.04] border-teal/15' : 'bg-gray-100/60 border-gray-200']">
                        <div class="flex items-center gap-3 mb-3">
                            <div :class="['rounded-lg border px-3 py-1.5 text-xs font-bold', conv.primary ? 'border-teal/20 text-teal' : 'border-gray-300 text-gray-600']">
                                {{ conv.from }}
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" :class="['w-4 h-4', conv.primary ? 'text-teal/40' : 'text-gray-500']" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            <div :class="['rounded-lg border px-3 py-1.5 text-xs font-bold', conv.primary ? 'border-teal/20 text-teal' : 'border-gray-300 text-gray-600']">
                                {{ conv.to }}
                            </div>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-1">{{ conv.label }}</h3>
                        <p class="text-xs text-gray-600">{{ conv.desc }}</p>
                    </div>
                </div>
            </section>

            <!-- What is Aspect Ratio -->
            <section class="pb-16">
                <div class="bg-surface/60 rounded-2xl border border-gray-200 p-8 sm:p-10">
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight mb-4">Understanding Video Aspect Ratios</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        An aspect ratio describes the proportional relationship between a video's width and height. It is expressed as two numbers separated by a colon. The most common aspect ratios in video are:
                    </p>
                    <div class="grid sm:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-4 text-center">
                            <div class="w-10 h-16 mx-auto mb-2 rounded border-2 border-gray-300 bg-gray-100/70 flex items-center justify-center">
                                <span class="text-[9px] text-gray-600 font-bold">9:16</span>
                            </div>
                            <p class="text-xs font-semibold text-gray-500">Portrait</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">TikTok, Reels, Shorts</p>
                        </div>
                        <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-2 rounded border-2 border-gray-300 bg-gray-100/70 flex items-center justify-center">
                                <span class="text-[9px] text-gray-600 font-bold">1:1</span>
                            </div>
                            <p class="text-xs font-semibold text-gray-500">Square</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Instagram posts, old video</p>
                        </div>
                        <div class="bg-gray-100/60 rounded-xl border border-teal/10 p-4 text-center">
                            <div class="w-16 h-10 mx-auto mb-2 rounded border-2 border-teal/20 bg-teal/[0.04] flex items-center justify-center">
                                <span class="text-[9px] text-teal/50 font-bold">16:9</span>
                            </div>
                            <p class="text-xs font-semibold text-gray-500">Landscape</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">YouTube, TV, presentations</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        When you change a video's aspect ratio without cropping or stretching, the empty areas need to be filled.
                        ConvertPortrait fills those areas with your choice of professional backgrounds — blurred mirror, solid color,
                        gradient wash, or geometric patterns — instead of leaving ugly black bars.
                    </p>
                </div>
            </section>

            <!-- Benefits -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-10">Why Use ConvertPortrait</h2>
                <div class="grid sm:grid-cols-3 gap-6">
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-2">No Upload Required</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Your video is processed in your browser. No data leaves your device in free mode. No account, no tracking, complete privacy.</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-2">No Quality Loss</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Your original video is placed at its native resolution inside the landscape frame. No cropping, no stretching, no re-encoding artifacts.</p>
                    </div>
                    <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-6">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-600 mb-2">Professional Backgrounds</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Four background styles — blurred mirror, solid color, gradient wash, and pattern fill — replace ugly black bars with clean, professional designs.</p>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="pb-16 text-center">
                <div class="bg-gradient-to-br from-teal/[0.06] to-emerald/[0.03] rounded-2xl border border-teal/10 p-10">
                    <h2 class="text-2xl font-bold tracking-tight mb-3">Change Your Video's Aspect Ratio</h2>
                    <p class="text-sm text-gray-600 mb-6 max-w-md mx-auto">Convert portrait to landscape in three clicks. No software, no account, no watermark. Completely free.</p>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-transform group-open:rotate-180 shrink-0 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </summary>
                        <p class="px-5 pb-4 text-xs text-gray-600 leading-relaxed">{{ faq.a }}</p>
                    </details>
                </div>
            </section>

            <!-- Internal Links -->
            <section class="pb-16">
                <h2 class="text-sm font-bold text-gray-500 text-center mb-4 uppercase tracking-wider">Related Tools</h2>
                <div class="flex flex-wrap justify-center gap-3">
                    <Link href="/convert-tiktok-to-youtube" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">TikTok to YouTube Converter</Link>
                    <Link href="/portrait-to-landscape-video-converter" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Portrait to Landscape Converter</Link>
                    <Link href="/vertical-to-horizontal-video" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Vertical to Horizontal Video</Link>
                    <Link href="/convert-reels-to-landscape" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Convert Reels to Landscape</Link>
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
                        <span class="text-xs text-gray-500">ConvertPortrait.com</span>
                    </div>
                    <p class="text-[11px] text-gray-500 text-center leading-relaxed">
                        Free video aspect ratio converter. Change 9:16 to 16:9 in your browser — no upload, no software required.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
