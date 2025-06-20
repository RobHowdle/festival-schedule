<script setup>
import { ref, onMounted, nextTick } from "vue";
import { router } from "@inertiajs/vue3";
import Countdown from "@/components/Countdown.vue";
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import "../../css/maintenance-style.css";
import "../../css/maintenance-print.css";

const props = defineProps({
    status: {
        type: String,
        default: "",
    },
});

// Reactive data
const showLoginForm = ref(false);
const loginInertiaForm = useForm({
    email: "",
    password: "",
});

const loginError = ref('');

// Methods
const toggleLoginForm = () => {
    console.log(
        "toggleLoginForm called, current showLoginForm:",
        showLoginForm.value
    );
    showLoginForm.value = !showLoginForm.value;
    console.log("toggleLoginForm new showLoginForm:", showLoginForm.value);
    if (!showLoginForm.value) {
        loginInertiaForm.reset();
    }
};

const backToSplash = () => {
    toggleLoginForm();
};

const submitLogin = () => {
    loginError.value = '';

    loginInertiaForm.post(route('login'), {
        onFinish: () => {
            loginInertiaForm.reset('password'); 
        },
        onError: (errors) => {
            if (loginInertiaForm.errors.email || loginInertiaForm.errors.password) {
                // InputError components handle these specific errors, no need to set loginError
            } else if (errors.message) {
                // If the backend sends a generic error message (e.g., 'Unauthorized')
                loginError.value = errors.message;
            } else {
                // Fallback for unexpected errors
                loginError.value = "Login failed. Please check your credentials or try again.";
            }
            console.error("Login errors:", errors);
        },
        onSuccess: () => {
            // Inertia handles redirection on success. No explicit action needed here.
        }
    });

};

// Initialize dog scroll functionality
const initializeDogScroll = () => {
    let dogVisible = false;
    const dogTrigger = document.getElementById("dl-dog-trigger");

    if (!dogTrigger) {
        console.warn("Dog trigger element not found");
        return;
    }

    const showDog = () => {
        if (!dogVisible) {
            dogTrigger.classList.add("visible");
            dogVisible = true;
        }
    };

    const hideDog = () => {
        if (dogVisible) {
            dogTrigger.classList.remove("visible");
            dogVisible = false;
        }
    };

    const handleScroll = () => {
        const scrollTop =
            window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        // Show dog when user scrolls near the bottom (within 100px)
        if (scrollTop + windowHeight >= documentHeight - 100) {
            showDog();
        } else {
            hideDog();
        }
    };

    // Add scroll event listener
    window.addEventListener("scroll", handleScroll, { passive: true });

    // Initial check in case user is already at bottom
    handleScroll();

    console.log("Dog scroll functionality initialized");
};

// Browser detection function
function sniff() {
    (this.version = "1.3"),
        this.browserType,
        this.browserVersion,
        this.mobile,
        (this.browserVersionExtended = null);
    for (
        var e,
            i = !1,
            s =
                navigator.userAgent.match(/Android/i) ||
                navigator.userAgent.match(/BlackBerry/i) ||
                navigator.userAgent.match(/iPhone|iPad|iPod/i) ||
                navigator.userAgent.match(/Opera Mini/i) ||
                navigator.userAgent.match(/IEMobile/i) ||
                null,
            o = navigator.appName,
            n = navigator.userAgent,
            t = n.match(
                /(opera|chrome|safari|firefox|msie|trident|edge)\/?\s*(\.?\d+(\.\d+)*)/i
            ),
            r = n.match(
                /(opera|chrome|safari|firefox|msie|trident|edge)\/?\s*(\.?\d+(\.\d+)*)/gi
            ),
            a = 0;
        a < r.length;
        a++
    )
        if (r[a].toLowerCase().indexOf("edge") >= 0) {
            var d = r[a].split("index.html"),
                m = d[0].toLowerCase(),
                f = d[1];
            (i = !0), console.log("so qui");
        }
    if (!i) {
        t && null != (e = n.match(/version\/([\.\d]+)/i)) && (t[2] = e[1]),
            (t = t ? [t[1], t[2]] : [o, navigator.appVersion, "-?"]);
        var m = t[0].toLowerCase(),
            f = t[1];
        if ("trident" == t[0].toLowerCase()) {
            (m = "msie"),
                console.log(n),
                (f = n.match(/(rv)\:?(\.?\d+(\.\d+)*)/i));
            for (var l = 0; l < f.length; l++)
                if (!isNaN(parseInt(f[l]))) {
                    f = f[l];
                    break;
                }
        }
    }
    (this.mobile = s),
        (this.browserType = m),
        (this.browserVersion = parseInt(f)),
        (this.browserVersionExtended = f);
}

// Initialize browser detection
document.documentElement.className = document.documentElement.className.replace(
    "no-js",
    "js"
);

var sniffInstance = new sniff();
"undefined" != typeof document.documentElement.classList
    ? (document.documentElement.classList.add(sniffInstance.browserType),
      document.documentElement.classList.add(
          "v" + sniffInstance.browserVersion
      ),
      null != sniffInstance.mobile &&
          document.documentElement.classList.add(
              sniffInstance.mobile.toString().toLowerCase()
          ))
    : ((document.documentElement.className +=
          " " +
          sniffInstance.browserType +
          " v" +
          sniffInstance.browserVersion),
      null != sniffInstance.mobile &&
          (document.documentElement.className +=
              " " + sniffInstance.mobile.toString().toLowerCase()));

