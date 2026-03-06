<script setup>
import { Head, Link } from '@inertiajs/vue3';

const faqs = [
    {
        q: 'Can I convert a TikTok video to YouTube format for free?',
        a: 'Yes. ConvertPortrait lets you convert TikTok videos from 9:16 portrait to 16:9 landscape completely free. The free tier processes your video right in your browser using WebAssembly — no upload, no account, no watermark.',
    },
    {
        q: 'What resolution does the converted video have?',
        a: 'All converted videos output at 1920x1080 (Full HD), which is the standard resolution for YouTube. Your original TikTok video is centered in the frame with a stylish background filling the sides.',
    },
    {
        q: 'Will converting my TikTok video reduce the quality?',
        a: 'ConvertPortrait preserves your original video quality. The portrait video is placed at its native resolution inside a 1920x1080 landscape frame. There is no re-encoding of the original content — it is composited with the background template you choose.',
    },
    {
        q: 'How long does the conversion take?',
        a: 'In free browser mode, conversion takes roughly 20x the video duration (a 30-second TikTok takes about 10 minutes). Pro server-side processing is up to 140x faster — the same 30-second video converts in under 10 seconds.',
    },
    {
        q: 'Do I need to download the TikTok video first?',
        a: 'Yes. Save your TikTok video to your device first (use TikTok\'s built-in download or a screen recording), then upload it to ConvertPortrait. We support MP4, MOV, WebM, and AVI formats up to 200MB in free mode.',
    },
];

