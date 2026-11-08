<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    employees: {
        type: Object,
        required: true,
    },
});

const status = computed(() => usePage().props.flash?.status);

const destroy = (employee) => {
    if (confirm(`Удалить сотрудника «${employee.person_name}»?`)) {
        router.delete(route('employees.destroy', employee.id));
    }
};
</script>

<template>
    <Head title="Сотрудники" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Сотрудники
                </h2>
                <div class="flex items-center gap-2">
                    <Link :href="route('directories.index')">
                        <SecondaryButton type="button">К справочникам</SecondaryButton>
                    </Link>
                    <Link :href="route('employees.create')">
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
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Подразделение</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Позиция</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Таб. №</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Активен</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-for="employee in employees.data" :key="employee.id">
                                    <td class="px-4 py-3 text-gray-900">{{ employee.person_name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ employee.department_name || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ employee.staff_position_title || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ employee.personnel_number || '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ employee.is_active ? 'Да' : 'Нет' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex justify-end gap-2">
                                            <Link :href="route('employees.edit', employee.id)">
                                                <SecondaryButton type="button">Изменить</SecondaryButton>
                                            </Link>
                                            <DangerButton type="button" @click="destroy(employee)">
                                                Удалить
                                            </DangerButton>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="employees.data.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        Записей пока нет.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <Pagination :links="employees.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
