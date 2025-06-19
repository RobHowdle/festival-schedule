import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import MaintenanceMode from "./components/MaintenanceMode.vue";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";
const MAINTENANCE_MODE = import.meta.env.VITE_MAINTENANCE_MODE === "true";

// If maintenance mode is enabled, render only the maintenance component
if (MAINTENANCE_MODE) {
    const app = createApp(MaintenanceMode);
    app.mount("#app");
} else {
    // Normal Inertia app
    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob("./Pages/**/*.vue")
            ),
        setup({ el, App, props, plugin }) {
            return createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue)
                .mount(el);
        },
        progress: {
            color: "#4B5563",
        },
    });
}
