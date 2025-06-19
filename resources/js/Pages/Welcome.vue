/Pages/Welcome.vue
<template>
    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div
                        class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700"
                    >
                        <h1
                            class="text-2xl font-medium text-gray-900 dark:text-white"
                        >
                            Welcome to Festival Schedule
                        </h1>
                        <p
                            class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed"
                        >
                            Your festival schedule and countdown timer.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
</script>
