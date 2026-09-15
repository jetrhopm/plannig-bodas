<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="theme-color" content="#9f5f68" />
        <title>Casa de Bodas</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link
            href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:ital,wght@0,500;0,600;1,500;1,600&display=swap"
            rel="stylesheet"
        />
        <style>
            :root {
                --rose: #9f5f68;
                --ink: #322b2a;
                --cream: #faf6f1;
            }
            * {
                box-sizing: border-box;
            }
            html {
                scroll-behavior: smooth;
            }
            body {
                margin: 0;
                background: var(--cream);
                color: var(--ink);
                font-family: "DM Sans", Arial;
            }
            .bar {
                height: 76px;
                position: absolute;
                z-index: 2;
                width: 100%;
                padding: 0 6vw;
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #fffdfae8;
            }
            .brand {
                color: inherit;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 9px;
            }
            .mark {
                border: 1px solid var(--rose);
                border-radius: 50%;
                width: 37px;
                height: 37px;
                display: grid;
                place-items: center;
                color: var(--rose);
                font: 22px Georgia;
            }
            .brand strong,
            h1,
            h2,
            h3 {
                font-family: "Playfair Display", Georgia;
            }
            .brand small {
                display: block;
                font-size: 7px;
                letter-spacing: 0.19em;
                color: #9d7776;
            }
            .portal {
                color: #804d54;
                text-decoration: none;
                font-size: 12px;
                border-bottom: 1px solid #bd8b8f;
                padding-bottom: 3px;
            }
            .hero {
                min-height: 760px;
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: end;
                background: #b9a19a;
            }
            .hero > img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: 64% center;
            }
            .shade {
                position: absolute;
                inset: 0;
                background: linear-gradient(#00000008 25%, #251714c9);
            }
            .copy {
                z-index: 1;
                color: white;
                padding: 0 7vw 90px;
                max-width: 690px;
                animation: rise 0.8s ease both;
            }
            .eyebrow {
                font-size: 10px;
                letter-spacing: 0.22em;
                font-weight: 600;
            }
            .copy h1 {
                font-size: clamp(48px, 12vw, 92px);
                line-height: 0.94;
                letter-spacing: -0.05em;
                margin: 17px 0;
            }
            .copy i {
                color: #f6d8d1;
            }
            .intro {
                max-width: 440px;
                line-height: 1.65;
                font-size: 15px;
            }
            .button {
                display: inline-block;
                margin-top: 14px;
                padding: 14px 21px;
                border-radius: 999px;
                background: #c98288;
                color: white;
                text-decoration: none;
                font-size: 12px;
                font-weight: 600;
            }
            .section {
                padding: 80px 7vw;
            }
            .section h2 {
                font-size: clamp(36px, 7vw, 60px);
                line-height: 1.04;
                letter-spacing: -0.04em;
                max-width: 720px;
            }
            .rose {
                color: var(--rose);
            }
            .grid {
                margin-top: 42px;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                border: 1px solid #decfc6;
            }
            .card {
                min-height: 195px;
                padding: 20px;
                border-right: 1px solid #decfc6;
                border-bottom: 1px solid #decfc6;
            }
            .card b {
                color: var(--rose);
                font-size: 25px;
            }
            .card h3 {
                margin: 40px 0 8px;
                font-size: 21px;
            }
            .card p {
                font-size: 12px;
                color: #766b67;
            }
            .editorial {
                margin: 0 5vw 75px;
                background: #ead8d2;
                display: grid;
            }
            .editorial img {
                width: 100%;
                height: 330px;
                object-fit: cover;
            }
            .editorial div {
                padding: 38px 30px;
            }
            .editorial h2 {
                font-size: clamp(34px, 7vw, 58px);
                line-height: 1.04;
            }
            .plans {
                background: #f0e7df;
                text-align: center;
            }
            .plans-grid {
                display: grid;
                gap: 12px;
                margin-top: 35px;
                text-align: left;
            }
            .plan {
                background: #fffaf6;
                border: 1px solid #dbcac0;
                padding: 28px;
                min-height: 205px;
            }
            .plan.hot {
                background: #493937;
                color: white;
            }
            .plan h3 {
                font-size: 29px;
                margin: 35px 0 3px;
            }
            .closing {
                padding: 84px 7vw;
                text-align: center;
                color: #fff;
                background: var(--rose);
            }
            .closing h2 {
                font-size: clamp(36px, 7vw, 60px);
                line-height: 1.05;
            }
            .closing .button {
                background: #fff;
                color: var(--rose);
            }
            footer {
                padding: 27px 7vw;
                display: flex;
                justify-content: space-between;
                font-size: 9px;
                color: #887b76;
            }
            @keyframes rise {
                from {
                    opacity: 0;
                    transform: translateY(25px);
                }
                to {
                    opacity: 1;
                    transform: none;
                }
            }
            @media (min-width: 760px) {
                .hero {
                    min-height: 820px;
                }
                .copy {
                    padding-bottom: 130px;
                }
                .grid {
                    grid-template-columns: repeat(4, 1fr);
                }
                .editorial {
                    grid-template-columns: 1fr 1fr;
                }
                .editorial img {
                    height: 100%;
                }
                .plans-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            @media (prefers-reduced-motion: reduce) {
                * {
                    animation: none !important;
                    scroll-behavior: auto;
                }
            }
            .botanical { position: fixed; z-index: 4; pointer-events: none; width: 104px; height: 280px; opacity: .55; transform-origin: bottom; animation: breeze 5s ease-in-out infinite alternate; }
            .botanical::before,.botanical::after { content: ''; position: absolute; width: 52px; height: 76px; border-radius: 100% 0 100% 0; background: linear-gradient(135deg,#dba4a7,#f4d8c9); box-shadow: 22px 51px 0 -10px #d6aa98,48px 120px 0 -13px #e9c0af; }
            .botanical::before { left: 16px; top: 21px; transform: rotate(-25deg); }.botanical::after { left: 43px; top: 82px; transform: rotate(72deg); }.botanical i { position:absolute; bottom:0; left:54px; width:2px; height:240px; background:#82936d; transform:rotate(-11deg); }.botanical.left { left:-30px; bottom:8vh; }.botanical.right { right:-30px; top:20vh; transform:scaleX(-1); animation-delay:-2.6s; }
            .float-note { position:absolute; z-index:1; right:7vw; top:145px; width:156px; padding:18px; background:#fffaf5e8; box-shadow:0 18px 40px #28171430; color:#69534f; font-size:12px; line-height:1.45; transform:rotate(3deg); transition:opacity .45s ease,transform .45s ease; }.float-note b{display:block;color:var(--rose);font:22px 'Playfair Display',Georgia}.float-note.is-away{opacity:0;transform:translateY(-25px) rotate(3deg)}
            .scroll-card { margin:-38px 7vw 0 auto; position:relative; z-index:2; width:min(86vw,330px); padding:21px 23px; background:#fffaf7; box-shadow:0 18px 45px #50312b20; color:#5d504c; transition:opacity .5s ease,transform .5s ease; }.scroll-card p{margin:3px 0 0;font:20px/1.15 'Playfair Display',Georgia}.scroll-card.is-away{opacity:0;transform:translateY(34px)}
            @keyframes breeze { from { rotate:-3deg } to { rotate:4deg } } @media(max-width:600px){.botanical{width:76px;opacity:.36}.botanical.right{top:33vh}.float-note{right:5vw;top:112px;width:118px;padding:13px;font-size:10px}.float-note b{font-size:18px}}
        </style>
    </head>
    <body>
        <aside class="botanical left" aria-hidden="true"><i></i></aside>
        <aside class="botanical right" aria-hidden="true"><i></i></aside>
        <header class="bar">
            <a class="brand" href="{{ $basePath }}/"
                ><span class="mark">♡</span
                ><span
                    ><strong>Casa de Bodas</strong
                    ><small>ESTUDIO DE CELEBRACIONES</small></span
                ></a
            ><a class="portal" href="{{ $portalUrl }}">Portal →</a>
        </header>
        <main>
            <section class="hero">
                <img
                    src="{{ $basePath }}/images/casa-de-bodas-hero.png"
                    alt="Pareja celebrando su boda al atardecer"
                />
                <div class="shade"></div>
                <aside class="float-note scroll-react"><b>01</b>Momentos diseñados para sentirse.</aside>
                <div class="copy">
                    <p class="eyebrow">MÉXICO · BODAS CON INTENCIÓN</p>
                    <h1>Una boda que se siente como <i>ustedes.</i></h1>
                    <p class="intro">
                        Planeación, diseño y coordinación para celebrar su
                        historia con belleza, calma y una atención
                        extraordinaria al detalle.
                    </p>
                    <a class="button" href="#planes"
                        >Descubrir su experiencia ↗</a
                    >
                </div>
            </section>
            <aside class="scroll-card scroll-react"><span class="eyebrow rose">CELEBRACIONES CON ALMA</span><p>Detalles que aparecen justo cuando deben hacerlo.</p></aside>
            <section class="section">
                <p class="eyebrow rose">NUESTRA PROMESA</p>
                <h2>La magia sucede cuando todo está cuidado.</h2>
                <div class="grid">
                    <article class="card">
                        <b>✦</b>
                        <h3>Planeación impecable</h3>
                        <p>Cada decisión tiene una ruta clara.</p>
                    </article>
                    <article class="card">
                        <b>♡</b>
                        <h3>Diseño con intención</h3>
                        <p>Una atmósfera que habla de ustedes.</p>
                    </article>
                    <article class="card">
                        <b>⌁</b>
                        <h3>Coordinación serena</h3>
                        <p>Ustedes viven el momento.</p>
                    </article>
                    <article class="card">
                        <b>✧</b>
                        <h3>Invitados cuidados</h3>
                        <p>Una experiencia fluida de inicio a fin.</p>
                    </article>
                </div>
            </section>
            <section class="editorial">
                <img
                    src="{{ $basePath }}/images/casa-de-bodas-hero.png"
                    alt="Detalles de una boda"
                />
                <div>
                    <p class="eyebrow rose">NUESTRO ENFOQUE</p>
                    <h2>Diseñamos el ambiente. Ustedes crean el recuerdo.</h2>
                    <p>
                        Una experiencia irrepetible, construida desde su forma
                        de celebrar.
                    </p>
                </div>
            </section>
            <section id="planes" class="section plans">
                <p class="eyebrow rose">FORMAS DE ACOMPAÑAR</p>
                <h2>Elijan el ritmo de su historia.</h2>
                <div class="plans-grid">
                    <article class="plan">
                        <p>01 · ESENCIAL</p>
                        <h3>Un inicio claro</h3>
                        <span>Planeación base</span>
                    </article>
                    <article class="plan hot">
                        <p>02 · ACOMPAÑADA</p>
                        <h3>Todo en armonía</h3>
                        <span>Diseño y coordinación</span>
                    </article>
                    <article class="plan">
                        <p>03 · A MEDIDA</p>
                        <h3>Solo de ustedes</h3>
                        <span>Experiencia integral</span>
                    </article>
                </div>
            </section>
            <section class="closing">
                <p class="eyebrow">SU HISTORIA EMPIEZA AQUÍ</p>
                <h2>Hagamos que el gran día se sienta inolvidable.</h2>
                <a class="button" href="{{ $portalUrl }}">Entrar al portal ↗</a>
            </section>
        </main>
        <footer>
            <span>Casa de Bodas</span
            ><span>Planeación · Diseño · Coordinación</span
            ><span>© {{ now()->year }}</span>
        </footer>
        <script>
            document.querySelectorAll('.scroll-react').forEach((element) => {
                new IntersectionObserver(([entry]) => element.classList.toggle('is-away', !entry.isIntersecting), { threshold: 0.18 }).observe(element);
            });
        </script>
    </body>
</html>
