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
    department: {
        type: Object,
        required: true,
    },
    parents: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    parent_id: props.department.parent_id,
    code: props.department.code ?? '',
    name: props.department.name,
    organization_name: props.department.organization_name ?? '',
    is_active: props.department.is_active,
});

const submit = () => {
    form.put(route('departments.update', props.department.id));
};
</script>

<template>
    <Head title="Редактирование подразделения" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Редактирование подразделения
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" value="Название" />
                            <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="code" value="Код" />
                            <TextInput id="code" v-model="form.code" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.code" />
                        </div>

                        <div>
                            <InputLabel for="organization_name" value="Организация" />
                            <TextInput
                                id="organization_name"
                                v-model="form.organization_name"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.organization_name" />
                        </div>

                        <div>
                            <InputLabel for="parent_id" value="Родительское подразделение" />
                            <SelectInput id="parent_id" v-model="form.parent_id" class="mt-1 block w-full">
                                <option value="">— нет —</option>
                                <option
                                    v-for="parent in parents"
                                    :key="parent.id"
                                    :value="parent.id"
                                >
                                    {{ parent.label }}
                                </option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.parent_id" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="is_active" v-model:checked="form.is_active" />
                            <InputLabel for="is_active" value="Активно" />
                        </div>
                        <InputError :message="form.errors.is_active" />

                        <div class="flex items-center gap-3">
                            <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>
                            <Link :href="route('departments.index')">
                                <SecondaryButton type="button">Отмена</SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
