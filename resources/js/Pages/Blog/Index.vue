<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    posts: Object,
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
        title="Blog — ConvertPortrait"
        meta-description="Tips, guides, and tutorials on converting portrait videos to landscape format. Learn about video aspect ratios, TikTok to YouTube conversion, and more."
    >
        <div class="max-w-4xl mx-auto px-6 pt-16 sm:pt-24 pb-20">
            <!-- Hero -->
            <div class="text-center mb-14 stagger">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight mb-4">
                    The <span class="font-editorial font-normal text-teal">ConvertPortrait</span> Blog
                </h1>
                <p class="text-[15px] text-gray-600 max-w-lg mx-auto leading-relaxed">
                    Tips, tutorials, and guides on converting portrait videos to landscape — from TikTok to YouTube and beyond.
                </p>
            </div>

            <!-- Posts Grid -->
            <div v-if="posts.data.length" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <a
                    v-for="post in posts.data"
                    :key="post.id"
                    :href="`/blog/${post.slug}`"
                    class="group block bg-surface/50 border border-gray-200 rounded-2xl p-6 hover:border-teal/20 hover:bg-surface-light/50 transition-all duration-300"
                >
                    <time class="text-xs text-gray-500 mb-3 block">{{ formatDate(post.published_at) }}</time>
                    <h2 class="text-lg font-bold text-gray-800 group-hover:text-gray-900 transition mb-2 leading-snug">
                        {{ post.title }}
                    </h2>
                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                        {{ post.excerpt }}
                    </p>
                    <span class="inline-flex items-center gap-1 text-xs text-teal/60 mt-4 group-hover:text-teal transition">
                        Read more
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>
            </div>

            <div v-else class="text-center py-20">
                <p class="text-gray-600">No posts yet. Check back soon!</p>
            </div>

            <!-- Pagination -->
            <div v-if="posts.last_page > 1" class="flex justify-center gap-2 mt-12">
                <a
                    v-for="page in posts.last_page"
                    :key="page"
                    :href="`/blog?page=${page}`"
                    :class="[
                        'w-9 h-9 rounded-lg flex items-center justify-center text-sm font-medium transition',
                        page === posts.current_page
                            ? 'bg-teal text-white'
                            : 'bg-gray-100/80 text-gray-600 hover:bg-gray-200/80 hover:text-gray-800'
                    ]"
                >
                    {{ page }}
                </a>
            </div>
        </div>
    </AppLayout>
</template>
