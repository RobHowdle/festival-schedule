<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import axios from 'axios';

const props = defineProps({
    users_count: Number,
    artists_count: Number,
    vendors_count: Number,
    stages_count: Number,
});

// Reactive counts that we'll update dynamically
const counts = ref({
    users: props.users_count,
    artists: props.artists_count,
    vendors: props.vendors_count,
    stages: props.stages_count,
});

const currentAdminView = ref('dashboard');

// Modal state
const showModal = ref(false);
const modalType = ref("");

// Form data
const artistForm = useForm({
    name: "",
    description: "",
    genre: "",
    image_url: "",
    website: "",
    social_links: "",
});

const stageForm = useForm({
    name: "",
    location: "",
    w3w_link: "",
    capacity: "",
    type: "",
});

const vendorForm = useForm({
    name: "",
    type: "",
    categories: "",
    description: "",
    email: "",
    phone: "",
    social_links: "",
    logo: "",
    menu: "",
    tags: "",
    dietary_restrictions: "",
    restrictions: "",
});

// Modal functions
const openModal = (type) => {
    modalType.value = type;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    modalType.value = "";
    // Reset forms
    artistForm.reset();
    stageForm.reset();
    vendorForm.reset();
};

// Submit functions with dynamic updates
const submitArtist = () => {
    artistForm.post(route("admin.artists.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            counts.value.artists++;
            closeModal();
        },
        onError: (errors) => {
            console.log("Validation errors:", errors);
        },
    });
};

const submitStage = () => {
    stageForm.post(route("admin.stages.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            counts.value.stages++;
            closeModal();
        },
        onError: (errors) => {
            console.log("Validation errors:", errors);
        },
    });
};

const submitVendor = () => {
    vendorForm.post(route("admin.vendors.store"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
            counts.value.vendors++;
            closeModal();
        },
        onError: (errors) => {
            console.log("Validation errors:", errors);
        },
    });
};

const usersData = ref([]);
const searchTerm = ref('');
const loadingUsers = ref(false);

const fetchUsers = async () => {
    loadingUsers.value = true;
    try {
        const response = await axios.get(route('admin.users.api', { search: searchTerm.value })); // Use route() helper
        usersData.value = response.data;
    } catch (error) {
        console.error("Failed to fetch users:", error);
        // Handle error display to the user if needed
    } finally {
        loadingUsers.value = false;
    }
};

// Computed property for reactive search
const filteredUsers = computed(() => {
    if (!searchTerm.value) {
        return usersData.value;
    }
    const lowerSearch = searchTerm.value.toLowerCase();
    return usersData.value.filter(user => 
        user.name.toLowerCase().includes(lowerSearch) ||
        user.email.toLowerCase().includes(lowerSearch)
    );
});

const showUserManagement = () => {
    currentAdminView.value = 'userList';
    fetchUsers();
};

const backToDashboard = () => {
    currentAdminView.value = 'dashboard';
};

const selectedUser = ref(null); // Stores the user object being edited

// Form for editing user data. Initialize with empty values, then populate from selectedUser.
const userForm = useForm({
    id: null,
    name: '',
    email: '',
    is_admin: false,
    is_dev: false,
    password: '', // For password change if desired
    password_confirmation: '', // For password change confirmation
});

// Watch selectedUser and populate userForm when it changes
watch(selectedUser, (newUser) => {
    if (newUser) {
        userForm.id = newUser.id;
        userForm.name = newUser.name;
        userForm.email = newUser.email;
        userForm.is_admin = newUser.is_admin;
        userForm.is_dev = newUser.is_dev;
        userForm.password = ''; // Clear password fields when a new user is selected
        userForm.password_confirmation = '';
        userForm.clearErrors(); // Clear any previous errors
    } else {
        userForm.reset(); // Reset form if no user is selected
    }
}, { immediate: true }); // Run immediately if selectedUser has an initial value

// Function to handle clicking on a user in the list
const editUser = (user) => {
    selectedUser.value = user; // Set the user to be edited
    currentAdminView.value = 'userEdit'; // Change to the edit view
};

// Function to handle "Back" or "Cancel" from user edit form
const backToUserList = () => {
    currentAdminView.value = 'userList'; // Go back to the user list
    selectedUser.value = null; // Clear selected user
    userForm.reset(); // Reset the form
    fetchUsers();
};

