<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Mesas · {{ $wedding->name }}</title>
    <style>
        :root{color:#3e302e;background:#fbf7f3;font-family:Arial,sans-serif}*{box-sizing:border-box}body{margin:0;background:radial-gradient(circle at 92% 10%,#f7e7e3 0,transparent 30rem),#fbf7f3}.wrap{max-width:940px;margin:auto;padding:24px max(1rem,5vw) 48px}.back{display:inline-flex;align-items:center;gap:8px;color:#934e5b;font-weight:700;text-decoration:none}.back svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2}.hero{display:flex;gap:20px;align-items:center;padding:28px 0 22px}.hero__icon,.section-icon,.table-icon{display:grid;place-items:center;flex:0 0 auto;border-radius:18px;color:#934e5b;background:#f5e4e1}.hero__icon{width:68px;height:68px}.hero__icon svg{width:38px;height:38px}.hero small{font-weight:700;letter-spacing:.16em;color:#a35e68}.hero h1{margin:7px 0;font:600 clamp(2rem,8vw,3.25rem)/1.03 Georgia,serif}.hero p{margin:0;color:#766864;line-height:1.5}.card{padding:20px;margin:12px 0;border:1px solid #ead8d0;border-radius:22px;background:#fffdfa;box-shadow:0 10px 25px #68443c0b}.card-head{display:flex;align-items:center;gap:11px}.section-icon{width:40px;height:40px}.section-icon svg{width:23px;height:23px}.card h2{margin:0;font:600 1.45rem Georgia,serif}.card form{display:grid;grid-template-columns:1fr 150px auto;gap:10px;margin-top:17px}input{width:100%;padding:12px;border:1px solid #ddcdc5;border-radius:12px;background:#fffdfb;color:#3e302e;font:14px Arial}input:focus{outline:2px solid #d49aa2;border-color:transparent}button{display:inline-flex;align-items:center;justify-content:center;gap:7px;border:0;border-radius:999px;padding:11px 16px;background:linear-gradient(135deg,#934d5d,#cf7880);color:#fff;font-weight:700;cursor:pointer}button svg{width:17px;height:17px;fill:none;stroke:currentColor;stroke-width:2}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;margin-top:18px}.table{min-height:156px;padding:18px;border:1px solid #ead8d0;border-radius:20px;background:#fffdfa;box-shadow:0 8px 20px #68443c0b}.table-top{display:flex;align-items:center;justify-content:space-between;gap:12px}.table-icon{width:42px;height:42px}.table-icon svg{width:25px;height:25px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}.table b{display:block;margin-top:14px;font:600 1.45rem Georgia,serif}.occupancy{margin:5px 0 10px;color:#8b7771}.members{color:#5f504d;font-size:12px;line-height:1.55}.empty{grid-column:1/-1;text-align:center;color:#766864}.line-icon{fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}@media(max-width:620px){.hero{align-items:flex-start}.hero__icon{width:55px;height:55px}.hero__icon svg{width:31px;height:31px}.card form{grid-template-columns:1fr}.card form button{width:100%}}
    </style>
</head>
<body>
    <main class="wrap">
        <a class="back" href="{{ $basePath }}/bodas/{{ $wedding->id }}/centro"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>Centro de trabajo</a>
        <section class="hero">
            <span class="hero__icon"><svg class="line-icon" viewBox="0 0 24 24"><path d="M4 10h16M7 10v10m10-10v10M10 4h4v6h-4z"/></svg></span>
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
                    <div class="table-top"><span class="table-icon"><svg viewBox="0 0 24 24"><path d="M4 10h16M7 10v10m10-10v10M10 4h4v6h-4z"/></svg></span><span class="occupancy">{{ $table->members->count() }}/{{ $table->capacity }}</span></div>
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
