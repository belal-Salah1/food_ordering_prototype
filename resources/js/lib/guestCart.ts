import { computed, onMounted, ref } from 'vue';

export type GuestCartProduct = {
    id: number;
    mealdbId: number;
    name: string;
    category: string | null;
    imageUrl: string;
    description: string | null;
    price: number;
};

export type GuestCartItem = GuestCartProduct & {
    quantity: number;
};

const STORAGE_KEY = 'food-ordering:guest-cart';
const cartItems = ref<GuestCartItem[]>([]);
let hasHydrated = false;

const isBrowser = typeof window !== 'undefined';

function toNumber(value: unknown, fallback = 0): number {
    const parsed = Number(value);

    return Number.isFinite(parsed) ? parsed : fallback;
}

function normalizeCartItem(item: unknown): GuestCartItem | null {
    if (!item || typeof item !== 'object') {
        return null;
    }

    const candidate = item as Partial<GuestCartItem>;

    if (candidate.id === undefined || candidate.name === undefined) {
        return null;
    }

    return {
        id: toNumber(candidate.id),
        mealdbId: toNumber(candidate.mealdbId, toNumber(candidate.id)),
        name: String(candidate.name),
        category:
            typeof candidate.category === 'string' ? candidate.category : null,
        imageUrl:
            typeof candidate.imageUrl === 'string' ? candidate.imageUrl : '',
        description:
            typeof candidate.description === 'string'
                ? candidate.description
                : null,
        price: toNumber(candidate.price),
        quantity: Math.max(toNumber(candidate.quantity, 1), 1),
    };
}

function readCart(): GuestCartItem[] {
    if (!isBrowser) {
        return [];
    }

    try {
        const raw = window.localStorage.getItem(STORAGE_KEY);

        if (!raw) {
            return [];
        }

        const parsed = JSON.parse(raw);

        if (!Array.isArray(parsed)) {
            return [];
        }

        return parsed
            .map(normalizeCartItem)
            .filter((item): item is GuestCartItem => item !== null);
    } catch {
        return [];
    }
}

function persistCart(): void {
    if (!isBrowser) {
        return;
    }

    window.localStorage.setItem(STORAGE_KEY, JSON.stringify(cartItems.value));
}

function hydrateCart(): void {
    if (!isBrowser || hasHydrated) {
        return;
    }

    cartItems.value = readCart();
    hasHydrated = true;
}

function normalizeProduct(product: GuestCartProduct): GuestCartItem {
    return {
        ...product,
        category: product.category ?? null,
        description: product.description ?? null,
        quantity: 1,
    };
}

export function formatCurrency(value: number, locale = 'en'): string {
    return new Intl.NumberFormat(locale, {
        currency: 'USD',
        style: 'currency',
        maximumFractionDigits: 2,
    }).format(value);
}

export function useGuestCart() {
    onMounted(() => {
        hydrateCart();
    });

    const itemCount = computed(() =>
        cartItems.value.reduce((count, item) => count + item.quantity, 0),
    );

    const subtotal = computed(() =>
        cartItems.value.reduce(
            (total, item) => total + item.price * item.quantity,
            0,
        ),
    );

    function addItem(product: GuestCartProduct): void {
        hydrateCart();

        const index = cartItems.value.findIndex(
            (item) => item.id === product.id,
        );

        if (index === -1) {
            cartItems.value = [...cartItems.value, normalizeProduct(product)];
            persistCart();

            return;
        }

        cartItems.value = cartItems.value.map((item) =>
            item.id === product.id
                ? {
                      ...item,
                      quantity: item.quantity + 1,
                  }
                : item,
        );
        persistCart();
    }

    function removeItem(productId: number): void {
        hydrateCart();
        cartItems.value = cartItems.value.filter((item) => item.id !== productId);
        persistCart();
    }

    function clearCart(): void {
        hydrateCart();
        cartItems.value = [];
        persistCart();
    }

    function updateQuantity(productId: number, quantity: number): void {
        hydrateCart();

        if (quantity < 1) {
            removeItem(productId);
            return;
        }

        cartItems.value = cartItems.value.map((item) =>
            item.id === productId ? { ...item, quantity } : item,
        );
        persistCart();
    }

    function hasItem(productId: number): boolean {
        return getQuantity(productId) > 0;
    }

    function getQuantity(productId: number): number {
        return (
            cartItems.value.find((item) => item.id === productId)?.quantity ?? 0
        );
    }

    return {
        cartItems,
        itemCount,
        subtotal,
        totalItems: itemCount,
        totalPrice: subtotal,
        addItem,
        removeItem,
        clearCart,
        updateQuantity,
        hasItem,
        getQuantity,
    };
}
