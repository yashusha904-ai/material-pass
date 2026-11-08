<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    departments: {
        type: Object,
        required: true,
    },
});

const status = computed(() => usePage().props.flash?.status);

const destroy = (department) => {
    if (confirm(`Удалить подразделение «${department.name}»?`)) {
        router.delete(route('departments.destroy', department.id));
    }
};
</script>

<template>
    <Head title="Подразделения" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Подразделения
                </h2>
                <div class="flex items-center gap-2">
                    <Link :href="route('directories.index')">
                        <SecondaryButton type="button">К справочникам</SecondaryButton>
                    </Link>
                    <Link :href="route('departments.create')">
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
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Код</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Название</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Родитель</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Организация</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Активно</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="department in departments.data" :key="department.id">
                                    <td class="px-4 py-3 text-gray-600">{{ department.code || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-900">{{ department.name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ department.parent_name || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ department.organization_name || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ department.is_active ? 'Да' : 'Нет' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="route('departments.edit', department.id)">
                                                <SecondaryButton type="button">Изменить</SecondaryButton>
                                            </Link>
                                            <DangerButton type="button" @click="destroy(department)">
                                                Удалить
                                            </DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="departments.data.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Записей пока нет.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <Pagination :links="departments.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
