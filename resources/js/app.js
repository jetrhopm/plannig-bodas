import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// A page restored from the browser back-forward cache can still show a private
// form after logout. Reloading it forces Laravel to check the current session.
window.addEventListener('pageshow', (event) => {
    if (event.persisted) window.location.reload();
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        document.documentElement.classList.add('inertia-ready');

        const mounted = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);

        // The error notice in app.blade only represents a failure before Vue
        // mounts; async asset-preload warnings after this point are harmless.
        window.__inertiaBooted = true;
        document.getElementById('frontend-boot-error')?.setAttribute('hidden', '');
        return mounted;
    },
    progress: {
        color: '#4B5563',
    },
});
