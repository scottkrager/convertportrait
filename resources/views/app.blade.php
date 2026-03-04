<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ConvertPortrait — Turn Shorts into Landscape Videos</title>
        <meta name="description" content="Convert portrait TikTok, Reels, and YouTube Shorts to landscape 16:9 format instantly. Free, private, runs in your browser — zero uploads, zero tracking.">

        <!-- Canonical -->
        <link rel="canonical" href="{{ url('/') }}">

        <!-- Open Graph -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="ConvertPortrait — Turn Shorts into Landscape Videos">
        <meta property="og:description" content="Convert portrait TikTok, Reels, and YouTube Shorts to landscape 16:9 format instantly. Free, private, runs in your browser.">
        <meta property="og:site_name" content="ConvertPortrait">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="ConvertPortrait — Turn Shorts into Landscape Videos">
        <meta name="twitter:description" content="Convert portrait TikTok, Reels, and YouTube Shorts to landscape 16:9 format instantly. Free, private, runs in your browser.">

        <!-- Structured Data -->
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebApplication",
            "name": "ConvertPortrait",
            "url": "{{ url('/') }}",
            "description": "Convert portrait TikTok, Reels, and YouTube Shorts to landscape 16:9 format instantly. Free, private, runs in your browser.",
            "applicationCategory": "MultimediaApplication",
            "operatingSystem": "Any",
            "offers": [
                {
                    "@@type": "Offer",
                    "price": "0",
                    "priceCurrency": "USD",
                    "description": "Free tier — browser-based conversion with blurred and solid color backgrounds"
                },
                {
                    "@@type": "Offer",
                    "price": "19.99",
                    "priceCurrency": "USD",
                    "description": "Pro — server-side fast conversion, all templates, lifetime access"
                }
            ],
            "featureList": [
                "Convert portrait video to 16:9 landscape",
                "Blurred mirror background",
                "Solid color background",
                "Gradient background",
                "Pattern background",
                "Browser-based processing",
                "Server-side fast processing",
                "No account required",
                "Privacy-first — video never uploaded in free mode"
            ]
        }
        </script>

        <!-- FAQ Structured Data -->
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "FAQPage",
            "mainEntity": [
                {
                    "@@type": "Question",
                    "name": "How do I convert a portrait video to landscape?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Upload your portrait video (TikTok, Reel, or YouTube Short) to ConvertPortrait, choose a background style, and click Convert. The video is converted to 16:9 landscape format right in your browser."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "Is ConvertPortrait free?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Yes! The free tier includes browser-based conversion with blurred mirror and solid color backgrounds. Pro ($19.99 one-time) adds server-side fast processing and premium templates."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "Is my video uploaded to a server?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "In free mode, no — your video is processed entirely in your browser using WebAssembly and never leaves your device. Pro users can optionally use server-side processing for faster conversion, where the video is deleted immediately after."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "What video formats are supported?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "ConvertPortrait supports MP4, MOV, WebM, and AVI files up to 200MB in browser mode or 500MB with Pro server processing."
                    }
                },
                {
                    "@@type": "Question",
                    "name": "What background styles are available?",
                    "acceptedAnswer": {
                        "@@type": "Answer",
                        "text": "Free backgrounds include Blurred Mirror (your video blurred as background) and Solid Color (pick any color). Pro backgrounds include Gradient Wash (5 preset gradients) and Pattern Fill (dots, lines, chevrons)."
                    }
                }
            ]
        }
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Newsreader:ital,wght@1,400&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia

        <!-- SEO fallback for crawlers that don't execute JavaScript -->
        <noscript>
            <div style="max-width: 800px; margin: 0 auto; padding: 40px 20px; font-family: sans-serif; color: #333;">
                <h1>ConvertPortrait — Turn Shorts into Landscape Videos</h1>
                <p>Convert portrait TikTok, Instagram Reels, and YouTube Shorts to landscape 16:9 format instantly. Free, private, and runs entirely in your browser.</p>

                <h2>How It Works</h2>
                <ol>
                    <li><strong>Upload</strong> — Drop your portrait video (MP4, MOV, WebM, AVI up to 200MB)</li>
                    <li><strong>Choose a Background</strong> — Pick from blurred mirror, solid color, gradient, or pattern styles</li>
                    <li><strong>Convert</strong> — Your video is converted to 1920x1080 landscape format</li>
                    <li><strong>Download</strong> — Save the converted video to your device</li>
                </ol>

                <h2>Background Styles</h2>
                <ul>
                    <li><strong>Blurred Mirror</strong> (Free) — Your video blurred and scaled as the background</li>
                    <li><strong>Solid Color</strong> (Free) — Clean single-color backdrop with color picker</li>
                    <li><strong>Gradient Wash</strong> (Pro) — Beautiful color gradients including Sunset, Ocean, Neon, Forest, and Mono</li>
                    <li><strong>Pattern Fill</strong> (Pro) — Geometric patterns: dots, lines, or chevrons with custom colors</li>
                </ul>

                <h2>Privacy First</h2>
                <p>Your video never leaves your device in free mode. Processing happens entirely in your browser using WebAssembly technology. No account required, no tracking, no data collection.</p>

                <h2>Pro Features — $19.99 Lifetime</h2>
                <ul>
                    <li>Server-side processing — up to 140x faster than browser mode</li>
                    <li>All premium background templates</li>
                    <li>Support for files up to 500MB</li>
                    <li>Future features included</li>
                </ul>

                <h2>Frequently Asked Questions</h2>
                <h3>How do I convert a portrait video to landscape?</h3>
                <p>Upload your portrait video to ConvertPortrait, choose a background style, and click Convert. The video is converted to 16:9 landscape format right in your browser.</p>

                <h3>Is ConvertPortrait free?</h3>
                <p>Yes! The free tier includes browser-based conversion with blurred mirror and solid color backgrounds. Pro ($19.99 one-time) adds server-side fast processing and premium templates.</p>

                <h3>Is my video uploaded to a server?</h3>
                <p>In free mode, no — your video is processed entirely in your browser using WebAssembly and never leaves your device. Pro users can optionally use server-side processing for faster conversion, where the video is deleted immediately after.</p>

                <h3>What video formats are supported?</h3>
                <p>ConvertPortrait supports MP4, MOV, WebM, and AVI files up to 200MB in browser mode or 500MB with Pro server processing.</p>

                <h3>What background styles are available?</h3>
                <p>Free backgrounds include Blurred Mirror and Solid Color. Pro backgrounds include Gradient Wash and Pattern Fill with dots, lines, or chevrons.</p>
            </div>
        </noscript>
    </body>
</html>
