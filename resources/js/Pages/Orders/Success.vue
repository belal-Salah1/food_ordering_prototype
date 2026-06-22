<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { formatCurrency } from '@/lib/guestCart';
import { __ } from '@/lib/i18n';

const props = defineProps({
    order: Object,
});

const isPaid = computed(() => props.order.payment_status === 'paid');
const isCod = computed(() => props.order.payment_method === 'cod');
</script>

<template>
    <PublicLayout>
        <Head :title="__('order_confirmed')" />

        <div class="py-12">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-8 flex justify-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-10 w-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-4xl font-extrabold text-[#231f1b]">{{ __('order_confirmed') }}</h1>
                <p class="mt-4 text-lg text-[#6b5f55]">
                    {{ __('thank_you') }}
                </p>
                <p class="mt-2 font-mono text-sm text-[#9b8f85]">{{ __('order_number') }}{{ order.id }}</p>

                <div class="mt-12 rounded-[2.5rem] border border-[#e7ded3] bg-white p-8 text-left shadow-sm">
                    <h2 class="text-lg font-bold text-[#231f1b] mb-4">{{ __('order_details') }}</h2>
                    
                    <div class="mt-6 space-y-4">
                        <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="font-bold text-[#da532c]">{{ item.quantity }}x</span>
                                <span class="text-[#231f1b]">{{ item.product.name }}</span>
                            </div>
                            <span class="font-medium text-[#231f1b]">{{ formatCurrency(item.total_price) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-[#e7ded3] pt-6">
                        <div class="flex justify-between">
                            <p class="font-bold text-[#231f1b]">{{ __('payment_method') }}</p>
                            <span class="font-bold uppercase text-[#231f1b]">{{ order.payment_method }}</span>
                        </div>
                        <div class="mt-2 flex justify-between">
                            <p class="font-bold text-[#231f1b]">{{ __('payment_status') }}</p>
                            <span 
                                class="rounded-full px-3 py-1 text-xs font-bold uppercase"
                                :class="isPaid ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                            >
                                {{ order.payment_status }}
                            </span>
                        </div>
                        <div class="mt-6 flex justify-between text-xl font-bold">
                            <p class="font-bold text-[#231f1b]">{{ __('total_paid') }}</p>
                            <span class="text-[#da532c]">{{ formatCurrency(order.total_amount) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 rounded-2xl bg-[#fbfaf8] p-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#9b8f85]">{{ __('delivery_address') }}</h3>
                        <p class="mt-2 text-[#6b5f55]">{{ order.address }}</p>
                    </div>
                </div>

                <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <Link 
                        :href="route('orders.tracking', order.id)"
                        class="rounded-full bg-[#da532c] px-8 py-3 font-semibold text-white transition hover:bg-[#bf4926]"
                    >
                        {{ __('track_order') }}
                    </Link>
                    <Link 
                        :href="route('menu')"
                        class="rounded-full border border-[#e7ded3] bg-white px-8 py-3 font-semibold text-[#5b4f45] transition hover:bg-[#fbfaf8]"
                    >
                        {{ __('back_menu') }}
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
