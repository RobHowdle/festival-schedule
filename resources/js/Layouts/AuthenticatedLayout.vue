<script setup>
import { ref, onMounted, computed } from "vue";
import ApplicationLogo from "@/components/ApplicationLogo.vue";
import Dropdown from "@/components/Dropdown.vue";
import DropdownLink from "@/components/DropdownLink.vue";
import Countdown from "@/components/Countdown.vue";
import ResponsiveNavLink from "@/components/ResponsiveNavLink.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(false);
const particles = ref([]);
const page = usePage();

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

// Create Particles Data Array
const createParticles = () => {
    const particleCount = 20;
    particles.value = Array.from({ length: particleCount }, (_, i) => ({
        id: i,
        left: Math.random() * 100,
        animationDelay: Math.random() * 8,
        animationDuration: 8 + Math.random() * 4,
    }));
};

onMounted(() => {
    createParticles();
});
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
        <nav
            class="bg-white/5 backdrop-blur-xl backdrop-saturate-150 border-b border-white/20 shadow-lg relative z-50"
        >
            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-white"
                                />
                            </Link>
                        </div>
                    </div>

                    <!-- Centered Countdown -->
                    <div class="absolute left-1/2 transform -translate-x-1/2">
                        <Countdown />
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:space-x-4">
                        <!-- Settings Dropdown -->
                        <div class="relative z-[60]">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-white/20 hover:text-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <div
                                        class="bg-black/80 backdrop-blur-xl border border-white/20 rounded-lg shadow-lg"
                                        style="
                                            z-index: 9999;
                                            position: relative;
                                        "
                                    >
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                            class="block px-4 py-2 text-sm text-gray-200 hover:bg-white/10 hover:text-white transition-colors duration-200"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-white/10 hover:text-white transition-colors duration-200"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Sidebar Toggle Button -->
                        <button
                            @click="toggleSidebar"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-200 transition duration-150 ease-in-out hover:bg-white/10 hover:text-cyan-400 focus:bg-white/10 focus:text-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50"
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

                    <!-- Mobile Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-200 transition duration-150 ease-in-out hover:bg-white/10 hover:text-cyan-400 focus:bg-white/10 focus:text-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
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

            <!-- Mobile countdown (visible on small screens) -->
            <div
                class="sm:hidden flex justify-center py-2 border-t border-white/20"
            >
                <Countdown />
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden bg-white/5 backdrop-blur-lg border-t border-white/20"
            >
                <div class="space-y-1 pb-3 pt-2">
                    <ResponsiveNavLink
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                        class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-200 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white focus:outline-none"
                        :class="{
                            'bg-white/15 text-white border-l-4 border-cyan-400':
                                route().current('dashboard'),
                        }"
                    >
                        Dashboard
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div class="border-t border-white/20 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-white">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="text-sm font-medium text-gray-300">
                            {{ $page.props.auth.user.email }}
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('profile.edit')"
                            class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-200 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white focus:outline-none"
                        >
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block w-full ps-3 pe-4 py-2 text-start text-base font-medium text-gray-200 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:bg-white/10 focus:text-white focus:outline-none"
                        >
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar - positioned on the right (same as GuestLayout) -->
        <div
            :class="[
                'fixed inset-y-0 right-0 z-50 w-64 bg-white/5 backdrop-blur-xl backdrop-saturate-150 border-l border-white/20 shadow-2xl transform transition-all duration-300 ease-in-out',
                sidebarOpen ? 'translate-x-0' : 'translate-x-full',
            ]"
            style="top: 64px"
        >
            <nav class="h-full px-4 py-6 overflow-y-auto">
                <div class="space-y-1">
                    <Link
                        :href="route('dashboard')"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                route().current('dashboard'),
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Dashboard</span
                        >
                    </Link>

                    <Link
                        :href="route('profile.edit')"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                route().current('profile.edit'),
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
                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Profile</span
                        >
                    </Link>
                </div>

                <!-- Divider with glass effect -->
                <div
                    class="my-6 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"
                ></div>

                <!-- Public Links -->
                <div class="space-y-1">
                    <div class="px-4 py-2">
                        <h4
                            class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                        >
                            Festival
                        </h4>
                    </div>

                    <Link
                        href="/"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Home</span
                        >
                    </Link>

                    <Link
                        href="/schedule"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
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
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Schedule</span
                        >
                    </Link>

                    <Link
                        href="/my-artists"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >My Artists</span
                        >
                    </Link>
                </div>

                <!-- Divider with glass effect -->
                <div
                    class="my-6 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"
                ></div>

                <!-- Logout -->
                <div class="space-y-1">
                    <Link
                        :href="route('logout')"
                        method="post"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-cyan-400 transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Logout</span
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
                style="top: 64px"
            ></div>
        </Transition>

        <!-- Main content -->
        <main class="min-h-screen text-white">
            <!-- Floating particles background -->
            <div class="particles">
                <div
                    v-for="particle in particles"
                    :key="particle.id"
                    class="particle"
                    :style="{
                        left: particle.left + '%',
                        animationDelay: particle.animationDelay + 's',
                        animationDuration: particle.animationDuration + 's',
                    }"
                ></div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Floating particles background */
.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
}

.particle {
    position: absolute;
    width: 2px;
    height: 2px;
    background: #00ffc3;
    border-radius: 50%;
    animation: float-particle 8s infinite ease-in-out;
    opacity: 0.3;
}

@keyframes float-particle {
    0%,
    100% {
        transform: translateY(100vh) translateX(0) scale(0.5);
        opacity: 0;
    }
    50% {
        opacity: 0.8;
        transform: translateY(0) translateX(50px) scale(1);
    }
}

/* Custom dropdown styling */
.dropdown-content {
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
