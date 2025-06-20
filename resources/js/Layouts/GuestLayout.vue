<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Navbar from "@/components/Navbar.vue";
import { Link } from "@inertiajs/vue3";

const sidebarOpen = ref(false);

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};
</script>

<template>
    <div
        class="min-h-screen"
        style="
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4)),
                url('/storage/images/timetable_background.png') center/cover
                    no-repeat fixed;
        "
    >
        <!-- Navbar with glass effect -->
        <Navbar @toggle-sidebar="toggleSidebar" />

        <!-- Sidebar - positioned on the right -->
        <div
            :class="[
                'fixed inset-y-0 right-0 z-50 w-64 bg-white/5 backdrop-blur-xl backdrop-saturate-150 border-l border-white/20 shadow-2xl transform transition-all duration-300 ease-in-out',
                sidebarOpen ? 'translate-x-0' : 'translate-x-full',
            ]"
            style="top: 96px"
        >
            <nav class="h-full px-4 py-6 overflow-y-auto">
                <div class="space-y-1">
                    <Link
                        href="/"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                            ></path>
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Home</span
                        >
                    </Link>

                    <Link
                        href="/schedule"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/schedule',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Schedule</span
                        >
                    </Link>

                    <Link
                        href="/artists"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/artists',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Artists</span
                        >
                    </Link>

                    <Link
                        href="/stages"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/stages',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Stages</span
                        >
                    </Link>
                </div>

                <!-- Divider with glass effect -->
                <div
                    class="my-6 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"
                ></div>

                <!-- Authentication Links -->
                <div class="space-y-1">
                    <Link
                        href="/register"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/register',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"
                            ></path>
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Register</span
                        >
                    </Link>

                    <Link
                        href="/login"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/login',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Login</span
                        >
                    </Link>
                </div>
            </nav>
        </div>

        <!-- Enhanced overlay with smooth animation -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-all duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm"
                style="top: 96px"
            ></div>
        </Transition>

        <!-- Main content - full width -->
        <main class="min-h-screen text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                <slot />
            </div>
        </main>
    </div>
</template>
