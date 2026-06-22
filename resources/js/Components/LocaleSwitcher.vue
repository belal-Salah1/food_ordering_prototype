<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { update as updateLocale } from '@/routes/locale';

const page = usePage();

const currentLocale = computed(() => page.props.locale ?? 'en');
const locales = computed(() => page.props.locales ?? []);
</script>

<template>
    <div class="inline-flex items-center rounded-full border border-[#e7ded3] bg-white p-1">
        <Link
            v-for="locale in locales"
            :key="locale.code"
            :href="updateLocale.url({ locale: locale.code })"
            class="rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] transition"
            :class="
                currentLocale === locale.code
                    ? 'bg-[#da532c] text-white'
                    : 'text-[#6b5f55] hover:text-[#da532c]'
            "
        >
            {{ locale.label }}
        </Link>
    </div>
</template>
