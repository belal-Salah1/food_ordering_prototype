<script setup>
import { computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { __ } from '@/lib/i18n';

const props = defineProps({
    orders: Object,
});

const page = usePage();
const isArabic = computed(() => page.props.locale === 'ar');

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
        <Head :title="__('order_management')" />

        <div class="py-10">
            <h1 class="text-3xl font-bold text-[#231f1b] mb-8">
                {{ __('orders') }}
            </h1>

            <div class="overflow-hidden rounded-[2.5rem] border border-[#e7ded3] bg-white shadow-sm">
                <table class="w-full text-left rtl:text-right">
                    <thead class="bg-[#fbfaf8] border-b border-[#e7ded3] text-[10px] font-bold uppercase tracking-[0.2em] text-[#6b5f55]">
                        <tr>
                            <th class="px-8 py-4">ID</th>
                            <th class="px-8 py-4">{{ __('customer') }}</th>
                            <th class="px-8 py-4">{{ __('total') }}</th>
                            <th class="px-8 py-4">{{ __('status') }}</th>
                            <th class="px-8 py-4">{{ __('date') }}</th>
                            <th class="px-8 py-4 text-center">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7ded3]">
                        <tr v-for="order in orders.data" :key="order.id" class="transition hover:bg-[#fbfaf8]/50">
                            <td class="px-8 py-4 font-mono text-xs">#{{ order.id }}</td>
                            <td class="px-8 py-4">
                                <span class="font-bold text-[#231f1b]">{{ order.user.name }}</span>
                            </td>
                            <td class="px-8 py-4 font-bold text-[#231f1b]">{{ formatCurrency(order.total_amount) }}</td>
                            <td class="px-8 py-4">
                                <span 
                                    class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest"
                                    :class="{
                                        'bg-yellow-100 text-yellow-700': order.status === 'pending',
                                        'bg-blue-100 text-blue-700': order.status === 'confirmed' || order.status === 'preparing',
                                        'bg-purple-100 text-purple-700': order.status === 'out_for_delivery',
                                        'bg-green-100 text-green-700': order.status === 'delivered',
                                        'bg-red-100 text-red-700': order.status === 'cancelled',
                                    }"
                                >
                                    {{ getStatusLabel(order.status) }}
                                </span>
                            </td>
                            <td class="px-8 py-4 text-xs text-[#6b5f55]">
                                {{ new Date(order.created_at).toLocaleDateString() }}
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-4">
                                    <Link 
                                        :href="route('admin.orders.show', order.id)"
                                        class="text-xs font-bold uppercase tracking-widest text-[#da532c] hover:underline"
                                    >
                                        {{ __('view') }}
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </PublicLayout>
</template>