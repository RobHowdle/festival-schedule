<template>
    <nav class="bg-gray-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <Link href="/" class="text-xl font-bold text-cyan-400">
                        Festival Schedule
                    </Link>
                </div>

                <!-- Countdown Timer -->
                <CountdownTimer />

                <!-- Navigation Links -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <Link
                            href="/"
                            class="hover:text-cyan-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            :class="{ 'text-cyan-400': $page.url === '/' }"
                        >
                            Home
                        </Link>
                        <Link
                            href="/schedule"
                            class="hover:text-cyan-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            :class="{
                                'text-cyan-400': $page.url === '/schedule',
                            }"
                        >
                            Schedule
                        </Link>
                        <Link
                            href="/artists"
                            class="hover:text-cyan-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            :class="{
                                'text-cyan-400': $page.url === '/artists',
                            }"
                        >
                            Artists
                        </Link>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-gray-400 hover:text-white focus:outline-none focus:text-white"
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: mobileMenuOpen,
                                    'inline-flex': !mobileMenuOpen,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !mobileMenuOpen,
                                    'inline-flex': mobileMenuOpen,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div
            :class="{ block: mobileMenuOpen, hidden: !mobileMenuOpen }"
            class="md:hidden"
        >
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-800">
                <!-- Mobile Countdown -->
                <div class="flex items-center justify-center py-4">
                    <div
                        v-if="mobilePhase !== 'pit'"
                        class="flex items-center space-x-3"
                    >
                        <div class="timer-unit text-center">
                            <div class="timer-number text-2xl font-bold">
                                {{ mobileDays }}
                            </div>
                            <div class="timer-label text-xs text-cyan-400">
                                DAYS
                            </div>
                        </div>
                        <div class="timer-unit text-center">
                            <div class="timer-number text-2xl font-bold">
                                {{ mobileHours }}
                            </div>
                            <div class="timer-label text-xs text-cyan-400">
                                HRS
                            </div>
                        </div>
                        <div class="timer-unit text-center">
                            <div class="timer-number text-2xl font-bold">
                                {{ mobileMinutes }}
                            </div>
                            <div class="timer-label text-xs text-cyan-400">
                                MINS
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        <span class="text-lg font-bold text-cyan-400"
                            >See You In The Pit!</span
                        >
                    </div>
                </div>

                <Link
                    href="/"
                    class="hover:text-cyan-400 block px-3 py-2 rounded-md text-base font-medium"
                    @click="mobileMenuOpen = false"
                >
                    Home
                </Link>
                <Link
                    href="/schedule"
                    class="hover:text-cyan-400 block px-3 py-2 rounded-md text-base font-medium"
                    @click="mobileMenuOpen = false"
                >
                    Schedule
                </Link>
                <Link
                    href="/artists"
                    class="hover:text-cyan-400 block px-3 py-2 rounded-md text-base font-medium"
                    @click="mobileMenuOpen = false"
                >
                    Artists
                </Link>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import CountdownTimer from "./CountdownTimer.vue";

const mobileMenuOpen = ref(false);
const mobileDays = ref("00");
const mobileHours = ref("00");
const mobileMinutes = ref("00");
const mobilePhase = ref("countdown25");
const mobileInterval = ref(null);

const festivalStart = new Date("2025-06-12T00:00:00").getTime();
const festivalEnd = new Date("2025-06-16T00:00:00").getTime();
const nextDownloadStart = new Date("2026-06-10T00:00:00").getTime();

const updateMobileTimer = () => {
    const now = Date.now();
    let targetDate;

    if (now < festivalStart) {
        targetDate = festivalStart;
        mobilePhase.value = "countdown25";
    } else if (now >= festivalStart && now < festivalEnd) {
        mobilePhase.value = "pit";
        mobileDays.value = "--";
        mobileHours.value = "--";
        mobileMinutes.value = "--";
        return;
    } else {
        targetDate = nextDownloadStart;
        mobilePhase.value = "countdown26";
    }

    const distance = targetDate - now;

    if (distance > 0) {
        mobileDays.value = Math.floor(distance / (1000 * 60 * 60 * 24))
            .toString()
            .padStart(2, "0");
        mobileHours.value = Math.floor(
            (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
        )
            .toString()
            .padStart(2, "0");
        mobileMinutes.value = Math.floor(
            (distance % (1000 * 60 * 60)) / (1000 * 60)
        )
            .toString()
            .padStart(2, "0");
    } else {
        mobileDays.value = "00";
        mobileHours.value = "00";
        mobileMinutes.value = "00";
    }
};

onMounted(() => {
    updateMobileTimer();
    mobileInterval.value = setInterval(updateMobileTimer, 1000);
});

onUnmounted(() => {
    if (mobileInterval.value) {
        clearInterval(mobileInterval.value);
    }
});
</script>
