<script setup>
import Checkbox from "@/components/Checkbox.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <MainLayout>
        <Head title="Log in" />

        <div
            class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
        >
            <div class="max-w-md w-full space-y-8">
                <!-- Header -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">
                        Welcome Back
                    </h2>
                    <p class="text-gray-300">
                        Sign in to your festival account
                    </p>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="bg-green-500/20 backdrop-blur-sm border border-green-400/30 rounded-xl p-4"
                >
                    <p class="text-green-300 text-sm font-medium text-center">
                        {{ status }}
                    </p>
                </div>

                <!-- Login Form -->
                <div
                    class="bg-white/10 backdrop-blur-xl backdrop-saturate-150 border border-white/20 rounded-2xl shadow-2xl p-8"
                >
                    <form @submit.prevent="submit" class="space-y-6">
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
                                autofocus
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
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            />
                            <InputError
                                class="mt-2 text-red-400"
                                :message="form.errors.password"
                            />
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <Checkbox
                                    name="remember"
                                    v-model:checked="form.remember"
                                    class="rounded border-white/30 bg-white/10 text-cyan-400 focus:ring-cyan-400/50 focus:ring-offset-0"
                                />
                                <span class="ml-2 text-sm text-gray-300"
                                    >Remember me</span
                                >
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-cyan-400 hover:text-cyan-300 underline decoration-cyan-400/50 hover:decoration-cyan-300 transition-colors duration-200"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <PrimaryButton
                                class="w-full py-3 px-4 bg-gradient-to-r justify-center from-gray-800 to-gray-900 hover:from-cyan-500 hover:to-cyan-400 border border-cyan-400/50 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-cyan-400/50 focus:ring-offset-2 focus:ring-offset-transparent"
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
                                    Sign In
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
                                    Signing In...
                                </span>
                            </PrimaryButton>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center pt-4 border-t border-white/20">
                            <p class="text-gray-300">
                                Don't have an account?
                                <Link
                                    :href="route('register')"
                                    class="font-medium text-cyan-400 hover:text-cyan-300 transition-colors duration-200 underline decoration-cyan-400/50 hover:decoration-cyan-300"
                                >
                                    Create one here
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Additional Info -->
                <div class="text-center">
                    <p class="text-gray-400 text-sm">
                        Need help?
                        <a
                            href="#"
                            class="text-cyan-400 hover:text-cyan-300 underline"
                            >Contact Support</a
                        >
                    </p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
