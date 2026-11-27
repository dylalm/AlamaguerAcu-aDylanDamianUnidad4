<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Crear cuenta" />

        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-[#b26b2b]">
                Forma parte de tu comunidad
            </p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-[#1b4332]">
                Crea tu cuenta
            </h1>
            <p class="mt-2 text-sm leading-6 text-gray-500">
                Conecta con tus vecinos para compartir herramientas y habilidades.
            </p>
        </div>

        <form class="mt-7 space-y-4" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nombre" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-2 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Correo electrónico" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full"
                    v-model="form.email"
                    required
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
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirmar contraseña"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-2 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <PrimaryButton
                class="mt-2 w-full justify-center rounded-xl !bg-[#1b4332] py-3 text-sm shadow-lg shadow-[#1b4332]/15 transition hover:!bg-[#153628] focus:!ring-[#d68c45]"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Crear cuenta
            </PrimaryButton>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            ¿Ya tienes una cuenta?
            <Link
                :href="route('login')"
                class="font-semibold text-[#1b4332] transition hover:text-[#b26b2b] focus:outline-none focus:ring-2 focus:ring-[#d68c45] focus:ring-offset-2"
            >
                Inicia sesión
            </Link>
        </p>
    </GuestLayout>
</template>
