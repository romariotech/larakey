<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { redirect } from '@/actions/App/Http/Controllers/AuthController';
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="relative flex min-h-screen flex-col items-center overflow-hidden p-6 font-sans lg:justify-center lg:p-8"
        style="
            background:
                radial-gradient(
                    circle at top left,
                    rgba(99, 102, 241, 0.08),
                    transparent 30%
                ),
                radial-gradient(
                    circle at bottom right,
                    rgba(239, 68, 68, 0.06),
                    transparent 30%
                ),
                #f1f5f9;
        "
    >
        <header
            class="z-10 mb-6 w-full max-w-83.75 text-sm not-has-[nav]:hidden lg:max-w-5xl"
        >
            <nav class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="rounded-md border border-white/10 bg-white/10 p-2 text-slate-900 backdrop-blur-md"
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <h2
                            class="text-lg font-semibold tracking-tight text-slate-900"
                        >
                            Larakey
                        </h2>
                        <p class="text-xs text-slate-600">
                            Authentication Platform
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="redirect()"
                        class="inline-flex items-center gap-2 rounded-md bg-gradient-to-r from-indigo-600 to-indigo-700 px-5 py-1.5 text-sm font-medium text-white transition-all duration-200 hover:from-indigo-700 hover:to-indigo-800"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="redirect()"
                            class="inline-flex items-center gap-2 rounded-md border border-slate-300 px-5 py-1.5 text-sm font-medium text-slate-700 transition-all duration-200 hover:bg-slate-50"
                        >
                            Log in
                        </Link>
                    </template>
                </div>
            </nav>
        </header>
        <div
            class="z-10 flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main class="w-full max-w-83.75 lg:max-w-5xl">
                <!-- Blurred gradient circles -->
                <div
                    class="absolute top-20 -left-40 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"
                ></div>
                <div
                    class="absolute -right-40 -bottom-20 h-80 w-80 rounded-full bg-cyan-500/10 blur-3xl"
                ></div>

                <div
                    class="relative grid items-center gap-8 lg:grid-cols-2 lg:gap-12"
                >
                    <!-- Left Content -->
                    <div
                        class="order-2 flex flex-col justify-center lg:order-1"
                    >
                        <div class="space-y-6">
                            <div>
                                <span
                                    class="mb-4 inline-flex items-center rounded-lg border border-white/10 bg-white/10 px-3 py-1 text-xs font-medium text-slate-600"
                                >
                                    Bem-vindo
                                </span>
                                <h1
                                    class="text-4xl leading-tight font-bold text-slate-900 lg:text-5xl"
                                >
                                    Plataforma de autenticação segura
                                </h1>
                            </div>
                            <p class="text-lg leading-relaxed text-slate-600">
                                Gerencie autenticação, sessões e acesso de
                                usuários em um ambiente moderno e seguro.
                                Construído com tecnologias de ponta para máxima
                                confiabilidade.
                            </p>
                        </div>

                        <!-- Features -->
                        <div class="mt-8 space-y-2">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex-shrink-0">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-white"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="text-base font-semibold text-slate-900"
                                    >
                                        Segurança em primeiro lugar
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Implementação robusta de autenticação
                                        com Keycloak
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex-shrink-0">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-white"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="text-base font-semibold text-slate-900"
                                    >
                                        Interface moderna
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Design responsivo e intuitivo com
                                        Tailwind CSS
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex-shrink-0">
                                    <div
                                        class="flex h-6 w-6 items-center justify-center rounded-md bg-indigo-600 text-white"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="text-base font-semibold text-slate-900"
                                    >
                                        Escalável e confiável
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-600">
                                        Arquitetura preparada para produção
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Visual -->
                    <div class="order-1 flex justify-center lg:order-2">
                        <div class="relative w-full max-w-sm">
                            <!-- Card background glow -->
                            <div
                                class="absolute inset-0 rounded-2xl bg-gradient-to-br from-indigo-100 to-cyan-100 opacity-30 blur-2xl"
                            ></div>

                            <!-- Main card -->
                            <div
                                class="relative space-y-6 rounded-2xl border border-white/50 bg-white p-8 shadow-2xl backdrop-blur-xl"
                            >
                                <div
                                    class="mx-auto flex h-20 w-20 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-700"
                                >
                                    <svg
                                        class="h-10 w-10 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                        ></path>
                                    </svg>
                                </div>

                                <div class="space-y-2 text-center">
                                    <h3
                                        class="text-xl font-semibold text-slate-900"
                                    >
                                        Proteção avançada
                                    </h3>
                                    <p class="text-sm text-slate-600">
                                        Seus dados estão seguros com nossa
                                        autenticação de ponta
                                    </p>
                                </div>

                                <div class="space-y-3 pt-4">
                                    <div
                                        class="flex items-center gap-3 rounded-lg bg-indigo-50 p-3"
                                    >
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-indigo-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900"
                                        >
                                            Autenticação Multi-fator
                                        </span>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 rounded-lg bg-cyan-50 p-3"
                                    >
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-cyan-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900"
                                        >
                                            Tokens Seguros
                                        </span>
                                    </div>
                                    <div
                                        class="flex items-center gap-3 rounded-lg bg-slate-50 p-3"
                                    >
                                        <div class="flex-shrink-0">
                                            <svg
                                                class="h-5 w-5 text-slate-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-900"
                                        >
                                            Gerenciamento de Sessões
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
