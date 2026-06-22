<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import LocaleSwitcher from '@/Components/LocaleSwitcher.vue';
import { useGuestCart } from '@/lib/guestCart';
import { dashboard, login, logout, menu as menuRoute, register } from '@/routes';

const page = usePage();
const { itemCount } = useGuestCart();

const defaultUi = {
    brand: 'MealBox',
    brand_tagline: 'Real meals, local cart',
    navigation: {
        menu: 'Menu',
        login: 'Log in',
        register: 'Register',
        dashboard: 'Dashboard',
        logout: 'Log out',
    },
    cart: {
        badge: 'Cart',
        note: 'Guest cart count is saved locally on this device.',
    },
    footer: {
        copy: 'MealBox prototype menu powered by TheMealDB data.',
    },
};

const ui = computed(() => page.props.ui ?? defaultUi);
const currentUrl = computed(() => page.url ?? '');
const isArabic = computed(() => (page.props.locale ?? 'en') === 'ar');
const isHomeMenu = computed(() => currentUrl.value.startsWith(menuRoute.url()));
</script>

<template>
    <div
        :dir="isArabic ? 'rtl' : 'ltr'"
        class="min-h-screen bg-white text-[#231f1b]"
        style="font-family: Arial, Helvetica, sans-serif"
    >
        <header class="border-b border-[#e7ded3] bg-white">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8"
            >
                <Link :href="menuRoute.url()" class="group inline-flex items-center gap-3">
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-[#da532c] bg-[#fff4f0] text-sm font-bold uppercase tracking-[0.24em] text-[#da532c]"
                    >
                        MB
                    </span>

                    <span class="space-y-1">
                        <span
                            class="block text-lg font-semibold uppercase tracking-[0.3em] text-[#231f1b]"
                        >
                            {{ ui.brand }}
                        </span>
                        <span
                            class="block text-xs uppercase tracking-[0.28em] text-[#da532c]"
                        >
                            {{ ui.brand_tagline }}
                        </span>
                    </span>
                </Link>

                <div
                    class="flex flex-wrap items-center gap-3 lg:justify-end"
                >
                    <nav
                        class="flex items-center gap-1 rounded-full border border-[#e7ded3] bg-[#fbfaf8] p-1 text-sm font-medium text-[#5b4f45]"
                    >
                        <Link
                            :href="menuRoute.url()"
                            class="rounded-full px-4 py-2 transition"
                            :class="
                                isHomeMenu
                                    ? 'bg-[#da532c] text-white'
                                    : 'hover:text-[#da532c]'
                            "
                        >
                            {{ ui.navigation.menu }}
                        </Link>

                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="dashboard.url()"
                                class="rounded-full px-4 py-2 transition hover:text-[#da532c]"
                            >
                                {{ ui.navigation.dashboard }}
                            </Link>

                            <Link
                                :href="logout.url()"
                                method="post"
                                class="rounded-full px-4 py-2 transition hover:text-[#da532c]"
                            >
                                {{ ui.navigation.logout }}
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="login.url()"
                                class="rounded-full px-4 py-2 transition hover:text-[#da532c]"
                            >
                                {{ ui.navigation.login }}
                            </Link>

                            <Link
                                :href="register.url()"
                                class="rounded-full px-4 py-2 transition hover:text-[#da532c]"
                            >
                                {{ ui.navigation.register }}
                            </Link>
                        </template>
                    </nav>

                    <LocaleSwitcher />

                    <Link
                        :href="route('cart')"
                        class="inline-flex items-center gap-3 rounded-full border border-[#e7ded3] bg-[#fbfaf8] px-4 py-2 text-sm font-semibold text-[#5b4f45] transition hover:border-[#da532c]"
                        :class="{ 'bg-[#da532c] text-white border-[#da532c]': $page.url === '/cart' }"
                    >
                        <span>{{ ui.cart.badge }}</span>
                        <span
                            class="inline-flex h-6 min-w-6 items-center justify-center rounded-full px-2 text-xs font-bold transition"
                            :class="$page.url === '/cart' ? 'bg-white text-[#da532c]' : 'bg-[#da532c] text-white'"
                        >
                            {{ itemCount }}
                        </span>
                    </Link>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />
        </main>

        <footer class="border-t border-[#e7ded3] bg-white">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col gap-3 px-4 py-5 text-sm text-[#6b5f55] sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8"
            >
                <p>{{ ui.footer.copy }}</p>
                <p class="text-[#da532c]">{{ ui.cart.note }}</p>
            </div>
        </footer>
    </div>
</template>
