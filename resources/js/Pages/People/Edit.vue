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
    person: {
        type: Object,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    user_id: props.person.user_id,
    last_name: props.person.last_name,
    first_name: props.person.first_name,
    middle_name: props.person.middle_name ?? '',
    email: props.person.email ?? '',
    phone: props.person.phone ?? '',
    is_active: props.person.is_active,
});

const submit = () => {
    form.put(route('people.update', props.person.id));
};
</script>

<template>
    <Head title="Редактирование физического лица" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Редактирование физического лица
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <InputLabel for="last_name" value="Фамилия" />
                            <TextInput
                                id="last_name"
                                v-model="form.last_name"
                                class="mt-1 block w-full"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.last_name" />
                        </div>

                        <div>
                            <InputLabel for="first_name" value="Имя" />
                            <TextInput
                                id="first_name"
                                v-model="form.first_name"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.first_name" />
                        </div>

                        <div>
                            <InputLabel for="middle_name" value="Отчество" />
                            <TextInput
                                id="middle_name"
                                v-model="form.middle_name"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.middle_name" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="phone" value="Телефон" />
                            <TextInput
                                id="phone"
                                v-model="form.phone"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>

                        <div>
                            <InputLabel for="user_id" value="Пользователь системы" />
                            <SelectInput
                                id="user_id"
                                v-model="form.user_id"
                                class="mt-1 block w-full"
                            >
                                <option value="">— не выбран —</option>
                                <option
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.label }}
                                </option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.user_id" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox
                                id="is_active"
                                v-model:checked="form.is_active"
                            />
                            <InputLabel for="is_active" value="Активен" />
                        </div>
                        <InputError :message="form.errors.is_active" />

                        <div class="flex items-center gap-3">
                            <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>
                            <Link :href="route('people.index')">
                                <SecondaryButton type="button">Отмена</SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
