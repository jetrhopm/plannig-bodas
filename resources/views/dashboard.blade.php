<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="theme-color" content="#934e5b" />
        <title>Panel · Casa de Bodas</title>
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
                color: #382e2d;
                font:
                    14px "DM Sans",
                    Arial;
            }
            .nav {
                height: 68px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 5vw;
                background: #fffdfa;
                border-bottom: 1px solid #eadbd3;
            }
            .brand {
                color: #7d4b54;
                text-decoration: none;
                font:
                    600 18px "Playfair Display",
                    Georgia;
            }
            .profile {
                color: #675956;
                font-size: 12px;
            }
            .wrap {
                max-width: 1180px;
                margin: auto;
                padding: 32px 5vw 70px;
            }
            .eyebrow {
                color: #a46268;
                font-size: 10px;
                letter-spacing: 0.18em;
                font-weight: 600;
            }
            .hero {
                position: relative;
                overflow: hidden;
                padding: 34px;
                border-radius: 25px;
                color: white;
                background: linear-gradient(
                    120deg,
                    #4a3035,
                    #914f5e 65%,
                    #c58085
                );
            }
            .hero:after {
                content: "♡";
                position: absolute;
                right: 4%;
                bottom: -70px;
                color: #ffffff16;
                font: 180px Georgia;
            }
            .hero h1,
            h2,
            h3 {
                font-family: "Playfair Display", Georgia;
            }
            .hero h1 {
                font-size: clamp(30px, 6vw, 49px);
                margin: 8px 0;
            }
            .action {
                display: inline-block;
                padding: 12px 18px;
                border-radius: 999px;
                background: #fffaf6;
                color: #80434d;
                text-decoration: none;
                font-weight: 600;
            }
            .grid {
                display: grid;
                gap: 17px;
                margin-top: 25px;
            }
            .card {
                padding: 22px;
                border: 1px solid #eadbd3;
                border-radius: 20px;
                background: #fffdfa;
                box-shadow: 0 12px 28px #623a320b;
            }
            .card h2 {
                margin: 5px 0 15px;
                font-size: 26px;
            }
            .event {
                display: block;
                margin-top: 9px;
                padding: 14px;
                border-radius: 12px;
                background: #faf4f0;
                color: #4c3d3b;
                text-decoration: none;
            }
            .event b,
            .event small {
                display: block;
            }
            .event small {
                margin-top: 4px;
                color: #81716b;
            }
            .weddings {
                display: grid;
                gap: 17px;
            }
            .wedding {
                position: relative;
                overflow: hidden;
                padding: 23px;
                border: 1px solid #eadbd3;
                border-radius: 22px;
                background: #fffdfa;
                box-shadow: 0 12px 28px #623a320b;
                display:grid;grid-template-columns:92px 1fr;gap:16px;align-items:stretch;
            }
            .wedding::after{content:'';grid-row:1 / span 3;grid-column:1;background:linear-gradient(#9c5361a8,#d9a09e55),url('{{ $basePath }}/images/casa-de-bodas-hero.png') center/cover;border-radius:15px 0 0 15px;min-height:185px}
            .wedding:before {
                content: "";
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 5px;
                background: #a65c68;
            }
            .wedding h3 {
                font-size: 29px;
                margin: 5px 0;
            }
            .links {
                grid-column:2;
                position:relative;z-index:3;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:7px;
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 17px;
            }
            .links a {
                border: 1px solid #e3d0c8;
                border-radius: 999px;
                padding: 9px 12px;
                color: #654b49;
                text-decoration: none;
                font-size: 12px;
            }
            .links a:first-child {
                background: #934e5b;
                color: #fff;
                border-color: #934e5b;
            }
            .links a{min-height:58px;display:grid;place-items:center;text-align:center;padding:7px 4px;border-radius:13px;background:#fff9f6;font-size:10px;box-shadow:0 5px 12px #653a3010}.links a:first-child{font-size:11px}.links a:first-child:before{content:'⌂';display:block;font-size:18px}.links a:nth-child(2):before{content:'▤';display:block;font-size:18px;color:#a25662}.links a:nth-child(3):before{content:'▥';display:block;font-size:18px;color:#a25662}.links a:nth-child(4):before{content:'♨';display:block;font-size:18px;color:#a25662}
            /* Mobile card composition: image is decorative, never a grid item. */
            .wedding{display:block;min-height:238px;padding:24px 22px 20px 151px}.wedding::after{position:absolute;z-index:0;left:24px;top:24px;width:105px;height:calc(100% - 48px);min-height:0;display:block;background:linear-gradient(#9c536155,#d9a09e44),url('{{ $basePath }}/images/casa-de-bodas-hero.png') center/cover;border-radius:15px 0 0 15px}.wedding>.eyebrow,.wedding>h3,.wedding>p{position:relative;z-index:2;display:block;grid-column:auto}.wedding>.eyebrow{margin:0 0 8px}.wedding>h3{margin:0 0 18px;font-size:31px}.wedding>p{font-size:13px}.links{position:relative;z-index:3;margin:20px 0 0;display:grid;grid-template-columns:1.32fr repeat(3,1fr);gap:7px}.links a{min-height:65px}.links a:first-child{font-size:10px}@media(max-width:430px){.wedding{min-height:224px;padding:19px 15px 17px 126px}.wedding::after{left:16px;top:19px;width:91px;height:calc(100% - 38px)}.wedding h3{font-size:26px!important;margin-bottom:13px}.wedding>p{font-size:12px}.links{margin-top:14px;gap:5px}.links a{min-height:57px;font-size:8px}.links a:first-child{font-size:9px}}
            .wedding>.eyebrow,.wedding>h3,.wedding>p{grid-column:2;margin-left:0}.wedding>h3{font-size:31px}.wedding>p{margin:0;color:#795e59}.wedding>.eyebrow{display:inline-block;width:max-content;margin-bottom:-4px;padding:5px 8px;border-radius:999px;background:#f3e7df;color:#8f665d}
            @media (min-width: 760px) {
                .weddings {
                    grid-template-columns: repeat(2, 1fr);
                }
                .grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            .botanical{position:fixed;z-index:5;pointer-events:none;width:90px;height:280px;opacity:.38;transform-origin:bottom;animation:breeze 5s ease-in-out infinite alternate}.botanical:before,.botanical:after{content:'';position:absolute;width:48px;height:78px;border-radius:100% 0 100% 0;background:linear-gradient(135deg,#d6a497,#f2d7c9);box-shadow:28px 63px 0 -12px #b99076,47px 132px 0 -15px #d2ad91}.botanical:before{top:10px;left:16px;rotate:-28deg}.botanical:after{top:77px;left:39px;rotate:70deg}.botanical i{position:absolute;height:240px;width:2px;background:#82936d;bottom:0;left:48px;rotate:-12deg}.botanical.left{left:-26px;bottom:5vh}.botanical.right{right:-27px;top:19vh;scale:-1 1;animation-delay:-2.5s}.scroll-fade{transition:opacity .55s ease,transform .55s cubic-bezier(.2,.75,.25,1)}.scroll-fade.is-away{opacity:.08;transform:translateY(28px) scale(.975)}.scroll-fade.is-visible{opacity:1;transform:none}@keyframes breeze{from{rotate:-3deg}to{rotate:4deg}}@media(max-width:600px){.botanical{width:62px;opacity:.25}.botanical.right{top:35vh}}@media(prefers-reduced-motion:reduce){.botanical{animation:none}.scroll-fade{transition:none}}
            .petals{position:fixed;inset:0;z-index:4;pointer-events:none;overflow:hidden}.petal{position:absolute;width:28px;height:42px;border-radius:100% 0 100% 0;background:radial-gradient(circle at 75% 25%,#ffe1e2 0 12%,#db8293 68%,#a94f65 100%);box-shadow:0 7px 12px #71384730;animation:petal-drift 11s linear infinite}.petal:nth-child(1){left:4%;top:-8%;animation-delay:-2s}.petal:nth-child(2){right:8%;top:8%;scale:.75;animation-delay:-7s}.petal:nth-child(3){left:15%;top:42%;scale:.6;animation-delay:-4s}.petal:nth-child(4){right:3%;bottom:16%;animation-delay:-9s}.petal:nth-child(5){left:40%;bottom:-8%;scale:.85;animation-delay:-5s}@keyframes petal-drift{0%{transform:translate3d(0,-10vh,0) rotate(-20deg)}50%{transform:translate3d(25px,52vh,0) rotate(120deg)}100%{transform:translate3d(-18px,115vh,0) rotate(245deg)}}@media(prefers-reduced-motion:reduce){.petal{animation:none}}
            .links a:before{content:none!important}.links a svg{width:19px;height:19px;display:block;fill:none;stroke:#a25662;stroke-width:1.65;stroke-linecap:round;stroke-linejoin:round}.links a:first-child svg{stroke:#fff}.links a:first-child{display:flex;flex-direction:column;justify-content:center;gap:3px}
            .menu-btn{border:0;border-radius:50%;width:43px;height:43px;background:#f5e5e1;color:#934e5b;font-size:20px}.mobile-menu{display:none;position:fixed;z-index:20;right:5vw;top:63px;width:min(88vw,300px);padding:17px;border:1px solid #ead8d0;border-radius:18px;background:#fffdfa;box-shadow:0 18px 42px #5d38252b}.mobile-menu.open{display:block}.mobile-menu a,.mobile-menu button{display:block;width:100%;padding:11px 4px;border:0;border-bottom:1px solid #f0e2db;background:none;color:#5f4946;text-align:left;text-decoration:none;font:13px 'DM Sans',Arial}.mobile-menu button{color:#934e5b}
        </style>
    </head>
    <body>
        <div class="petals" aria-hidden="true"><i class="petal"></i><i class="petal"></i><i class="petal"></i><i class="petal"></i><i class="petal"></i></div>
        <aside class="botanical left" aria-hidden="true"><i></i></aside><aside class="botanical right" aria-hidden="true"><i></i></aside>
        <nav class="nav">
            <a class="brand" href="{{ $basePath }}/">♡ Casa de Bodas</a
            ><span class="profile"
                >{{ auth()->user()->name }} ·
                <form
                    style="display: inline"
                    method="POST"
                    action="{{ $basePath }}/logout"
                >
                    @csrf<button
                        style="border: 0; background: none; color: #934e5b"
                    >
                        Salir
                    </button>
                </form></span
            >
            <button class="menu-btn" type="button" aria-label="Abrir menú" aria-expanded="false">☰</button>
        </nav>
        <aside class="mobile-menu" aria-label="Menú principal"><a href="{{ $basePath }}/dashboard">Inicio</a><a href="{{ $basePath }}/notificaciones">Avisos</a><a href="{{ $basePath }}/profile">Mi perfil</a><form method="POST" action="{{ $basePath }}/logout">@csrf<button>Cerrar sesión</button></form></aside>
        <main class="wrap">
            <p class="eyebrow">CASA DE BODAS · PANEL OPERATIVO</p>
        <section class="hero scroll-fade">
                <p class="eyebrow" style="color: #ffe0e0">CENTRO OPERATIVO</p>
                <h1>
                    {{ $unreadNotifications ? $unreadNotifications.' asuntos
                    esperan tu atención.' : 'Todo está listo para crear momentos
                    inolvidables.' }}
                </h1>
                @if($canCreateWedding)<a
                    class="action"
                    href="{{ $basePath }}/bodas/nueva"
                    >+ Nueva boda</a
                >@else<a class="action" href="{{ $basePath }}/notificaciones"
                    >Ver avisos</a
                >@endif
            </section>
        <section class="grid scroll-fade">
                <article class="card">
                    <p class="eyebrow">PRÓXIMOS EVENTOS</p>
                    <h2>Calendario inmediato</h2>
                    @forelse($roleSummary['nextEvents'] as $event)<a
                        class="event"
                        href="{{ $basePath }}/bodas/{{ $event->wedding_id }}/centro"
                        ><b>{{ $event->name }}</b
                        ><small
                            >{{ $event->event_date?->format('d M Y') }} · {{
                            $event->venue ?: 'Lugar pendiente' }}</small
                        ></a
                    >@empty
                    <p>No hay eventos próximos.</p>
                    @endforelse
                </article>
                <article class="card">
                    <p class="eyebrow">OPERACIÓN</p>
                    <h2>Tareas pendientes</h2>
                    @forelse($roleSummary['tasks'] as $task)<a
                        class="event"
                        href="{{ $basePath }}/bodas/{{ $task->wedding_id }}/planeacion"
                        ><b>{{ $task->title }}</b
                        ><small
                            >{{ $task->priority }} · {{
                            $task->due_date?->format('d M Y') ?: 'Fecha
                            pendiente' }}</small
                        ></a
                    >@empty
                    <p>No hay tareas pendientes.</p>
                    @endforelse
                </article>
                <article class="card">
                    <p class="eyebrow">FINANZAS</p>
                    <h2>Por resolver</h2>
                    @forelse($roleSummary['financeItems'] as $item)<a
                        class="event"
                        href="{{ $basePath }}/bodas/{{ $item->wedding_id }}/finanzas"
                        ><b>{{ $item->description }}</b
                        ><small
                            >${{ $item->amount }} · {{ $item->status }}</small
                        ></a
                    >@empty
                    <p>No hay pendientes financieros.</p>
                    @endforelse
                </article>
            </section>
        <section class="scroll-fade" style="margin-top: 30px">
                <p class="eyebrow">BODAS DISPONIBLES</p>
                <h2 style="font-size: 32px; margin: 6px 0 15px">
                    Elige dónde continuar
                </h2>
                <div class="weddings">
                    @forelse($weddings as $wedding)
                <article class="wedding scroll-fade">
                        <p class="eyebrow">{{ $wedding->status }}</p>
                        <h3>{{ $wedding->name }}</h3>
                        <p>
                            {{ optional($wedding->events->first())->name ?:
                            'Evento principal' }} · {{
                            optional($wedding->events->first())->venue ?: 'Lugar
                            pendiente' }}
                        </p>
                        <div class="links">
                            <a
                                href="{{ $basePath }}/bodas/{{ $wedding->id }}/centro"
                                ><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zM9 21v-6h6v6"/></svg>Abrir centro</a
                            ><a
                                href="{{ $basePath }}/bodas/{{ $wedding->id }}/planeacion"
                                ><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4m8-4v4M3 10h18m-12 4h6m-6 4h4"/></svg>Planeación</a
                            ><a
                                href="{{ $basePath }}/bodas/{{ $wedding->id }}/finanzas"
                                ><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10m6 10V4m6 16v-7m4 7H2"/></svg>Finanzas</a
                            ><a
                                href="{{ $basePath }}/bodas/{{ $wedding->id }}/recepcion"
                                ><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h16M6 20v-7h12v7M8 13V8h8v5M5 8h14M7 4h10"/></svg>Recepción</a
                            >
                        </div>
                    </article>
                    @empty
                    <p>No tienes bodas disponibles.</p>
                    @endforelse
                </div>
            </section>
        </main>
        <script>const mb=document.querySelector('.menu-btn'),mm=document.querySelector('.mobile-menu');mb?.addEventListener('click',()=>{mm.classList.toggle('open');mb.setAttribute('aria-expanded',mm.classList.contains('open'))});document.querySelectorAll('.scroll-fade').forEach((element)=>new IntersectionObserver(([entry])=>element.classList.toggle('is-away',!entry.isIntersecting),{threshold:.16}).observe(element));</script>
    </body>
</html>
