<script setup>
import { computed, ref } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { formatCurrency, useGuestCart } from '@/lib/guestCart';

const page = usePage();
const { cartItems, updateQuantity, removeItem, clearCart, totalItems, totalPrice } = useGuestCart();

const ui = computed(() => page.props.ui);
const locale = computed(() => page.props.locale ?? 'en');
const authUser = computed(() => page.props.auth.user);
const isArabic = computed(() => locale.value === 'ar');

const form = ref({
    payment_method: 'cod',
    address: '',
    notes: '',
});

const isProcessing = ref(false);

const checkout = async () => {
    if (!authUser.value) {
        router.get(route('checkout'));
        return;
    }

    if (!form.value.address) {
        alert(isArabic.value ? 'يرجى إدخال العنوان' : 'Please enter your address');
        return;
    }

    isProcessing.value = true;

    try {
        const response = await axios.post(route('orders.store'), {
            items: cartItems.value,
            total: totalPrice.value,
            payment_method: form.value.payment_method,
            address: form.value.address,
            notes: form.value.notes,
        });

        if (response.data.url) {
            window.location.href = response.data.url;
        } else {
            clearCart();
            window.location.href = route('orders.success', { order: response.data.id });
        }
    } catch (error) {
        console.error(error);
        alert('An error occurred. Please try again.');
    } finally {
        isProcessing.value = false;
    }
};
</script>

