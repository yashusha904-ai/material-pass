<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    employee: {
        type: Object,
        required: true,
    },
    people: {
        type: Array,
        required: true,
    },
    departments: {
        type: Array,
        required: true,
    },
    staffPositions: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    person_id: props.employee.person_id,
    department_id: props.employee.department_id,
    staff_position_id: props.employee.staff_position_id,
    personnel_number: props.employee.personnel_number ?? '',
    hired_on: props.employee.hired_on ?? '',
    fired_on: props.employee.fired_on ?? '',
    is_active: props.employee.is_active,
});

const submit = () => {
    form.put(route('employees.update', props.employee.id));
};
</script>

<template>
    <Head title="Редактирование сотрудника" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Редактирование сотрудника
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <InputLabel for="person_id" value="Физическое лицо" />
                            <SelectInput id="person_id" v-model="form.person_id" class="mt-1 block w-full" required>
                                <option value="">— выберите —</option>
                                <option v-for="person in people" :key="person.id" :value="person.id">
                                    {{ person.label }}
                                </option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.person_id" />
                        </div>

                        <div>
                            <InputLabel for="department_id" value="Подразделение" />
                            <SelectInput id="department_id" v-model="form.department_id" class="mt-1 block w-full">
                                <option value="">— не выбрано —</option>
                                <option
                                    v-for="department in departments"
                                    :key="department.id"
                                    :value="department.id"
                                >
                                    {{ department.label }}
                                </option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.department_id" />
                        </div>

                        <div>
                            <InputLabel for="staff_position_id" value="Позиция штатного расписания" />
                            <SelectInput
                                id="staff_position_id"
                                v-model="form.staff_position_id"
                                class="mt-1 block w-full"
                            >
                                <option value="">— не выбрана —</option>
                                <option
                                    v-for="position in staffPositions"
                                    :key="position.id"
                                    :value="position.id"
                                >
                                    {{ position.label }}
                                </option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.staff_position_id" />
                        </div>

                        <div>
                            <InputLabel for="personnel_number" value="Табельный номер" />
                            <TextInput
                                id="personnel_number"
                                v-model="form.personnel_number"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.personnel_number" />
                        </div>

                        <div>
                            <InputLabel for="hired_on" value="Дата приёма" />
                            <TextInput
                                id="hired_on"
                                v-model="form.hired_on"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.hired_on" />
                        </div>

                        <div>
                            <InputLabel for="fired_on" value="Дата увольнения" />
                            <TextInput
                                id="fired_on"
                                v-model="form.fired_on"
                                type="date"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.fired_on" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="is_active" v-model:checked="form.is_active" />
                            <InputLabel for="is_active" value="Активен" />
                        </div>
                        <InputError :message="form.errors.is_active" />

                        <div class="flex items-center gap-3">
                            <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>
                            <Link :href="route('employees.index')">
                                <SecondaryButton type="button">Отмена</SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
