<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import Countdown from "./Countdown.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const emit = defineEmits(["toggle-sidebar"]);

const toggleSidebar = () => {
    emit("toggle-sidebar");
};
</script>

<template>
    <nav
        class="bg-black/20 backdrop-blur-xl py-4 backdrop-saturate-180 border-b border-white/20 shadow-2xl relative z-60 w-full"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side: Logo -->
                <div class="flex items-center space-x-4">
                    <!-- Download Logo -->
                    <Link href="/" class="flex items-center">
                        <ApplicationLogo class="h-6 w-auto md:h-8" />
                    </Link>
                </div>

                <!-- Center: Countdown Timer -->
                <div class="flex-1 flex justify-center max-w-md mx-4">
                    <Countdown />
                </div>

                <!-- Right side: Hamburger menu button -->
                <div class="flex items-center space-x-4">
                    <button
                        @click="toggleSidebar"
                        class="p-2 rounded-md text-gray-300 hover:text-white hover:bg-white/20 hover:backdrop-blur-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-400 transition-all duration-200"
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>
