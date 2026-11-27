<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-[#f7f5ef] text-[#1f2a24]">
        <div
            aria-hidden="true"
            class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-[#d68c45]/10 blur-3xl"
        ></div>
        <div
            aria-hidden="true"
            class="pointer-events-none absolute -bottom-40 -right-32 h-[28rem] w-[28rem] rounded-full bg-[#1b4332]/10 blur-3xl"
        ></div>

        <div
            class="relative mx-auto grid min-h-screen max-w-7xl grid-cols-1 items-center gap-8 px-5 py-8 sm:px-8 lg:grid-cols-[minmax(0,1fr)_minmax(420px,500px)] lg:gap-16 lg:px-12"
        >
            <section class="mx-auto w-full max-w-xl lg:mx-0">
                <Link href="/" class="inline-flex items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#d68c45] focus:ring-offset-4">
                    <span
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#1b4332] text-sm font-bold tracking-wide text-white shadow-lg shadow-[#1b4332]/20"
                    >
                        MV
                    </span>
                    <span>
                        <span class="block text-xl font-bold tracking-tight text-[#1b4332]">ManoVecina</span>
                        <span class="block text-xs font-medium text-[#68736b]">Herramientas y habilidades entre vecinos</span>
                    </span>
                </Link>

                <div class="mt-12 hidden lg:block">
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-[#b26b2b]">
                        Una comunidad más cercana
                    </p>
                    <h1 class="mt-4 max-w-lg text-5xl font-bold leading-[1.08] tracking-tight text-[#1b4332]">
                        Compartir nos hace crecer.
                    </h1>
                    <p class="mt-5 max-w-lg text-lg leading-8 text-[#5b665f]">
                        Encuentra lo que necesitas, ofrece lo que sabes y construye confianza con quienes viven cerca.
                    </p>

                    <div class="mt-9 space-y-4">
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white font-semibold text-[#1b4332] shadow-sm">01</span>
                            <p class="text-sm font-medium text-[#3d4a42]">Presta y encuentra herramientas en tu comunidad.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white font-semibold text-[#1b4332] shadow-sm">02</span>
                            <p class="text-sm font-medium text-[#3d4a42]">Intercambia habilidades y ayuda entre vecinos.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white font-semibold text-[#1b4332] shadow-sm">03</span>
                            <p class="text-sm font-medium text-[#3d4a42]">Haz crecer una red basada en la confianza.</p>
                        </div>
                    </div>
                </div>
            </section>

            <main class="mx-auto w-full max-w-xl">
                <div class="rounded-3xl border border-white/80 bg-white/95 p-6 shadow-[0_24px_70px_-30px_rgba(27,67,50,0.28)] backdrop-blur sm:p-9">
                    <slot />
                </div>
                <p class="mt-6 text-center text-xs text-[#7b857e]">
                    ManoVecina · Compartir, ayudar y confiar.
                </p>
            </main>
        </div>
    </div>
</template>
