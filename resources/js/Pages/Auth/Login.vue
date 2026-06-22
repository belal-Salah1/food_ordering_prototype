<script setup>
import GoogleButton from '@/Components/GoogleButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    oauthError: {
        type: String,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const ui = computed(() => page.props.ui.auth.login);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
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

        <div v-if="status" class="mb-6 rounded-2xl bg-green-50 p-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div
            v-if="oauthError"
            class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700"
        >
            {{ oauthError }}
        </div>

        <div class="mb-8 space-y-4">
            <GoogleButton
                :href="route('auth.google.redirect', { source: 'login' })"
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
                <InputLabel for="email" :value="ui.email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="name@example.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <InputLabel for="password" :value="ui.password" />

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-bold uppercase tracking-widest text-[#da532c] hover:underline"
                    >
                        {{ ui.forgot_password }}
                    </Link>
                </div>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <Checkbox name="remember" v-model:checked="form.remember" class="border-[#e7ded3] text-[#da532c] focus:ring-[#da532c]" />
                    <span class="text-sm font-medium text-[#6b5f55]">{{ ui.remember_me }}</span>
                </label>
            </div>

            <PrimaryButton
                class="w-full"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                {{ ui.submit }}
            </PrimaryButton>

            <p class="text-center text-sm text-[#6b5f55]">
                {{ ui.no_account }}
                <Link
                    :href="route('register')"
                    class="font-bold text-[#da532c] hover:underline"
                >
                    {{ ui.create_account }}
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
