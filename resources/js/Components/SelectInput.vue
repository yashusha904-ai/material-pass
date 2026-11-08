<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :value="modelValue === null || modelValue === undefined ? '' : modelValue"
        @change="
            $emit(
                'update:modelValue',
                $event.target.value === '' ? null : $event.target.value,
            )
        "
        ref="input"
    >
        <slot />
    </select>
</template>
