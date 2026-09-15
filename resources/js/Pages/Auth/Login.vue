<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
const props = defineProps({
    canResetPassword: Boolean,
    status: String,
    appBasePath: { type: String, default: "" },
});
const form = useForm({ email: "", password: "", remember: false });
const submit = () =>
    form.post(route("login"), { onFinish: () => form.reset("password") });
const homeUrl = props.appBasePath ? `${props.appBasePath}/` : "/";
const heroUrl = `${props.appBasePath}/images/casa-de-bodas-hero.png`;
</script>
<template>
    <Head title="Acceso al portal" />
    <main class="shell">
        <section class="visual">
            <img :src="heroUrl" alt="" />
            <div class="veil"></div>
            <div class="blossom one"></div>
            <div class="blossom two"></div>
            <div class="quote">
                <span>♡</span>
                <p>CASA DE BODAS</p>
                <h1>Detrás de cada gran día hay una historia bien cuidada.</h1>
            </div>
        </section>
        <section class="panel">
            <div class="form">
                <Link :href="homeUrl" class="brand">♡ <b>Casa de Bodas</b></Link>
                <p class="eyebrow">PORTAL DE CELEBRACIONES</p>
                <h2>Bienvenido de vuelta.</h2>
                <p class="lead">
                    Ingresa para continuar con los detalles de tu celebración.
                </p>
                <p v-if="status" class="success">{{ status }}</p>
                <form @submit.prevent="submit">
                    <label for="email">Correo electrónico</label
                    ><input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="nombre@correo.com"
                    />
                    <p v-if="form.errors.email" class="error">
                        {{ form.errors.email }}
                    </p>
                    <div class="row">
                        <label for="password">Contraseña</label
                        ><Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            >¿La olvidaste?</Link
                        >
                    </div>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <p v-if="form.errors.password" class="error">
                        {{ form.errors.password }}
                    </p>
                    <label class="remember"
                        ><input v-model="form.remember" type="checkbox" />
                        Recordarme en este dispositivo</label
                    ><button :disabled="form.processing">
                        {{
                            form.processing
                                ? "Ingresando…"
                                : "Entrar al portal →"
                        }}
                    </button>
                </form>
                <p class="support">
                    ¿Necesitas ayuda?
                    <a href="mailto:hola@casadebodas.local">Escríbenos</a>
                </p>
            </div>
        </section>
    </main>
</template>
<style scoped>
@import url("https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap");
.shell {
    min-height: 100vh;
    background: #fcf8f4;
    color: #382e2d;
    font-family: "DM Sans", Arial;
}
.visual {
    display: none;
    position: relative;
    overflow: hidden;
}
.visual > img {
    position: absolute;
    inset: 0;
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
    left: 10%;
    bottom: 10%;
    color: #fff;
}
.quote span {
    font: 28px Georgia;
    border: 1px solid #fff;
    border-radius: 50%;
    padding: 8px 13px;
}
.quote p,
.eyebrow {
    font-size: 10px;
    letter-spacing: 0.2em;
    font-weight: 600;
}
.quote h1,
h2,
.brand b {
    font-family: "Playfair Display", Georgia;
    font-weight: 500;
}
.quote h1 {
    max-width: 500px;
    font-size: clamp(42px, 5vw, 68px);
    line-height: 1.02;
}
.blossom {
    position: absolute;
    z-index: 1;
    width: 80px;
    height: 110px;
    border-radius: 100% 0 100% 0;
    background: #edc9c8a8;
    animation: sway 5s ease-in-out infinite alternate;
}
.one {
    right: -15px;
    top: 16%;
    rotate: 38deg;
}
.two {
    left: 9%;
    top: 15%;
    width: 44px;
    height: 62px;
    animation-delay: -2s;
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
    color: #7c5055;
    text-decoration: none;
    font-size: 17px;
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
.success,
.error {
    font-size: 12px;
}
.success {
    padding: 11px;
    background: #edf6ef;
    color: #356040;
}
.error {
    margin: 7px 0;
    color: #ad4254;
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
.row a {
    font-size: 11px;
    color: #95565e;
    text-decoration: none;
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
    color: #fff;
    font:
        600 13px "DM Sans",
        Arial;
    cursor: pointer;
}
button:disabled {
    opacity: 0.65;
}
.support {
    text-align: center;
    margin-top: 30px;
    color: #82746f;
    font-size: 12px;
}
.support a {
    color: #8d555c;
}
@keyframes sway {
    from {
        rotate: -5deg;
    }
    to {
        rotate: 8deg;
    }
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
@media (prefers-reduced-motion: reduce) {
    .blossom {
        animation: none;
    }
}
</style>
