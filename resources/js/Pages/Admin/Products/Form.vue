<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { __ } from '@/lib/i18n';

const props = defineProps({
    product: Object,
});

const page = usePage();
const isArabic = computed(() => page.props.locale === 'ar');
const isEditing = computed(() => !!props.product);

const form = useForm({
    name: props.product?.name || '',
    description: props.product?.description || '',
    price: props.product?.price || '',
    category: props.product?.category || '',
    image: null,
    is_available: props.product ? !!props.product.is_available : true,
    _method: isEditing.value ? 'PUT' : 'POST'
});

const submit = () => {
    if (isEditing.value) {
        // Inertia doesn't support file uploads via PUT, so we use POST with _method spoofing
        form.post(route('admin.products.update', props.product.id));
    } else {
        form.post(route('admin.products.store'));
    }
};

const handleImage = (e) => {
    form.image = e.target.files[0];
};
</script>

<template>
    <PublicLayout>
        <Head :title="isEditing ? __('edit_product') : __('add_product')" />

        <div class="mx-auto max-w-2xl py-10">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-[#231f1b]">
                    {{ isEditing ? __('edit_product') : __('add_product') }}
                </h1>
                <Link :href="route('admin.products.index')" class="text-sm font-bold uppercase tracking-widest text-[#6b5f55] hover:text-[#da532c]">
                    {{ __('cancel') }}
                </Link>
            </div>

            <form @submit.prevent="submit" class="rounded-[2.5rem] border border-[#e7ded3] bg-white p-8 space-y-6">
                <div>
                    <InputLabel :value="__('product_name')" />
                    <TextInput v-model="form.name" type="text" class="mt-2 w-full" required />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div>
                    <InputLabel :value="__('description')" />
                    <textarea 
                        v-model="form.description" 
                        class="mt-2 w-full rounded-2xl border-[#e7ded3] bg-[#fbfaf8] p-4 text-[#5b4f45] transition focus:border-[#da532c] focus:ring-[#da532c]"
                        rows="4"
                    ></textarea>
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div>
                        <InputLabel :value="__('price_usd')" />
                        <TextInput v-model="form.price" type="number" step="0.01" class="mt-2 w-full" required />
                        <InputError :message="form.errors.price" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel :value="__('category')" />
                        <TextInput v-model="form.category" type="text" class="mt-2 w-full" />
                        <InputError :message="form.errors.category" class="mt-2" />
                    </div>
                </div>

                <div>
                    <InputLabel :value="__('product_image')" />
                    <input 
                        type="file" 
                        @change="handleImage"
                        class="mt-2 w-full text-sm text-[#6b5f55] file:mr-4 file:rounded-full file:border-0 file:bg-[#fff4f0] file:px-4 file:py-2 file:text-xs file:font-bold file:text-[#da532c] hover:file:bg-[#ffe8e0]"
                    >
                    <InputError :message="form.errors.image" class="mt-2" />
                    <div v-if="product?.image_url && !form.image" class="mt-4">
                        <img :src="product.image_url" class="h-20 w-20 rounded-xl object-cover border border-[#e7ded3]">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input 
                        type="checkbox" 
                        v-model="form.is_available"
                        id="is_available"
                        class="rounded border-[#e7ded3] text-[#da532c] focus:ring-[#da532c]"
                    >
                    <label for="is_available" class="text-sm font-bold text-[#231f1b]">
                        {{ __('available_order') }}
                    </label>
                </div>

                <div class="pt-4">
                    <PrimaryButton class="w-full py-4 text-base" :disabled="form.processing">
                        {{ isEditing ? __('update_product') : __('save_product') }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>