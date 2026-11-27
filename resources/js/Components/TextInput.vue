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
        class="rounded-xl border-[#e5e7eb] bg-[#faf9f6] shadow-none transition focus:border-[#1b4332] focus:ring-[#1b4332]"
        v-model="model"
        ref="input"
    />
</template>
