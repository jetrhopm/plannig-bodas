<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script>
            window.__showFrontendBootError = function (message) {
                var box = document.getElementById('frontend-boot-error');
                if (box) { box.hidden = false; box.querySelector('code').textContent = message; }
            };
            window.addEventListener('error', function (event) {
                window.__showFrontendBootError(event.message || 'Error desconocido al iniciar JavaScript.');
            });
            window.addEventListener('unhandledrejection', function (event) {
                window.__showFrontendBootError('Promesa rechazada: ' + (event.reason?.message || String(event.reason)));
            });
        </script>
        @routes
        <script src="{{ rtrim(request()->getBaseUrl(), '/') }}/js/wedding-area-menu.js" defer></script>
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        <aside id="frontend-boot-error" hidden style="position:fixed;z-index:99999;left:12px;right:12px;bottom:12px;padding:14px;border:1px solid #b44b57;border-radius:12px;background:#fff7f7;color:#571c24;font:12px/1.45 Arial;box-shadow:0 12px 34px #0003">
            <b>No se pudo iniciar la interfaz.</b><br><code></code>
        </aside>
    </body>
</html>
