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
    code: '',
    title: '',
    is_active: true,
});

const submit = () => {
    form.post(route('staff-positions.store'));
};
</script>

<template>
    <Head title="Новая позиция штатного расписания" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Новая позиция штатного расписания
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <InputLabel for="code" value="Код" />
                            <TextInput id="code" v-model="form.code" class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.code" />
                        </div>

                        <div>
                            <InputLabel for="title" value="Название" />
                            <TextInput id="title" v-model="form.title" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div class="flex items-center gap-2">
                            <Checkbox id="is_active" v-model:checked="form.is_active" />
                            <InputLabel for="is_active" value="Активна" />
                        </div>
                        <InputError :message="form.errors.is_active" />

                        <div class="flex items-center gap-3">
                            <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>
                            <Link :href="route('staff-positions.index')">
                                <SecondaryButton type="button">Отмена</SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
