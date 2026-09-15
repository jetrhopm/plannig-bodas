<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="theme-color" content="#9b5663" />
        <title>{{ $wedding->name }} · Casa de Bodas</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap"
            rel="stylesheet"
        />
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
                background: #fbf7f3;
                color: #392d2c;
                font:
                    14px "DM Sans",
                    Arial;
            }
            .nav {
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 5vw;
                background: #fffdfae8;
            }
            .brand {
                color: #814b55;
                text-decoration: none;
                font:
                    600 17px "Playfair Display",
                    Georgia;
            }
            .menu {
                border: 0;
                border-radius: 50%;
                width: 45px;
                height: 45px;
                background: #f7e8e5;
                color: #914f5d;
                font-size: 21px;
            }
            .hero {
                min-height: 310px;
                padding: 35px 6vw;
                position: relative;
                overflow: hidden;
                background:
                    linear-gradient(90deg, #fff4efdd, #fff4ef8c),
                    url("{{ $basePath }}/images/casa-de-bodas-hero.png")
                        center/cover;
            }
            .hero h1,
            h2,
            h3 {
                font-family: "Playfair Display", Georgia;
            }
            .hero h1 {
                font-size: clamp(42px, 12vw, 68px);
                line-height: 0.95;
                margin: 9px 0;
            }
            .hero p {
                max-width: 330px;
                font-size: 16px;
                line-height: 1.45;
                color: #77635e;
            }
            .eyebrow {
                font-size: 10px;
                letter-spacing: 0.22em;
                color: #a35e68;
                font-weight: 600;
            }
            .date {
                position: absolute;
                right: 6vw;
                bottom: 23px;
                padding: 14px 17px;
                border-radius: 17px;
                background: #fffdfbe8;
                box-shadow: 0 12px 30px #663a321e;
            }
            .date b,
            .date small {
                display: block;
            }
            .wrap {
                padding: 20px 5vw 60px;
                max-width: 900px;
                margin: auto;
            }
            .areas {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
            .area {
                min-height: 105px;
                padding: 14px;
                border-radius: 17px;
                background: #fffdfa;
                color: #493938;
                text-decoration: none;
                box-shadow: 0 10px 24px #5f383111;
                transition: 0.25s;
            }
            .area:first-child {
                background: linear-gradient(140deg, #a04f5f, #d8898c);
                color: #fff;
            }
            .area:hover {
                transform: translateY(-3px);
            }
            .icon { width:27px;height:27px;display:block;fill:none;stroke:currentColor;stroke-width:1.65;stroke-linecap:round;stroke-linejoin:round; }
            .area b,
            .area small {
                display: block;
            }
            .area b {
                font:
                    18px "Playfair Display",
                    Georgia;
                margin-top: 12px;
            }
            .area small {
                font-size: 10px;
                color: inherit;
                opacity: 0.78;
            }
            .next {
                margin-top: 27px;
            }
            .next h2 {
                margin: 6px 0 13px;
                font-size: 28px;
            }
            .event {
                display: flex;
                gap: 14px;
                align-items: center;
                padding: 12px;
                border: 1px solid #ead8d0;
                border-radius: 18px;
                background: #fffdfa;
            }
            .event img {
                width: 110px;
                height: 82px;
                object-fit: cover;
                border-radius: 12px;
            }
            .event b,
            .event small {
                display: block;
            }
            .event small {
                margin-top: 5px;
                color: #77635e;
            }
            .scroll-fade {
                transition:
                    opacity 0.5s,
                    transform 0.5s;
            }
            .is-away {
                opacity: 0.08;
                transform: translateY(25px);
            }
            @media (max-width: 390px) {
                .areas {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (prefers-reduced-motion: reduce) {
                .scroll-fade,
                .area {
                    transition: none;
                }
            }
            .workspace-menu{display:none;position:fixed;z-index:30;right:5vw;top:62px;width:min(88vw,300px);padding:14px;border:1px solid #ead8d0;border-radius:18px;background:#fffdfa;box-shadow:0 18px 42px #5d38252b}.workspace-menu.open{display:block}.workspace-menu a,.workspace-menu button{display:block;width:100%;padding:11px 4px;border:0;border-bottom:1px solid #f0e2db;background:none;color:#5f4946;text-align:left;text-decoration:none;font:13px 'DM Sans',Arial}.workspace-menu button{color:#934e5b}
        </style>
    </head>
    <body>
        <nav class="nav">
            <a class="brand" href="{{ $basePath }}/dashboard"
                >♡ {{ $wedding->name }}</a
            ><button class="menu" type="button" aria-label="Abrir menú" aria-expanded="false">☰</button>
        </nav>
        <aside class="workspace-menu" aria-label="Menú de la boda">
            <a href="{{ $basePath }}/dashboard">Panel principal</a>
            <a href="{{ $basePath }}/bodas/{{ $wedding->id }}/centro">Centro de trabajo</a>
            <a href="{{ $basePath }}/bodas/{{ $wedding->id }}/planeacion">Planeación</a>
            @if($canFinance)
                <a href="{{ $basePath }}/bodas/{{ $wedding->id }}/finanzas">Finanzas</a>
            @endif
            @if($canReceive)
                <a href="{{ $basePath }}/bodas/{{ $wedding->id }}/recepcion">Recepción</a>
            @endif
            <form method="POST" action="{{ $basePath }}/logout">@csrf<button>Cerrar sesión</button></form>
        </aside>
        @php($event=$wedding->events->first())
        <section class="hero scroll-fade">
            <p class="eyebrow">CENTRO DE TRABAJO</p>
            <h1>{{ $wedding->name }}</h1>
            <p>Organiza, planifica y haz realidad momentos inolvidables.</p>
            @if($event)
            <aside class="date">
                <small>{{ $event->event_date?->translatedFormat('l') }}</small
                ><b>{{ $event->event_date?->format('d M Y') }}</b
                ><small>{{ $event->venue }}</small>
            </aside>
            @endif
        </section>
        <main class="wrap">
            <section class="areas scroll-fade">
                @if($canEdit)
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/eventos/nuevo"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4m8-4v4M3 10h18m-6 4v4m-2-2h4"/></svg><b>Crear evento</b
                    ><small>Nuevo momento</small></a
                >
                @endif
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/familias"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="10" r="2"/><path d="M3 20c0-4 3-6 6-6s6 2 6 6m-1-4c3 0 5 1 5 4"/></svg><b>Familias</b
                    ><small>Invitados y RSVPs</small></a
                >
                @if($canPlan)
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/planeacion"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg><b>Planeación</b
                    ><small>Cronograma</small></a
                ><a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/planeacion/mesas"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M5 21v-5a7 7 0 0 1 14 0v5M9 12v9m6-9v9"/></svg><b>Mesas</b
                    ><small>Distribución</small></a
                ><a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/planeacion/logistica"
                    ><span class="icon">◇</span><b>Logística</b
                    ><small>Proveedores</small></a
                >
                @endif
                @if($canFinance)
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/finanzas"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10m6 10V4m6 16v-7m4 7H2"/></svg><b>Finanzas</b
                    ><small>Presupuesto</small></a
                >
                @endif
                @if($canEdit)
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/regalos"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="9" width="16" height="12" rx="1"/><path d="M12 9v12M4 13h16M12 9C7 9 6 3 9 3c2 0 3 3 3 6Zm0 0c5 0 6-6 3-6-2 0-3 3-3 6Z"/></svg><b>Regalos</b
                    ><small>Mesa de regalos</small></a
                >
                @endif
                @if($canReceive)
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/recepcion"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h16M6 20v-7h12v7M8 13V8h8v5M5 8h14M7 4h10"/></svg><b>Recepción</b
                    ><small>Día del evento</small></a
                >
                @endif
                @if($canPlan)
                <a
                    class="area"
                    href="{{ $basePath }}/bodas/{{ $wedding->id }}/entrevista"
                    ><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 21c0-4 3-7 7-7s7 3 7 7M16 7h4m-2-2v4"/></svg><b>Entrevista</b
                    ><small>Pareja / Cliente</small></a
                >
                @endif
            </section>
            <section class="next scroll-fade">
                <p class="eyebrow">EVENTO PRÓXIMO</p>
                <h2>{{ $event?->name ?: 'Evento por definir' }}</h2>
                <div class="event">
                    <img
                        src="{{ $basePath }}/images/casa-de-bodas-hero.png"
                        alt="Decoración de boda"
                    />
                    <div>
                        <b>{{ $event?->venue ?: 'Lugar pendiente' }}</b
                        ><small
                            >{{ $event?->event_date?->translatedFormat('d \d\e F
                            Y') ?: 'Fecha pendiente' }}</small
                        ><small
                            >{{ $event?->event_time ?: 'Hora pendiente'
                            }}</small
                        >
                    </div>
                </div>
            </section>
        </main>
        <script>
            const menuButton=document.querySelector('.menu'),workspaceMenu=document.querySelector('.workspace-menu');menuButton?.addEventListener('click',()=>{workspaceMenu.classList.toggle('open');menuButton.setAttribute('aria-expanded',workspaceMenu.classList.contains('open'))});
            document
                .querySelectorAll(".scroll-fade")
                .forEach((e) =>
                    new IntersectionObserver(
                        ([x]) =>
                            e.classList.toggle("is-away", !x.isIntersecting),
                        { threshold: 0.15 },
                    ).observe(e),
                );
        </script>
    </body>
</html>
