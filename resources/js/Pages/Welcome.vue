<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const clicks = ref(0);
const vueWorks = computed(() => clicks.value > 0);
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="mx-auto flex min-h-screen max-w-3xl flex-col justify-center px-6 py-16">
            <header class="mb-10 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-emerald-400">
                        Material Pass
                    </p>
                    <h1 class="mt-2 text-4xl font-semibold tracking-tight">
                        Laravel + Vue + Inertia
                    </h1>
                </div>

                <nav v-if="canLogin" class="flex gap-3 text-sm">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-md border border-slate-700 px-3 py-2 hover:border-emerald-400"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md border border-slate-700 px-3 py-2 hover:border-emerald-400"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-md bg-emerald-500 px-3 py-2 font-medium text-slate-950 hover:bg-emerald-400"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <section class="rounded-2xl border border-slate-800 bg-slate-900/70 p-8 shadow-xl shadow-emerald-950/30">
                <p class="text-lg text-slate-300">
                    This page is rendered by Vue through Inertia. Click the button to confirm
                    client-side reactivity is working.
                </p>

                <button
                    type="button"
                    class="mt-6 rounded-md bg-emerald-500 px-4 py-2 font-medium text-slate-950 transition hover:bg-emerald-400"
                    @click="clicks += 1"
                >
                    Prove Vue works
                </button>

                <p class="mt-4 text-sm text-emerald-300" data-testid="vue-proof">
                    <span v-if="vueWorks">Vue is interactive — clicks: {{ clicks }}</span>
                    <span v-else>Waiting for a click…</span>
                </p>

                <dl class="mt-8 grid gap-3 text-sm text-slate-400 sm:grid-cols-2">
                    <div>
                        <dt class="uppercase tracking-wide text-slate-500">Laravel</dt>
                        <dd class="mt-1 text-slate-200">{{ laravelVersion }}</dd>
                    </div>
                    <div>
                        <dt class="uppercase tracking-wide text-slate-500">PHP</dt>
                        <dd class="mt-1 text-slate-200">{{ phpVersion }}</dd>
                    </div>
                </dl>
            </section>
        </div>
    </div>
</template>
