<template>
    <div>
        <div class="hero-panel" id="page-content">
            <div class="hero-detail"></div>
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
                        <form
                            id="admin-login-form"
                            @submit.prevent="handleLogin"
                        >
                            <div class="form-group">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="Email Address"
                                    required
                                    autocomplete="email"
                                    v-model="loginForm.email"
                                />
                            </div>
                            <div class="form-group">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Password"
                                    required
                                    autocomplete="current-password"
                                    v-model="loginForm.password"
                                />
                            </div>
                            <button type="submit" class="button-submit">
                                Login
                            </button>
                            <button
                                type="button"
                                @click="backToSplash"
                                class="button-submit"
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

<script setup>
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

import "../../css/maintenance-style.css";
import "../../css/maintenance-print.css";

// Reactive data
const showLoginForm = ref(false);
const loginForm = ref({
    email: "",
    password: "",
});
const loginError = ref("");

// Methods
const toggleLoginForm = () => {
    showLoginForm.value = !showLoginForm.value;
    loginError.value = "";
    // Clear form when toggling
    if (!showLoginForm.value) {
        loginForm.value.email = "";
        loginForm.value.password = "";
    }
};

const backToSplash = () => {
    showLoginForm.value = false;
    loginError.value = "";
    // Clear form data
    loginForm.value.email = "";
    loginForm.value.password = "";
};

const handleLogin = async () => {
    try {
        loginError.value = "";

        // Example login check (replace with your actual authentication)
        const response = await fetch("/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
            },
            body: JSON.stringify({
                email: loginForm.value.email,
                password: loginForm.value.password,
            }),
        });

        if (response.ok) {
            // Redirect to dashboard or wherever you want
            window.location.href = "/dashboard";
        } else {
            const errorData = await response.json();
            loginError.value = errorData.message || "Invalid credentials";
        }
    } catch (error) {
        console.error("Login error:", error);
        loginError.value = "An error occurred. Please try again.";
    }
};

document.documentElement.className = document.documentElement.className.replace(
    "no-js",
    "js"
);

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
    // Load external JavaScript files from resources
    try {
        // Import jQuery
        await import("../../js/jquery.min.js");

        // Import main scripts
        await import("../../js/maintenance-script.js");

        console.log("All maintenance scripts loaded successfully");
    } catch (error) {
        console.warn("Error loading maintenance scripts:", error);
    }
});
</script>
