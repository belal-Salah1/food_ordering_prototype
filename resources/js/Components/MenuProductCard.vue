<script setup>
import { computed } from 'vue';
import { formatCurrency } from '@/lib/guestCart';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    locale: {
        type: String,
        default: 'en',
    },
    quantity: {
        type: Number,
        default: 0,
    },
    labels: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['add']);

const priceLabel = computed(() =>
    formatCurrency(Number(props.product.price), props.locale),
);

function handleAdd() {
    emit('add', props.product);
}
</script>

<template>
    <article
        class="group flex h-full flex-col overflow-hidden rounded-[28px] border border-[#e7ded3] bg-white shadow-[0px_8px_24px_rgba(33,22,11,0.04)] transition duration-300 hover:-translate-y-1 hover:shadow-[0px_18px_40px_rgba(33,22,11,0.08)]"
    >
        <div class="relative aspect-[4/3] overflow-hidden bg-[#fbf7f2]">
            <img
                :src="product.imageUrl"
                :alt="product.name"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
            />

            <div
                class="absolute start-4 top-4 rounded-full border border-white/60 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-[#da532c] shadow-sm"
            >
                {{ product.category || labels.uncategorized }}
            </div>
        </div>

        <div class="flex flex-1 flex-col p-5">
            <div class="mb-4 flex items-start justify-between gap-4">
                <div>
                    <p
                        class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]"
                    >
                        {{ labels.category }}
                    </p>
                    <h3 class="mt-1 text-xl font-semibold text-[#231f1b]">
                        {{ product.name }}
                    </h3>
                </div>

                <div
                    class="shrink-0 rounded-full bg-[#fff4f0] px-3 py-2 text-sm font-semibold text-[#da532c]"
                >
                    {{ priceLabel }}
                </div>
            </div>

            <p
                class="flex-1 text-sm leading-6 text-[#65584d]"
                :style="{
                    display: '-webkit-box',
                    WebkitBoxOrient: 'vertical',
                    WebkitLineClamp: 4,
                    overflow: 'hidden',
                }"
            >
                {{ product.description }}
            </p>

            <div
                class="mt-5 flex items-center justify-between gap-3 border-t border-dashed border-[#e7ded3] pt-4"
            >
                <span class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]">
                    {{ quantity > 0 ? `${labels.added} (${quantity})` : labels.addToCart }}
                </span>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full bg-[#da532c] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#bf4926]"
                    @click="handleAdd"
                >
                    {{ quantity > 0 ? labels.added : labels.addToCart }}
                </button>
            </div>
        </div>
    </article>
</template>
