<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import LocaleSwitcher from '@/Components/LocaleSwitcher.vue';

const page = usePage();
const ui = computed(() => page.props.ui?.menu || {
    brand: 'MealBox',
    brand_tagline: 'Real meals, local cart',
});
const isArabic = computed(() => (page.props.locale ?? 'en') === 'ar');
</script>

<template>
    <div
        :dir="isArabic ? 'rtl' : 'ltr'"
        class="flex min-h-screen flex-col items-center bg-[#fbfaf8] py-12 sm:justify-center"
        style="font-family: Arial, Helvetica, sans-serif"
    >
        <div class="mb-8">
            <Link href="/" class="group flex flex-col items-center gap-4 text-center">
                <span
                    class="flex h-16 w-16 items-center justify-center rounded-full border border-[#da532c] bg-[#fff4f0] text-xl font-bold uppercase tracking-[0.24em] text-[#da532c]"
                >
                    MB
                </span>

                <span class="space-y-1">
                    <span
                        class="block text-2xl font-bold uppercase tracking-[0.3em] text-[#231f1b]"
                    >
                        {{ ui.brand }}
                    </span>
                    <span
                        class="block text-xs font-semibold uppercase tracking-[0.28em] text-[#da532c]"
                    >
                        {{ ui.brand_tagline }}
                    </span>
                </span>
            </Link>
        </div>

        <div
            class="w-full max-w-[440px] overflow-hidden rounded-[2.5rem] border border-[#e7ded3] bg-white p-8 shadow-sm sm:max-w-md lg:p-10"
        >
            <slot />
        </div>

        <div class="mt-8 flex items-center justify-center gap-4">
            <LocaleSwitcher />
        </div>
    </div>
</template>
