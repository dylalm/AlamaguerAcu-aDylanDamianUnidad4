<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({ categorias: { type: Array, default: () => [] } });

const form = useForm({
    nombre: '',
    categoria: '',
    barrio: '',
    descripcion: '',
    latitud: '',
    longitud: '',
});

const enviar = () => form.post(route('herramientas.store'));
</script>

<template>
    <Head title="Publicar herramienta" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Publicar herramienta</h2>
        </template>

        <div class="py-8">
            <form @submit.prevent="enviar" class="mx-auto max-w-xl space-y-4 rounded bg-white p-6 shadow">
                <div>
                    <InputLabel for="nombre" value="Nombre" />
                    <TextInput id="nombre" v-model="form.nombre" class="mt-1 block w-full" />
                    <InputError class="mt-1" :message="form.errors.nombre" />
                </div>

                <div>
                    <InputLabel for="categoria" value="Categoría" />
                    <select id="categoria" v-model="form.categoria"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="" disabled>Elige una categoría</option>
                        <option v-for="c in categorias" :key="c.valor" :value="c.valor">{{ c.etiqueta }}</option>                    
                    </select>
                    <InputError class="mt-1" :message="form.errors.categoria" />
                </div>

                <div>
                    <InputLabel for="barrio" value="Barrio" />
                    <TextInput id="barrio" v-model="form.barrio" class="mt-1 block w-full" />
                    <InputError class="mt-1" :message="form.errors.barrio" />
                </div>

                <div>
                    <InputLabel for="descripcion" value="Descripción" />
                    <textarea id="descripcion" v-model="form.descripcion" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    <InputError class="mt-1" :message="form.errors.descripcion" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="latitud" value="Latitud (ej. 19.432600)" />
                        <TextInput id="latitud" v-model="form.latitud" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.latitud" />
                    </div>
                    <div>
                        <InputLabel for="longitud" value="Longitud (ej. -99.133200)" />
                        <TextInput id="longitud" v-model="form.longitud" class="mt-1 block w-full" />
                        <InputError class="mt-1" :message="form.errors.longitud" />
                    </div>
                </div>

                <PrimaryButton :disabled="form.processing">Publicar</PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>