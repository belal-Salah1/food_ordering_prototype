<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="block w-full rounded-2xl border-[#e7ded3] bg-[#fbfaf8] px-4 py-3 text-[#231f1b] shadow-sm transition-all focus:border-[#da532c] focus:ring-[#da532c]"
        v-model="model"
        ref="input"
    />
</template>
