<script setup>
import { computed } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { __ } from '@/lib/i18n';

const props = defineProps({
    order: Object,
});

const page = usePage();
const isArabic = computed(() => page.props.locale === 'ar');

const statuses = [
    'pending',
    'confirmed',
    'preparing',
    'out_for_delivery',
    'delivered',
    'cancelled'
];

const updateStatus = (status) => {
    router.patch(route('admin.orders.update-status', props.order.id), {
        status: status
    });
};

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
        <Head :title="__('order_details')" />

        <div class="mx-auto max-w-4xl py-10">
            <h1 class="text-3xl font-bold text-[#231f1b] mb-8">
                {{ __('order') }} #{{ order.id }}
            </h1>

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Status Management -->
                    <div class="rounded-[2.5rem] border border-[#e7ded3] bg-white p-8">
                        <h2 class="text-lg font-bold text-[#231f1b] mb-6">{{ __('update_status') }}</h2>
                        <div class="flex flex-wrap gap-3">
                            <button 
                                v-for="status in statuses" 
                                :key="status"
                                @click="updateStatus(status)"
                                class="rounded-full px-4 py-2 text-[10px] font-bold uppercase tracking-widest transition"
                                :class="order.status === status 
                                    ? 'bg-[#da532c] text-white' 
                                    : 'bg-[#fbfaf8] text-[#6b5f55] hover:bg-[#fff4f0] hover:text-[#da532c] border border-[#e7ded3]'"
                            >
                                {{ getStatusLabel(status) }}
                            </button>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="rounded-[2.5rem] border border-[#e7ded3] bg-white p-8">
                        <h2 class="text-lg font-bold text-[#231f1b] mb-6">{{ __('items') }}</h2>
                        <div class="space-y-4">
                            <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-2xl bg-[#fbfaf8] overflow-hidden">
                                    <img :src="item.product.image_url" class="h-full w-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-[#231f1b]">{{ item.product.name }}</p>
                                    <p class="text-sm text-[#6b5f55]">{{ item.quantity }} x {{ formatCurrency(item.unit_price) }}</p>
                                </div>
                                <div class="text-right font-bold text-[#231f1b]">
                                    {{ formatCurrency(item.total_price) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-[#e7ded3] flex justify-between items-center font-bold text-xl">
                            <span>{{ __('total') }}</span>
                            <span class="text-[#da532c]">{{ formatCurrency(order.total_amount) }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- Customer -->
                    <div class="rounded-[2.5rem] border border-[#e7ded3] bg-[#fbfaf8] p-8">
                        <h2 class="text-lg font-bold text-[#231f1b] mb-4">{{ __('customer') }}</h2>
                        <p class="font-bold text-[#da532c]">{{ order.user.name }}</p>
                        <p class="text-sm text-[#6b5f55]">{{ order.user.email }}</p>
                    </div>

                    <!-- Delivery -->
                    <div class="rounded-[2.5rem] border border-[#e7ded3] bg-[#fbfaf8] p-8">
                        <h2 class="text-lg font-bold text-[#231f1b] mb-4">{{ __('delivery') }}</h2>
                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b5f55]">{{ __('address') }}</p>
                                <p class="mt-1 text-[#231f1b]">{{ order.address }}</p>
                            </div>
                            <div v-if="order.notes">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-[#6b5f55]">{{ __('notes') }}</p>
                                <p class="mt-1 text-[#231f1b] italic">"{{ order.notes }}"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>