<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    users_count: Number,
    artists_count: Number,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <MainLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"
                >
                    <!-- Users Stats Card -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-cyan-500/20">
                                <svg
                                    class="w-8 h-8 text-cyan-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">
                                    Total Users
                                </h3>
                                <p class="text-3xl font-bold text-cyan-400">
                                    {{ users_count }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Artists Stats Card -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500/20">
                                <svg
                                    class="w-8 h-8 text-purple-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">
                                    Total Artists
                                </h3>
                                <p class="text-3xl font-bold text-purple-400">
                                    {{ artists_count }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions Card -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500/20">
                                <svg
                                    class="w-8 h-8 text-green-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">
                                    Admin Panel
                                </h3>
                                <p class="text-sm text-gray-300">
                                    Manage festival data
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Welcome Message -->
                <div
                    class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-8"
                >
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-white mb-4">
                            Welcome to the Admin Dashboard
                        </h1>
                        <p class="text-gray-300 text-lg">
                            You have administrative access to manage the
                            festival schedule system.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
