<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <nav v-if="links.length > 3" class="flex flex-wrap items-center gap-1">
        <template v-for="(link, index) in links" :key="index">
            <div
                v-if="!link.url"
                class="rounded-md px-3 py-1 text-sm text-gray-400"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                class="rounded-md px-3 py-1 text-sm transition"
                :class="
                    link.active
                        ? 'bg-gray-800 text-white'
                        : 'bg-white text-gray-700 hover:bg-gray-100'
                "
                v-html="link.label"
                preserve-scroll
            />
        </template>
    </nav>
</template>
