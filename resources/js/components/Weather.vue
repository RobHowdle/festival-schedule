<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// Add preview props
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

    // Use systemSettings from shared props
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

// Color computed properties for template
const textColor = computed(() => getColor("text"));
const textSecondaryColor = computed(() => getColor("textSecondary"));
const accentColor = computed(() => getColor("accent"));
const primaryColor = computed(() => getColor("primary"));
const backgroundColor = computed(() => getColor("background"));

const isExpanded = ref(false);
const weatherData = ref(null);
const loading = ref(true);
const error = ref(null);
const showHourlyPopup = ref(false);
const selectedDay = ref(null);
const hourlyData = ref([]);
const loadingHourly = ref(false);

// Generate festival dates dynamically from environment variables
const generateFestivalDates = () => {
    const startDateEnv = import.meta.env.VITE_FESTIVAL_START_DATE;
    const endDateEnv = import.meta.env.VITE_FESTIVAL_END_DATE;

    if (!startDateEnv || !endDateEnv) {
        console.warn("Festival dates not found in environment variables.");
    }

    const startDate = new Date(startDateEnv);
    const endDate = new Date(endDateEnv);

    if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
        console.warn(
            "Invalid festival dates in environment variables, using defaults"
        );
        return [
            { date: "2025-06-20", day: "Friday" },
            { date: "2025-06-21", day: "Saturday" },
            { date: "2025-06-22", day: "Sunday" },
        ];
    }

    const dates = [];
    const currentDate = new Date(startDate);

    // Loop through each day from start to end (inclusive)
    while (currentDate <= endDate) {
        const dateString = currentDate.toISOString().split("T")[0];
        const dayName = currentDate.toLocaleDateString("en-US", {
            weekday: "long",
        });

        dates.push({
            date: dateString,
            day: dayName,
        });

        // Move to next day
        currentDate.setDate(currentDate.getDate() + 1);
    }

    return dates;
};

// Generate festival dates on component initialization
const festivalDates = generateFestivalDates();

const toggleExpanded = () => {
    isExpanded.value = !isExpanded.value;
};

const closeHourlyPopup = () => {
    showHourlyPopup.value = false;
    selectedDay.value = null;
    hourlyData.value = [];
};

