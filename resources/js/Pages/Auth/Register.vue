<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div
            class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-md w-full space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Join the Festival
                    </h2>
                    <p class="text-gray-300">
                        Create your account to get started
                    </p>
                </div>

                <!-- Registration Form -->
                <div
                    class="bg-white/10 backdrop-blur-xl backdrop-saturate-150 border border-white/20 rounded-2xl shadow-2xl p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="name"
                                value="Full Name"
                                class="text-white font-medium"
                            />
                            <TextInput
                                id="name"
                                type="text"
                                class="block w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Enter your full name"
                            />
                            <InputError
                                class="mt-2 text-red-400"
                                :message="form.errors.name"
                            />
                        </div>

                        <!-- Email Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="email"
                                value="Email Address"
                                class="text-white font-medium"
                            />
                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="Enter your email address"
                            />
                            <InputError
                                class="mt-2 text-red-400"
                                :message="form.errors.email"
                            />
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="password"
                                value="Password"
                                class="text-white font-medium"
                            />
                            <TextInput
                                id="password"
                                type="password"
                                class="block w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="Create a secure password"
                            />
                            <InputError
                                class="mt-2 text-red-400"
                                :message="form.errors.password"
                            />
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="space-y-2">
                            <InputLabel
                                for="password_confirmation"
                                value="Confirm Password"
                                class="text-white font-medium"
                            />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="block w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:border-cyan-400 transition-all duration-200"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm your password"
                            />
                            <InputError
                                class="mt-2 text-red-400"
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <PrimaryButton
                                class="w-full py-3 px-4 bg-gradient-to-r justify-center from-gray-800 to-gray-900 hover:from-cyan-500 hover:to-cyan-400 border border-cyan-login400/50 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:ring-offset-2 focus:ring-offset-transparent"
                                :class="{
                                    'opacity-50 cursor-not-allowed':
                                        form.processing,
                                }"
                                :disabled="form.processing"
                            >
                                <span
                                    v-if="!form.processing"
                                    class="flex items-center justify-center"
                                >
                                    Create Account
                                </span>
                                <span
                                    v-else
                                    class="flex items-center justify-center"
                                >
                                    <svg
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
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
                                    Creating Account...
                                </span>
                            </PrimaryButton>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center pt-4 border-t border-white/20">
                            <p class="text-gray-300">
                                Already have an account?
                                <Link
                                    :href="route('login')"
                                    class="font-medium text-cyan-400 hover:text-cyan-300 transition-colors duration-200 underline decoration-cyan-400/50 hover:decoration-cyan-300"
                                >
                                    Sign in here
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Additional Info -->
                <div class="text-center">
                    <p class="text-gray-400 text-sm">
                        By creating an account, you agree to our
                        <a
                            href="#"
                            class="text-cyan-400 hover:text-cyan-300 underline"
                            >Terms of Service</a
                        >
                        and
                        <a
                            href="#"
                            class="text-cyan-400 hover:text-cyan-300 underline"
                            >Privacy Policy</a
                        >
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
