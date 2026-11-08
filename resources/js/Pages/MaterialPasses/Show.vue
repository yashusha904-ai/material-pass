<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    materialPass: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="materialPass.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ materialPass.title }}
                </h2>
                <div class="flex flex-wrap gap-2">
                    <Link :href="route('dashboard')">
                        <SecondaryButton type="button">Назад</SecondaryButton>
                    </Link>
                    <SecondaryButton type="button" disabled>Сохранить</SecondaryButton>
                    <SecondaryButton type="button" disabled>
                        Подписать и отправить
                    </SecondaryButton>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <!-- Данные инициатора -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Данные инициатора
                    </h3>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">
                                Инициатор
                            </div>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ materialPass.initiator.name || '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">
                                Подразделение
                            </div>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ materialPass.initiator.department || '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">
                                Организация
                            </div>
                            <div class="mt-1 text-sm text-gray-900">
                                {{ materialPass.initiator.organization || '—' }}
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Материальный пропуск -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="mb-4 flex items-start justify-between gap-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Материальный пропуск
                        </h3>
                        <div
                            class="flex h-20 w-20 shrink-0 items-center justify-center border border-dashed border-gray-300 text-center text-[10px] text-gray-400"
                            :title="materialPass.qr_token || 'QR'"
                        >
                            QR
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <label class="block text-sm">
                            <span class="text-gray-700">Откуда <span class="text-red-600">*</span></span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.from || ''"
                            />
                        </label>
                        <label class="block text-sm">
                            <span class="text-gray-700">Куда <span class="text-red-600">*</span></span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.to || ''"
                            />
                        </label>
                        <label class="block text-sm">
                            <span class="text-gray-700">Дата действия <span class="text-red-600">*</span></span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.valid_until || ''"
                            />
                        </label>
                        <label class="block text-sm">
                            <span class="text-gray-700">Номер пломбы</span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.seal_numbers || ''"
                            />
                        </label>
                    </div>
                </section>

                <!-- Выдан сотруднику -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">
                        Выдан сотруднику
                    </h3>
                    <div class="grid gap-4 md:grid-cols-3">
                        <label class="block text-sm">
                            <span class="text-gray-700">Подразделение</span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.issued_to.department || ''"
                            />
                        </label>
                        <label class="block text-sm">
                            <span class="text-gray-700">Организация</span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.issued_to.organization || ''"
                            />
                        </label>
                        <label class="block text-sm">
                            <span class="text-gray-700">Кому выдан <span class="text-red-600">*</span></span>
                            <input
                                class="mt-1 w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                type="text"
                                readonly
                                :value="materialPass.issued_to.person || ''"
                            />
                        </label>
                    </div>
                </section>

                <!-- Данные ТС -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Данные ТС</h3>
                        <button
                            type="button"
                            class="text-sm font-medium text-sky-700"
                            disabled
                        >
                            + Добавить
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">Заявка на бронирование</th>
                                    <th class="px-3 py-2">Тип ТС</th>
                                    <th class="px-3 py-2">Гос. номер</th>
                                    <th class="px-3 py-2">Водитель</th>
                                    <th class="px-3 py-2">Комментарий</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="(row, index) in materialPass.vehicles"
                                    :key="`vehicle-${index}`"
                                >
                                    <td class="px-3 py-2">{{ row.booking_request || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.vehicle_type || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.plate_number || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.driver || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.comment || '—' }}</td>
                                </tr>
                                <tr v-if="!materialPass.vehicles.length">
                                    <td
                                        colspan="5"
                                        class="px-3 py-4 text-center text-gray-400"
                                    >
                                        Нет записей
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Перечень ТМЦ -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Перечень ТМЦ</h3>
                        <button
                            type="button"
                            class="text-sm font-medium text-sky-700"
                            disabled
                        >
                            + Добавить
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">Категория ТМЦ</th>
                                    <th class="px-3 py-2">Название</th>
                                    <th class="px-3 py-2">Организация-владелец</th>
                                    <th class="px-3 py-2">Количество</th>
                                    <th class="px-3 py-2">Вид упаковки</th>
                                    <th class="px-3 py-2">Общий вес</th>
                                    <th class="px-3 py-2">Ед.</th>
                                    <th class="px-3 py-2">Номер отвала</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="(row, index) in materialPass.items"
                                    :key="`item-${index}`"
                                >
                                    <td class="px-3 py-2">{{ row.category || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.name || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.owner_organization || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.quantity ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ row.packaging_type || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.total_weight ?? '—' }}</td>
                                    <td class="px-3 py-2">{{ row.unit || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.dump_number || '—' }}</td>
                                </tr>
                                <tr v-if="!materialPass.items.length">
                                    <td
                                        colspan="8"
                                        class="px-3 py-4 text-center text-gray-400"
                                    >
                                        Нет записей
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Согласование -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Согласование</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">№</th>
                                    <th class="px-3 py-2">Версия</th>
                                    <th class="px-3 py-2">Название</th>
                                    <th class="px-3 py-2">Участники</th>
                                    <th class="px-3 py-2">Статус</th>
                                    <th class="px-3 py-2">Дата и время поступления</th>
                                    <th class="px-3 py-2">Результат</th>
                                    <th class="px-3 py-2">Дата и время резолюции</th>
                                    <th class="px-3 py-2">ФИО, должность</th>
                                    <th class="px-3 py-2">Комментарий</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="row in materialPass.approvals"
                                    :key="`approval-${row.index}`"
                                >
                                    <td class="px-3 py-2">{{ row.index }}</td>
                                    <td class="px-3 py-2">{{ row.version }}</td>
                                    <td class="px-3 py-2">{{ row.stage_name }}</td>
                                    <td class="px-3 py-2">{{ row.participants || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.status || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.arrived_at || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.result || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.resolved_at || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.resolver_name_title || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.comment || '—' }}</td>
                                </tr>
                                <tr v-if="!materialPass.approvals.length">
                                    <td
                                        colspan="10"
                                        class="px-3 py-4 text-center text-gray-400"
                                    >
                                        Нет записей
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Маршрут -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Маршрут</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">№</th>
                                    <th class="px-3 py-2">КПП</th>
                                    <th class="px-3 py-2">Статус прохождения</th>
                                    <th class="px-3 py-2">Дата и время прохождения</th>
                                    <th class="px-3 py-2">ФИО сотрудника охраны</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="row in materialPass.route_passages"
                                    :key="`route-${row.index}`"
                                >
                                    <td class="px-3 py-2">{{ row.index }}</td>
                                    <td class="px-3 py-2">{{ row.checkpoint }}</td>
                                    <td class="px-3 py-2">{{ row.passage_status || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.passed_at || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.security_officer || '—' }}</td>
                                </tr>
                                <tr v-if="!materialPass.route_passages.length">
                                    <td
                                        colspan="5"
                                        class="px-3 py-4 text-center text-gray-400"
                                    >
                                        Нет записей
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Комментарии -->
                <section class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Комментарии</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                                <tr>
                                    <th class="px-3 py-2">№</th>
                                    <th class="px-3 py-2">Дата</th>
                                    <th class="px-3 py-2">Роль</th>
                                    <th class="px-3 py-2">Комментарий</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="row in materialPass.comments"
                                    :key="`comment-${row.index}`"
                                >
                                    <td class="px-3 py-2">{{ row.index }}</td>
                                    <td class="px-3 py-2">{{ row.date || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.role || '—' }}</td>
                                    <td class="px-3 py-2">{{ row.body }}</td>
                                </tr>
                                <tr v-if="!materialPass.comments.length">
                                    <td
                                        colspan="4"
                                        class="px-3 py-4 text-center text-gray-400"
                                    >
                                        На текущем этапе добавление комментариев недоступно
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
