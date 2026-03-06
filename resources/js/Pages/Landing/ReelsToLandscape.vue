<script setup>
import { Head, Link } from '@inertiajs/vue3';

const faqs = [
    {
        q: 'Can I convert Instagram Reels to landscape for free?',
        a: 'Yes. ConvertPortrait converts Instagram Reels from 9:16 portrait to 16:9 landscape completely free. The free tier processes your video in your browser — no upload to any server, no account, no watermark on the output.',
    },
    {
        q: 'How do I save an Instagram Reel to my device?',
        a: 'On Instagram, tap the three-dot menu on any Reel and select "Save to Camera Roll" or "Download." If that option is not available, you can use a screen recording. Once saved, upload the video file to ConvertPortrait for conversion.',
    },
    {
        q: 'What happens to the sides of the video?',
        a: 'Instead of ugly black bars, ConvertPortrait fills the side areas with a stylish background of your choice. Options include Blurred Mirror (your video blurred as background), Solid Color, Gradient Wash, and Pattern Fill. The result looks clean and professional.',
    },
    {
        q: 'Will the audio from my Reel be preserved?',
        a: 'Yes. ConvertPortrait preserves the original audio track from your Reel. The audio is carried over to the landscape output without re-encoding, so there is no quality loss in the sound.',
    },
    {
        q: 'Can I use the converted video on platforms other than YouTube?',
        a: 'Absolutely. The 1920x1080 landscape output works on YouTube, Vimeo, Facebook (landscape posts), LinkedIn, Twitter/X, in presentations (PowerPoint, Keynote, Google Slides), on TV screens, digital signage, and any other platform that expects widescreen video.',
    },
];

const reasons = [
    { title: 'YouTube Long-Form', desc: 'Repurpose your best Reels as YouTube content. Landscape format fills the YouTube player and looks professional.' },
    { title: 'Presentations & Demos', desc: 'Embed Reels in slide decks without awkward black bars. Perfect for marketing reviews, social media reports, and client presentations.' },
    { title: 'Website Embeds', desc: 'Landscape videos fit naturally in website layouts, blog posts, and landing pages where horizontal video is the standard.' },
    { title: 'TV & Digital Signage', desc: 'Display your Instagram content on widescreen TVs in stores, offices, events, and digital signage setups.' },
];

