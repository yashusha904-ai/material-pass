<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    short_name: '',
    inn: '',
    kpp: '',
    address: '',
    is_active: true,
});

const submit = () => {
    form.post(route('external-organizations.store'));
};
</script>

<template>
    <Head title="Новая внешняя организация" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Новая внешняя организация
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
                            <InputLabel for="short_name" value="Краткое название" />
                            <TextInput id="short_name" v-model="form.short_name" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.short_name" />
                        </div>

                        <div>
                            <InputLabel for="inn" value="ИНН" />
                            <TextInput id="inn" v-model="form.inn" class="mt-1 block w-full" maxlength="12" />
                            <InputError class="mt-2" :message="form.errors.inn" />
                        </div>

                        <div>
                            <InputLabel for="kpp" value="КПП" />
                            <TextInput id="kpp" v-model="form.kpp" class="mt-1 block w-full" maxlength="9" />
                            <InputError class="mt-2" :message="form.errors.kpp" />
                        </div>

                        <div>
                            <InputLabel for="address" value="Адрес" />
                            <TextInput id="address" v-model="form.address" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="is_active" v-model:checked="form.is_active" />
                            <InputLabel for="is_active" value="Активна" />
                        </div>
                        <InputError :message="form.errors.is_active" />

                        <div class="flex items-center gap-3">
                            <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>
                            <Link :href="route('external-organizations.index')">
                                <SecondaryButton type="button">Отмена</SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