const fetchHourlyWeather = async (day) => {
    if (!day.available) return;

    try {
        loadingHourly.value = true;
        selectedDay.value = day;
        showHourlyPopup.value = true;

        const lat = 52.8306;
        const lon = -1.3764;
        const apiKey = import.meta.env.VITE_OPENWEATHER_API_KEY;

        if (!apiKey) {
            throw new Error("OpenWeather API key not configured");
        }

        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`
        );

        if (!response.ok) {
            throw new Error("Failed to fetch hourly weather data");
        }

        const data = await response.json();

        // Filter forecasts for the selected day
        const dayForecasts = data.list.filter((forecast) => {
            const forecastDate = new Date(forecast.dt * 1000)
                .toISOString()
                .split("T")[0];
            return forecastDate === day.date;
        });

        // Process hourly data
        hourlyData.value = dayForecasts.map((forecast) => {
            const time = new Date(forecast.dt * 1000);
            return {
                time: time.toLocaleTimeString("en-GB", {
                    hour: "2-digit",
                    minute: "2-digit",
                    hour12: false,
                }),
                temp: Math.round(forecast.main.temp),
                feels_like: Math.round(forecast.main.feels_like),
                icon: getWeatherIconType(forecast.weather[0].icon),
                description: forecast.weather[0].description
                    .split(" ")
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(" "),
                humidity: forecast.main.humidity,
                wind_speed: Math.round(forecast.wind.speed * 3.6), // Convert m/s to km/h
                rain: forecast.rain ? Math.round(forecast.rain["3h"] || 0) : 0,
                pop: Math.round(forecast.pop * 100), // Probability of precipitation
            };
        });

        loadingHourly.value = false;
    } catch (err) {
        console.error("Hourly weather fetch error:", err);
        loadingHourly.value = false;
    }
};

// Fetch weather data from OpenWeather API
const fetchWeatherData = async () => {
    try {
        loading.value = true;
        error.value = null;

        // Download Festival location: Donington Park, UK
        const lat = 52.8306;
        const lon = -1.3764;
        const apiKey = import.meta.env.VITE_OPENWEATHER_API_KEY;

        if (!apiKey) {
            throw new Error("OpenWeather API key not configured");
        }

        // Fetch 5-day forecast
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`
        );

        if (!response.ok) {
            throw new Error("Failed to fetch weather data");
        }

        const data = await response.json();

        // Process forecast data for festival dates
        const processedWeather = festivalDates.map((festivalDay) => {
            // Find forecasts for this date
            const dayForecasts = data.list.filter((forecast) => {
                const forecastDate = new Date(forecast.dt * 1000)
                    .toISOString()
                    .split("T")[0];
                return forecastDate === festivalDay.date;
            });

            if (dayForecasts.length === 0) {
                return {
                    ...festivalDay,
                    icon: "unknown",
                    forecast: "No forecast",
                    temp: "--",
                    rain: 0,
                    available: false,
                };
            }

            // Get midday forecast or closest available
            const middayForecast =
                dayForecasts.find((f) => {
                    const hour = new Date(f.dt * 1000).getHours();
                    return hour >= 12 && hour <= 15;
                }) || dayForecasts[Math.floor(dayForecasts.length / 2)];

            // Calculate average temp and max rain chance for the day
            const avgTemp = Math.round(
                dayForecasts.reduce((sum, f) => sum + f.main.temp, 0) /
                    dayForecasts.length
            );

            const maxRain = Math.max(
                ...dayForecasts.map((f) =>
                    (f.rain?.["3h"] || 0) > 0 ? f.pop * 100 : 0
                )
            );

            return {
                ...festivalDay,
                icon: getWeatherIconType(middayForecast.weather[0].icon),
                forecast: middayForecast.weather[0].description
                    .split(" ")
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(" "),
                temp: avgTemp,
                rain: Math.round(maxRain),
                available: true,
            };
        });

        weatherData.value = processedWeather;
        loading.value = false;
    } catch (err) {
        console.error("Weather fetch error:", err);
        error.value = "Unable to fetch weather data";
        loading.value = false;
    }
};

// Convert OpenWeather icon codes to our icon types
const getWeatherIconType = (iconCode) => {
    const iconMap = {
        "01d": "sunny", // clear sky day
        "01n": "clear", // clear sky night
        "02d": "cloudy", // few clouds day
        "02n": "cloudy", // few clouds night
        "03d": "cloudy", // scattered clouds
        "03n": "cloudy",
        "04d": "cloudy", // broken clouds
        "04n": "cloudy",
        "09d": "rainy", // shower rain
        "09n": "rainy",
        "10d": "rainy", // rain day
        "10n": "rainy", // rain night
        "11d": "stormy", // thunderstorm
        "11n": "stormy",
        "13d": "snowy", // snow
        "13n": "snowy",
        "50d": "foggy", // mist
        "50n": "foggy",
    };

    return iconMap[iconCode] || "cloudy";
};

const getWeatherIcon = (iconType) => {
    const icons = {
        sunny: "☀️",
        clear: "🌙",
        cloudy: "⛅",
        rainy: "🌧️",
        stormy: "⛈️",
        snowy: "❄️",
        foggy: "🌫️",
        unknown: "🌤️",
    };
    return icons[iconType] || "🌤️";
};

const getAverageTemp = () => {
    if (!weatherData.value) return "--";
    const availableDays = weatherData.value.filter(
        (day) => day.available && day.temp !== "--"
    );
    if (availableDays.length === 0) return "--";

    const total = availableDays.reduce((sum, day) => sum + day.temp, 0);
    return Math.round(total / availableDays.length);
};

const hasAnyForecast = () => {
    return weatherData.value && weatherData.value.some((day) => day.available);
};

const getCurrentForecast = () => {
    if (!weatherData.value) return "Loading...";
    const firstAvailable = weatherData.value.find((day) => day.available);
    return firstAvailable ? firstAvailable.forecast : "No forecast";
};