// Function to submit user update
const submitUserUpdate = () => {
    const routeName = 'admin.users.update'; 

    userForm.patch(route(routeName, { user: selectedUser.value.id }), { 
        // Preserve scroll behavior
        preserveScroll: true, 
        
        onSuccess: (page) => {
            // Trigger the transition back to the user list AND re-fetch the updated list
            backToUserList(); 

            // Check for and display a flash message if provided by Laravel
            if (page.props.flash && page.props.flash.message) {
                console.log("Success message:", page.props.flash.message);
                // You might want to display this message using a transient notification system in your UI.
                // Example: store.dispatch('showNotification', { message: page.props.flash.message, type: 'success' });
            }
        },
        onError: (errors) => {
            console.error("User update errors:", errors);
            // userForm.errors will automatically be populated by Inertia
        },
    });
};
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
                <!-- Stats Cards with dynamic counts -->
                <transition
                mode = "out-in"
                enter-active-class="transition-opacity duration-300 ease-out"
                enter-from-class="opacity 0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
                >
                <div v-if="currentAdminView === 'dashboard'" key="dashboard-view">
                    <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
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
                                    Users
                                </h3>
                                <Transition
                                    enter-active-class="duration-300 ease-out"
                                    enter-from-class="opacity-0 scale-90"
                                    enter-to-class="opacity-100 scale-100"
                                    leave-active-class="duration-200 ease-in"
                                    leave-from-class="opacity-100 scale-100"
                                    leave-to-class="opacity-0 scale-90"
                                >
                                    <p
                                        :key="counts.users"
                                        class="text-3xl font-bold text-cyan-400"
                                    >
                                        {{ counts.users }}
                                    </p>
                                </Transition>
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
                                    Artists
                                </h3>
                                <Transition
                                    enter-active-class="duration-300 ease-out"
                                    enter-from-class="opacity-0 scale-90"
                                    enter-to-class="opacity-100 scale-100"
                                    leave-active-class="duration-200 ease-in"
                                    leave-from-class="opacity-100 scale-100"
                                    leave-to-class="opacity-0 scale-90"
                                >
                                    <p
                                        :key="counts.artists"
                                        class="text-3xl font-bold text-purple-400"
                                    >
                                        {{ counts.artists }}
                                    </p>
                                </Transition>
                            </div>
                        </div>
                    </div>

                    <!-- Vendors Stats Card -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                    >
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-500/20">
                                <svg
                                    class="w-8 h-8 text-orange-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                        clip-rule="evenodd"
                                    />
                                    <path
                                        d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">
                                    Vendors
                                </h3>
                                <Transition
                                    enter-active-class="duration-300 ease-out"
                                    enter-from-class="opacity-0 scale-90"
                                    enter-to-class="opacity-100 scale-100"
                                    leave-active-class="duration-200 ease-in"
                                    leave-from-class="opacity-100 scale-100"
                                    leave-to-class="opacity-0 scale-90"
                                >
                                    <p
                                        :key="counts.vendors"
                                        class="text-3xl font-bold text-orange-400"
                                    >
                                        {{ counts.vendors || 0 }}
                                    </p>
                                </Transition>
                            </div>
                        </div>
                    </div>

                    <!-- Stages Stats Card -->
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
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">
                                    Stages
                                </h3>
                                <Transition
                                    enter-active-class="duration-300 ease-out"
                                    enter-from-class="opacity-0 scale-90"
                                    enter-to-class="opacity-100 scale-100"
                                    leave-active-class="duration-200 ease-in"
                                    leave-from-class="opacity-100 scale-100"
                                    leave-to-class="opacity-0 scale-90"
                                >
                                    <p
                                        :key="counts.stages"
                                        class="text-3xl font-bold text-green-400"
                                    >
                                        {{ counts.stages || 0 }}
                                    </p>
                                </Transition>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Sections -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Data Management -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                    >
                        <h3
                            class="text-xl font-bold text-white mb-6 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 mr-3 text-cyan-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Data Management
                        </h3>
                        <div class="space-y-4">
                            <!-- Users Management -->
                            <button
    @click="showUserManagement"
    class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200 w-full text-left"
