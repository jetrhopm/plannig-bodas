<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="theme-color" content="#9f5f68" />
        <title>Acceso · Casa de Bodas</title>
        <x-theme-assets :base-path="$basePath" />
        <style>
            * {
                box-sizing: border-box;
            }
            body {
                margin: 0;
                background: #fcf8f4;
                color: #382e2d;
                font-family: "DM Sans", Arial;
            }
            .shell {
                min-height: 100vh;
            }
            .visual {
                display: none;
                position: relative;
                overflow: hidden;
            }
            .visual img {
                position: absolute;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .veil {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, #26171975, #5a29375e);
            }
            .quote {
                position: absolute;
                z-index: 1;
                bottom: 10%;
                left: 10%;
                right: 10%;
                color: white;
            }
            .quote span,
            .brand span {
                display: inline-grid;
                place-items: center;
                border: 1px solid currentColor;
                border-radius: 50%;
                width: 43px;
                height: 43px;
                font: 25px Georgia;
            }
            .quote p,
            .eyebrow {
                font-size: 10px;
                letter-spacing: 0.2em;
                font-weight: 600;
            }
            .quote h1,
            h2 {
                font-family: "Playfair Display", Georgia;
                font-weight: 500;
            }
            .quote h1 {
                max-width: 500px;
                font-size: clamp(42px, 5vw, 68px);
                line-height: 1.02;
            }
            .panel {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 34px 25px;
            }
            .form {
                width: min(100%, 410px);
            }
            .brand {
                display: flex;
                align-items: center;
                gap: 9px;
                color: #7c5055;
                text-decoration: none;
                font:
                    600 17px "Playfair Display",
                    Georgia;
            }
            .eyebrow {
                margin: 52px 0 11px;
                color: #a46369;
            }
            .form h2 {
                margin: 0;
                font-size: clamp(39px, 11vw, 54px);
                line-height: 1;
            }
            .lead {
                margin: 16px 0 34px;
                color: #766865;
                font-size: 14px;
                line-height: 1.65;
            }
            label {
                display: block;
                margin: 20px 0 8px;
                font-size: 12px;
                font-weight: 600;
            }
            .row {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .row label {
                margin-bottom: 8px;
            }
            .row a,
            .support a {
                color: #95565e;
                font-size: 11px;
            }
            input:not([type="checkbox"]) {
                width: 100%;
                border: 1px solid #ddcfca;
                background: #fffdfa;
                padding: 14px 13px;
                font:
                    14px "DM Sans",
                    Arial;
                outline: none;
            }
            input:focus {
                border-color: #ae6e74;
                box-shadow: 0 0 0 3px #b66b7220;
            }
            .remember {
                display: flex;
                gap: 8px;
                margin: 19px 0 25px;
                font-size: 12px !important;
                font-weight: 400;
            }
            .remember input {
                accent-color: #9f5f68;
            }
            button {
                width: 100%;
                border: 0;
                border-radius: 999px;
                padding: 15px;
                background: #9f5f68;
                color: white;
                font:
                    600 13px "DM Sans",
                    Arial;
            }
            .error {
                margin: 7px 0;
                color: #ad4254;
                font-size: 12px;
            }
            .success {
                padding: 11px;
                background: #edf6ef;
                color: #356040;
                font-size: 12px;
            }
            .support {
                text-align: center;
                margin-top: 30px;
                color: #82746f;
                font-size: 12px;
            }
            @media (min-width: 850px) {
                .shell {
                    display: grid;
                    grid-template-columns: 1.08fr 0.92fr;
                }
                .visual {
                    display: block;
                }
                .panel {
                    padding: 55px;
                }
                .brand {
                    position: absolute;
                    top: 40px;
                }
                .eyebrow {
                    margin-top: 0;
                }
            }
            .petals { position: fixed; inset: 0; z-index: 3; pointer-events: none; overflow: hidden; }
            .petal { position: absolute; width: 34px; height: 50px; border-radius: 100% 0 100% 0; background: radial-gradient(circle at 75% 25%,#ffdfe1 0 10%,#d98697 66%,#a84d64 100%); box-shadow: 0 8px 13px #7c405630; animation: drift 9s linear infinite; }
            .petal:nth-child(1){left:4%;top:4%;animation-delay:-2s}.petal:nth-child(2){right:7%;top:27%;scale:.8;animation-delay:-5s}.petal:nth-child(3){left:2%;bottom:18%;animation-delay:-7s}.petal:nth-child(4){right:8%;bottom:7%;scale:1.2;animation-delay:-1s}.petal:nth-child(5){left:18%;bottom:2%;scale:.7;animation-delay:-4s}
            .form { position: relative; z-index: 1; padding: 28px; border: 1px solid #fff; border-radius: 28px; background: linear-gradient(135deg,#fffdfbe8,#faeeeadd); box-shadow: 0 24px 65px #7a4f4820; backdrop-filter: blur(10px); }
            .panel::before,.panel::after{content:'';position:fixed;z-index:0;width:170px;height:260px;opacity:.35;background:radial-gradient(ellipse at 30% 20%,#b38a72 0 10%,transparent 12%),radial-gradient(ellipse at 80% 45%,#d4ae91 0 9%,transparent 11%),radial-gradient(ellipse at 45% 72%,#a77d66 0 11%,transparent 13%);filter:blur(1px)}.panel::before{left:-35px;bottom:0;transform:rotate(-20deg)}.panel::after{right:-45px;top:0;transform:rotate(30deg)}
            @keyframes drift { 0%{transform:translate3d(0,-12vh,0) rotate(-20deg)} 50%{transform:translate3d(28px,48vh,0) rotate(125deg)} 100%{transform:translate3d(-18px,115vh,0) rotate(260deg)} }
            @media (prefers-reduced-motion: reduce) { .petal { animation: none; } }
        </style>
    </head>
    <body>
        <div class="petals" aria-hidden="true"><i class="petal"></i><i class="petal"></i><i class="petal"></i><i class="petal"></i><i class="petal"></i></div>
        <main class="shell">
            <section class="visual">
                <img
                    src="{{ $basePath }}/images/casa-de-bodas-hero.png"
                    alt=""
                />
                <div class="veil"></div>
                <div class="quote">
                    <span>♡</span>
                    <p>CASA DE BODAS</p>
                    <h1>
                        Detrás de cada gran día hay una historia bien cuidada.
                    </h1>
                </div>
            </section>
            <section class="panel">
                <div class="form">
                    <a href="{{ $basePath }}/" class="brand"
                        ><span>♡</span>Casa de Bodas</a
                    >
                    <p class="eyebrow">PORTAL DE CELEBRACIONES</p>
                    <h2>Bienvenido de vuelta.</h2>
                    <p class="lead">
                        Ingresa para continuar con los detalles de tu
                        celebración.
                    </p>
                    @if($status)
                    <p class="success">{{ $status }}</p>
                    @endif
                    <form method="POST" action="{{ $basePath }}/login">
                        @csrf<label for="email">Correo electrónico</label
                        ><input
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            type="email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nombre@correo.com"
                        />@error('email')
                        <p class="error">{{ $message }}</p>
                        @enderror
                        <div class="row">
                            <label for="password">Contraseña</label
                            >@if($canResetPassword)<a
                                href="{{ $basePath }}/forgot-password"
                                >¿La olvidaste?</a
                            >@endif
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                        />@error('password')
                        <p class="error">{{ $message }}</p>
                        @enderror<label class="remember"
                            ><input type="checkbox" name="remember" />
                            Recordarme en este dispositivo</label
                        ><button>Entrar al portal →</button>
                    </form>
                    <p class="support">
                        ¿Necesitas ayuda?
                        <a href="mailto:hola@casadebodas.local">Escríbenos</a>
                    </p>
                </div>
            </section>
        </main>
    </body>
</html>