const jsonLdWebApp = JSON.stringify({
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "ConvertPortrait — Instagram Reels to Landscape Converter",
    "url": "https://convertportrait.com/convert-reels-to-landscape",
    "description": "Convert Instagram Reels from 9:16 portrait to 16:9 landscape format. Free online tool, no upload required.",
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
        <title>Convert Instagram Reels to Landscape — Free Tool</title>
        <meta name="description" content="Convert Instagram Reels from 9:16 portrait to 16:9 landscape format. Free online tool, no upload required. Repurpose Reels for YouTube, presentations, and TV.">
        <link rel="canonical" href="https://convertportrait.com/convert-reels-to-landscape">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://convertportrait.com/convert-reels-to-landscape">
        <meta property="og:title" content="Convert Instagram Reels to Landscape — Free Tool">
        <meta property="og:description" content="Convert Instagram Reels from 9:16 portrait to 16:9 landscape format. Free, private, no upload required.">
        <meta property="og:site_name" content="ConvertPortrait">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Convert Instagram Reels to Landscape — Free Tool">
        <meta name="twitter:description" content="Convert Instagram Reels from 9:16 portrait to 16:9 landscape format. Free, private, no upload required.">
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
                    Free — no account, no watermark
                </div>

                <h1 class="text-4xl sm:text-[3.25rem] font-extrabold tracking-tight leading-[1.1] mb-5">
                    Convert Instagram Reels
                    <br>
                    <span class="font-editorial font-normal text-teal">to Landscape Video</span>
                </h1>

                <p class="text-[15px] sm:text-base text-gray-600 max-w-xl mx-auto leading-relaxed mb-8">
                    Repurpose your best Instagram Reels for YouTube, presentations, TV screens, and more.
                    ConvertPortrait transforms your vertical Reels into professional 16:9 landscape videos
                    with stylish backgrounds — no black bars, no cropping, no quality loss.
                </p>

                <Link href="/" class="inline-flex items-center gap-2 font-bold text-base px-8 py-4 rounded-xl bg-gradient-to-r from-pink-400 to-purple-400 hover:from-pink-500 hover:to-purple-500 text-white transition-all duration-200 hover:shadow-lg hover:shadow-pink-400/10 active:scale-[0.98]">
                    Convert Your Video Now — Free
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </Link>
            </section>

            <!-- Why Convert Reels -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-3">Why Convert Your Reels to Landscape?</h2>
                <p class="text-sm text-gray-600 text-center mb-10 max-w-lg mx-auto">Instagram Reels are created in 9:16 portrait format, optimized for mobile feeds. But many platforms and use cases demand 16:9 landscape video. Here is where landscape Reels shine.</p>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div v-for="reason in reasons" :key="reason.title" class="bg-gray-100/60 rounded-xl border border-gray-200 p-5">
                        <h3 class="text-sm font-bold text-gray-600 mb-2">{{ reason.title }}</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ reason.desc }}</p>
                    </div>
                </div>
            </section>

            <!-- How It Works -->
            <section class="pb-16">
                <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-10">How It Works</h2>
                <div class="grid sm:grid-cols-3 gap-6 stagger">
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal">1</div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Save Your Reel</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Download your Instagram Reel to your device using Instagram's built-in save option or a screen recording. Then drag the file into ConvertPortrait.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal">2</div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Pick a Background Style</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Choose from Blurred Mirror, Solid Color, Gradient Wash, or Pattern Fill. Preview updates instantly so you see exactly what the output will look like.</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-4 rounded-full bg-teal/10 border border-teal/10 flex items-center justify-center text-sm font-bold text-teal">3</div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Convert & Download</h3>
                        <p class="text-xs text-gray-600 leading-relaxed">Click convert and wait for processing. Download your 1920x1080 landscape video, ready for YouTube, Vimeo, presentations, or any widescreen platform.</p>
                    </div>
                </div>
            </section>

            <!-- Reels vs YouTube comparison -->
            <section class="pb-16">
                <div class="bg-surface/60 rounded-2xl border border-gray-200 p-8 sm:p-10">
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-center mb-6">Instagram Reels vs YouTube Format</h2>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-100/60 rounded-xl border border-gray-200 p-5 text-center">
                            <div class="w-12 h-20 mx-auto mb-3 rounded-lg border-2 border-pink-400/30 bg-pink-400/[0.06] flex items-center justify-center">
                                <span class="text-[10px] text-pink-400/60 font-bold">9:16</span>
                            </div>
                            <p class="text-xs text-gray-600 font-semibold">Instagram Reels</p>
                            <p class="text-[11px] text-gray-500 mt-1">1080 x 1920 portrait</p>
                        </div>
                        <div class="bg-gray-100/60 rounded-xl border border-teal/10 p-5 text-center">
                            <div class="w-20 h-12 mx-auto mb-3 rounded-lg border-2 border-teal/30 bg-teal/[0.06] flex items-center justify-center">
                                <span class="text-[10px] text-teal/60 font-bold">16:9</span>
                            </div>
                            <p class="text-xs text-gray-600 font-semibold">YouTube / Landscape</p>
                            <p class="text-[11px] text-gray-500 mt-1">1920 x 1080 widescreen</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 text-center max-w-lg mx-auto leading-relaxed">
                        ConvertPortrait places your Reel at full height in the center of a 1920x1080 landscape frame. The side panels are filled with your chosen background — not ugly black bars. The result is a professional-looking widescreen video that fills the player on any platform.
                    </p>
                </div>
            </section>

            <!-- CTA -->
            <section class="pb-16 text-center">
                <div class="bg-gradient-to-br from-teal/[0.06] to-emerald/[0.03] rounded-2xl border border-teal/10 p-10">
                    <h2 class="text-2xl font-bold tracking-tight mb-3">Repurpose Your Reels Today</h2>
                    <p class="text-sm text-gray-600 mb-6 max-w-md mx-auto">Give your best Instagram content a second life on YouTube and beyond. Convert in minutes, completely free.</p>
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
                        Free Instagram Reels to landscape converter. Browser-based — your video never leaves your device.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>
