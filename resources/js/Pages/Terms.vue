<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout
        title="Terms of Service — ConvertPortrait"
        meta-description="ConvertPortrait terms of service. Read our terms for using the free and Pro video conversion service."
    >
        <article class="max-w-3xl mx-auto px-6 pt-16 sm:pt-24 pb-20">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-gray-900 mb-10">Terms of Service</h1>

            <div class="prose prose-gray max-w-none prose-headings:font-bold prose-headings:tracking-tight prose-h2:text-2xl prose-h2:text-gray-900 prose-h2:mt-12 prose-h2:mb-4 prose-p:text-gray-700 prose-p:leading-[1.8] prose-p:mb-5 prose-p:text-[16.5px] prose-strong:text-gray-900 prose-ul:text-gray-700 prose-li:mb-2 prose-li:text-[16.5px]">
                <p><strong>Last updated:</strong> March 6, 2026</p>
                <p>These terms govern your use of ConvertPortrait, operated by Krager Labs LLC. By using the service, you agree to these terms.</p>

                <h2>The Service</h2>
                <p>ConvertPortrait is a video conversion tool that transforms portrait (vertical) videos into landscape (horizontal) format. The service is offered in two tiers:</p>
                <ul>
                    <li><strong>Free tier:</strong> Browser-based conversion using WebAssembly. No account required. Video never leaves your device.</li>
                    <li><strong>Pro tier ($19.99 one-time):</strong> Server-side conversion via AWS Lambda for faster processing. Includes all background templates and larger file support.</li>
                </ul>

                <h2>Acceptable Use</h2>
                <p>You agree to use ConvertPortrait only for lawful purposes. You must have the right to convert any video you process through our service. You may not:</p>
                <ul>
                    <li>Use the service to process content you don't have rights to</li>
                    <li>Attempt to reverse-engineer, abuse, or overload the service</li>
                    <li>Use automated tools to bulk-process videos without permission</li>
                    <li>Resell or redistribute the service as your own</li>
                </ul>

                <h2>Pro Purchases</h2>
                <p>Pro is a <strong>one-time lifetime purchase</strong> at $19.99 USD. "Lifetime" means for the lifetime of the service. There are no recurring charges. Your Pro status is tied to your email address.</p>
                <p>Refunds are available within 7 days of purchase if you have not used the server-side processing feature. Contact <a href="mailto:scott@kragerlab.com">scott@kragerlab.com</a> for refund requests.</p>

                <h2>Your Content</h2>
                <p>You retain all rights to your videos. We do not claim any ownership or license over content you process through ConvertPortrait.</p>
                <p>In free mode, your video is processed locally and we never have access to it. In Pro mode, your video is temporarily uploaded for processing and <strong>automatically deleted within 24 hours</strong>.</p>

                <h2>Service Availability</h2>
                <p>We strive to keep ConvertPortrait available 24/7, but we do not guarantee uninterrupted service. We may modify, suspend, or discontinue any part of the service at any time. Free tier functionality may change over time.</p>

                <h2>Limitation of Liability</h2>
                <p>ConvertPortrait is provided "as is" without warranties of any kind. Krager Labs LLC is not liable for any indirect, incidental, or consequential damages arising from your use of the service. Our total liability is limited to the amount you paid for Pro access ($19.99).</p>

                <h2>Changes to Terms</h2>
                <p>We may update these terms from time to time. Continued use of the service after changes constitutes acceptance of the new terms.</p>

                <h2>Contact</h2>
                <p>Questions about these terms? Contact us at <a href="mailto:scott@kragerlab.com">scott@kragerlab.com</a>.</p>
                <p>Krager Labs LLC<br><a href="https://kragerlabs.com" target="_blank" rel="noopener">KragerLabs.com</a></p>
            </div>
        </article>
    </AppLayout>
</template>