onMounted(() => {
    fetchWeatherData();
});
</script>

<template>
    <div
        class="backdrop-blur-lg rounded-xl border border-white/20 overflow-hidden"
        :style="{ backgroundColor: backgroundColor + '10' }"
    >
        <!-- Collapsed Header -->
        <button
            @click="toggleExpanded"
            class="w-full px-4 py-4 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-cyan-400/50"
            :class="[isExpanded ? '' : 'hover:bg-white/5']"
            :style="{
                backgroundColor: isExpanded ? backgroundColor + '05' : '',
            }"
        >
            <!-- Title -->
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-semibold text-lg" :style="{ color: textColor }">
                    Weather
                </h3>
                <div class="flex items-center space-x-3">
                    <div class="font-semibold" :style="{ color: textColor }">
                        {{ loading ? "--" : getAverageTemp() }}°C
                    </div>
                    <svg
                        :class="[
                            'w-5 h-5 transition-transform duration-200',
                            isExpanded ? 'rotate-180' : '',
                        ]"
                        :style="{ color: textSecondaryColor }"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
            </div>

            <!-- Forecast Preview -->
            <div class="flex items-center space-x-2 text-left">
                <div class="text-xl">
                    {{
                        loading ? "🌤️" : getWeatherIcon(weatherData?.[0]?.icon)
                    }}
                </div>
                <div class="text-sm" :style="{ color: textSecondaryColor }">
                    {{ loading ? "Loading..." : getCurrentForecast() }}
                </div>
            </div>
        </button>

        <!-- Expanded Content -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-96 opacity-100"
            leave-active-class="transition-all duration-300 ease-in"
            leave-from-class="max-h-96 opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <div v-if="isExpanded" class="border-t border-white/20">
                <!-- Loading State -->
                <div v-if="loading" class="p-4 text-center">
                    <div class="animate-pulse">
                        <div
                            class="text-sm"
                            :style="{ color: textSecondaryColor }"
                        >
                            Loading forecast...
                        </div>
                    </div>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="p-4 text-center">
                    <div class="text-sm" :style="{ color: '#FCA5A5' }">
                        {{ error }}
                        <br />
                        <button
                            @click="fetchWeatherData"
                            class="mt-2 underline text-xs transition-colors"
                            :style="{ color: accentColor }"
                        >
                            Try again
                        </button>
                    </div>
                </div>

                <!-- No Forecast State -->
                <div v-else-if="!hasAnyForecast()" class="p-4 text-center">
                    <div class="text-sm" :style="{ color: textSecondaryColor }">
                        Weather forecast not available yet.
                        <br />
                        Check back closer to the festival!
                    </div>
                </div>

                <!-- Weather Data -->
                <div v-else class="p-4 space-y-3">
                    <div
                        v-for="day in weatherData"
                        :key="day.date"
                        :class="[
                            'flex items-center justify-between p-3 rounded-lg transition-all duration-200',
                            day.available ? 'cursor-pointer' : 'opacity-60',
                        ]"
                        :style="{
                            backgroundColor: day.available
                                ? backgroundColor + '05'
                                : backgroundColor + '02',
                        }"
                        @click="day.available ? fetchHourlyWeather(day) : null"
                        @mouseenter="
                            day.available
                                ? ($event.target.style.backgroundColor =
                                      backgroundColor + '10')
                                : null
                        "
                        @mouseleave="
                            day.available
                                ? ($event.target.style.backgroundColor =
                                      backgroundColor + '05')
                                : null
                        "
                    >
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl">
                                {{ getWeatherIcon(day.icon) }}
                            </div>
                            <div>
                                <div
                                    class="font-medium text-sm"
                                    :style="{ color: textColor }"
                                >
                                    {{ day.day }}
                                </div>
                                <div
                                    class="text-xs"
                                    :style="{ color: textSecondaryColor }"
                                >
                                    {{
                                        day.available
                                            ? day.forecast
                                            : "No forecast"
                                    }}
                                </div>
                            </div>
                        </div>

                        <div v-if="day.available" class="text-right">
                            <div
                                class="font-semibold"
                                :style="{ color: textColor }"
                            >
                                {{ day.temp }}°C
                            </div>
                            <div
                                class="text-xs"
                                :style="{ color: accentColor }"
                            >
                                {{ day.rain }}% rain
                            </div>
                        </div>

                        <div v-else class="text-right">
                            <div
                                class="text-sm"
                                :style="{ color: textSecondaryColor }"
                            >
                                --°C
                            </div>
                        </div>
                    </div>

                    <!-- Click hint -->
                    <div class="text-center mt-2">
                        <div
                            class="text-xs"
                            :style="{ color: textSecondaryColor }"
                        >
                            Click on a day for hourly forecast
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>

    <!-- Hourly Weather Popup -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="showHourlyPopup"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            @click="closeHourlyPopup"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <!-- Popup Content -->
            <div
                class="relative backdrop-blur-xl border border-white/20 rounded-2xl max-w-md w-full max-h-[80vh] overflow-hidden"
                :style="{ backgroundColor: backgroundColor + '80' }"
                @click.stop
            >
                <!-- Header -->
                <div class="p-4 border-b border-white/20">
                    <div class="flex items-center justify-between">
                        <h3
                            class="font-semibold text-lg"
                            :style="{ color: textColor }"
                        >
                            {{ selectedDay?.day }} - Hourly Forecast
                        </h3>
                        <button
                            @click="closeHourlyPopup"
                            class="transition-colors p-1"
                            :style="{ color: textSecondaryColor }"
                            @mouseenter="$event.target.style.color = textColor"
                            @mouseleave="
                                $event.target.style.color = textSecondaryColor
                            "
                        >
                            <svg
                                class="w-6 h-6"
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

                <!-- Loading State -->
                <div v-if="loadingHourly" class="p-8 text-center">
                    <div
                        class="animate-pulse"
                        :style="{ color: textSecondaryColor }"
                    >
                        Loading hourly forecast...
                    </div>
                </div>

                <!-- Hourly Data -->
                <div v-else class="p-4 max-h-96 overflow-y-auto">
                    <div class="space-y-3">
                        <div
                            v-for="hour in hourlyData"
                            :key="hour.time"
                            class="rounded-lg p-3 transition-all duration-200"
                            :style="{ backgroundColor: backgroundColor + '05' }"
                            @mouseenter="
                                $event.target.style.backgroundColor =
                                    backgroundColor + '10'
                            "
                            @mouseleave="
                                $event.target.style.backgroundColor =
                                    backgroundColor + '05'
                            "
                        >
                            <div class="flex items-center justify-between">
                                <!-- Time & Weather -->
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="font-medium text-sm min-w-[3rem]"
                                        :style="{ color: accentColor }"
                                    >
                                        {{ hour.time }}
                                    </div>
                                    <div class="text-lg">
                                        {{ getWeatherIcon(hour.icon) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-medium text-sm"
                                            :style="{ color: textColor }"
                                        >
                                            {{ hour.temp }}°C
                                        </div>
                                        <div
                                            class="text-xs"
                                            :style="{
                                                color: textSecondaryColor,
                                            }"
                                        >
                                            Feels {{ hour.feels_like }}°C
                                        </div>
                                    </div>
                                </div>

                                <!-- Weather details -->
                                <div class="text-right">
                                    <div
                                        class="text-xs"
                                        :style="{ color: textSecondaryColor }"
                                    >
                                        {{ hour.description }}
                                    </div>
                                    <div
                                        class="text-xs"
                                        :style="{ color: accentColor }"
                                    >
                                        {{ hour.pop }}% chance rain
                                    </div>
                                </div>
                            </div>

                            <!-- Additional info -->
                            <div
                                class="mt-2 flex items-center justify-between text-xs"
                                :style="{ color: textSecondaryColor }"
                            >
                                <span>Humidity: {{ hour.humidity }}%</span>
                                <span>Wind: {{ hour.wind_speed }} km/h</span>
                                <span v-if="hour.rain > 0"
                                    >Rain: {{ hour.rain }}mm</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.max-h-0 {
    max-height: 0;
}
.max-h-96 {
    max-height: 24rem;
}
</style>
