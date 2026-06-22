<script setup>
import { computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { __ } from '@/lib/i18n';

const props = defineProps({
    products: Object,
});

const page = usePage();
const isArabic = computed(() => page.props.locale === 'ar');

const deleteProduct = (id) => {
    if (confirm(__('delete_confirm'))) {
        router.delete(route('admin.products.destroy', id));
    }
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
        <Head :title="__('product_management')" />

        <div class="py-10">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-[#231f1b]">
                    {{ __('products') }}
                </h1>
                <Link 
                    :href="route('admin.products.create')"
                    class="rounded-full bg-[#da532c] px-6 py-2.5 font-bold text-white transition hover:bg-[#bf4926]"
                >
                    {{ __('add_product') }}
                </Link>
            </div>

            <div class="overflow-hidden rounded-[2.5rem] border border-[#e7ded3] bg-white shadow-sm">
                <table class="w-full text-left rtl:text-right">
                    <thead class="bg-[#fbfaf8] border-b border-[#e7ded3] text-[10px] font-bold uppercase tracking-[0.2em] text-[#6b5f55]">
                        <tr>
                            <th class="px-8 py-4">{{ __('product') }}</th>
                            <th class="px-8 py-4">{{ __('category') }}</th>
                            <th class="px-8 py-4">{{ __('price') }}</th>
                            <th class="px-8 py-4">{{ __('status') }}</th>
                            <th class="px-8 py-4 text-center">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e7ded3]">
                        <tr v-for="product in products.data" :key="product.id" class="transition hover:bg-[#fbfaf8]/50">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-xl bg-[#fbfaf8]">
                                        <img :src="product.image_url" class="h-full w-full object-cover">
                                    </div>
                                    <span class="font-bold text-[#231f1b]">{{ product.name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-sm text-[#5b4f45]">{{ product.category }}</td>
                            <td class="px-8 py-4 font-bold text-[#231f1b]">{{ formatCurrency(product.price) }}</td>
                            <td class="px-8 py-4">
                                <span 
                                    class="inline-flex rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest"
                                    :class="product.is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                >
                                    {{ product.is_available ? __('in_stock') : __('out_stock') }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-4">
                                    <Link 
                                        :href="route('admin.products.edit', product.id)"
                                        class="text-xs font-bold uppercase tracking-widest text-[#da532c] hover:underline"
                                    >
                                        {{ __('edit') }}
                                    </Link>
                                    <button 
                                        @click="deleteProduct(product.id)"
                                        class="text-xs font-bold uppercase tracking-widest text-red-500 hover:underline"
                                    >
                                        {{ __('delete') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Simple -->
            <div class="mt-8 flex items-center justify-center gap-4">
                <Link 
                    v-if="products.prev_page_url" 
                    :href="products.prev_page_url"
                    class="rounded-full border border-[#e7ded3] px-6 py-2 text-sm font-bold text-[#5b4f45] transition hover:border-[#da532c] hover:text-[#da532c]"
                >
                    {{ __('previous') }}
                </Link>
                <Link 
                    v-if="products.next_page_url" 
                    :href="products.next_page_url"
                    class="rounded-full border border-[#e7ded3] px-6 py-2 text-sm font-bold text-[#5b4f45] transition hover:border-[#da532c] hover:text-[#da532c]"
                >
                    {{ __('next') }}
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>