<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — BatchDesk Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#0F172A', paper: '#F6F8FA', navy: '#13405E', brand: '#0E7490',
                        line: '#E2E8F0', muted: '#64748B', amber: '#B45309', danger: '#B91C1C', pass: '#047857',
                    },
                    fontFamily: { sans: ['Inter','system-ui','sans-serif'], mono: ['"IBM Plex Mono"','monospace'] },
                }
            }
        }
    </script>
    <style>
        body { font-family: Inter, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        .card { background:#fff; border:1px solid #E2E8F0; border-radius:0.625rem; }
        .field { width:100%; border:1px solid #CBD5E1; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:14px; }
        .field:focus { outline:2px solid #0E7490; outline-offset:-1px; border-color:#0E7490; }
        .btn { font-weight:600; border-radius:0.5rem; padding:0.5rem 1rem; font-size:14px; }
        .btn-primary { background:#13405E; color:#fff; }
        .btn-accent { background:#0E7490; color:#fff; }
        .btn-danger { background:#B91C1C; color:#fff; }
        .btn-ghost { border:1px solid #E2E8F0; color:#334155; }
    </style>
</head>
<body class="bg-paper text-ink min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-56 bg-navy text-white min-h-screen flex flex-col shrink-0">
        <div class="px-5 py-4 border-b border-white/10">
            <div class="font-bold text-base">BatchDesk</div>
            <div class="text-cyan-300 text-[11px]">Admin Panel</div>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            @php
                $navItem = fn($route, $label, $icon) =>
                    '<a href="' . route($route) . '" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium ' .
                    (request()->routeIs($route) ? 'bg-white/15 text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white') .
                    '">' . $icon . $label . '</a>';
            @endphp
            {!! $navItem('admin.dashboard', 'Dashboard',
                '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>') !!}
            {!! $navItem('admin.companies', 'All Companies',
                '<svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>') !!}
        </nav>
        <div class="px-5 py-4 border-t border-white/10">
            <div class="text-xs text-slate-400">Signed in as</div>
            <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="text-xs text-slate-400 hover:text-white">Sign out</button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-line px-6 py-3.5 flex items-center justify-between">
            <h1 class="font-semibold text-base">@yield('title', 'Dashboard')</h1>
            <a href="{{ route('dashboard') }}" class="text-sm text-brand font-semibold">← Back to app</a>
        </header>

        @if (session('success'))
        <div class="mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <main class="flex-1 px-6 py-5">
            @yield('content')
        </main>
    </div>

</body>
</html>
