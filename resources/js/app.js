import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

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
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
        delay: 250,
        color: "#8B5CF6",
        includeCSS: true,
        showSpinner: true,
    },
});

// Add global transition styles
const style = document.createElement("style");
style.textContent = `
    .page-enter-active,
    .page-leave-active {
        transition: all 0.3s ease-out;
    }
    
    .page-enter-from {
        opacity: 0;
        transform: translateY(20px);
    }
    
    .page-leave-to {
        opacity: 0;
        transform: translateY(-20px);
    }
    
    .page-enter-to,
    .page-leave-from {
        opacity: 1;
        transform: translateY(0);
    }
`;
document.head.appendChild(style);
