<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout
        title="Privacy Policy — ConvertPortrait"
        meta-description="ConvertPortrait privacy policy. Your video never leaves your device in free mode. No tracking, no cookies, no data collection."
    >
        <article class="max-w-3xl mx-auto px-6 pt-16 sm:pt-24 pb-20">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-gray-900 mb-10">Privacy Policy</h1>

            <div class="prose prose-gray max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-h2:text-2xl prose-h2:text-gray-900 prose-h2:mt-12 prose-h2:mb-4 prose-p:text-gray-700 prose-p:leading-[1.8] prose-p:mb-5 prose-p:text-[16.5px] prose-strong:text-gray-900 prose-ul:text-gray-700 prose-li:mb-2 prose-li:text-[16.5px]">
                <p><strong>Last updated:</strong> March 6, 2026</p>
                <p>ConvertPortrait is operated by Krager Labs LLC. We take your privacy seriously. This policy explains what data we collect (spoiler: almost none) and how we handle it.</p>

                <h2>Free Mode — Zero Data Collection</h2>
                <p>When you use ConvertPortrait in free mode, your video is processed <strong>entirely in your browser</strong> using WebAssembly technology. Your video file never leaves your device. We do not upload, store, or have access to your video content in any way.</p>
                <p>In free mode, we collect:</p>
                <ul>
                    <li><strong>Nothing.</strong> No cookies, no analytics, no tracking pixels, no fingerprinting. Your browser does all the work locally.</li>
                </ul>

                <h2>Pro Mode — Minimal Data</h2>
                <p>If you purchase Pro and use server-side processing, the following occurs:</p>
                <ul>
                    <li>Your video is uploaded to a temporary Amazon S3 bucket for processing</li>
                    <li>The video is processed by AWS Lambda and the result is made available for download</li>
                    <li><strong>All video files are automatically deleted within 24 hours</strong> via S3 lifecycle rules</li>
                    <li>We store your email address to verify your Pro purchase — nothing else</li>
                </ul>

                <h2>Payment Information</h2>
                <p>Payments are processed by <strong>Stripe</strong>. We never see or store your credit card number, CVV, or billing details. Stripe handles all payment data under their own <a href="https://stripe.com/privacy" target="_blank" rel="noopener">privacy policy</a>.</p>
                <p>We store only your email address and Stripe customer ID to manage your Pro status.</p>

                <h2>Cookies</h2>
                <p>ConvertPortrait uses <strong>no tracking cookies</strong>. The only local storage used is a localStorage key to remember your Pro status on your device. No third-party cookies are set.</p>

                <h2>Third-Party Services</h2>
                <ul>
                    <li><strong>Stripe</strong> — payment processing (Pro purchases only)</li>
                    <li><strong>Amazon Web Services</strong> — video processing and temporary storage (Pro server mode only)</li>
                    <li><strong>DigitalOcean</strong> — web hosting</li>
                    <li><strong>Google Fonts</strong> — font delivery (standard browser caching, no tracking)</li>
                </ul>

                <h2>Data Retention</h2>
                <ul>
                    <li>Free mode: No data is collected or retained</li>
                    <li>Pro video files: Automatically deleted within 24 hours</li>
                    <li>Pro email addresses: Retained for the lifetime of your account</li>
                </ul>

                <h2>Your Rights</h2>
                <p>You can request deletion of your email and Pro purchase record at any time by contacting us at <a href="mailto:scott@kragerlab.com">scott@kragerlab.com</a>.</p>

                <h2>Contact</h2>
                <p>If you have questions about this privacy policy, contact us at <a href="mailto:scott@kragerlab.com">scott@kragerlab.com</a>.</p>
                <p>Krager Labs LLC<br><a href="https://kragerlabs.com" target="_blank" rel="noopener">KragerLabs.com</a></p>
            </div>
        </article>
    </AppLayout>
</template>
