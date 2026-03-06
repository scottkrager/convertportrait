<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        $now = Carbon::now();

        $posts = [
            [
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
                'published_at' => $now->copy()->subDays(3),
            ],
            [
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
                'published_at' => $now->copy()->subDays(7),
            ],
            [
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
                'published_at' => $now->copy()->subDay(),
            ],
            [
                'title' => 'How to Convert YouTube Shorts to Regular YouTube Videos',
                'slug' => 'convert-youtube-shorts-to-regular-videos',
                'meta_description' => 'Learn how to convert YouTube Shorts from 9:16 vertical to 16:9 landscape format so you can upload them as regular YouTube videos.',
                'excerpt' => 'YouTube Shorts are great for discovery, but sometimes you need that same content as a regular landscape video. Here\'s how to convert them without losing quality.',
                'content' => '<h2>Why Convert YouTube Shorts to Regular Videos?</h2>
<p>YouTube Shorts are capped at 60 seconds and displayed in a vertical player. They\'re great for quick engagement and reaching new audiences through the Shorts shelf, but they have limitations. Regular YouTube videos get recommended differently, appear in search results more prominently, can be any length, and display properly on desktop and TV screens.</p>
<p>If you\'ve created a Short that performs well, converting it to a regular landscape video lets you republish it for a completely different audience — desktop viewers, TV watchers, and people searching for your topic on YouTube.</p>

<h2>The Conversion Process</h2>
<p>Converting a YouTube Short to a regular video means changing the aspect ratio from 9:16 (portrait) to 16:9 (landscape). The key is to do this without cropping or stretching, which would either cut off content or distort it.</p>
<p>The best approach is to place your Short at full height inside a 16:9 frame and fill the sides with a styled background. This preserves every pixel of your original content while creating a professional landscape video.</p>

<h3>Step by Step</h3>
<ol>
<li><strong>Download your Short</strong> — use YouTube Studio to download the original file, or a screen recording if needed</li>
<li><strong>Go to ConvertPortrait</strong> and upload your video file</li>
<li><strong>Choose a background style</strong> — Blurred Mirror works best for most Shorts content since it extends the visual naturally</li>
<li><strong>Convert and download</strong> — you\'ll get a 1920x1080 landscape video</li>
<li><strong>Upload to YouTube</strong> as a regular video with a proper title, description, and tags</li>
</ol>

<h2>Tips for Maximizing Reach</h2>
<p>When you upload the converted version, treat it as fresh content. Write a new title optimized for search (not just the Short\'s title), add a detailed description with keywords, and create a custom thumbnail. Since landscape videos appear differently in YouTube\'s algorithm, you\'re essentially getting a second chance to reach viewers with the same content.</p>

<h2>Should You Convert All Your Shorts?</h2>
<p>Not necessarily. Focus on converting Shorts that performed well, have evergreen value, or contain information people might search for. A viral dance trend probably doesn\'t need conversion, but a tutorial, tip, or product review absolutely does.</p>',
                'published_at' => $now->copy()->subDays(2),
            ],
            [
                'title' => 'TikTok to YouTube: The Complete Guide to Repurposing Short-Form Content',
                'slug' => 'tiktok-to-youtube-complete-repurposing-guide',
                'meta_description' => 'The complete guide to repurposing TikTok content for YouTube. Learn format conversion, optimization tips, and strategies for reaching a new audience.',
                'excerpt' => 'Your TikTok library is a goldmine of YouTube content waiting to happen. This guide covers everything from format conversion to optimization strategies.',
                'content' => '<h2>Why Repurpose TikTok Content for YouTube?</h2>
<p>TikTok and YouTube have fundamentally different audiences and discovery mechanisms. TikTok\'s algorithm pushes content to new viewers regardless of follower count. YouTube rewards search intent and watch time. By repurposing your TikTok content for YouTube, you\'re not duplicating — you\'re reaching an entirely different audience through a different discovery channel.</p>
<p>Many creators report that their YouTube audience has minimal overlap with their TikTok following. The same video can perform well on both platforms with the right formatting and optimization.</p>

<h2>The Format Problem</h2>
<p>TikTok uses 9:16 portrait video. YouTube\'s standard format is 16:9 landscape. If you upload a portrait video to YouTube directly, it gets displayed with massive black bars on both sides — wasting 68% of the screen and signaling to viewers that the content wasn\'t made for the platform.</p>
<p>The solution is to convert your TikTok videos to landscape format by adding a styled background to fill the 16:9 frame. This keeps your original content intact while making it look native to YouTube.</p>

<h2>Three Repurposing Strategies</h2>

<h3>1. Individual Video Conversion</h3>
<p>Take your best-performing TikToks and convert each one to a standalone YouTube video. This works best for tutorials, reviews, and educational content that people actively search for. Optimize each video with YouTube-specific titles, descriptions, and tags.</p>

<h3>2. Compilation Videos</h3>
<p>Combine multiple related TikToks into a longer compilation. For example, "10 Cooking Tips in 5 Minutes" or "Best Travel Moments 2026." Convert each clip to landscape first, then edit them together in a video editor. Compilations tend to get higher watch time, which YouTube\'s algorithm rewards.</p>

<h3>3. Extended Versions</h3>
<p>Use your TikTok as a teaser or introduction, then add extended content for YouTube. Convert the TikTok portion to landscape, then film additional landscape content to create a complete video. This gives existing fans a reason to follow you on both platforms.</p>

<h2>Optimization Checklist for YouTube</h2>
<ul>
<li><strong>Title:</strong> Use searchable keywords, not just your TikTok caption. Think about what someone would type into YouTube search.</li>
<li><strong>Description:</strong> Write at least 200 words. Include keywords naturally and link to related videos.</li>
<li><strong>Thumbnail:</strong> Create a custom landscape thumbnail — don\'t rely on auto-generated frames from the vertical video.</li>
<li><strong>Tags:</strong> Add 10-15 relevant tags including variations of your main keywords.</li>
<li><strong>End screen:</strong> Add end screen elements to drive viewers to more of your content.</li>
</ul>

<h2>Getting Started</h2>
<p>Start with your top 5 TikToks by view count. Download them, convert them to landscape with ConvertPortrait, and upload to YouTube with optimized metadata. Track the performance over 30 days before committing to a regular repurposing schedule.</p>',
                'published_at' => $now->copy()->subDays(4),
            ],
            [
                'title' => 'What Aspect Ratio Does YouTube Use? And Why It Matters',
                'slug' => 'what-aspect-ratio-does-youtube-use',
                'meta_description' => 'YouTube uses 16:9 aspect ratio as its standard. Learn why this matters for your videos and how to convert other formats to 16:9.',
                'excerpt' => 'YouTube\'s standard aspect ratio is 16:9, but what does that actually mean for your videos? Here\'s everything you need to know about YouTube video dimensions.',
                'content' => '<h2>YouTube\'s Standard: 16:9</h2>
<p>YouTube\'s default player is designed for 16:9 aspect ratio video. This is the widescreen format you see on TVs, monitors, and laptop screens. When you upload a 16:9 video, it fills the entire player perfectly — no black bars, no wasted space.</p>
<p>The most common resolutions in 16:9 are:</p>
<ul>
<li><strong>3840 x 2160</strong> — 4K Ultra HD</li>
<li><strong>2560 x 1440</strong> — 2K / QHD</li>
<li><strong>1920 x 1080</strong> — Full HD (most common)</li>
<li><strong>1280 x 720</strong> — HD</li>
</ul>

<h2>What Happens with Other Aspect Ratios?</h2>
<p><strong>9:16 (portrait/vertical):</strong> Gets pillarboxed with black bars on both sides. This is what TikTok, Reels, and Shorts use. On desktop, it looks particularly bad — your video only takes up about 32% of the player area.</p>
<p><strong>1:1 (square):</strong> Gets pillarboxed with smaller black bars on the sides. Less wasted space than portrait, but still not ideal.</p>
<p><strong>4:3 (old TV format):</strong> Gets pillarboxed with narrow black bars. This is the format of older cameras and some webcams.</p>
<p><strong>21:9 (ultrawide):</strong> Gets letterboxed with black bars on top and bottom. Used in cinematic content.</p>

<h2>Why 16:9 Matters for YouTube Success</h2>
<p>Videos that fill the player look more professional and get higher engagement. When a viewer clicks on your video and sees black bars, it signals that the content wasn\'t optimized for the platform. This can lead to higher bounce rates and lower watch time — two metrics that directly impact how YouTube recommends your content.</p>

<h2>How to Convert Portrait Video to 16:9</h2>
<p>If you have portrait (9:16) videos from TikTok, Instagram Reels, or YouTube Shorts, you can convert them to 16:9 landscape using ConvertPortrait\'s aspect ratio converter. Your original video stays centered at full resolution while a styled background fills the 16:9 frame.</p>

<h2>YouTube Shorts Are Different</h2>
<p>YouTube Shorts use 9:16 portrait format and have their own dedicated player. If you\'re uploading specifically as a Short (under 60 seconds, #Shorts hashtag), 9:16 is correct. But if you want the same content as a regular YouTube video, you need to convert it to 16:9 first.</p>',
                'published_at' => $now->copy()->subDays(5),
            ],
            [
                'title' => 'How to Remove Black Bars from Vertical Videos',
                'slug' => 'remove-black-bars-from-vertical-videos',
                'meta_description' => 'Remove ugly black bars from vertical videos with styled backgrounds. Free online tool that replaces black bars without cropping your content.',
                'excerpt' => 'Black bars make your videos look unprofessional. Here\'s how to replace them with styled backgrounds that actually look good.',
                'content' => '<h2>Why Do Black Bars Appear?</h2>
<p>Black bars (technically called "pillarboxing") appear when a vertical 9:16 video is displayed in a horizontal 16:9 player. The player needs to fill a widescreen frame, but your video only covers the center portion. The remaining space on both sides defaults to black.</p>

<h2>Why Black Bars Are a Problem</h2>
<p>Beyond looking unprofessional, black bars actively hurt your content\'s performance:</p>
<ul>
<li><strong>Lower engagement:</strong> Viewers associate black bars with low-quality or lazy content</li>
<li><strong>Wasted screen space:</strong> 68% of the player is empty, making your content harder to see</li>
<li><strong>Poor thumbnails:</strong> YouTube auto-generates thumbnails from your video — black bars make them look terrible</li>
<li><strong>Reduced watch time:</strong> Viewers are more likely to click away from videos with large black bars</li>
</ul>

<h2>The Wrong Way to Remove Black Bars</h2>
<p><strong>Don\'t crop to fill.</strong> Some editors offer a "crop to fill" option that zooms in until the video fills the 16:9 frame. This cuts off the top and bottom of your video — roughly 68% of the original content is lost.</p>
<p><strong>Don\'t stretch.</strong> Stretching a 9:16 video to fill 16:9 distorts everything horizontally. Faces look wide, text becomes unreadable, and the result looks worse than black bars.</p>

<h2>The Right Way: Replace with Styled Backgrounds</h2>
<p>The professional approach is to keep your video at its original aspect ratio and replace the black bars with a visually appealing background. ConvertPortrait does this automatically with four background options:</p>
<ul>
<li><strong>Blurred Mirror:</strong> Your video, blurred and scaled, fills the background. Creates visual continuity.</li>
<li><strong>Solid Color:</strong> Clean, minimal backdrop in any color you choose.</li>
<li><strong>Gradient Wash:</strong> Colorful gradients that add energy to the frame.</li>
<li><strong>Pattern Fill:</strong> Geometric dots, lines, or chevrons for a textured look.</li>
</ul>

<h2>How to Do It</h2>
<ol>
<li>Go to ConvertPortrait.com</li>
<li>Upload your vertical video (MP4, MOV, WebM, or AVI)</li>
<li>Choose a background style — Blurred Mirror is the most popular choice</li>
<li>Click Convert and download your black-bar-free video</li>
</ol>',
                'published_at' => $now->copy()->subDays(6),
            ],
            [
                'title' => '5 Ways to Repurpose Instagram Reels for Other Platforms',
                'slug' => 'repurpose-instagram-reels-for-other-platforms',
                'meta_description' => '5 practical strategies to repurpose your Instagram Reels content for YouTube, websites, presentations, and other platforms.',
                'excerpt' => 'Your Instagram Reels have more value than a single platform. Here are 5 ways to repurpose them and reach new audiences everywhere.',
                'content' => '<h2>Your Reels Are Valuable Content Assets</h2>
<p>Every Reel you create represents hours of planning, filming, and editing. Letting that content live on only one platform means you\'re leaving views, engagement, and potential revenue on the table. The good news: with a few format adjustments, your Reels can work across multiple platforms.</p>

<h2>1. Convert to YouTube Videos</h2>
<p>This is the biggest opportunity. YouTube has 2+ billion monthly users and strong search intent — people actively looking for content like yours. Convert your Reels to 16:9 landscape, write search-optimized titles and descriptions, and upload them as regular YouTube videos.</p>

<h2>2. Embed on Your Website or Blog</h2>
<p>Converted landscape Reels work great as embedded video content on your website. A product demo Reel becomes a product page video. A tutorial Reel becomes a blog post supplement. Landscape video looks significantly better embedded on websites than portrait video.</p>

<h2>3. Use in Presentations and Pitches</h2>
<p>Sales presentations, investor decks, and conference talks all use widescreen formats. Your best Reels — product demos, customer reactions, behind-the-scenes content — can be powerful additions to presentations. Convert them to landscape so they fill the presentation screen properly.</p>

<h2>4. Create YouTube Shorts</h2>
<p>Instagram Reels and YouTube Shorts use the same 9:16 format, so no conversion is needed — just download your Reel and upload it as a Short. This is the easiest repurposing strategy.</p>

<h2>5. Compile into Long-Form Content</h2>
<p>Take 5-10 related Reels, convert them all to landscape, and edit them into a single compilation video. These compilations perform well on YouTube and can be 5-10 minutes long, qualifying for mid-roll ads.</p>',
                'published_at' => $now->copy()->subDays(8),
            ],
            [
                'title' => 'How to Make Vertical Videos Look Good on TV and Big Screens',
                'slug' => 'vertical-videos-on-tv-big-screens',
                'meta_description' => 'Make vertical videos look professional on TVs, monitors, and projectors. Convert portrait video to landscape with styled backgrounds.',
                'excerpt' => 'Vertical videos look terrible on TVs. Here\'s how to convert them so they fill the screen and actually look good on big displays.',
                'content' => '<h2>The Big Screen Problem</h2>
<p>TVs, monitors, and projectors are all landscape-oriented. When you play a vertical video on any of these screens, the result is a tiny sliver of content in the center surrounded by black. On a 65-inch TV, your portrait video might only use the equivalent of a 22-inch strip.</p>

<h2>Why This Matters</h2>
<p>Big screens amplify the black bar problem. On a phone, portrait video fills the screen perfectly. On a TV, those same videos look comically small. If you\'re displaying content professionally — in a store, at a conference, or in a client presentation — this is especially problematic.</p>

<h2>The Solution: Convert Before Displaying</h2>
<p>Before playing vertical videos on any big screen, convert them to landscape format. This replaces the black bars with a styled background and creates a proper 16:9 video that fills widescreen displays edge to edge.</p>

<h3>Best Background Styles for Big Screens</h3>
<p><strong>Blurred Mirror</strong> is the best choice for TV viewing. The blurred version of your video fills the background, creating a cohesive look that\'s visually interesting without distracting from the main content.</p>
<p><strong>Solid Color</strong> works well for branded content in retail or corporate settings.</p>
<p><strong>Gradient</strong> backgrounds look stunning on large displays, especially OLED TVs where the colors really pop.</p>

<h2>Common Use Cases</h2>
<ul>
<li><strong>Home TV:</strong> Convert phone videos before AirPlaying or Chromecasting</li>
<li><strong>Digital signage:</strong> Convert portrait social media content for landscape display screens</li>
<li><strong>Events:</strong> Convert attendee-submitted videos for big screen playback</li>
<li><strong>Meetings:</strong> Convert social media clips for presentation on conference room screens</li>
<li><strong>Retail:</strong> Convert user-generated TikToks/Reels for in-store displays</li>
</ul>',
                'published_at' => $now->copy()->subDays(10),
            ],
            [
                'title' => 'The Best Video Formats for YouTube in 2026',
                'slug' => 'best-video-formats-for-youtube-2026',
                'meta_description' => 'The complete guide to YouTube video formats in 2026. Resolution, aspect ratio, file type, frame rate, and codec recommendations.',
                'excerpt' => 'Everything you need to know about YouTube video formats — resolution, aspect ratio, file type, frame rate, and what settings get the best quality.',
                'content' => '<h2>YouTube\'s Recommended Specs</h2>
<p>YouTube accepts almost any video format, but not all formats are equal. Using the right settings ensures your video looks sharp, loads fast, and gets the best possible quality after YouTube\'s processing.</p>

<h3>Resolution</h3>
<ul>
<li><strong>4K (3840x2160):</strong> Best quality, recommended if your source is 4K</li>
<li><strong>1440p (2560x1440):</strong> Great quality, good balance of file size and sharpness</li>
<li><strong>1080p (1920x1080):</strong> The standard. Most viewers watch at this resolution.</li>
<li><strong>720p (1280x720):</strong> Minimum for HD. Acceptable but not ideal.</li>
</ul>

<h3>Aspect Ratio</h3>
<p><strong>16:9</strong> is YouTube\'s standard aspect ratio. This fills the player perfectly on desktop, mobile, TV apps, and embedded players. If your video isn\'t 16:9, YouTube adds black bars to fill the space.</p>

<h3>File Format</h3>
<p><strong>MP4 with H.264 codec</strong> is the safest choice. It\'s universally compatible, efficient in file size, and YouTube processes it quickly.</p>

<h3>Frame Rate</h3>
<p>Upload at your source frame rate. YouTube supports 24, 25, 30, 48, 50, and 60 fps. Most content looks best at 30fps. Gaming and sports content benefits from 60fps.</p>

<h3>Audio</h3>
<p><strong>AAC codec, stereo, 48kHz sample rate.</strong> Bitrate of 384kbps for stereo is ideal.</p>

<h2>Common Mistakes to Avoid</h2>
<ul>
<li><strong>Uploading portrait video without converting:</strong> Results in black bars and wasted screen space</li>
<li><strong>Re-encoding before upload:</strong> Each re-encode loses quality. Upload the highest quality source you have.</li>
<li><strong>Wrong frame rate conversion:</strong> Don\'t convert 30fps to 60fps or vice versa. Upload at the original frame rate.</li>
<li><strong>Ignoring thumbnail:</strong> Upload a custom 1280x720 thumbnail — don\'t rely on auto-generated options</li>
</ul>',
                'published_at' => $now->copy()->subDays(12),
            ],
        ];

        foreach ($posts as $post) {
            DB::table('blog_posts')->updateOrInsert(
                ['slug' => $post['slug']],
                array_merge($post, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        $slugs = [
            'convert-tiktok-videos-to-youtube-format',
            'portrait-vs-landscape-video-when-to-convert',
            'convert-9-16-to-16-9-without-black-bars',
            'convert-youtube-shorts-to-regular-videos',
            'tiktok-to-youtube-complete-repurposing-guide',
            'what-aspect-ratio-does-youtube-use',
            'remove-black-bars-from-vertical-videos',
            'repurpose-instagram-reels-for-other-platforms',
            'vertical-videos-on-tv-big-screens',
            'best-video-formats-for-youtube-2026',
        ];

        DB::table('blog_posts')->whereIn('slug', $slugs)->delete();
    }
};