<template>
    <PublicLayout>
        <Head :title="ui.cart.badge" />

        <div class="space-y-8 py-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-[#231f1b]">{{ ui.cart.badge }}</h1>
                    <p class="mt-2 text-[#6b5f55]">{{ ui.cart.note }}</p>
                </div>

                <Link
                    v-if="cartItems.length > 0"
                    @click.prevent="clearCart"
                    href="#"
                    class="group flex items-center gap-2 rounded-full border border-red-100 bg-red-50/50 px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-red-500 transition-all hover:bg-red-50 hover:text-red-600"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5 transition-transform group-hover:rotate-12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    {{ ui.cart.clear }}
                </Link>
            </div>

            <div v-if="cartItems.length === 0" class="flex flex-col items-center justify-center rounded-[2.5rem] border border-dashed border-[#e7ded3] bg-[#fbfaf8] p-16 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fff4f0] text-[#da532c]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.112 16.856a.75.75 0 0 1-.747.799H3.83a.75.75 0 0 1-.747-.799l1.112-16.856a.375.375 0 0 1 .375-.38h15.216a.375.375 0 0 1 .375.38Z" />
                    </svg>
                </div>
                <h2 class="mt-6 text-xl font-bold text-[#231f1b]">{{ ui.cart.empty_title }}</h2>
                <p class="mt-2 text-[#6b5f55]">{{ ui.cart.empty_description }}</p>
                <Link :href="route('menu')" class="mt-8 rounded-full bg-[#da532c] px-8 py-3 font-semibold text-white transition hover:bg-[#bf4926]">
                    {{ ui.hero.primary_action }}
                </Link>
            </div>

            <div v-else class="grid gap-8 lg:grid-cols-3">
                <div class="space-y-4 lg:col-span-2">
                    <div
                        v-for="item in cartItems"
                        :key="item.id"
                        class="group flex items-center gap-4 rounded-3xl border border-[#e7ded3] bg-white p-4 transition-all hover:bg-[#fbfaf8]"
                    >
                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl bg-[#fbfaf8]">
                            <img :src="item.imageUrl" :alt="item.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                        </div>

                        <div class="flex flex-1 flex-col gap-1">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#da532c]">
                                {{ item.category || ui.cards.uncategorized }}
                            </span>
                            <h3 class="font-bold text-[#231f1b]">{{ item.name }}</h3>
                            <div class="mt-2 flex items-center gap-4">
                                <div class="flex items-center rounded-full border border-[#e7ded3] bg-white p-1">
                                    <button
                                        @click="updateQuantity(item.id, item.quantity - 1)"
                                        class="flex h-8 w-8 items-center justify-center rounded-full text-[#6b5f55] transition hover:bg-[#fff4f0] hover:text-[#da532c]"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="h-3 w-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                        </svg>
                                    </button>
                                    <span class="min-w-8 text-center text-sm font-bold text-[#231f1b]">{{ item.quantity }}</span>
                                    <button
                                        @click="updateQuantity(item.id, item.quantity + 1)"
                                        class="flex h-8 w-8 items-center justify-center rounded-full text-[#6b5f55] transition hover:bg-[#fff4f0] hover:text-[#da532c]"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="h-3 w-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </div>
                                <button @click="removeItem(item.id)" class="text-xs font-bold uppercase tracking-widest text-red-400 hover:text-red-500">
                                    {{ ui.cart.remove }}
                                </button>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="font-bold text-[#231f1b]">{{ formatCurrency(item.price * item.quantity, locale) }}</p>
                            <p class="text-[10px] text-[#6b5f55]">{{ formatCurrency(item.price, locale) }} / {{ ui.cart.unit }}</p>
                        </div>
                    </div>

                    <div v-if="authUser" class="mt-8 space-y-6 rounded-[2.5rem] border border-[#e7ded3] bg-white p-8">
                        <h2 class="text-xl font-bold text-[#231f1b]">{{ isArabic ? 'تفاصيل التوصيل' : 'Delivery Details' }}</h2>
                        
                        <div class="grid gap-6">
                            <div class="space-y-2">
                                <InputLabel :value="isArabic ? 'العنوان' : 'Delivery Address'" />
                                <TextInput 
                                    v-model="form.address"
                                    type="text"
                                    class="w-full"
                                    :placeholder="isArabic ? 'أدخل عنوان التوصيل بالتفصيل' : 'Enter your full delivery address'"
                                />
                            </div>

                            <div class="space-y-2">
                                <InputLabel :value="isArabic ? 'ملاحظات إضافية' : 'Notes (Optional)'" />
                                <textarea 
                                    v-model="form.notes"
                                    class="w-full rounded-2xl border-[#e7ded3] bg-[#fbfaf8] p-4 text-[#5b4f45] transition focus:border-[#da532c] focus:ring-[#da532c]"
                                    rows="2"
                                    :placeholder="isArabic ? 'أي ملاحظات للمطعم أو السائق؟' : 'Any notes for the restaurant or driver?'"
                                ></textarea>
                            </div>

                            <div class="space-y-4">
                                <InputLabel :value="isArabic ? 'طريقة الدفع' : 'Payment Method'" />
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <label 
                                        class="relative flex cursor-pointer items-center gap-4 rounded-2xl border p-4 transition"
                                        :class="form.payment_method === 'cod' ? 'border-[#da532c] bg-[#fff4f0]' : 'border-[#e7ded3] hover:bg-[#fbfaf8]'"
                                    >
                                        <input type="radio" v-model="form.payment_method" value="cod" class="hidden" />
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full" :class="form.payment_method === 'cod' ? 'bg-[#da532c] text-white' : 'bg-[#e7ded3] text-[#6b5f55]'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 0v1.5m0-1.5h1.5m-1.5 0h1.5m-1.5 0V4.5m1.5 0v.75m0 0v1.5m0-1.5h1.5m-1.5 0h1.5m-1.5 0V4.5m1.5 0h1.5m0 1.5V4.5m0 0h1.5m-1.5 0V4.5m0 0H6.75m1.5 0h1.5m1.5 0h1.5m1.5 0h1.5m0 0v1.5m0 1.5v1.5m0 1.5v1.5m0 1.5c0 .332-.27.603-.603.603H15.75m-4.5 0H9m-1.5 0H6.75m-3 0h1.5" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#231f1b]">Cash on Delivery</p>
                                            <p class="text-xs text-[#6b5f55]">Pay when you receive</p>
                                        </div>
                                    </label>

                                    <label 
                                        class="relative flex cursor-pointer items-center gap-4 rounded-2xl border p-4 transition"
                                        :class="form.payment_method === 'online' ? 'border-[#da532c] bg-[#fff4f0]' : 'border-[#e7ded3] hover:bg-[#fbfaf8]'"
                                    >
                                        <input type="radio" v-model="form.payment_method" value="online" class="hidden" />
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full" :class="form.payment_method === 'online' ? 'bg-[#da532c] text-white' : 'bg-[#e7ded3] text-[#6b5f55]'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75-6.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 .75.75v9a.75.75 0 0 1-.75.75H3.75a.75.75 0 0 1-.75-.75v-9c0-.215.11-.422.3-.532a.75.75 0 0 1 .45-.218Z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#231f1b]">Online Payment</p>
                                            <p class="text-xs text-[#6b5f55]">Credit Card / Stripe</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="sticky top-8 rounded-[2.5rem] border border-[#e7ded3] bg-[#fbfaf8] p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-[#231f1b]">{{ ui.cart.summary }}</h2>

                        <div class="mt-8 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6b5f55]">{{ ui.cart.items_count }}</span>
                                <span class="font-bold text-[#231f1b]">{{ totalItems }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-[#6b5f55]">{{ ui.cart.subtotal }}</span>
                                <span class="font-bold text-[#231f1b]">{{ formatCurrency(totalPrice, locale) }}</span>
                            </div>
                            <div class="h-px bg-[#e7ded3]"></div>
                            <div class="flex justify-between text-lg">
                                <span class="font-bold text-[#231f1b]">{{ ui.cart.total }}</span>
                                <span class="font-bold text-[#da532c]">{{ formatCurrency(totalPrice, locale) }}</span>
                            </div>
                        </div>

                        <div v-if="!authUser" class="mt-8 rounded-2xl bg-[#fff4f0] p-4 text-center">
                            <p class="text-sm font-medium text-[#da532c]">
                                {{ ui.cart.signin_note }}
                            </p>
                        </div>

                        <PrimaryButton
                            @click="checkout"
                            class="mt-6 w-full py-4 text-base"
                            :disabled="isProcessing"
                        >
                            <template v-if="isProcessing">
                                ...
                            </template>
                            <template v-else-if="authUser">
                                {{ ui.cart.confirm }}
                            </template>
                            <template v-else>
                                {{ ui.cart.signin_to_checkout }}
                            </template>
                        </PrimaryButton>

                        <p class="mt-4 text-center text-xs text-[#6b5f55]">
                            {{ ui.cart.terms }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
