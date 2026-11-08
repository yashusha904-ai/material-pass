<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    people: {
        type: Object,
        required: true,
    },
});

const status = computed(() => usePage().props.flash?.status);

const destroy = (person) => {
    if (confirm(`Удалить физическое лицо «${person.full_name}»?`)) {
        router.delete(route('people.destroy', person.id));
    }
};
</script>

<template>
    <Head title="Физические лица" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Физические лица
                </h2>
                <div class="flex items-center gap-2">
                    <Link :href="route('directories.index')">
                        <SecondaryButton type="button">К справочникам</SecondaryButton>
                    </Link>
                    <Link :href="route('people.create')">
                        <PrimaryButton type="button">Добавить</PrimaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-4 sm:px-6 lg:px-8">
                <p v-if="status" class="text-sm font-medium text-green-600">
                    {{ status }}
                </p>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">ФИО</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Телефон</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Активен</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="person in people.data" :key="person.id">
                                    <td class="px-4 py-3 text-gray-900">{{ person.full_name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ person.email || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ person.phone || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ person.is_active ? 'Да' : 'Нет' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="route('people.edit', person.id)">
                                                <SecondaryButton type="button">Изменить</SecondaryButton>
                                            </Link>
                                            <DangerButton type="button" @click="destroy(person)">
                                                Удалить
                                            </DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="people.data.length === 0">
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        Записей пока нет.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <Pagination :links="people.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
