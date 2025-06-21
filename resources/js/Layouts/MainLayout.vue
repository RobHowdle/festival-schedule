<script setup>
import { ref, onMounted, computed } from "vue";
import ApplicationLogo from "@/components/ApplicationLogo.vue";
import Navbar from "@/components/Navbar.vue";
import { Link, usePage } from "@inertiajs/vue3";

const sidebarOpen = ref(false);
const particles = ref([]);
const page = usePage();

const props = defineProps({
    expandedLayout: {
        type: Boolean,
        default: false,
    },
});

// Check if user is authenticated
const isAuthenticated = computed(() => {
    return page.props.auth && page.props.auth.user;
});

// Get system settings
const systemSettings = computed(() => {
    return page.props.systemSettings || {};
});

// Compute background style
const backgroundStyle = computed(() => {
    const settings = systemSettings.value;

    if (settings.background_type === "image" && settings.background_image) {
        return {
            background: `linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4)), url('/storage/${settings.background_image}') center/cover no-repeat fixed`,
        };
    } else {
        // Use color background
        const bgColor = settings.background_color || "#1F2937";
        return {
            background: `linear-gradient(135deg, ${bgColor}e6, ${bgColor}cc)`,
        };
    }
});

// Compute CSS custom properties for colors
const colorVariables = computed(() => {
    const settings = systemSettings.value;
    return {
        "--color-primary": settings.color_primary || "#8B5CF6",
        "--color-secondary": settings.color_secondary || "#06B6D4",
        "--color-accent": settings.color_accent || "#F59E0B",
        "--color-background": settings.color_background || "#1F2937",
        "--color-text": settings.color_text || "#FFFFFF",
        "--color-text-secondary": settings.color_textSecondary || "#9CA3AF",
    };
});

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
        :style="{ ...backgroundStyle, ...colorVariables }"
    >
        <!-- Navbar with glass effect -->
        <Navbar @toggle-sidebar="toggleSidebar" />

        <!-- Sidebar - positioned on the right -->
        <div
            :class="[
                'fixed inset-y-0 right-0 z-50 w-64 bg-white/5 backdrop-blur-xl backdrop-saturate-150 border-l border-white/20 shadow-2xl transform transition-all duration-300 ease-in-out',
                sidebarOpen ? 'translate-x-0' : 'translate-x-full',
            ]"
            style="top: 80px"
        >
            <nav class="h-full px-4 py-6 overflow-y-auto">
                <div class="space-y-1">
                    <Link
                        href="/"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
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
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/schedule',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
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
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/artists',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Artists</span
                        >
                    </Link>

                    <Link
                        href="/my-artists"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/my-artists',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
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

                    <Link
                        href="/stages"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/stages',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM15.657 6.343a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-1.929 3.657 1 1 0 11-1.414-1.414A7.971 7.971 0 0017 10c0-.995-.102-1.951-.343-2.657a1 1 0 010-1.414z"
                            />
                            <path
                                d="M13.828 7.172a1 1 0 011.414 0A5.983 5.983 0 0116 10a5.983 5.983 0 01-.758 2.828 1 1 0 11-1.414-1.414A3.987 3.987 0 0014 10c0-.537-.121-1.045-.342-1.414a1 1 0 010-1.414z"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Stages</span
                        >
                    </Link>
                    <Link
                        href="/vendors"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/vendors',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6V5a2 2 0 114 0v1H8zm-3.5 5a.5.5 0 001 0 1.5 1.5 0 003 0 .5.5 0 001 0 3.5 3.5 0 01-7 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Vendors</span
                        >
                    </Link>

                    <Link
                        href="/map"
                        class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                        :class="{
                            'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                $page.url === '/map',
                        }"
                        @click="sidebarOpen = false"
                    >
                        <svg
                            class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <span
                            class="group-hover:translate-x-1 transition-transform duration-200"
                            >Map</span
                        >
                    </Link>
                </div>

                <!-- Divider with glass effect -->
                <div
                    class="my-6 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"
                ></div>
                <Link
                    href="/wallpaper-generator"
                    class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                    :class="{
                        'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                            $page.url === '/wallpaper-generator',
                    }"
                    @click="sidebarOpen = false"
                >
                    <svg
                        class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zM6 4a1 1 0 011-1h6a1 1 0 011 1v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4zm4 10a1 1 0 100 2 1 1 0 000-2z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span
                        class="group-hover:translate-x-1 transition-transform duration-200"
                        >Phone Wallpaper</span
                    >
                </Link>

                <Link
                    href="/poster-generator"
                    class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                    :class="{
                        'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                            $page.url === '/poster-generator',
                    }"
                    @click="sidebarOpen = false"
                >
                    <svg
                        class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span
                        class="group-hover:translate-x-1 transition-transform duration-200"
                        >Poster Generator</span
                    >
                </Link>

                <!-- Divider with glass effect -->
                <div
                    class="my-6 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"
                ></div>

                <!-- Authentication Links -->
                <div class="space-y-1">
                    <template v-if="isAuthenticated">
                        <Link
                            href="/dashboard"
                            class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                            :class="{
                                'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                    $page.url === '/dashboard',
                            }"
                            @click="sidebarOpen = false"
                        >
                            <svg
                                class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
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
                            v-if="page.props.auth.user.is_admin"
                            href="/admin/dashboard"
                            class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                            :class="{
                                'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                    $page.url === '/admin/dashboard',
                            }"
                            @click="sidebarOpen = false"
                        >
                            <svg
                                class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="group-hover:translate-x-1 transition-transform duration-200"
                                >Admin Dashboard</span
                            >
                        </Link>

                        <Link
                            href="/logout"
                            method="post"
                            class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                            @click="sidebarOpen = false"
                        >
                            <svg
                                class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
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
                    </template>

                    <!-- Show Register and Login if not authenticated -->
                    <template v-else>
                        <Link
                            href="/register"
                            class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                            :class="{
                                'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                    $page.url === '/register',
                            }"
                            @click="sidebarOpen = false"
                        >
                            <svg
                                class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"
                                />
                            </svg>
                            <span
                                class="group-hover:translate-x-1 transition-transform duration-200"
                                >Register</span
                            >
                        </Link>

                        <Link
                            href="/login"
                            class="group flex items-center px-4 py-3 text-sm font-medium text-gray-200 rounded-xl hover:text-white hover:bg-white/10 hover:backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary/50 focus:bg-white/10 active:bg-white/15 transition-all duration-200"
                            :class="{
                                'bg-white/15 text-white shadow-lg backdrop-blur-sm':
                                    $page.url === '/login',
                            }"
                            @click="sidebarOpen = false"
                        >
                            <svg
                                class="w-5 h-5 mr-3 group-hover:text-secondary transition-colors duration-200"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="group-hover:translate-x-1 transition-transform duration-200"
                                >Login</span
                            >
                        </Link>
                    </template>
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
                style="top: 80px"
            ></div>
        </Transition>

        <!-- Main content - full width -->
        <Transition
            name="page"
            mode="out-in"
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
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
                <div
                    class="mx-auto px-4 sm:px-6 lg:px-8 pt-8 transition-all duration-700 ease-out"
                    :class="props.expandedLayout ? 'max-w-none' : 'max-w-7xl'"
                    :style="props.expandedLayout ? 'max-width: 120rem;' : ''"
                >
                    <slot />
                </div>
            </main>
        </Transition>
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
    background: var(--color-secondary, #00ffc3);
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

/* Custom color classes that use CSS variables */
.text-primary {
    color: var(--color-primary);
}

.text-secondary {
    color: var(--color-secondary);
}

.text-accent {
    color: var(--color-accent);
}

.bg-primary {
    background-color: var(--color-primary);
}

.bg-secondary {
    background-color: var(--color-secondary);
}

.bg-accent {
    background-color: var(--color-accent);
}

.border-primary {
    border-color: var(--color-primary);
}

.ring-primary {
    --tw-ring-color: var(--color-primary);
}
</style>