onMounted(async () => {
    await nextTick();

    try {
        // Try to load external scripts
        await import("../../js/jquery.min.js");
        await import("../../js/maintenance-script.js");
        console.log("External maintenance scripts loaded successfully");
    } catch (error) {
        console.warn("Error loading external maintenance scripts:", error);
        console.log("Falling back to built-in functionality");
    }

    // Always initialize our own dog scroll functionality as fallback
    setTimeout(() => {
        initializeDogScroll();
    }, 500); // Small delay to ensure DOM is ready
});
</script>

<style scoped>
/* Countdown overlay positioning */
.countdown-overlay {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    z-index: 100;
    width: 100%;
    text-align: center;
    pointer-events: none; /* Allow clicks to pass through to elements below */
}

.countdown-overlay :deep(.countdown-timer) {
    pointer-events: auto; /* Re-enable pointer events for the actual countdown */
}

/* Ensure countdown styling works with the maintenance page theme */
.countdown-overlay :deep(.timer-number) {
    color: #fff;
    font-size: 2.5rem;
    font-weight: bold;
}

.countdown-overlay :deep(.timer-label) {
    color: #00ffe6;
}

/* Add a subtle background to make countdown more readable */
.countdown-overlay :deep(.timer-unit) {
    margin: 0 0.25rem;
}

.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .countdown-overlay {
        top: 10px;
    }

    .countdown-overlay :deep(.timer-number) {
        font-size: 2rem;
    }

    .countdown-overlay :deep(.timer-unit) {
        padding: 0.3rem;
        margin: 0 0.15rem;
    }
}
</style>

<template>
    <div>
        <div class="hero-panel" id="page-content">
            <div class="hero-detail"></div>

            <div class="countdown-overlay">
                <Countdown />
            </div>

            <div class="title-wrap">
                <!-- Main splash screen -->
                <div
                    class="hero-pane splash"
                    id="maintenance-splash"
                    :class="{ hidden: showLoginForm }"
                >
                    <h1>
                        <img
                            class="animation-slideUp"
                            src="/maintenance_assets/images/dl-logo.svg"
                            alt="Download Festival"
                        />
                    </h1>
                    <div class="hp-panel tag-line">
                        The UK's Premier Rock Festival
                    </div>
                    <div class="hp-panel details animation-fadeIn">
                        <span>See you next year...</span>
                        <span class="splash-link-wrap">
                            <a
                                class="splash-link"
                                href="https://discord.gg/ZEsUUVq2MT"
                                target="_blank"
                                >Discord</a
                            >
                        </span>
                    </div>
                </div>

                <!-- Admin Login Form -->
        <div
            class="hero-pane splash"
            id="maintenance-login-form"
            :class="{ hidden: !showLoginForm }"
        >
            <h1>
                <img
                    class="animation-slideUp"
                    src="/maintenance_assets/images/dl-logo.svg"
                    alt="Download Festival"
                />
            </h1>
            <div class="hp-panel tag-line">Admin Login</div>
            <div class="hp-panel details animation-fadeIn">
                <form id="admin-login-form" @submit.prevent="submitLogin">
                    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                        {{ status }}
                    </div>

                    <div class="input-container mt-4">
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full input-field"
                            v-model="loginInertiaForm.email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Email Address"
                        />
                        <InputError class="mt-2" :message="loginInertiaForm.errors.email" />
                    </div>

                    <div class="input-container mt-4">
                        <TextInput
                            id="password"
                            type="password"
                            class="block w-full input-field"
                            v-model="loginInertiaForm.password"
                            required
                            autocomplete="current-password"
                            placeholder="Password"
                        />
                        <InputError class="mt-2" :message="loginInertiaForm.errors.password" />
                    </div>

                    <div class="mt-4 flex items-center justify-center">
                        <button
                            type="submit"
                            class="button-submit"
                            :class="{ 'opacity-25': loginInertiaForm.processing }"
                            :disabled="loginInertiaForm.processing"
                        >
                            Log in
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="backToSplash"
                        class="button-submit mt-4"
                    >
                        Back
                    </button>
                    <div
                        class="login-error-message"
                        :class="{ hidden: !loginError }"
                    >
                        {{ loginError }}
                    </div>
                </form>
            </div>
        </div>
            </div>
            <div class="hero-vid">
                <video
                    class="hero-vid"
                    id="hero-vid"
                    autoplay
                    muted
                    playsinline
                    loop
                >
                    <source
                        src="/maintenance_assets/videos/DL-HomeLoop-01.mp4"
                        type="video/mp4"
                    />
                </video>
            </div>
            <button
                id="hero-pause-play"
                class="playing"
            >
                <span class="icon play"></span>
                <span class="icon pause"></span>
            </button>
        </div>
        <footer>
            <div
                class="visually-hidden"
                role="region"
                id="hidden-aria-alerts-polite"
                aria-live="polite"
            ></div>
            <div
                class="visually-hidden"
                role="region"
                id="hidden-aria-alerts-assertive"
                aria-live="assertive"
            ></div>
            <!-- Download dog trigger for admin login -->
            <a
                href="#"
                class="dl-dog-detail"
                id="dl-dog-trigger"
                aria-label="Toggle Admin Login"
                @click.prevent="toggleLoginForm"
            ></a>
            <div class="say-hello"></div>
        </footer>
        <div class="footer-detailing-back">
            <div class="fade"></div>
        </div>
        <div class="footer-detailing-front"></div>
    </div>
</template>