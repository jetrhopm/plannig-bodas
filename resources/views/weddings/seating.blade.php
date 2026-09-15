<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#934e5b">
    <title>Mesas · {{ $wedding->name }}</title>
    <x-theme-assets :base-path="$basePath" />
    <style>
        *{box-sizing:border-box}body{margin:0}.wrap{max-width:900px;margin:auto;padding:24px 5vw 48px}.back{display:inline-flex;align-items:center;gap:8px;color:var(--cb-wine);font-weight:700;text-decoration:none}.back svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2}.section-title{display:flex;gap:20px;align-items:center;padding:28px 0 22px}.hero__icon,.section-icon,.table-icon{display:grid;place-items:center;flex:0 0 auto;border-radius:18px;color:var(--cb-wine);background:#f5e4e1}.hero__icon{width:68px;height:68px}.hero__icon svg{width:38px;height:38px}.section-title small{font-weight:700;letter-spacing:.16em;color:#a35e68}.section-title h1{margin:7px 0;font-size:clamp(2rem,8vw,3.25rem);line-height:1.03}.section-title p{margin:0;color:var(--cb-muted);line-height:1.5}.card{padding:20px;margin:12px 0}.card-head{display:flex;align-items:center;gap:11px}.section-icon{width:40px;height:40px}.section-icon svg{width:23px;height:23px}.card h2{margin:0;font-size:1.45rem}.card form{display:grid;grid-template-columns:1fr 150px auto;gap:10px;margin-top:17px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-top:18px}.table{min-height:156px;padding:18px}.table-top{display:flex;align-items:center;justify-content:space-between;gap:12px}.table-icon{width:42px;height:42px}.table-icon svg{width:25px;height:25px}.table b{display:block;margin-top:14px;font:600 1.45rem 'Playfair Display',Georgia,serif}.occupancy{margin:5px 0 10px;color:var(--cb-muted)}.members{color:#5f504d;font-size:12px;line-height:1.55}.empty{grid-column:1/-1;text-align:center;color:var(--cb-muted)}.line-icon,.table-icon svg{fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}@media(max-width:620px){.section-title{align-items:flex-start}.hero__icon{width:55px;height:55px}.hero__icon svg{width:31px;height:31px}.card form{grid-template-columns:1fr}.card form button{width:100%}}
    </style>
</head>
<body>
    <x-wedding-areas-menu :navigation="$navigation" :base-path="$basePath" active-route="weddings.planning.seating" />
    <main class="wrap">
        <a class="back" href="{{ $basePath }}/bodas/{{ $wedding->id }}/centro"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>Centro de trabajo</a>
        <section class="section-title">
            <span class="hero__icon"><svg class="line-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21v-5a7 7 0 0 1 14 0v5M9 12v9m6-9v9"/></svg></span>
            <div><small>ORGANIZACIÓN DE INVITADOS</small><h1>Mesas y distribución</h1><p>Define capacidad y organiza a cada familia para el gran día.</p></div>
        </section>
        <section class="card">
            <div class="card-head"><span class="section-icon"><svg class="line-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v8m-4-4h8"/></svg></span><h2>Agregar mesa</h2></div>
            <form method="POST" action="{{ $basePath }}/bodas/{{ $wedding->id }}/planeacion/mesas">
                @csrf
                <input name="label" required placeholder="Ej. Mesa 1 · Familia">
                <input name="capacity" type="number" required min="1" placeholder="Capacidad">
                <button><svg viewBox="0 0 24 24"><path d="M12 5v14m-7-7h14"/></svg>Crear mesa</button>
            </form>
        </section>
        <section class="grid">
            @forelse($wedding->tables as $table)
                <article class="table">
                    <div class="table-top"><span class="table-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21v-5a7 7 0 0 1 14 0v5M9 12v9m6-9v9"/></svg></span><span class="occupancy">{{ $table->members->count() }}/{{ $table->capacity }}</span></div>
                    <b>{{ $table->label }}</b><p class="occupancy">lugares ocupados</p>
                    <div class="members">@forelse($table->members as $member)<div>{{ $member->name }}</div>@empty<span>Aún sin invitados asignados.</span>@endforelse</div>
                </article>
            @empty
                <p class="card empty">Aún no hay mesas. Crea la primera para empezar.</p>
            @endforelse
        </section>
    </main>
</body>
</html>
