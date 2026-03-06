<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    post: Object,
});

function formatDate(dateStr) {
    return new Date(dateStr).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
</script>

<template>
    <AppLayout
        :title="`${post.title} — ConvertPortrait Blog`"
        :meta-description="post.meta_description || post.excerpt"
        :canonical="`https://convertportrait.com/blog/${post.slug}`"
    >
        <article class="max-w-3xl mx-auto px-6 pt-16 sm:pt-24 pb-20">
            <!-- Back link -->
            <a href="/blog" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-gray-500 transition mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Blog
            </a>

            <!-- Header -->
            <header class="mb-10">
                <time class="text-xs text-gray-300 mb-3 block">{{ formatDate(post.published_at) }}</time>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-gray-900">
                    {{ post.title }}
                </h1>
            </header>

            <!-- Content -->
            <div
                class="prose prose-sm max-w-none
                    prose-headings:font-bold prose-headings:tracking-tight
                    prose-h2:text-xl prose-h2:text-gray-800 prose-h2:mt-10 prose-h2:mb-4
                    prose-h3:text-lg prose-h3:text-gray-700 prose-h3:mt-8 prose-h3:mb-3
                    prose-p:text-gray-500 prose-p:leading-relaxed prose-p:mb-4
                    prose-a:text-teal prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-gray-700 prose-strong:font-semibold
                    prose-ul:text-gray-500 prose-ol:text-gray-500
                    prose-li:mb-1
                    prose-blockquote:border-teal/30 prose-blockquote:text-gray-400"
                v-html="post.content"
            />

            <!-- CTA -->
            <div class="mt-16 bg-surface/50 border border-gray-200 rounded-2xl p-8 text-center">
                <h2 class="text-xl font-bold tracking-tight mb-2 text-gray-800">Ready to convert your video?</h2>
                <p class="text-sm text-gray-400 mb-6">Convert portrait videos to landscape format — free, private, no upload required.</p>
                <a
                    href="/"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-400 to-purple-400 text-white font-bold text-sm px-6 py-3 rounded-full hover:opacity-90 transition"
                >
                    Convert Your Video Now
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </article>
    </AppLayout>
</template>
