<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    directories: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Head title="Справочники" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Справочники
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <div
                    v-for="directory in directories"
                    :key="directory.route"
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="flex items-center justify-between gap-4 p-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ directory.name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ directory.description }}
                            </p>
                        </div>
                        <Link
                            :href="route(directory.route)"
                            class="rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700"
                        >
                            Открыть
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
