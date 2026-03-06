<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout
        title="Contact Us — ConvertPortrait"
        meta-description="Get in touch with the ConvertPortrait team. Questions about the tool, Pro purchases, or anything else — we're here to help."
    >
        <div class="max-w-2xl mx-auto px-6 pt-16 sm:pt-24 pb-20">
            <div class="text-center mb-14">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-gray-900 mb-4">
                    Get in Touch
                </h1>
                <p class="text-[16.5px] text-gray-600 max-w-lg mx-auto leading-relaxed">
                    Have a question, feature request, or need help with your Pro purchase? We'd love to hear from you.
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-8 sm:p-10 shadow-sm">
                <div class="space-y-8">
                    <!-- Email -->
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-teal/10 border border-teal/10 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-teal" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900 mb-1">Email</h2>
                            <a href="mailto:scott@kragerlab.com" class="text-[16.5px] text-teal hover:underline">scott@kragerlab.com</a>
                            <p class="text-sm text-gray-500 mt-1.5">We typically respond within 24 hours.</p>
                        </div>
                    </div>

                    <!-- Common topics -->
                    <div class="border-t border-gray-100 pt-8">
                        <h2 class="text-base font-bold text-gray-900 mb-4">Common Topics</h2>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <span class="text-teal mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                </span>
                                <p class="text-sm text-gray-700"><strong class="text-gray-900">Pro purchase issues</strong> — Can't activate your Pro status? Email us with the email you used at checkout.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-teal mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                </span>
                                <p class="text-sm text-gray-700"><strong class="text-gray-900">Feature requests</strong> — We're always looking to improve. Let us know what you'd like to see.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-teal mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                </span>
                                <p class="text-sm text-gray-700"><strong class="text-gray-900">Bug reports</strong> — If something isn't working, include your browser and the file type you're converting.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-teal mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                </span>
                                <p class="text-sm text-gray-700"><strong class="text-gray-900">Refunds</strong> — Available within 7 days of purchase if you haven't used server processing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company info -->
            <div class="mt-10 text-center">
                <p class="text-sm text-gray-500">
                    ConvertPortrait is built by <a href="https://kragerlabs.com" target="_blank" rel="noopener" class="text-teal hover:underline">Krager Labs LLC</a>
                </p>
            </div>
        </div>
    </AppLayout>
</template>