>
    <div class="flex items-center">
        <div class="p-2 rounded-lg bg-cyan-500/20 group-hover:bg-cyan-500/30">
            <!-- SVG icon for Users -->
            <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
            </svg>
        </div>
        <div class="ml-3">
            <h4 class="text-white font-medium">Manage Users</h4>
            <p class="text-gray-400 text-sm">View, edit, and manage user accounts</p>
        </div>
    </div>
    <!-- Arrow SVG -->
    <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
    </svg>
</button>

                            <!-- Artists Management -->
                            <Link
                                href="/admin/artists"
                                class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="p-2 rounded-lg bg-purple-500/20 group-hover:bg-purple-500/30"
                                    >
                                        <svg
                                            class="w-5 h-5 text-purple-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-white font-medium">
                                            Manage Artists
                                        </h4>
                                        <p class="text-gray-400 text-sm">
                                            Add, edit, and manage festival
                                            artists
                                        </p>
                                    </div>
                                </div>
                                <svg
                                    class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>

                            <!-- Vendors Management -->
                            <Link
                                href="/admin/vendors"
                                class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="p-2 rounded-lg bg-orange-500/20 group-hover:bg-orange-500/30"
                                    >
                                        <svg
                                            class="w-5 h-5 text-orange-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-white font-medium">
                                            Manage Vendors
                                        </h4>
                                        <p class="text-gray-400 text-sm">
                                            Control vendor listings and
                                            locations
                                        </p>
                                    </div>
                                </div>
                                <svg
                                    class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>

                            <!-- Stages Management -->
                            <Link
                                href="/admin/stages"
                                class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="p-2 rounded-lg bg-green-500/20 group-hover:bg-green-500/30"
                                    >
                                        <svg
                                            class="w-5 h-5 text-green-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-white font-medium">
                                            Manage Stages
                                        </h4>
                                        <p class="text-gray-400 text-sm">
                                            Configure stages and performance
                                            areas
                                        </p>
                                    </div>
                                </div>
                                <svg
                                    class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                    >
                        <h3
                            class="text-xl font-bold text-white mb-6 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 mr-3 text-green-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            System Settings
                        </h3>
                        <div class="space-y-4">
                            <!-- Layout Settings -->
                            <Link
                                href="/admin/layout"
                                class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="p-2 rounded-lg bg-indigo-500/20 group-hover:bg-indigo-500/30"
                                    >
                                        <svg
                                            class="w-5 h-5 text-indigo-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-white font-medium">
                                            Layout Settings
                                        </h4>
                                        <p class="text-gray-400 text-sm">
                                            Configure app layout and appearance
                                        </p>
                                    </div>
                                </div>
                                <svg
                                    class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>

                            <!-- Festival Settings -->
                            <Link
                                href="/admin/festival-settings"
                                class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="p-2 rounded-lg bg-pink-500/20 group-hover:bg-pink-500/30"
                                    >
                                        <svg
                                            class="w-5 h-5 text-pink-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-white font-medium">
                                            Festival Settings
                                        </h4>
                                        <p class="text-gray-400 text-sm">
                                            Configure dates, times, and details
                                        </p>
                                    </div>
                                </div>
                                <svg
                                    class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>

                            <!-- Security Settings -->
                            <Link
                                href="/admin/security"
                                class="group flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-200"
                            >
                                <div class="flex items-center">
                                    <div
                                        class="p-2 rounded-lg bg-red-500/20 group-hover:bg-red-500/30"
                                    >
                                        <svg
                                            class="w-5 h-5 text-red-400"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-white font-medium">
                                            Security Settings
                                        </h4>
                                        <p class="text-gray-400 text-sm">
                                            Manage permissions and security
                                        </p>
                                    </div>
                                </div>
                                <svg
                                    class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div
                    class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-8"
                >
                    <h3
                        class="text-xl font-bold text-white mb-6 flex items-center"
                    >
                        <svg
                            class="w-6 h-6 mr-3 text-yellow-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button
                            @click="openModal('artist')"
                            class="flex items-center justify-center p-4 bg-purple-500/20 hover:bg-purple-500/30 rounded-xl transition-all duration-200 group"
                        >
                            <svg
                                class="w-6 h-6 mr-2 text-purple-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="text-white font-medium group-hover:text-purple-300"
                                >Add New Artist</span
                            >
                        </button>
                        <button
                            @click="openModal('stage')"
                            class="flex items-center justify-center p-4 bg-green-500/20 hover:bg-green-500/30 rounded-xl transition-all duration-200 group"
                        >
                            <svg
                                class="w-6 h-6 mr-2 text-green-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="text-white font-medium group-hover:text-green-300"
                                >Add New Stage</span
                            >
                        </button>
                        <button
                            @click="openModal('vendor')"
                            class="flex items-center justify-center p-4 bg-orange-500/20 hover:bg-orange-500/30 rounded-xl transition-all duration-200 group"
                        >
                            <svg
                                class="w-6 h-6 mr-2 text-orange-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                class="text-white font-medium group-hover:text-orange-300"
                                >Add New Vendor</span
                            >
                        </button>
                    </div>
                </div>
                </div>
                <div v-else-if="currentAdminView === 'userList'" key="user-list-view">
    <div
        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
    >
        <!-- Header with Back Button and Search Input -->
        <div class="flex items-center mb-6">
            <button
                @click="backToDashboard"
                class="text-gray-400 hover:text-white transition-colors duration-200 mr-4"
                aria-label="Back to Dashboard"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </button>
            <input
                v-model="searchTerm"
                @input="fetchUsers"
                type="text"
                placeholder="SEARCH"
                class="flex-1 px-4 py-2 bg-white/5 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
            />
        </div>

        <!-- User List Area -->
        <div class="user-list-area max-h-[calc(100vh-250px)] overflow-y-auto pr-2"> <!-- Added max-height and overflow-y-auto for scrollability -->
            <div v-if="loadingUsers" class="text-center py-8 text-gray-400">
                <svg class="animate-spin h-8 w-8 text-cyan-400 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading users...
            </div>

            <div v-else-if="filteredUsers.length === 0" class="text-center py-8 text-gray-400">
                No users found.
            </div>

            
            <div v-else class="space-y-4">
                <!-- User List Items -->
                <button
                    v-for="user in filteredUsers"
                    :key="user.id"
                    @click="editUser(user)"
                    class="flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 w-full text-left transition-colors duration-200"
                >
                    <div class="flex items-center">
                        <div class="p-2 rounded-full bg-cyan-500/20">
                            <svg class="w-5 h-5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-white font-medium">
                                {{ user.name }}
                            </h4>
                            <p class="text-gray-400 text-sm">
                                {{ user.email }}
                                <span v-if="user.is_admin" class="ml-2 px-2 py-0.5 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Admin</span>
                                <span v-if="user.is_dev" class="ml-2 px-2 py-0.5 bg-red-500/20 text-red-400 text-xs rounded-full">Developer</span>
                            </p>
                        </div>
                    </div>
                    <svg
                        class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- === USER EDIT FORM VIEW (NEW) === -->
                    <div v-else-if="currentAdminView === 'userEdit'" key="user-edit-view">
                        <div
                            class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6"
                        >
                            <!-- Header with Back Button and User's Name -->
                            <div class="flex items-center mb-6">
                                <button
                                    @click="backToUserList"
                                    class="text-gray-400 hover:text-white transition-colors duration-200 mr-4"
                                    aria-label="Back to User List"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                </button>
                                <h3 class="text-xl font-bold text-white capitalize">
                                    {{ selectedUser ? selectedUser.name : 'User' }}
                                </h3>
                            </div>

                            <!-- User Edit Form -->
                            <form @submit.prevent="submitUserUpdate" class="space-y-4">
                                <!-- Name Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-200 mb-2">Name</label>
                                    <input
                                        v-model="userForm.name"
                                        type="text"
                                        class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                        required
                                    />
                                    <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                                        <div v-if="userForm.errors.name" class="mt-1 text-sm text-red-400">{{ userForm.errors.name }}</div>
                                    </Transition>
                                </div>

                                <!-- Email Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-200 mb-2">Email</label>
                                    <input
                                        v-model="userForm.email"
                                        type="email"
                                        class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                        required
                                    />
                                    <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                                        <div v-if="userForm.errors.email" class="mt-1 text-sm text-red-400">{{ userForm.errors.email }}</div>
                                    </Transition>
                                </div>

                                <!-- Admin Status Checkbox -->
                                <div class="flex items-center pt-2">
                                    <input
                                        type="checkbox"
                                        id="is_admin"
                                        v-model="userForm.is_admin"
                                        class="form-checkbox h-5 w-5 text-cyan-400 bg-white/10 border-white/30 rounded focus:ring-cyan-400 focus:ring-offset-gray-900"
                                    />
                                    <label for="is_admin" class="ml-2 text-sm font-medium text-gray-200">Is Admin</label>
                                    <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                                        <div v-if="userForm.errors.is_admin" class="mt-1 text-sm text-red-400">{{ userForm.errors.is_admin }}</div>
                                    </Transition>
                                </div>

                                <!-- Dev Status Checkbox -->
                                <div class="flex items-center pt-2">
                                    <input
                                        type="checkbox"
                                        id="is_dev"
                                        v-model="userForm.is_dev"
                                        class="form-checkbox h-5 w-5 text-cyan-400 bg-white/10 border-white/30 rounded focus:ring-cyan-400 focus:ring-offset-gray-900"
                                    />
                                    <label for="is_dev" class="ml-2 text-sm font-medium text-gray-200">Is Developer</label>
                                    <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                                        <div v-if="userForm.errors.is_dev" class="mt-1 text-sm text-red-400">{{ userForm.errors.is_dev }}</div>
                                    </Transition>
                                </div>

                                <!-- Password Fields (Optional for password change) -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-200 mb-2">New Password (leave blank to keep current)</label>
                                    <input
                                        v-model="userForm.password"
                                        type="password"
                                        class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                        placeholder="Enter new password"
                                    />
                                    <Transition enter-active-class="duration-200 ease-out" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="duration-150 ease-in" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
                                        <div v-if="userForm.errors.password" class="mt-1 text-sm text-red-400">{{ userForm.errors.password }}</div>
                                    </Transition>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-200 mb-2">Confirm New Password</label>
                                    <input
                                        v-model="userForm.password_confirmation"
                                        type="password"
                                        class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                        placeholder="Confirm new password"
                                    />
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4 pt-4">
                                    <button
                                        type="button"
                                        @click="backToUserList"
                                        class="flex-1 py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium rounded-xl transition-all duration-200 hover:scale-105 active:scale-95"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="userForm.processing"
                                        class="flex-1 py-3 px-4 bg-gradient-to-r from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800 text-white font-medium rounded-xl transition-all duration-200 disabled:opacity-50 hover:scale-105 active:scale-95 disabled:hover:scale-100"
                                    >
                                        <Transition mode="out-in" enter-active-class="duration-150" leave-active-class="duration-150">
                                            <span v-if="!userForm.processing" key="save">
                                                Save
                                            </span>
                                            <span v-else key="saving" class="flex items-center justify-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Saving...
                                            </span>
                                        </Transition>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </transition>
            </div>
        </div>

        <!-- Dynamic Modal with Smooth Animations -->
        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showModal"
                class="fixed inset-0 z-50 overflow-y-auto"
                @click.self="closeModal"
            >
                <!-- Background overlay with fade animation -->
                <Transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="showModal"
                        class="fixed inset-0 transition-opacity bg-black/75 backdrop-blur-sm"
                        @click="closeModal"
                    ></div>
                </Transition>

                <div
                    class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
                >
                    <!-- Modal container with scale and fade animation -->
                    <Transition
                        enter-active-class="duration-300 ease-out"
                        enter-from-class="opacity-0 scale-95 translate-y-4"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="duration-200 ease-in"
                        leave-from-class="opacity-100 scale-100 translate-y-0"
                        leave-to-class="opacity-0 scale-95 translate-y-4"
                    >
                        <div
                            v-if="showModal"
                            class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white/10 backdrop-blur-xl border border-white/20 shadow-xl rounded-2xl sm:max-w-lg"
                        >
                            <!-- Modal header -->
                            <div class="flex items-center justify-between mb-6">
                                <h3
                                    class="text-xl font-bold text-white capitalize"
                                >
                                    Add New {{ modalType }}
                                </h3>
                                <button
                                    @click="closeModal"
                                    class="text-gray-400 hover:text-white transition-colors duration-200 hover:rotate-90 transform"
                                >
                                    <svg
                                        class="w-6 h-6 transition-transform duration-200"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- Artist Form -->
                            <Transition
                                mode="out-in"
                                enter-active-class="duration-200 ease-out"
                                enter-from-class="opacity-0 translate-x-2"
                                enter-to-class="opacity-100 translate-x-0"
                                leave-active-class="duration-150 ease-in"
                                leave-from-class="opacity-100 translate-x-0"
                                leave-to-class="opacity-0 -translate-x-2"
                            >
                                <!-- Artist Form -->
                                <form
                                    v-if="modalType === 'artist'"
                                    @submit.prevent="submitArtist"
                                    class="space-y-4"
                                    key="artist-form"
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Artist Name
                                        </label>
                                        <input
                                            v-model="artistForm.name"
                                            @input="generateSlug"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-purple-400 transition-all duration-200"
                                            placeholder="Enter artist name"
                                            required
                                        />
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="artistForm.errors.name"
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{ artistForm.errors.name }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Genre
                                        </label>
                                        <input
                                            v-model="artistForm.genre"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-purple-400 transition-all duration-200"
                                            placeholder="e.g., Rock, Metal, Pop"
                                        />
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="artistForm.errors.genre"
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{ artistForm.errors.genre }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Description
                                        </label>
                                        <textarea
                                            v-model="artistForm.description"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-purple-400 transition-all duration-200 resize-none"
                                            placeholder="Brief description of the artist"
                                        ></textarea>
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="
                                                    artistForm.errors
                                                        .description
                                                "
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{
                                                    artistForm.errors
                                                        .description
                                                }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Website
                                        </label>
                                        <input
                                            v-model="artistForm.website"
                                            type="url"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-purple-400 transition-all duration-200"
                                            placeholder="https://example.com"
                                        />
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="artistForm.errors.website"
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{ artistForm.errors.website }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Social Media Links
                                        </label>
                                        <textarea
                                            v-model="artistForm.social_links"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-purple-400 transition-all duration-200 resize-none"
                                            placeholder="https://facebook.com/artist, https://instagram.com/artist, https://twitter.com/artist"
                                        ></textarea>
                                        <p class="mt-1 text-xs text-gray-400">
                                            Separate multiple links with commas.
                                            Include full URLs (https://...)
                                        </p>
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="
                                                    artistForm.errors
                                                        .social_links
                                                "
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{
                                                    artistForm.errors
                                                        .social_links
                                                }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Artist Image URL
                                        </label>
                                        <input
                                            v-model="artistForm.image_url"
                                            type="url"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-400/50 focus:border-purple-400 transition-all duration-200"
                                            placeholder="https://example.com/artist-image.jpg"
                                        />
                                        <p class="mt-1 text-xs text-gray-400">
                                            Optional: Link to artist's profile
                                            image or promotional photo
                                        </p>
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="
                                                    artistForm.errors.image_url
                                                "
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{
                                                    artistForm.errors.image_url
                                                }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div class="flex gap-4 pt-4">
                                        <button
                                            type="button"
                                            @click="closeModal"
                                            class="flex-1 py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium rounded-xl transition-all duration-200 hover:scale-105 active:scale-95"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            :disabled="artistForm.processing"
                                            class="flex-1 py-3 px-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium rounded-xl transition-all duration-200 disabled:opacity-50 hover:scale-105 active:scale-95 disabled:hover:scale-100"
                                        >
                                            <Transition
                                                mode="out-in"
                                                enter-active-class="duration-150"
                                                leave-active-class="duration-150"
                                            >
                                                <span
                                                    v-if="
                                                        !artistForm.processing
                                                    "
                                                    key="create"
                                                >
                                                    Create Artist
                                                </span>
                                                <span
                                                    v-else
                                                    key="creating"
                                                    class="flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle
                                                            class="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            stroke-width="4"
                                                        ></circle>
                                                        <path
                                                            class="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                        ></path>
                                                    </svg>
                                                    Creating...
                                                </span>
                                            </Transition>
                                        </button>
                                    </div>
                                </form>

                                <!-- Stage Form -->
                                <form
                                    v-else-if="modalType === 'stage'"
                                    @submit.prevent="submitStage"
                                    class="space-y-4"
                                    key="stage-form"
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                            >Stage Name</label
                                        >
                                        <input
                                            v-model="stageForm.name"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400/50 focus:border-green-400 transition-all duration-200"
                                            placeholder="Enter stage name"
                                            required
                                        />
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="stageForm.errors.name"
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{ stageForm.errors.name }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                            >Location</label
                                        >
                                        <input
                                            v-model="stageForm.location"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400/50 focus:border-green-400 transition-all duration-200"
                                            placeholder="e.g., Main Field, Arena"
                                        />
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                            >Capacity</label
                                        >
                                        <input
                                            v-model="stageForm.capacity"
                                            type="number"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400/50 focus:border-green-400 transition-all duration-200"
                                            placeholder="e.g., 50000"
                                        />
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                            >Stage Type</label
                                        >
                                        <select
                                            v-model="stageForm.type"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-green-400/50 focus:border-green-400 transition-all duration-200"
                                        >
                                            <option value="">
                                                Select type
                                            </option>
                                            <option value="main">
                                                Open Air Stage
                                            </option>
                                            <option value="second">
                                                Tent Stage
                                            </option>
                                            <option value="acoustic">
                                                Indoor Stage
                                            </option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="flex gap-4 pt-4">
                                        <button
                                            type="button"
                                            @click="closeModal"
                                            class="flex-1 py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium rounded-xl transition-all duration-200 hover:scale-105 active:scale-95"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            :disabled="stageForm.processing"
                                            class="flex-1 py-3 px-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-xl transition-all duration-200 disabled:opacity-50 hover:scale-105 active:scale-95 disabled:hover:scale-100"
                                        >
                                            <Transition
                                                mode="out-in"
                                                enter-active-class="duration-150"
                                                leave-active-class="duration-150"
                                            >
                                                <span
                                                    v-if="!stageForm.processing"
                                                    key="create"
                                                    >Create Stage</span
                                                >
                                                <span
                                                    v-else
                                                    key="creating"
                                                    class="flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle
                                                            class="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            stroke-width="4"
                                                        ></circle>
                                                        <path
                                                            class="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                        ></path>
                                                    </svg>
                                                    Creating...
                                                </span>
                                            </Transition>
                                        </button>
                                    </div>
                                </form>

                                <!-- Vendor Form -->
                                <form
                                    v-else-if="modalType === 'vendor'"
                                    @submit.prevent="submitVendor"
                                    class="space-y-4"
                                    key="vendor-form"
                                >
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Vendor Name
                                        </label>
                                        <input
                                            v-model="vendorForm.name"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                            placeholder="Enter vendor name"
                                            required
                                        />
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="vendorForm.errors.name"
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{ vendorForm.errors.name }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Vendor Type
                                        </label>
                                        <select
                                            v-model="vendorForm.type"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                            required
                                        >
                                            <option value="">
                                                Select type
                                            </option>
                                            <option value="food">
                                                Food & Drink
                                            </option>
                                            <option value="merchandise">
                                                Merchandise
                                            </option>
                                            <option value="services">
                                                Services
                                            </option>
                                            <option value="other">Other</option>
                                        </select>
                                        <Transition
                                            enter-active-class="duration-200 ease-out"
                                            enter-from-class="opacity-0 translate-y-1"
                                            enter-to-class="opacity-100 translate-y-0"
                                            leave-active-class="duration-150 ease-in"
                                            leave-from-class="opacity-100 translate-y-0"
                                            leave-to-class="opacity-0 translate-y-1"
                                        >
                                            <div
                                                v-if="vendorForm.errors.type"
                                                class="mt-1 text-sm text-red-400"
                                            >
                                                {{ vendorForm.errors.type }}
                                            </div>
                                        </Transition>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Categories
                                        </label>
                                        <input
                                            v-model="vendorForm.categories"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                            placeholder="e.g., Pizza, Burgers, Vegetarian"
                                        />
                                        <p class="mt-1 text-xs text-gray-400">
                                            Separate multiple categories with
                                            commas
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Description
                                        </label>
                                        <textarea
                                            v-model="vendorForm.description"
                                            rows="3"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200 resize-none"
                                            placeholder="Brief description of the vendor"
                                        ></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-200 mb-2"
                                            >
                                                Email
                                            </label>
                                            <input
                                                v-model="vendorForm.email"
                                                type="email"
                                                class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                                placeholder="contact@vendor.com"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-200 mb-2"
                                            >
                                                Phone
                                            </label>
                                            <input
                                                v-model="vendorForm.phone"
                                                type="tel"
                                                class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                                placeholder="+1 (555) 123-4567"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Social Links
                                        </label>
                                        <textarea
                                            v-model="vendorForm.social_links"
                                            rows="2"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200 resize-none"
                                            placeholder="https://facebook.com/vendor, https://instagram.com/vendor"
                                        ></textarea>
                                        <p class="mt-1 text-xs text-gray-400">
                                            Separate multiple links with commas
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Logo URL
                                        </label>
                                        <input
                                            v-model="vendorForm.logo"
                                            type="url"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                            placeholder="https://example.com/logo.jpg"
                                        />
                                    </div>

                                    <!-- Food & Drink Specific Fields -->
                                    <Transition
                                        enter-active-class="duration-300 ease-out"
                                        enter-from-class="opacity-0 -translate-y-2"
                                        enter-to-class="opacity-100 translate-y-0"
                                        leave-active-class="duration-200 ease-in"
                                        leave-from-class="opacity-100 translate-y-0"
                                        leave-to-class="opacity-0 -translate-y-2"
                                    >
                                        <div
                                            v-if="vendorForm.type === 'food'"
                                            class="space-y-4 p-4 bg-orange-500/10 rounded-xl border border-orange-500/20"
                                        >
                                            <h4
                                                class="text-orange-400 font-medium text-sm"
                                            >
                                                Food & Drink Details
                                            </h4>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-200 mb-2"
                                                >
                                                    Menu
                                                </label>
                                                <textarea
                                                    v-model="vendorForm.menu"
                                                    rows="3"
                                                    class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200 resize-none"
                                                    placeholder="List menu items, prices, etc."
                                                ></textarea>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-200 mb-2"
                                                >
                                                    Dietary Restrictions
                                                </label>
                                                <input
                                                    v-model="
                                                        vendorForm.dietary_restrictions
                                                    "
                                                    type="text"
                                                    class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                                    placeholder="e.g., Vegetarian, Vegan, Gluten-free, Halal"
                                                />
                                                <p
                                                    class="mt-1 text-xs text-gray-400"
                                                >
                                                    Separate multiple
                                                    restrictions with commas
                                                </p>
                                            </div>
                                        </div>
                                    </Transition>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            Tags
                                        </label>
                                        <input
                                            v-model="vendorForm.tags"
                                            type="text"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200"
                                            placeholder="e.g., local, organic, family-friendly"
                                        />
                                        <p class="mt-1 text-xs text-gray-400">
                                            Separate multiple tags with commas
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-200 mb-2"
                                        >
                                            General Restrictions
                                        </label>
                                        <textarea
                                            v-model="vendorForm.restrictions"
                                            rows="2"
                                            class="w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400/50 focus:border-orange-400 transition-all duration-200 resize-none"
                                            placeholder="e.g., Cash only, No outside food, 21+ only"
                                        ></textarea>
                                    </div>

                                    <div class="flex gap-4 pt-4">
                                        <button
                                            type="button"
                                            @click="closeModal"
                                            class="flex-1 py-3 px-4 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-medium rounded-xl transition-all duration-200 hover:scale-105 active:scale-95"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            :disabled="vendorForm.processing"
                                            class="flex-1 py-3 px-4 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white font-medium rounded-xl transition-all duration-200 disabled:opacity-50 hover:scale-105 active:scale-95 disabled:hover:scale-100"
                                        >
                                            <Transition
                                                mode="out-in"
                                                enter-active-class="duration-150"
                                                leave-active-class="duration-150"
                                            >
                                                <span
                                                    v-if="
                                                        !vendorForm.processing
                                                    "
                                                    key="create"
                                                >
                                                    Create Vendor
                                                </span>
                                                <span
                                                    v-else
                                                    key="creating"
                                                    class="flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <circle
                                                            class="opacity-25"
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                            stroke="currentColor"
                                                            stroke-width="4"
                                                        ></circle>
                                                        <path
                                                            class="opacity-75"
                                                            fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                        ></path>
                                                    </svg>
                                                    Creating...
                                                </span>
                                            </Transition>
                                        </button>
                                    </div>
                                </form>
                            </Transition>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </MainLayout>
</template>
