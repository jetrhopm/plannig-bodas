@props(['navigation', 'basePath'])
@php
    $paths = [
        'weddings.workspace' => '/centro', 'weddings.show' => '', 'weddings.events.create' => '/eventos/nuevo',
        'weddings.families.index' => '/familias', 'weddings.planning.index' => '/planeacion',
        'weddings.planning.seating' => '/planeacion/mesas', 'weddings.planning.logistics' => '/planeacion/logistica',
        'weddings.finance.index' => '/finanzas', 'weddings.gifts.manage' => '/regalos',
        'weddings.reception.index' => '/recepcion', 'weddings.interview.edit' => '/entrevista',
    ];
    $icons = ['Centro'=>'center','Expediente'=>'dossier','Crear evento'=>'event','Familias'=>'families','Planeación'=>'planning','Mesas'=>'tables','Logística'=>'logistics','Finanzas'=>'finance','Regalos'=>'gifts','Recepción'=>'reception','Entrevista'=>'interview'];
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
                    <svg viewBox="0 0 24 24"><use href="{{ $basePath }}/images/wedding-icons.svg#{{ $icons[$item['label']] ?? 'dossier' }}" /></svg>
                </span><b>{{ $item['label'] }}</b><small>{{ $item['label'] === 'Crear evento' ? 'Nuevo momento' : 'Abrir área' }}</small>
            </a>
        @endforeach
    </div>
</section>