const jsonLdWebApp = JSON.stringify({
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "ConvertPortrait — TikTok to YouTube Converter",
    "url": "https://convertportrait.com/convert-tiktok-to-youtube",
    "description": "Convert TikTok videos from 9:16 portrait to 16:9 YouTube landscape format. Free online tool, no upload required.",
    "applicationCategory": "MultimediaApplication",
    "operatingSystem": "Any",
    "offers": [
        { "@type": "Offer", "price": "0", "priceCurrency": "USD", "description": "Free browser-based conversion" },
        { "@type": "Offer", "price": "19.99", "priceCurrency": "USD", "description": "Pro — fast server-side conversion, $19.99/year" }
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
        <title>Convert TikTok to YouTube Format — Free Online Tool | ConvertPortrait</title>
        <meta name="description" content="Convert TikTok videos from 9:16 portrait to 16:9 YouTube landscape format instantly. Free, private, no upload required. Works in your browser.">
        <link rel="canonical" href="https://convertportrait.com/convert-tiktok-to-youtube">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://convertportrait.com/convert-tiktok-to-youtube">
        <meta property="og:title" content="Convert TikTok to YouTube Format — Free Online Tool | ConvertPortrait">
        <meta property="og:description" content="Convert TikTok videos from 9:16 portrait to 16:9 YouTube landscape format instantly. Free, private, no upload required.">
        <meta property="og:site_name" content="ConvertPortrait">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Convert TikTok to YouTube Format — Free Online Tool | ConvertPortrait">
        <meta name="twitter:description" content="Convert TikTok videos from 9:16 portrait to 16:9 YouTube landscape format instantly. Free, private, no upload required.">
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
                    Free online tool — no account required
                </div>

                <h1 class="text-4xl sm:text-[3.25rem] font-extrabold tracking-tight leading-[1.1] mb-5">
                    Convert TikTok Videos to
                    <br>
                    <span class="font-editorial font-normal text-teal">YouTube Format</span>
                </h1>

                <p class="text-[15px] sm:text-base text-gray-600 max-w-xl mx-auto leading-relaxed mb-8">
                    TikTok videos are shot in 9:16 portrait. YouTube looks best at 16:9 landscape.
                    ConvertPortrait bridges the gap — converting your vertical TikToks into widescreen videos
                    with professional backgrounds, completely free and right in your browser.
                </p>

                <Link href="/" class="inline-flex items-center gap-2 font-bold text-base px-8 py-4 rounded-xl bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]">
                    Convert Your Video Now — Free
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </Link>
            </section>

            <!-- Problem / Solution -->
            <section class="pb-16">
                <div class="bg-surface/60 rounded-2xl border border-gray-200 p-8 sm:p-10">
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight mb-4">Why TikTok Videos Don't Work on YouTube</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        TikTok records in 9:16 portrait mode (1080x1920), designed for vertical phone screens. When you upload that same video to YouTube, it appears as a tiny strip in the center of the player with massive black bars on either side. This looks unprofessional and kills viewer retention — studies show that landscape videos on YouTube get significantly more watch time than portrait content displayed with black bars.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-5 text-center">
                            <div class="w-12 h-20 mx-auto mb-3 rounded-lg border-2 border-danger/30 bg-danger/[0.06] flex items-center justify-center">
                                <span class="text-[10px] text-danger/60 font-bold">9:16</span>
                            </div>
                            <p class="text-xs text-gray-600 font-semibold">TikTok Format</p>
                            <p class="text-[11px] text-gray-500 mt-1">Portrait, vertical</p>
                        </div>
                        <div class="bg-gray-100/60 rounded-xl border border-teal/10 p-5 text-center">
                            <div class="w-20 h-12 mx-auto mb-3 rounded-lg border-2 border-teal/30 bg-teal/[0.06] flex items-center justify-center">
                                <span class="text-[10px] text-teal/60 font-bold">16:9</span>
                            </div>
                            <p class="text-xs text-gray-600 font-semibold">YouTube Format</p>
                            <p class="text-[11px] text-gray-500 mt-1">Landscape, widescreen</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-10">How It Works</h2>
                <div class="grid sm:grid-cols-3 gap-6 stagger">
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal">1</div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Upload Your TikTok</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Save your TikTok video to your device, then drag and drop it into ConvertPortrait. Supports MP4, MOV, WebM, and AVI.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal">2</div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Choose a Background</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Pick from blurred mirror, solid color, gradient wash, or geometric pattern to fill the sides of your landscape video.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal">3</div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Download for YouTube</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Get a 1920x1080 landscape video ready to upload to YouTube, embed in presentations, or share anywhere widescreen is expected.</p>
                    </div>
                </div>
            </section>

            <!-- Free vs Pro -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-10">Free vs Pro</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-gray-100/60 rounded-2xl border border-gray-200 p-6">
                        <div class="text-xs font-bold text-teal uppercase tracking-wider mb-4">Free</div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Browser-based conversion (no upload)
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Blurred Mirror + Solid Color backgrounds
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                1920x1080 Full HD output
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Files up to 200MB
                            </li>
                        </ul>
                    </div>
                    <div class="bg-amber-400/[0.03] rounded-2xl border border-amber-400/10 p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Pro</span>
                            <span class="text-[10px] font-bold text-amber-400/60 bg-amber-400/10 px-2 py-0.5 rounded-full">$19.99/year</span>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Server-side processing — up to 140x faster
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                All templates (Gradient Wash, Pattern Fill)
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                Files up to 500MB
                            </li>
                            <li class="flex items-start gap-2.5 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                All future features included
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="pb-16 text-center">
                <div class="bg-gradient-to-br from-teal/[0.06] to-emerald/[0.03] rounded-2xl border border-teal/10 p-10">
                    <h2 class="text-2xl font-bold tracking-tight mb-3">Ready to Convert Your TikTok?</h2>
                    <p class="text-sm text-gray-600 mb-6 max-w-md mx-auto">Turn your portrait TikTok into a professional landscape video for YouTube in just a few clicks. No signup, no watermark.</p>
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
                    <Link href="/portrait-to-landscape-video-converter" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Portrait to Landscape Converter</Link>
                    <Link href="/vertical-to-horizontal-video" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Vertical to Horizontal Video</Link>
                    <Link href="/convert-reels-to-landscape" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Convert Reels to Landscape</Link>
                    <Link href="/video-aspect-ratio-converter" class="text-xs text-gray-600 hover:text-teal bg-gray-100/60 hover:bg-teal/[0.06] border border-gray-200 hover:border-teal/15 px-4 py-2 rounded-full transition-all">Video Aspect Ratio Converter</Link>
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
                        Free online video converter. Convert TikTok, Reels, and Shorts to landscape format.
                        100% browser-based in free mode — your video never leaves your device.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
