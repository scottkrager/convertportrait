<script setup>
import { Head, Link } from '@inertiajs/vue3';

const faqs = [
    {
        q: 'How do I convert a vertical video to horizontal?',
        a: 'Upload your vertical video to ConvertPortrait, select a background style (blurred, solid color, gradient, or pattern), and click Convert. The tool places your vertical footage in the center of a 16:9 horizontal frame with your chosen background filling the sides. No cropping, no quality loss.',
    },
    {
        q: 'Will my vertical video be stretched or cropped?',
        a: 'No. ConvertPortrait never stretches or crops your original footage. Your vertical video maintains its full height and original proportions. The empty left and right areas of the horizontal frame are filled with a styled background instead of ugly black bars.',
    },
    {
        q: 'What platforms need horizontal video?',
        a: 'YouTube (standard uploads), Vimeo, Facebook video posts, LinkedIn video, corporate presentations (PowerPoint, Keynote, Google Slides), TV and monitor displays, website embeds, and digital signage all expect or look best with 16:9 horizontal video.',
    },
    {
        q: 'Can I convert vertical video to horizontal on my phone?',
        a: 'Yes. ConvertPortrait works in any modern mobile browser — Chrome, Safari, Firefox, or Edge on both iOS and Android. No app installation required. The free mode processes your video right on your device.',
    },
    {
        q: 'Is this better than using video editing software?',
        a: 'For simple vertical-to-horizontal conversion, yes. Video editors like Premiere Pro or DaVinci Resolve require installing software, creating a project, manually setting up the composition, and exporting. ConvertPortrait does the same thing in three clicks, for free, in your browser.',
    },
];

const useCases = [
    { title: 'YouTube Uploads', desc: 'Convert phone recordings and TikToks to YouTube-ready 16:9 landscape format.', icon: 'M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553-1.106A1 1 0 0114 5.882V14.118a1 1 0 01-1.447.894l-4-2a1 1 0 010-1.788l4-2z' },
    { title: 'Presentations', desc: 'Embed vertical clips in PowerPoint, Keynote, or Google Slides without black bars.', icon: 'M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z' },
    { title: 'TV & Monitors', desc: 'Display vertical content on widescreen TVs, digital signage, and conference room screens.', icon: 'M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5z' },
    { title: 'Social Media Posts', desc: 'Repurpose Reels and Shorts as landscape Facebook, LinkedIn, or Twitter video posts.', icon: 'M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z' },
];

const jsonLdWebApp = JSON.stringify({
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "ConvertPortrait — Vertical to Horizontal Video Converter",
    "url": "https://convertportrait.com/vertical-to-horizontal-video",
    "description": "Convert vertical video to horizontal format online for free. No upload, no software — runs in your browser.",
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
        <title>Convert Vertical Video to Horizontal — Free Online Tool</title>
        <meta name="description" content="Convert vertical video to horizontal 16:9 format online for free. No software needed — works in your browser. Perfect for YouTube, presentations, and TV.">
        <link rel="canonical" href="https://convertportrait.com/vertical-to-horizontal-video">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://convertportrait.com/vertical-to-horizontal-video">
        <meta property="og:title" content="Convert Vertical Video to Horizontal — Free Online Tool">
        <meta property="og:description" content="Convert vertical video to horizontal 16:9 format online for free. No software needed — works in your browser.">
        <meta property="og:site_name" content="ConvertPortrait">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Convert Vertical Video to Horizontal — Free Online Tool">
        <meta name="twitter:description" content="Convert vertical video to horizontal 16:9 format online for free. No software needed — works in your browser.">
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
                    No software required — works in any browser
                </div>

                <h1 class="text-4xl sm:text-[3.25rem] font-extrabold tracking-tight leading-[1.1] mb-5">
                    Convert Vertical Video
                    <br>
                    <span class="font-editorial font-normal text-teal">to Horizontal</span>
                </h1>

                <p class="text-[15px] sm:text-base text-gray-400 max-w-xl mx-auto leading-relaxed mb-8">
                    Turn any vertical video into a clean horizontal format for YouTube, presentations, TV screens, and more.
                    Your video stays centered with a professional background filling the sides — no cropping, no stretching,
                    no black bars. Free and private.
                </p>

                <Link href="/" class="inline-flex items-center gap-2 font-bold text-base px-8 py-4 rounded-xl bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]">
                    Convert Your Video Now — Free
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </Link>
            </section>

            <!-- Use Cases -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-3">Where You Need Horizontal Video</h2>
                <p class="text-sm text-gray-400 text-center mb-10 max-w-md mx-auto">Vertical video looks out of place on widescreen platforms. Here is where horizontal format matters most.</p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div v-for="uc in useCases" :key="uc.title" class="bg-gray-100/60 rounded-xl border border-gray-200 p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-teal/10 border border-teal/10 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path :d="uc.icon" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-1">{{ uc.title }}</h3>
                            <p class="text-xs text-gray-400 leading-relaxed">{{ uc.desc }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step by Step Guide -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-10">Step-by-Step Guide</h2>
                <div class="space-y-6 max-w-lg mx-auto">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal shrink-0">1</div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-1">Save Your Vertical Video</h3>
                            <p class="text-xs text-gray-400 leading-relaxed">Download or export your vertical video from TikTok, Instagram, your phone's camera roll, or any other source. ConvertPortrait accepts MP4, MOV, WebM, and AVI files.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal shrink-0">2</div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-1">Upload to ConvertPortrait</h3>
                            <p class="text-xs text-gray-400 leading-relaxed">Open the converter tool and drag your video file onto the page, or click to browse. The tool detects the aspect ratio automatically and prepares your video for conversion.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal shrink-0">3</div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-1">Choose Your Background Style</h3>
                            <p class="text-xs text-gray-400 leading-relaxed">Pick from four options: Blurred Mirror (your video blurred as background), Solid Color, Gradient Wash, or Pattern Fill. Preview updates in real time so you can see exactly how it will look.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal shrink-0">4</div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-600 mb-1">Convert and Download</h3>
                            <p class="text-xs text-gray-400 leading-relaxed">Click "Convert to Landscape" and wait for processing. Once done, download your 1920x1080 horizontal video file ready for any widescreen platform.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="pb-16 text-center">
                <div class="bg-gradient-to-br from-teal/[0.06] to-emerald/[0.03] rounded-2xl border border-teal/10 p-10">
                    <h2 class="text-2xl font-bold tracking-tight mb-3">Convert Your Vertical Video Now</h2>
                    <p class="text-sm text-gray-400 mb-6 max-w-md mx-auto">No account, no installation, no watermark. Just drop your video and get a horizontal version in minutes.</p>
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
                    <Link href="/portrait-to-landscape-video-converter" class="text-xs text-gray-400 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Portrait to Landscape Converter</Link>
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
                        Free vertical to horizontal video converter. Browser-based processing — your files never leave your device.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
