<script setup lang="ts">
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import WeddingContextNav from '@/Components/WeddingContextNav.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="premium-page min-h-screen bg-gray-100">
            <nav :class="$page.props.weddingContext ? 'wedding-topbar' : 'border-b border-gray-100 bg-white/95 backdrop-blur'">
                <!-- Primary Navigation Menu -->
                <div :class="$page.props.weddingContext ? 'wedding-topbar__inner' : 'mx-auto max-w-7xl px-4 sm:px-6 lg:px-8'">
                    <div :class="$page.props.weddingContext ? 'flex h-[70px] justify-between' : 'flex h-16 justify-between'">
                        <div class="flex">
                            <!-- Logo -->
                            <div v-if="$page.props.weddingContext" class="flex shrink-0 items-center">
                                <a :href="route('weddings.workspace', $page.props.weddingContext.wedding.id)" class="wedding-topbar__brand">♡ {{ $page.props.weddingContext.wedding.name }}</a>
                            </div>
                            <div v-else class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-rose-800" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div v-if="!$page.props.weddingContext"
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Inicio
                                </NavLink>
                                <NavLink :href="route('notifications.index')" :active="route().current('notifications.*')">
                                    Avisos <span v-if="$page.props.notifications.unreadCount" class="ml-1 rounded-full bg-rose-100 px-1.5 py-0.5 text-xs text-rose-800">{{ $page.props.notifications.unreadCount }}</span>
                                </NavLink>
                            </div>
                        </div>

                        <div v-if="!$page.props.weddingContext" class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Mi perfil
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Cerrar sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Wedding hamburger: same behavior as Centro -->
                        <div v-if="$page.props.weddingContext" class="flex items-center">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                type="button"
                                aria-label="Abrir menú"
                                :aria-expanded="showingNavigationDropdown"
                                class="wedding-topbar__menu"
                            >☰</button>
                        </div>

                        <!-- Default hamburger outside wedding flows -->
                        <div v-else class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                type="button"
                                aria-label="Abrir menú de navegación"
                                :aria-expanded="showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div v-if="!$page.props.weddingContext"
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >Inicio</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('notifications.index')" :active="route().current('notifications.*')">Avisos ({{ $page.props.notifications.unreadCount }})</ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Mi perfil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Cerrar sesión
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
                <aside v-else-if="showingNavigationDropdown" class="wedding-drawer" aria-label="Menú de la boda">
                    <a :href="route('dashboard')">Panel principal</a>
                    <a :href="route('weddings.workspace', $page.props.weddingContext.wedding.id)">Centro de trabajo</a>
                    <a v-if="$page.props.weddingContext.items.some((item: any) => item.route === 'weddings.planning.index' && item.visible)" :href="route('weddings.planning.index', $page.props.weddingContext.wedding.id)">Planeación</a>
                    <a v-if="$page.props.weddingContext.items.some((item: any) => item.route === 'weddings.finance.index' && item.visible)" :href="route('weddings.finance.index', $page.props.weddingContext.wedding.id)">Finanzas</a>
                    <a v-if="$page.props.weddingContext.items.some((item: any) => item.route === 'weddings.reception.index' && item.visible)" :href="route('weddings.reception.index', $page.props.weddingContext.wedding.id)">Recepción</a>
                    <Link :href="route('logout')" method="post" as="button">Cerrar sesión</Link>
                </aside>
            </nav>

            <WeddingContextNav v-if="$page.props.weddingContext" :context="$page.props.weddingContext" />

            <!-- Page Heading -->
            <header
                class="border-b border-rose-100 bg-white/75 shadow-sm"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.wedding-topbar{height:70px;border-bottom:1px solid #f0e2db;background:#fffdfae8}.wedding-topbar__inner{padding:0 5vw}.wedding-topbar__brand{color:#814b55;text-decoration:none;font:600 17px 'Playfair Display',Georgia,serif}.wedding-topbar__menu{display:inline-flex;align-items:center;justify-content:center;width:45px;height:45px;border:0;border-radius:50%;background:#f7e8e5;color:#914f5d;font:21px/1 Arial;transition:transform .2s ease}.wedding-topbar__menu:active{transform:scale(.94)}.wedding-drawer{position:fixed;z-index:50;top:62px;right:5vw;width:min(88vw,300px);padding:14px;border:1px solid #ead8d0;border-radius:18px;background:#fffdfa;box-shadow:0 18px 42px #5d38252b}.wedding-drawer a,.wedding-drawer button{display:block;width:100%;padding:11px 4px;border:0;border-bottom:1px solid #f0e2db;background:none;color:#5f4946;text-align:left;text-decoration:none;font:13px 'DM Sans',Arial}.wedding-drawer button{color:#934e5b}
</style>
