<script setup>
defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <a
        :href="href"
        class="inline-flex w-full items-center justify-center gap-3 rounded-full border border-[#e7ded3] bg-white px-6 py-3 text-sm font-semibold text-[#231f1b] shadow-sm transition-all duration-150 ease-in-out hover:bg-[#fbfaf8] focus:outline-none focus:ring-2 focus:ring-[#da532c] focus:ring-offset-2"
    >
        <svg
            aria-hidden="true"
            class="h-5 w-5 shrink-0"
            viewBox="0 0 48 48"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill="#EA4335"
                d="M24 9.5c3.54 0 6.72 1.23 9.22 3.64l6.9-6.9C35.96 2.67 30.56 0 24 0 14.61 0 6.52 5.38 2.55 13.22l8.02 6.22C12.66 13.16 17.85 9.5 24 9.5z"
            />
            <path
                fill="#4285F4"
                d="M46.03 24.55c0-1.64-.15-3.22-.43-4.75H24v9.01h12.4c-.54 2.91-2.18 5.36-4.64 7.02l7.19 5.59c4.21-3.89 6.08-9.62 6.08-16.87z"
            />
            <path
                fill="#FBBC05"
                d="M10.57 28.44A14.48 14.48 0 0 1 9.5 24c0-1.54.26-3.04.74-4.44l-8.02-6.22A23.96 23.96 0 0 0 0 24c0 3.87.93 7.55 2.55 10.66l8.02-6.22z"
            />
            <path
                fill="#34A853"
                d="M24 48c6.56 0 12.08-2.16 16.11-5.88l-7.19-5.59c-2 1.34-4.56 2.14-8.92 2.14-6.15 0-11.34-3.67-13.43-8.95l-8.02 6.22C6.52 42.62 14.61 48 24 48z"
            />
        </svg>

        <span>
            <slot>Continue with Google</slot>
        </span>
    </a>
</template>
