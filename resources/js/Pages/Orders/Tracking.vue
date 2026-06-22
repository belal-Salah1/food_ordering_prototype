<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { __ } from '@/lib/i18n';

const props = defineProps({
    order: Object,
    statuses: Array,
});

const page = usePage();
const ui = computed(() => page.props.ui);
const isArabic = computed(() => page.props.locale === 'ar');

const currentStatusIndex = computed(() => {
    return props.statuses.indexOf(props.order.status);
});

const getStatusLabel = (status) => {
    const labels = {
        pending: __('pending'),
        confirmed: __('confirmed'),
        preparing: __('preparing'),
        out_for_delivery: __('out_delivery'),
        delivered: __('delivered'),
        cancelled: __('cancelled'),
    };
    return labels[status] || status;
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat(isArabic.value ? 'ar-EG' : 'en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};
</script>

<template>
    <PublicLayout>
        <Head :title="__('track_order')" />

        <div class="mx-auto max-w-3xl py-10">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#231f1b]">
                        {{ __('track_order') }} #{{ order.id }}
                    </h1>
                    <p class="mt-2 text-[#6b5f55]">
                        {{ __('current_status') }} 
                        <span class="font-bold text-[#da532c]">{{ getStatusLabel(order.status) }}</span>
                    </p>
                </div>
                <Link :href="route('menu')" class="text-sm font-bold uppercase tracking-widest text-[#da532c] hover:underline">
                    {{ __('back_menu') }}
                </Link>
            </div>

            <!-- Steps Progress -->
            <div v-if="order.status !== 'cancelled'" class="mb-12 relative flex items-center justify-between">
                <div class="absolute left-0 top-1/2 -z-10 h-0.5 w-full -translate-y-1/2 bg-[#e7ded3]"></div>
                <div 
                    class="absolute left-0 top-1/2 -z-0 h-0.5 -translate-y-1/2 bg-[#da532c] transition-all duration-500"
                    :style="{ width: (currentStatusIndex / (statuses.length - 2)) * 100 + '%' }"
                ></div>

                <div 
                    v-for="(status, index) in statuses.filter(s => s !== 'cancelled')" 
                    :key="status"
                    class="flex flex-col items-center gap-3"
                >
                    <div 
                        class="flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all duration-500"
                        :class="index <= currentStatusIndex ? 'border-[#da532c] bg-[#da532c] text-white' : 'border-[#e7ded3] bg-white text-[#6b5f55]'"
                    >
                        <svg v-if="index < currentStatusIndex" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span v-else class="text-sm font-bold">{{ index + 1 }}</span>
                    </div>
                    <span 
                        class="hidden text-[10px] font-bold uppercase tracking-widest sm:block"
                        :class="index <= currentStatusIndex ? 'text-[#da532c]' : 'text-[#6b5f55]'"
                    >
                        {{ getStatusLabel(status) }}
                    </span>
                </div>
            </div>

            <!-- Cancelled State -->
            <div v-else class="mb-12 rounded-3xl bg-red-50 p-8 text-center border border-red-100">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-red-700">{{ __('order_cancelled') }}</h2>
            </div>

            <!-- Order Details -->
            <div class="grid gap-8 md:grid-cols-2">
                <div class="rounded-[2.5rem] border border-[#e7ded3] bg-white p-8">
                    <h3 class="text-lg font-bold text-[#231f1b] mb-6">{{ __('order_items') }}</h3>
                    <div class="space-y-4">
                        <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-[#fbfaf8] overflow-hidden flex-shrink-0">
                                <img :src="item.product.image_url" class="h-full w-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-[#231f1b] truncate">{{ item.product.name }}</p>
                                <p class="text-xs text-[#6b5f55]">{{ item.quantity }} x {{ formatCurrency(item.unit_price) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-[#e7ded3]">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-[#231f1b]">{{ __('total') }}</span>
                            <span class="text-xl font-bold text-[#da532c]">{{ formatCurrency(order.total_amount) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2.5rem] border border-[#e7ded3] bg-[#fbfaf8] p-8">
                    <h3 class="text-lg font-bold text-[#231f1b] mb-6">{{ __('delivery_info') }}</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="font-bold text-[#6b5f55] uppercase tracking-widest text-[10px]">{{ __('address') }}</p>
                            <p class="mt-1 text-[#231f1b] leading-relaxed">{{ order.address }}</p>
                        </div>
                        <div v-if="order.notes">
                            <p class="font-bold text-[#6b5f55] uppercase tracking-widest text-[10px]">{{ __('notes') }}</p>
                            <p class="mt-1 text-[#231f1b] leading-relaxed italic">"{{ order.notes }}"</p>
                        </div>
                        <div>
                            <p class="font-bold text-[#6b5f55] uppercase tracking-widest text-[10px]">{{ __('payment_method') }}</p>
                            <p class="mt-1 text-[#231f1b] uppercase font-bold">{{ order.payment_method }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>