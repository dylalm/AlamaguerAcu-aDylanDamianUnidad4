<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar sesión" />

        <div
            v-if="status"
            class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"
        >
            {{ status }}
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#b26b2b]">
                Qué bueno verte
            </p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#1b4332]">
                Inicia sesión
            </h1>
            <p class="mt-2 text-sm leading-6 text-gray-500">
                Entra a tu cuenta y sigue compartiendo con tus vecinos.
            </p>
        </div>

        <form class="mt-8 space-y-5" @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Correo electrónico" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Contraseña" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <label class="inline-flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Mantener sesión iniciada</span>
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-semibold text-[#1b4332] transition hover:text-[#b26b2b] focus:outline-none focus:ring-2 focus:ring-[#d68c45] focus:ring-offset-2"
                >
                    ¿Olvidaste tu contraseña?
                </Link>
            </div>

            <PrimaryButton
                class="w-full justify-center rounded-xl !bg-[#1b4332] py-3 text-sm shadow-lg shadow-[#1b4332]/15 transition hover:!bg-[#153628] focus:!ring-[#d68c45]"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Iniciar sesión
            </PrimaryButton>
        </form>

        <div class="my-7 flex items-center gap-4">
            <span class="h-px flex-1 bg-gray-200"></span>
            <span class="text-xs font-medium uppercase tracking-wider text-gray-400">o</span>
            <span class="h-px flex-1 bg-gray-200"></span>
        </div>

        <div class="text-center">
            <p class="mb-3 text-sm text-gray-600">¿Todavía no tienes cuenta?</p>
            <a
                :href="route('register')"
                class="inline-flex w-full items-center justify-center rounded-xl border border-[#1b4332]/20 bg-[#f7f5ef] px-5 py-3 text-sm font-semibold text-[#1b4332] transition hover:border-[#1b4332] hover:bg-[#edf2ed] focus:outline-none focus:ring-2 focus:ring-[#d68c45] focus:ring-offset-2"
            >
                Crear una cuenta
            </a>
        </div>
    </GuestLayout>
</template>
