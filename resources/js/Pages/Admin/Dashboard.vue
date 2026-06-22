<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const page = usePage();
const isArabic = computed(() => page.props.locale === 'ar');
</script>

<template>
    <PublicLayout>
        <Head :title="isArabic ? 'لوحة تحكم الأدمن' : 'Admin Dashboard'" />

        <div class="py-10">
            <h1 class="text-3xl font-bold text-[#231f1b] mb-10">
                {{ isArabic ? 'نظرة عامة على النظام' : 'Admin Overview' }}
            </h1>

            <div class="grid gap-8 md:grid-cols-2">
                <Link 
                    :href="route('admin.orders.index')"
                    class="group relative overflow-hidden rounded-[2.5rem] border border-[#e7ded3] bg-white p-10 transition hover:border-[#da532c] hover:shadow-lg"
                >
                    <div class="relative z-10">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#fff4f0] text-[#da532c] mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-[#231f1b] mb-2">{{ isArabic ? 'إدارة الطلبات' : 'Order Management' }}</h2>
                        <p class="text-[#6b5f55]">{{ isArabic ? 'تتبع وتحديث حالة طلبات العملاء' : 'Track and update customer order status' }}</p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 h-32 w-32 rounded-full bg-[#fff4f0] opacity-0 transition group-hover:opacity-50"></div>
                </Link>

                <Link 
                    :href="route('admin.products.index')"
                    class="group relative overflow-hidden rounded-[2.5rem] border border-[#e7ded3] bg-white p-10 transition hover:border-[#da532c] hover:shadow-lg"
                >
                    <div class="relative z-10">
                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#fff4f0] text-[#da532c] mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-[#231f1b] mb-2">{{ isArabic ? 'إدارة المنتجات' : 'Product Management' }}</h2>
                        <p class="text-[#6b5f55]">{{ isArabic ? 'إضافة وتعديل وحذف المنتجات من القائمة' : 'Add, edit, and delete items from the menu' }}</p>
                    </div>
                    <div class="absolute -right-4 -bottom-4 h-32 w-32 rounded-full bg-[#fff4f0] opacity-0 transition group-hover:opacity-50"></div>
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>