@props(['navigation', 'basePath'])
@php
    $paths = [
        'weddings.workspace' => '/centro', 'weddings.show' => '', 'weddings.events.create' => '/eventos/nuevo',
        'weddings.families.index' => '/familias', 'weddings.planning.index' => '/planeacion',
        'weddings.planning.seating' => '/planeacion/mesas', 'weddings.planning.logistics' => '/planeacion/logistica',
        'weddings.finance.index' => '/finanzas', 'weddings.gifts.manage' => '/regalos',
        'weddings.reception.index' => '/recepcion', 'weddings.interview.edit' => '/entrevista',
    ];
@endphp
<section class="areas-menu" aria-label="Áreas de trabajo">
    <div class="areas-menu__hero" style="background-image:linear-gradient(90deg,#fff4efdd,#fff4ef8c),url('{{ $basePath }}/images/casa-de-bodas-hero.png')">
        <p>CENTRO DE TRABAJO</p><h1>{{ $navigation['wedding']['name'] }}</h1>
        <div>Organiza, planifica y haz realidad momentos inolvidables.</div>
        <aside><small>{{ $navigation['wedding']['next_event']['date'] ?? 'Fecha pendiente' }}</small><b>{{ $navigation['wedding']['next_event']['venue'] ?? 'Lugar pendiente' }}</b></aside>
    </div>
    <div class="areas-menu__head"><strong>{{ $navigation['wedding']['name'] }}</strong><small>ÁREAS DE TRABAJO</small></div>
    <div class="areas-menu__grid">
        @foreach($navigation['items'] as $item)
            @continue(! $item['visible'])
            <a class="areas-menu__item {{ request()->routeIs($item['route']) ? 'is-active' : '' }}" href="{{ $basePath }}/bodas/{{ $navigation['wedding']['id'] }}{{ $paths[$item['route']] }}">
                <span class="areas-menu__icon" aria-hidden="true">
                    @switch($item['label'])
                        @case('Centro') <svg viewBox="0 0 24 24"><path d="m3 10 9-7 9 7v10H3zM9 21v-6h6v6"/></svg> @break
                        @case('Expediente') <svg viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg> @break
                        @case('Crear evento') <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4m8-4v4M3 10h18m9 4v6m-3-3h6"/></svg> @break
                        @case('Familias') <svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 21v-2a6 6 0 0 1 12 0v2m2-12a3 3 0 1 0-1-5.83M18 21v-2a6 6 0 0 0-3-5.2"/></svg> @break
                        @case('Mesas') <svg viewBox="0 0 24 24"><path d="M4 10h16M7 10v10m10-10v10M10 4h4v6h-4z"/></svg> @break
                        @case('Logística') <svg viewBox="0 0 24 24"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7zM6 21a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm12 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/></svg> @break
                        @case('Finanzas') <svg viewBox="0 0 24 24"><path d="M4 20V10m6 10V4m6 16v-7m6 7H2"/></svg> @break
                        @case('Regalos') <svg viewBox="0 0 24 24"><path d="M3 10h18v11H3zM2 6h20v4H2zm10 4v11M7 6c-2 0-3-1-3-2s1-2 3-1l3 3m7 0c2 0 3-1 3-2s-1-2-3-1l-3 3"/></svg> @break
                        @case('Recepción') <svg viewBox="0 0 24 24"><path d="M3 20h18M5 20V9h14v11M3 9h18M8 9V4h8v5M9 14h6"/></svg> @break
                        @case('Entrevista') <svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M4 21a8 8 0 0 1 16 0m-2-12 3 3m0-3-3 3"/></svg> @break
                        @default <svg viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
                    @endswitch
                </span><b>{{ $item['label'] }}</b><small>{{ $item['label'] === 'Crear evento' ? 'Nuevo momento' : 'Abrir área' }}</small>
            </a>
        @endforeach
    </div>
</section>
