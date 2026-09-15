<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#9b5663">
    <title>{{ $wedding->name }} · Casa de Bodas</title>
    <x-theme-assets :base-path="$basePath" />
    <style>*{box-sizing:border-box}body{margin:0;background:#fbf7f3;color:#392d2c;font:14px 'DM Sans',Arial}.next{max-width:900px;margin:0 auto;padding:0 5vw 60px}.next h2{margin:6px 0 13px;font:28px 'Playfair Display',Georgia}.eyebrow{font-size:10px;letter-spacing:.22em;color:#a35e68;font-weight:600}.event{display:flex;gap:14px;align-items:center;padding:12px;border:1px solid #ead8d0;border-radius:18px;background:#fffdfa}.event img{width:110px;height:82px;object-fit:cover;border-radius:12px}.event b,.event small{display:block}.event small{margin-top:5px;color:#77635e}</style>
</head>
<body>
    <x-wedding-areas-menu :navigation="$navigation" :base-path="$basePath" active-route="weddings.workspace" />
    @php($event=$wedding->events->first())
    <section class="next"><p class="eyebrow">EVENTO PRÓXIMO</p><h2>{{ $event?->name ?: 'Evento por definir' }}</h2><div class="event"><img src="{{ $basePath }}/images/casa-de-bodas-hero.png" alt="Decoración de boda"><div><b>{{ $event?->venue ?: 'Lugar pendiente' }}</b><small>{{ $event?->event_date?->translatedFormat('d \d\e F Y') ?: 'Fecha pendiente' }}</small><small>{{ $event?->event_time ?: 'Hora pendiente' }}</small></div></div></section>
</body>
</html>
