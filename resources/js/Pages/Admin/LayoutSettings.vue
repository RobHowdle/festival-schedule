<template>
    <Head title="Layout Settings" />

    <MainLayout :expanded-layout="showLivePreview">
        <template #header>
            <div class="flex items-center">
                <Link
                    href="/admin/dashboard"
                    class="text-gray-400 hover:text-white transition-colors duration-200 mr-4"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        ></path>
                    </svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-white">
                    Layout Settings
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div
                class="px-4 sm:px-6 lg:px-8 transition-all duration-700 ease-out"
                :class="
                    showLivePreview ? 'max-w-none mx-auto' : 'max-w-7xl mx-auto'
                "
                :style="showLivePreview ? 'max-width: 120rem;' : ''"
            >
                <div
                    :class="[
                        'grid gap-8 transition-all duration-700 ease-out',
                        showLivePreview
                            ? 'grid-cols-1 xl:grid-cols-3 2xl:grid-cols-3'
                            : 'grid-cols-1 lg:grid-cols-2',
                    ]"
                >
                    <!-- Background Settings -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 transform transition-all duration-700 ease-out"
                    >
                        <h3
                            class="text-xl font-bold text-white mb-6 flex items-center"
                        >
                            <svg
                                class="w-6 h-6 mr-3 text-indigo-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            Background Settings
                        </h3>

                        <form
                            @submit.prevent="updateBackground"
                            class="space-y-6"
                        >
                            <!-- Background Type Selection -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-200 mb-3"
                                >
                                    Background Type
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center">
                                        <input
                                            v-model="
                                                backgroundForm.background_type
                                            "
                                            type="radio"
                                            value="color"
                                            class="form-radio h-4 w-4 text-indigo-400 bg-white/10 border-white/30"
                                            @change="updatePreview"
                                        />
                                        <span class="ml-2 text-white"
                                            >Solid Color</span
                                        >
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            v-model="
                                                backgroundForm.background_type
                                            "
                                            type="radio"
                                            value="image"
                                            class="form-radio h-4 w-4 text-indigo-400 bg-white/10 border-white/30"
                                            @change="updatePreview"
                                        />
                                        <span class="ml-2 text-white"
                                            >Background Image</span
                                        >
                                    </label>
                                </div>
                            </div>

                            <!-- Color Picker -->
                            <Transition
                                enter-active-class="duration-300 ease-out"
                                enter-from-class="opacity-0 -translate-y-2"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="duration-200 ease-in"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 -translate-y-2"
                            >
                                <div
                                    v-if="
                                        backgroundForm.background_type ===
                                        'color'
                                    "
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-200 mb-2"
                                    >
                                        Background Colour
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <input
                                            v-model="
                                                backgroundForm.background_color
                                            "
                                            type="color"
                                            class="w-12 h-12 rounded-lg border border-white/30 bg-white/10"
                                            @input="updatePreview"
                                        />
                                        <input
                                            v-model="
                                                backgroundForm.background_color
                                            "
                                            type="text"
                                            class="flex-1 px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-400/50 focus:border-indigo-400"
                                            placeholder="#1F2937"
                                            @input="updatePreview"
                                        />
                                    </div>
                                </div>
                            </Transition>

                            <!-- Image Upload -->
                            <Transition
                                enter-active-class="duration-300 ease-out"
                                enter-from-class="opacity-0 -translate-y-2"
                                enter-to-class="opacity-100 translate-y-0"
                                leave-active-class="duration-200 ease-in"
                                leave-from-class="opacity-100 translate-y-0"
                                leave-to-class="opacity-0 -translate-y-2"
                            >
                                <div
                                    v-if="
                                        backgroundForm.background_type ===
                                        'image'
                                    "
                                    class="space-y-4"
                                >
                                    <label
                                        class="block text-sm font-medium text-gray-200 mb-2"
                                    >
                                        Background Image
                                    </label>

                                    <!-- Current Image Preview -->
                                    <div
                                        v-if="
                                            props.currentBackground &&
                                            !backgroundForm.remove_background
                                        "
                                        class="relative"
                                    >
                                        <img
                                            :src="`/storage/${props.currentBackground}`"
                                            alt="Current background"
                                            class="w-full h-32 object-cover rounded-lg border border-white/30"
                                        />
                                        <button
                                            type="button"
                                            @click="removeBackground"
                                            class="absolute top-2 right-2 p-1 bg-red-500/80 hover:bg-red-500 text-white rounded-full transition-colors"
                                        >
                                            <svg
                                                class="w-4 h-4"
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

                                    <!-- File Input -->
                                    <div
                                        class="border-2 border-dashed border-white/30 rounded-lg p-6 text-center hover:border-white/50 transition-colors"
                                    >
                                        <input
                                            ref="fileInput"
                                            type="file"
                                            accept="image/*"
                                            @change="handleFileUpload"
                                            class="hidden"
                                        />
                                        <button
                                            type="button"
                                            @click="$refs.fileInput.click()"
                                            class="text-white hover:text-indigo-400 transition-colors"
                                        >
                                            <svg
                                                class="w-12 h-12 mx-auto mb-3 text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                />
                                            </svg>
                                            <p class="text-sm">
                                                Click to upload background image
                                            </p>
                                            <p
                                                class="text-xs text-gray-400 mt-1"
                                            >
                                                PNG, JPG up to 5MB
                                            </p>
                                        </button>
                                    </div>

                                    <!-- Image Preview -->
                                    <div v-if="imagePreview" class="relative">
                                        <img
                                            :src="imagePreview"
                                            alt="Preview"
                                            class="w-full h-32 object-cover rounded-lg border border-white/30"
                                        />
                                        <button
                                            type="button"
                                            @click="clearImagePreview"
                                            class="absolute top-2 right-2 p-1 bg-red-500/80 hover:bg-red-500 text-white rounded-full transition-colors"
                                        >
                                            <svg
                                                class="w-4 h-4"
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
                                </div>
                            </Transition>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="backgroundForm.processing"
                                class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-medium rounded-xl transition-all duration-200 disabled:opacity-50"
                            >
                                <span v-if="!backgroundForm.processing"
                                    >Update Background</span
                                >
                                <span
                                    v-else
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
                                    Updating...
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Color Palette Settings -->
                    <div
                        class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 transform transition-all duration-700 ease-out"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <h3
                                class="text-xl font-bold text-white flex items-center"
                            >
                                <div
                                    class="w-8 h-8 rounded-lg bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm mr-3"
                                >
                                    🎨
                                </div>
                                Colour Palettes
                            </h3>
                            <button
                                @click="toggleLivePreview"
                                :class="[
                                    'px-4 py-2 text-white text-sm font-medium rounded-lg transition-colors flex items-center',
                                    showLivePreview
                                        ? 'bg-red-600 hover:bg-red-700'
                                        : 'bg-green-600 hover:bg-green-700',
                                ]"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path
                                        fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                {{
                                    showLivePreview
                                        ? "Hide Preview"
                                        : "Show Preview"
                                }}
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Palette Name -->
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-300 mb-2"
                                >
                                    Palette Name
                                </label>
                                <input
                                    v-model="colorForm.name"
                                    type="text"
                                    placeholder="Enter palette name"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                />
                            </div>

                            <!-- Compact Color Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Primary -->
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <label
                                            class="text-sm font-medium text-gray-300"
                                            >Primary</label
                                        >
                                        <span class="text-xs text-gray-400"
                                            >Buttons</span
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            type="button"
                                            @click="
                                                $refs.primaryColorPicker?.click()
                                            "
                                            class="w-8 h-8 rounded border border-gray-600 cursor-pointer hover:scale-110 transition-transform"
                                            :style="{
                                                backgroundColor:
                                                    previewColors.primary,
                                            }"
                                        ></button>
                                        <input
                                            ref="primaryColorPicker"
                                            v-model="previewColors.primary"
                                            type="color"
                                            class="sr-only"
                                        />
                                        <input
                                            v-model="previewColors.primary"
                                            type="text"
                                            class="flex-1 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500"
                                        />
                                    </div>
                                </div>

                                <!-- Secondary -->
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <label
                                            class="text-sm font-medium text-gray-300"
                                            >Secondary</label
                                        >
                                        <span class="text-xs text-gray-400"
                                            >Links</span
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            type="button"
                                            @click="
                                                $refs.secondaryColorPicker?.click()
                                            "
                                            class="w-8 h-8 rounded border border-gray-600 cursor-pointer hover:scale-110 transition-transform"
                                            :style="{
                                                backgroundColor:
                                                    previewColors.secondary,
                                            }"
                                        ></button>
                                        <input
                                            ref="secondaryColorPicker"
                                            v-model="previewColors.secondary"
                                            type="color"
                                            class="sr-only"
                                        />
                                        <input
                                            v-model="previewColors.secondary"
                                            type="text"
                                            class="flex-1 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500"
                                        />
                                    </div>
                                </div>

                                <!-- Accent -->
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <label
                                            class="text-sm font-medium text-gray-300"
                                            >Accent</label
                                        >
                                        <span class="text-xs text-gray-400"
                                            >Highlights</span
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            type="button"
                                            @click="
                                                $refs.accentColorPicker?.click()
                                            "
                                            class="w-8 h-8 rounded border border-gray-600 cursor-pointer hover:scale-110 transition-transform"
                                            :style="{
                                                backgroundColor:
                                                    previewColors.accent,
                                            }"
                                        ></button>
                                        <input
                                            ref="accentColorPicker"
                                            v-model="previewColors.accent"
                                            type="color"
                                            class="sr-only"
                                        />
                                        <input
                                            v-model="previewColors.accent"
                                            type="text"
                                            class="flex-1 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500"
                                        />
                                    </div>
                                </div>

                                <!-- Background -->
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <label
                                            class="text-sm font-medium text-gray-300"
                                            >Background</label
                                        >
                                        <span class="text-xs text-gray-400"
                                            >Cards</span
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            type="button"
                                            @click="
                                                $refs.backgroundColorPicker?.click()
                                            "
                                            class="w-8 h-8 rounded border border-gray-600 cursor-pointer hover:scale-110 transition-transform"
                                            :style="{
                                                backgroundColor:
                                                    previewColors.background,
                                            }"
                                        ></button>
                                        <input
                                            ref="backgroundColorPicker"
                                            v-model="previewColors.background"
                                            type="color"
                                            class="sr-only"
                                        />
                                        <input
                                            v-model="previewColors.background"
                                            type="text"
                                            class="flex-1 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500"
                                        />
                                    </div>
                                </div>

                                <!-- Text -->
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <label
                                            class="text-sm font-medium text-gray-300"
                                            >Text</label
                                        >
                                        <span class="text-xs text-gray-400"
                                            >Headings</span
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            type="button"
                                            @click="
                                                $refs.textColorPicker?.click()
                                            "
                                            class="w-8 h-8 rounded border border-gray-600 flex items-center justify-center text-xs font-bold cursor-pointer hover:scale-110 transition-transform"
                                            :style="{
                                                backgroundColor:
                                                    previewColors.background,
                                                color: previewColors.text,
                                                borderColor:
                                                    previewColors.text + '40',
                                            }"
                                        >
                                            Aa
                                        </button>
                                        <input
                                            ref="textColorPicker"
                                            v-model="previewColors.text"
                                            type="color"
                                            class="sr-only"
                                        />
                                        <input
                                            v-model="previewColors.text"
                                            type="text"
                                            class="flex-1 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500"
                                        />
                                    </div>
                                </div>

                                <!-- Secondary Text -->
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <label
                                            class="text-sm font-medium text-gray-300"
                                            >Text Secondary</label
                                        >
                                        <span class="text-xs text-gray-400"
                                            >Subtle</span
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            type="button"
                                            @click="
                                                $refs.textSecondaryColorPicker?.click()
                                            "
                                            class="w-8 h-8 rounded border border-gray-600 flex items-center justify-center text-xs cursor-pointer hover:scale-110 transition-transform"
                                            :style="{
                                                backgroundColor:
                                                    previewColors.background,
                                                color: previewColors.textSecondary,
                                                borderColor:
                                                    previewColors.textSecondary +
                                                    '40',
                                            }"
                                        >
                                            abc
                                        </button>
                                        <input
                                            ref="textSecondaryColorPicker"
                                            v-model="
                                                previewColors.textSecondary
                                            "
                                            type="color"
                                            class="sr-only"
                                        />
                                        <input
                                            v-model="
                                                previewColors.textSecondary
                                            "
                                            type="text"
                                            class="flex-1 px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs font-mono focus:outline-none focus:ring-1 focus:ring-cyan-500"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Simplified Action Buttons -->
                            <div class="flex gap-3">
                                <button
                                    @click="resetPreview"
                                    class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors"
                                >
                                    Reset
                                </button>
                                <button
                                    @click="saveColors"
                                    class="flex-1 py-2 px-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-medium rounded-lg transition-colors"
                                >
                                    Save Colours
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview Panel -->
                    <Transition
                        enter-active-class="duration-700 ease-out"
                        enter-from-class="opacity-0 translate-x-8 scale-95"
                        enter-to-class="opacity-100 translate-x-0 scale-100"
                        leave-active-class="duration-500 ease-in"
                        leave-from-class="opacity-100 translate-x-0 scale-100"
                        leave-to-class="opacity-0 translate-x-8 scale-95"
                    >
                        <div
                            v-if="showLivePreview"
                            class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 transform transition-all duration-700 ease-out"
                        >
                            <h3
                                class="text-xl font-bold text-white mb-6 flex items-center"
                            >
                                <svg
                                    class="w-6 h-6 mr-3 text-green-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path
                                        fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Live Preview
                            </h3>

                            <!-- Preview Container -->
                            <div
                                class="relative w-full rounded-xl overflow-hidden border border-white/20 shadow-2xl"
                                style="height: 600px"
                                :style="previewBackgroundStyles"
                            >
                                <!-- Mini navbar -->
                                <div
                                    class="absolute top-0 left-0 right-0 h-12 bg-black/20 backdrop-blur-lg border-b border-white/10 flex items-center justify-between px-4 z-10"
                                >
                                    <div class="text-white font-bold text-sm">
                                        DOWNLOAD
                                    </div>
                                    <div
                                        class="flex items-center gap-4 text-white text-sm"
                                    >
                                        <div class="text-center">
                                            <div class="font-bold">353</div>
                                            <div class="text-xs text-gray-400">
                                                DAYS
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-bold">17</div>
                                            <div class="text-xs text-gray-400">
                                                HRS
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-bold">42</div>
                                            <div class="text-xs text-gray-400">
                                                MINS
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Welcome Content -->
                                <div
                                    class="absolute inset-0 top-12 overflow-y-auto"
                                    style="
                                        transform: scale(0.85);
                                        transform-origin: top left;
                                        width: 117.6%;
                                        height: 117.6%;
                                    "
                                >
                                    <WelcomeContent
                                        :preview-mode="true"
                                        :preview-colors="previewColors"
                                    />
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { Head, Link, useForm, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import WelcomeContent from "@/components/WelcomeContent.vue";
import { ref, computed, reactive } from "vue";

const props = defineProps({
    layoutSettings: Object,
    colorSettings: Object,
    colorPalettes: Object,
    currentBackground: String,
    currentBackgroundType: String,
    currentBackgroundColor: String,
});

const page = usePage();

// Live preview state
const showLivePreview = ref(false);
const imagePreview = ref(null);

// Background form
const backgroundForm = useForm({
    background_type: props.currentBackgroundType || "color",
    background_color: props.currentBackgroundColor || "#1F2937",
    background_image: null,
    remove_background: false,
});

// Color form
const colorForm = useForm({
    name: "",
});

// Preview colors that update in real-time
const previewColors = reactive({
    primary: "#8B5CF6",
    secondary: "#06B6D4",
    accent: "#F59E0B",
    background: "#1F2937",
    text: "#FFFFFF",
    textSecondary: "#9CA3AF",
});

// Initialize preview colors from current system settings
const initializePreviewColors = () => {
    const systemSettings = page.props.systemSettings || {};
    previewColors.primary = systemSettings.color_primary || "#8B5CF6";
    previewColors.secondary = systemSettings.color_secondary || "#06B6D4";
    previewColors.accent = systemSettings.color_accent || "#F59E0B";
    previewColors.background = systemSettings.color_background || "#1F2937";
    previewColors.text = systemSettings.color_text || "#FFFFFF";
    previewColors.textSecondary =
        systemSettings.color_textSecondary || "#9CA3AF";
};

// Initialize preview colors
initializePreviewColors();

const resetPreview = () => {
    initializePreviewColors();
};

const previewBackgroundStyles = computed(() => {
    if (
        backgroundForm.background_type === "image" &&
        (props.currentBackground || imagePreview.value)
    ) {
        const imageUrl =
            imagePreview.value || `/storage/${props.currentBackground}`;
        return {
            background: `linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4)), url('${imageUrl}') center/cover no-repeat`,
        };
    } else {
        const bgColor = backgroundForm.background_color || "#1F2937";
        return {
            background: `linear-gradient(135deg, ${bgColor}e6, ${bgColor}cc)`,
        };
    }
});

const updatePreview = () => {
    if (showLivePreview.value) {
        Object.assign(previewColors, colorForm.colors);
    }
};

// Single save function
const saveColors = () => {
    const formData = {
        palette_name:
            colorForm.name ||
            "Custom Palette " + new Date().toLocaleTimeString(),
        colors: {
            primary: previewColors.primary,
            secondary: previewColors.secondary,
            accent: previewColors.accent,
            background: previewColors.background,
            text: previewColors.text,
            textSecondary: previewColors.textSecondary,
        },
        apply_immediately: true, // Always apply when saving
    };

    router.post("/admin/layout/colors", formData, {
        preserveScroll: true,
        onSuccess: () => {
            colorForm.reset("name");
            // Colors are now saved, so the preview colors are the new "current" colors
        },
    });
};

const toggleLivePreview = () => {
    showLivePreview.value = !showLivePreview.value;
    if (showLivePreview.value) {
        resetPreview();
    }
};

// Update the saveColorPalette function
const saveColorPalette = () => {
    // Transform the flat colorForm to match controller expectations
    const formData = {
        palette_name: colorForm.name,
        colors: {
            primary: colorForm.primary,
            secondary: colorForm.secondary,
            accent: colorForm.accent,
            background: colorForm.background,
            text: colorForm.text,
            textSecondary: colorForm.textSecondary,
        },
        apply_immediately: false, // Just save, don't apply
    };

    router.post("/admin/layout/colors", formData, {
        preserveScroll: true,
        onSuccess: () => {
            colorForm.reset("name");
        },
    });
};

// Update the applyColors function
const applyColors = () => {
    // Transform the flat colorForm to match controller expectations
    const formData = {
        palette_name: colorForm.name,
        colors: {
            primary: colorForm.primary,
            secondary: colorForm.secondary,
            accent: colorForm.accent,
            background: colorForm.background,
            text: colorForm.text,
            textSecondary: colorForm.textSecondary,
        },
        apply_immediately: true, // Save and apply
    };

    router.post("/admin/layout/colors", formData, {
        preserveScroll: true,
        onSuccess: () => {
            colorForm.reset("name");
            // Update current system colors for preview
            resetPreview();
        },
    });
};

// Update applyPreviewColors function
const applyPreviewColors = () => {
    // Copy preview colors to color form and apply immediately
    const formData = {
        palette_name: "Preview Applied " + new Date().toLocaleTimeString(),
        colors: {
            primary: previewColors.primary,
            secondary: previewColors.secondary,
            accent: previewColors.accent,
            background: previewColors.background,
            text: previewColors.text,
            textSecondary: previewColors.textSecondary,
        },
        apply_immediately: true,
    };

    router.post("/admin/layout/colors", formData, {
        preserveScroll: true,
        onSuccess: () => {
            resetPreview();
        },
    });
};

// File upload handlers
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        backgroundForm.background_image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
            updatePreview();
        };
        reader.readAsDataURL(file);
    }
};

const clearImagePreview = () => {
    imagePreview.value = null;
    backgroundForm.background_image = null;
    updatePreview();
};

const removeBackground = () => {
    backgroundForm.remove_background = true;
    updatePreview();
};

// Form submission handlers
const updateBackground = () => {
    backgroundForm.post("/admin/layout/background", {
        preserveScroll: true,
        onSuccess: () => {
            imagePreview.value = null;
            if (showLivePreview.value) {
                updatePreview();
            }
        },
    });
};

const loadPalette = (palette) => {
    colorForm.palette_name = palette.name;
    Object.keys(colorForm.colors).forEach((key) => {
        if (palette[key]) {
            colorForm.colors[key] = palette[key];
        }
    });
    updatePreview();
};

const deletePalette = (paletteName) => {
    if (confirm("Are you sure you want to delete this color palette?")) {
        useForm({ palette_name: paletteName }).delete("/admin/layout/palette", {
            preserveScroll: true,
        });
    }
};
</script>

<style scoped>
/* Custom scrollbar */
.space-y-2::-webkit-scrollbar {
    width: 4px;
}

.space-y-2::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
}

.space-y-2::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 2px;
}

.space-y-2::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>
