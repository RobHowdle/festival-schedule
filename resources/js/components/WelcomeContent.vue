<template>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="overflow-hidden shadow-xl rounded-lg border border-white/20"
                :style="{ backgroundColor: getColor('background') + '20' }"
            >
                <div
                    class="p-6 lg:p-8 border-b border-white/10"
                    :style="{ backgroundColor: getColor('background') + '10' }"
                >
                    <h1
                        class="text-2xl font-medium mb-6"
                        :style="{ color: getColor('text') }"
                    >
                        Welcome to Your Next Festival
                    </h1>
                    <p
                        class="leading-relaxed"
                        :style="{ color: getColor('textSecondary') }"
                    >
                        View lineups, artists, vendors and more.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <Weather
                :preview-mode="previewMode"
                :preview-colors="previewColors"
            />
        </div>
    </div>
</template>

<script setup>
import Weather from "@/components/Weather.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    previewMode: {
        type: Boolean,
        default: false,
    },
    previewColors: {
        type: Object,
        default: () => ({}),
    },
});

const page = usePage();

// Get colors from preview or system settings
const getColor = (colorKey) => {
    if (props.previewMode && props.previewColors[colorKey]) {
        return props.previewColors[colorKey];
    }

    // Use systemSettings instead of storedColors
    const systemSettings = page.props.systemSettings || {};
    const colorMap = {
        primary: systemSettings.color_primary || "#8B5CF6",
        secondary: systemSettings.color_secondary || "#06B6D4",
        accent: systemSettings.color_accent || "#F59E0B",
        background: systemSettings.color_background || "#1F2937",
        text: systemSettings.color_text || "#FFFFFF",
        textSecondary: systemSettings.color_textSecondary || "#9CA3AF",
    };

    return colorMap[colorKey];
};
</script>
