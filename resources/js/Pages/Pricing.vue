<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const checkoutLoading = ref(false);
const showRestoreModal = ref(false);
const restoreEmail = ref('');
const restoreLoading = ref(false);
const restoreMessage = ref('');

async function startCheckout() {
    checkoutLoading.value = true;
    try {
        const res = await fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
        });
        const data = await res.json();
        if (data.url) window.location.href = data.url;
    } catch (err) {
        console.error('Checkout error:', err);
    } finally {
        checkoutLoading.value = false;
    }
}

async function restorePurchase() {
    if (!restoreEmail.value) return;
    restoreLoading.value = true;
    restoreMessage.value = '';

    try {
        const res = await fetch('/api/restore', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ email: restoreEmail.value }),
        });
        const data = await res.json();

        if (data.pro) {
            localStorage.setItem('convertportrait_pro', restoreEmail.value);
            restoreMessage.value = 'Pro activated! Redirecting...';
            setTimeout(() => { window.location.href = '/'; }, 1500);
        } else {
            restoreMessage.value = 'No Pro purchase found for that email. It may take a minute after payment — try again shortly.';
        }
    } catch {
        restoreMessage.value = 'Something went wrong. Please try again.';
    } finally {
        restoreLoading.value = false;
    }
}

const faqs = [
    {
        q: 'Can I cancel anytime?',
        a: 'Yes, cancel anytime from your Stripe billing portal. You keep access until your current period ends.',
    },
    {
        q: 'What happens when my subscription expires?',
        a: "You'll lose access to Pro features (server processing, premium templates) but can still use the free browser-based converter.",
    },
    {
        q: 'Can I get a refund?',
        a: 'Yes, contact scott@kragerlab.com within 7 days for a full refund.',
    },
];
</script>

<template>
    <AppLayout
        title="Pricing — ConvertPortrait"
        metaDescription="Simple, transparent pricing. Free browser-based video conversion or Pro for $19.99/year with server-side processing up to 140x faster."
    >
        <div class="max-w-5xl mx-auto px-6 py-16">
            <!-- Hero -->
            <div class="text-center mb-14">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 mb-3">Simple, transparent pricing</h1>
                <p class="text-gray-600 text-base max-w-lg mx-auto">Convert portrait videos to landscape for free in your browser, or go Pro for blazing-fast server processing.</p>
            </div>

            <!-- Pricing Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-3xl mx-auto mb-20">
                <!-- Free -->
                <div class="bg-gray-100/60 border border-gray-200 rounded-2xl p-8 flex flex-col">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Free</h2>
                    <p class="text-sm text-gray-600 mb-6">Always free, no account required</p>

                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-extrabold text-gray-900">$0</span>
                        <span class="text-sm text-gray-500">forever</span>
                    </div>

                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Browser-based conversion (video never leaves device)
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Blurred Mirror + Solid Color backgrounds
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            1920x1080 Full HD output
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Files up to 200MB
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            No account required
                        </li>
                    </ul>

                    <a href="/" class="block w-full text-center text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:border-gray-400 py-3 rounded-xl transition">
                        Start Converting
                    </a>
                </div>

                <!-- Pro -->
                <div class="relative bg-amber-400/[0.03] border-2 border-amber-400/20 rounded-2xl p-8 flex flex-col">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="text-[10px] font-bold text-white bg-gradient-to-r from-amber-400 to-orange-500 px-3 py-1 rounded-full uppercase tracking-wider">Most Popular</span>
                    </div>

                    <h2 class="text-lg font-bold text-gray-900 mb-1">Pro</h2>
                    <p class="text-sm text-gray-600 mb-6">For creators who need speed</p>

                    <div class="flex items-baseline gap-1 mb-1">
                        <span class="text-sm font-medium text-gray-600">USD</span>
                        <span class="text-4xl font-extrabold text-gray-900">$19</span>
                        <span class="text-xl font-bold text-gray-600">.99</span>
                        <span class="text-sm text-gray-500">/year</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-6">Cancel anytime</p>

                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-sm text-gray-700 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Everything in Free, plus:
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Server-side processing — up to 140x faster
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            All templates (Gradient Wash, Pattern Fill)
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Files up to 500MB
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Priority support
                        </li>
                        <li class="flex items-start gap-2.5 text-sm text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Cancel anytime
                        </li>
                    </ul>

                    <button
                        @click="startCheckout"
                        :disabled="checkoutLoading"
                        class="w-full text-center text-sm font-bold text-white bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-300 hover:to-orange-400 disabled:opacity-50 py-3 rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-amber-500/20 active:scale-[0.98]"
                    >
                        <span v-if="checkoutLoading" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Redirecting...
                        </span>
                        <span v-else>Get Pro — $19.99/year</span>
                    </button>

                    <div class="flex items-center justify-center gap-3 mt-4">
                        <div class="flex items-center gap-1.5 text-[11px] text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Secured by Stripe
                        </div>
                    </div>
                </div>
            </div>

            <!-- Restore -->
            <div class="text-center mb-20">
                <button @click="showRestoreModal = true" class="text-sm text-gray-500 hover:text-gray-700 transition underline underline-offset-2">
                    Already a Pro member? Restore here
                </button>
            </div>

            <!-- FAQ -->
            <div class="max-w-xl mx-auto mb-8">
                <h2 class="text-lg font-bold tracking-tight text-center mb-8 text-gray-900">Frequently asked questions</h2>
                <div class="space-y-3">
                    <details v-for="faq in faqs" :key="faq.q" class="group bg-gray-100/60 border border-gray-200 rounded-xl">
                        <summary class="flex items-center justify-between cursor-pointer px-5 py-4 text-base font-semibold text-gray-700 hover:text-gray-900 transition">
                            {{ faq.q }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 transition-transform group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </summary>
                        <p class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">{{ faq.a }}</p>
                    </details>
                </div>
            </div>
        </div>

        <!-- Restore Modal -->
        <Teleport to="body">
            <div v-if="showRestoreModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showRestoreModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6">
                    <button @click="showRestoreModal = false" class="absolute top-4 right-4 p-1 text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Restore Pro</h3>
                    <p class="text-sm text-gray-600 mb-4">Enter the email you used at checkout to restore your Pro access.</p>
                    <input
                        v-model="restoreEmail"
                        type="email"
                        placeholder="your@email.com"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-400/50 focus:border-amber-400 mb-3"
                        @keyup.enter="restorePurchase"
                    />
                    <p v-if="restoreMessage" class="text-xs mb-3" :class="restoreMessage.includes('activated') ? 'text-teal' : 'text-red-500'">{{ restoreMessage }}</p>
                    <button
                        @click="restorePurchase"
                        :disabled="restoreLoading || !restoreEmail"
                        class="w-full text-sm font-semibold text-white bg-gradient-to-r from-pink-400 to-purple-400 hover:opacity-90 disabled:opacity-50 py-2.5 rounded-lg transition"
                    >
                        <span v-if="restoreLoading">Checking...</span>
                        <span v-else>Restore Purchase</span>
                    </button>
                </div>
            </div>
        </Teleport>
    </AppLayout>
</template>
