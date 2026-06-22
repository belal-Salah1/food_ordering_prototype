<script setup>
import GoogleButton from '@/Components/GoogleButton.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    oauthError: {
        type: String,
    },
});

const page = usePage();
const ui = computed(() => page.props.ui.auth.register);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="ui.title" />

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-[#231f1b]">{{ ui.welcome }}</h1>
            <p class="mt-2 text-[#6b5f55]">{{ ui.subtitle }}</p>
        </div>

        <div
            v-if="oauthError"
            class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
        >
            {{ oauthError }}
        </div>

        <div class="mb-8 space-y-4">
            <GoogleButton
                :href="route('auth.google.redirect', { source: 'register' })"
            >
                {{ ui.google }}
            </GoogleButton>

            <div class="flex items-center gap-4">
                <div class="h-px flex-1 bg-[#e7ded3]"></div>
                <span
                    class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#6b5f55]"
                >
                    {{ ui.or }}
                </span>
                <div class="h-px flex-1 bg-[#e7ded3]"></div>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="name" :value="ui.name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-2"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Your Name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" :value="ui.email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="name@example.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" :value="ui.password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    :value="ui.confirm_password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-2"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <PrimaryButton
                class="w-full"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ ui.submit }}
            </PrimaryButton>

            <p class="text-center text-sm text-[#6b5f55]">
                {{ ui.has_account }}
                <Link
                    :href="route('login')"
                    class="font-bold text-[#da532c] hover:underline"
                >
                    {{ ui.login }}
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
