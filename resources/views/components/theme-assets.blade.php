@props(['basePath' => rtrim(request()->getBaseUrl(), '/')])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
<link href="{{ $basePath }}/css/casa-bodas-theme.css?v={{ filemtime(public_path('css/casa-bodas-theme.css')) }}" rel="stylesheet">
