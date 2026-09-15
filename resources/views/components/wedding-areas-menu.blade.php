@props(['navigation', 'basePath', 'activeRoute' => null])
@php
    $paths = ['weddings.workspace'=>'/centro','weddings.show'=>'','weddings.events.create'=>'/eventos/nuevo','weddings.families.index'=>'/familias','weddings.planning.index'=>'/planeacion','weddings.planning.seating'=>'/planeacion/mesas','weddings.planning.logistics'=>'/planeacion/logistica','weddings.finance.index'=>'/finanzas','weddings.gifts.manage'=>'/regalos','weddings.reception.index'=>'/recepcion','weddings.interview.edit'=>'/entrevista'];
    $icons = ['Centro'=>'center','Expediente'=>'dossier','Crear evento'=>'event','Familias'=>'families','Planeación'=>'planning','Mesas'=>'tables','Logística'=>'logistics','Finanzas'=>'finance','Regalos'=>'gifts','Recepción'=>'reception','Entrevista'=>'interview'];
    $config = ['basePath'=>$basePath,'wedding'=>$navigation['wedding'],'items'=>collect($navigation['items'])->map(fn ($item) => [...$item,'icon'=>$icons[$item['label']] ?? 'dossier','href'=>$basePath.'/bodas/'.$navigation['wedding']['id'].$paths[$item['route']],'active'=>$activeRoute ? $item['route'] === $activeRoute : request()->routeIs($item['route'])])->values()];
@endphp
<wedding-area-menu data-config='@json($config)'></wedding-area-menu>
<script src="{{ $basePath }}/js/wedding-area-menu.js" defer></script>
