<template>
    <GuestLayout>
        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div
                        class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
                    >
                        <h1
                            class="text-2xl font-medium text-gray-900 dark:text-white"
                        >
                            Welcome to Your Next Festival
                        </h1>
                        <p
                            class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed"
                        >
                            View lineups, artists, vendors and more.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto"><Weather /></div>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Weather from "@/components/Weather.vue";
</script>
