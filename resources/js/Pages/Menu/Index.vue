<script setup>
import { computed, ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MenuProductCard from '@/Components/MenuProductCard.vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { login } from '@/routes';
import { formatCurrency, useGuestCart } from '@/lib/guestCart';

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
    featuredProduct: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        required: true,
    },
    ui: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const { addItem, getQuantity, itemCount } = useGuestCart();
const selectedCategory = ref('all');

const locale = computed(() => page.props.locale ?? 'en');
const heroProduct = computed(() => props.featuredProduct ?? props.products[0] ?? null);
const filteredProducts = computed(() =>
    selectedCategory.value === 'all'
        ? props.products
        : props.products.filter(
              (product) => product.category === selectedCategory.value,
          ),
);
const categoryTabs = computed(() => [
    {
        value: 'all',
        label: props.ui.filters.all,
        count: props.products.length,
    },
    ...props.categories,
]);

const cardLabels = computed(() => ({
    category: props.ui.cards.category,
    price: props.ui.cards.price,
    addToCart: props.ui.cards.add_to_cart,
    added: props.ui.cards.added,
    uncategorized: props.ui.cards.uncategorized,
}));

const heroPrice = computed(() =>
    heroProduct.value ? formatCurrency(heroProduct.value.price, locale.value) : '',
);

function setCategory(category) {
    selectedCategory.value = category;
}
</script>

<template>
    <PublicLayout>
        <Head :title="ui.page_title" />

        <section class="space-y-12">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                <div class="space-y-6">
                    <span
                        class="inline-flex rounded-full border border-[#f0d7cc] bg-[#fff4f0] px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.28em] text-[#da532c]"
                    >
                        {{ ui.hero.eyebrow }}
                    </span>

                    <div class="space-y-4">
                        <h1
                            class="max-w-2xl text-4xl font-semibold leading-tight text-[#231f1b] sm:text-5xl"
                        >
                            {{ ui.hero.title }}
                        </h1>
                        <p class="max-w-xl text-base leading-7 text-[#6b5f55]">
                            {{ ui.hero.description }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a
                            href="#menu-grid"
                            class="inline-flex rounded-full bg-[#da532c] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#bf4926]"
                        >
                            {{ ui.hero.primary_action }}
                        </a>

                        <Link
                            :href="login.url()"
                            class="inline-flex rounded-full border border-[#e7ded3] bg-white px-5 py-3 text-sm font-semibold text-[#231f1b] transition hover:border-[#da532c] hover:text-[#da532c]"
                        >
                            {{ ui.hero.secondary_action }}
                        </Link>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <article
                            class="rounded-[22px] border border-[#e7ded3] bg-white px-4 py-5"
                        >
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]"
                            >
                                {{ ui.stats.meals }}
                            </p>
                            <p class="mt-3 text-3xl font-semibold text-[#231f1b]">
                                {{ stats.meals }}
                            </p>
                        </article>

                        <article
                            class="rounded-[22px] border border-[#e7ded3] bg-white px-4 py-5"
                        >
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]"
                            >
                                {{ ui.stats.categories }}
                            </p>
                            <p class="mt-3 text-3xl font-semibold text-[#231f1b]">
                                {{ stats.categories }}
                            </p>
                        </article>

                        <article
                            class="rounded-[22px] border border-[#e7ded3] bg-white px-4 py-5"
                        >
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]"
                            >
                                {{ ui.stats.images }}
                            </p>
                            <p class="mt-3 text-3xl font-semibold text-[#231f1b]">
                                {{ stats.images }}
                            </p>
                        </article>
                    </div>

                    <p class="text-sm text-[#6b5f55]">
                        {{ ui.hero.note }}
                    </p>

                    <div
                        class="inline-flex items-center gap-3 rounded-full border border-[#e7ded3] bg-white px-4 py-3 text-sm text-[#6b5f55]"
                    >
                        <span class="font-semibold text-[#231f1b]">
                            {{ itemCount }}
                        </span>
                        <span>{{ ui.cart.note }}</span>
                    </div>
                </div>

                <div v-if="heroProduct" class="relative">
                    <div
                        class="overflow-hidden rounded-[32px] border border-[#e7ded3] bg-white p-3 shadow-[0px_24px_60px_rgba(33,22,11,0.08)]"
                    >
                        <img
                            :src="heroProduct.imageUrl"
                            :alt="heroProduct.name"
                            class="aspect-[4/3] w-full rounded-[24px] object-cover"
                        />

                        <div
                            class="mt-4 flex items-end justify-between gap-4 border-t border-dashed border-[#e7ded3] pt-4"
                        >
                            <div>
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]"
                                >
                                    {{ ui.sections.featured }}
                                </p>
                                <h2
                                    class="mt-2 text-2xl font-semibold text-[#231f1b]"
                                >
                                    {{ heroProduct.name }}
                                </h2>
                                <p class="mt-2 text-sm text-[#6b5f55]">
                                    {{
                                        heroProduct.category ||
                                        ui.cards.uncategorized
                                    }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.24em] text-[#8c7c6f]"
                                >
                                    {{ ui.cards.price }}
                                </p>
                                <p class="mt-2 text-2xl font-semibold text-[#da532c]">
                                    {{ heroPrice }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="space-y-4">
                <div
                    class="flex flex-col gap-3 border-b border-[#e7ded3] pb-4 sm:flex-row sm:items-end sm:justify-between"
                >
                    <div>
                        <p
                            class="text-xs font-semibold uppercase tracking-[0.28em] text-[#da532c]"
                        >
                            {{ ui.sections.categories }}
                        </p>
                        <h2 class="mt-1 text-2xl font-semibold text-[#231f1b]">
                            {{ ui.sections.menu }}
                        </h2>
                    </div>

                    <p class="text-sm text-[#6b5f55]">
                        {{ filteredProducts.length }}
                        {{ ui.stats.meals.toLowerCase() }}
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="tab in categoryTabs"
                        :key="tab.value"
                        type="button"
                        class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                        :class="
                            selectedCategory === tab.value
                                ? 'border-[#da532c] bg-[#da532c] text-white'
                                : 'border-[#e7ded3] bg-white text-[#5b4f45] hover:border-[#da532c] hover:text-[#da532c]'
                        "
                        @click="setCategory(tab.value)"
                    >
                        <span>{{ tab.label }}</span>
                        <span class="ms-2 text-xs opacity-80">
                            {{ tab.count }}
                        </span>
                    </button>
                </div>
            </section>

            <div
                v-if="filteredProducts.length > 0"
                id="menu-grid"
                class="grid gap-6 md:grid-cols-2 xl:grid-cols-3"
            >
                <MenuProductCard
                    v-for="product in filteredProducts"
                    :key="product.id"
                    :product="product"
                    :locale="locale"
                    :quantity="getQuantity(product.id)"
                    :labels="cardLabels"
                    @add="addItem"
                />
            </div>

            <div
                v-else
                id="menu-grid"
                class="rounded-[28px] border border-dashed border-[#e7ded3] bg-white px-6 py-14 text-center"
            >
                <p class="text-xl font-semibold text-[#231f1b]">
                    {{ ui.empty_state.title }}
                </p>
                <p class="mt-3 text-sm leading-6 text-[#6b5f55]">
                    {{ ui.empty_state.description }}
                </p>
            </div>
        </section>
    </PublicLayout>
</template>
