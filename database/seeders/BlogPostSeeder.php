<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        BlogPost::create([
            'title' => 'How to Convert TikTok Videos to YouTube Format',
            'slug' => 'convert-tiktok-videos-to-youtube-format',
            'meta_description' => 'Learn how to convert TikTok videos from 9:16 portrait to 16:9 landscape format for YouTube. Free, fast, and no software to install.',
            'excerpt' => 'TikTok videos are shot in 9:16 portrait, but YouTube looks best in 16:9 landscape. Here\'s exactly how to convert between the two formats without losing quality.',
            'content' => '<h2>Why TikTok Videos Don\'t Work on YouTube</h2>
<p>TikTok records in 9:16 portrait orientation — perfect for scrolling on your phone, but awkward on YouTube where viewers expect 16:9 landscape video. When you upload a portrait video to YouTube, it gets sandwiched between large black bars on both sides, making it look unprofessional and wasting most of the screen.</p>

<h2>The Simple Solution: Add a Background</h2>
<p>Instead of cropping your video (which cuts off content) or stretching it (which distorts it), the best approach is to keep your portrait video at full height and add a styled background to fill the 16:9 frame. This preserves every pixel of your original content while creating a professional-looking landscape video.</p>

<h2>How to Convert with ConvertPortrait</h2>
<p>ConvertPortrait makes this conversion effortless. Upload your TikTok video, choose a background style — blurred mirror, solid color, gradient, or pattern — and download your converted landscape video in minutes. The free version processes everything in your browser, so your video never leaves your device.</p>

<h3>Step-by-Step</h3>
<ol>
<li><strong>Download your TikTok video</strong> to your device (Save Video in the TikTok app)</li>
<li><strong>Upload to ConvertPortrait</strong> — drag and drop or click to browse</li>
<li><strong>Choose a background</strong> — the Blurred Mirror option looks great for most content</li>
<li><strong>Click Convert</strong> and wait for processing</li>
<li><strong>Download</strong> your 1920x1080 landscape video, ready for YouTube</li>
</ol>

<h2>Which Background Style Should You Use?</h2>
<p><strong>Blurred Mirror</strong> is the most popular choice — it uses a blurred, zoomed version of your video as the background, creating a cohesive look. <strong>Solid Color</strong> works well for branded content where you want clean, professional sides. <strong>Gradient</strong> and <strong>Pattern</strong> backgrounds are available with Pro for more creative options.</p>',
            'published_at' => now()->subDays(3),
        ]);

        BlogPost::create([
            'title' => 'Portrait vs Landscape Video: When to Convert and Why',
            'slug' => 'portrait-vs-landscape-video-when-to-convert',
            'meta_description' => 'Understand when to use portrait vs landscape video and when converting between formats makes sense for your content strategy.',
            'excerpt' => 'Portrait and landscape video each have their place. Understanding when to convert between formats can help you reach more viewers and repurpose content across platforms.',
            'content' => '<h2>The Platform Divide</h2>
<p>Social media has created a clear split in video formats. TikTok, Instagram Reels, and YouTube Shorts all use 9:16 portrait video, optimized for mobile viewing. Meanwhile, YouTube long-form, TV screens, presentations, and websites still favor 16:9 landscape. If you\'re creating content for multiple platforms, you\'ll inevitably need to convert between these formats.</p>

<h2>When You Should Convert Portrait to Landscape</h2>
<p>The most common scenario is <strong>repurposing short-form content for YouTube</strong>. If you\'ve built an audience on TikTok and want to create compilation videos, tutorials, or behind-the-scenes content for YouTube, converting your portrait clips to landscape is essential.</p>
<p>Other situations where conversion makes sense:</p>
<ul>
<li><strong>Presentations</strong> — embedding video in slides or display screens</li>
<li><strong>Websites</strong> — landscape video fits standard web layouts better</li>
<li><strong>TV/monitors</strong> — any horizontal display will show black bars with portrait video</li>
<li><strong>Video editing</strong> — combining portrait and landscape clips in a timeline</li>
</ul>

<h2>The Best Approach to Converting</h2>
<p>Never crop or stretch your video to change orientation. Both methods destroy quality. Instead, maintain the original video at full size and add a complementary background. ConvertPortrait does this automatically — your portrait video stays centered and sharp while the 16:9 frame is filled with a background of your choice.</p>

<h2>Tips for Better Conversions</h2>
<p><strong>Match your background to your brand.</strong> If you have brand colors, use the Solid Color or Gradient options to maintain consistency. For organic, natural-looking results, the Blurred Mirror background works best — it extends your video\'s own visuals to fill the frame.</p>',
            'published_at' => now()->subDays(7),
        ]);

        BlogPost::create([
            'title' => 'Best Ways to Convert 9:16 Video to 16:9 Without Black Bars',
            'slug' => 'convert-9-16-to-16-9-without-black-bars',
            'meta_description' => 'Learn the best methods to convert 9:16 vertical video to 16:9 landscape without ugly black bars. Free online tools and techniques explained.',
            'excerpt' => 'Black bars ruin the viewing experience. Here are the best ways to convert 9:16 video to 16:9 landscape while keeping your video looking professional.',
            'content' => '<h2>The Black Bar Problem</h2>
<p>When you upload a 9:16 vertical video to a platform that expects 16:9, the platform adds black bars (called "pillarboxing") on both sides. This wastes over 68% of the screen real estate and looks amateurish. Most viewers will scroll past or click away from videos with large black bars.</p>

<h2>Method 1: Blurred Background Fill</h2>
<p>The most popular and professional-looking approach. Your original video stays centered at full height, while a blurred, zoomed version of the same video fills the 16:9 background. This creates visual continuity without distracting from the main content. ConvertPortrait\'s "Blurred Mirror" template does this automatically.</p>

<h2>Method 2: Solid or Gradient Background</h2>
<p>For branded content, using a solid color or gradient that matches your brand creates a clean, intentional look. This works especially well for talking-head videos, product reviews, and educational content where the background isn\'t the focus.</p>

<h2>Method 3: Pattern Fill</h2>
<p>Geometric patterns like dots, lines, or chevrons add visual interest without distracting from your content. This is a good middle ground between the blurred approach and solid colors.</p>

<h2>What NOT to Do</h2>
<p><strong>Don\'t crop to fill.</strong> Cropping a 9:16 video to 16:9 cuts off roughly 68% of the frame — you\'ll lose the top and bottom of your video. <strong>Don\'t stretch.</strong> Stretching distorts faces, text, and everything else in your video. Both methods destroy the viewing experience.</p>

<h2>The Fastest Way to Convert</h2>
<p>ConvertPortrait handles all of these methods in one tool. Upload your 9:16 video, pick a background style, and get a clean 16:9 result in minutes. The free version runs entirely in your browser — no upload, no account, no data collection.</p>',
            'published_at' => now()->subDay(),
        ]);
    }
}
