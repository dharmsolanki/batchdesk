<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#13405E">
    <title>@yield('title', 'BatchDesk')</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink:    '#0F172A',
                        paper:  '#F6F8FA',
                        navy:   '#13405E',
                        brand:  '#0E7490',
                        line:   '#E2E8F0',
                        muted:  '#64748B',
                        amber:  '#B45309',
                        danger: '#B91C1C',
                        pass:   '#047857',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>
    <style>
        body { font-family: Inter, system-ui, sans-serif; -webkit-font-smoothing: antialiased; }
        input, select, textarea { font-size: 16px !important; }
        [x-cloak] { display: none !important; }
        .card { background: #fff; border: 1px solid #E2E8F0; border-radius: 0.625rem; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04); }
        .label { display: block; font-size: 0.8125rem; font-weight: 500; color: #334155; margin-bottom: 0.25rem; }
        .field { width: 100%; border: 1px solid #CBD5E1; border-radius: 0.5rem; padding: 0.55rem 0.75rem; background: #fff; }
        .field:focus { outline: 2px solid #0E7490; outline-offset: -1px; border-color: #0E7490; }
        .btn-primary { background: #13405E; color: #fff; font-weight: 600; border-radius: 0.5rem; padding: 0.65rem 1rem; }
        .btn-primary:hover { background: #0F3450; }
        .btn-accent { background: #0E7490; color: #fff; font-weight: 600; border-radius: 0.5rem; padding: 0.65rem 1rem; }
        .btn-accent:hover { background: #0C6379; }
        .section-title { font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #0E7490; }
        @media print { .no-print { display: none !important; } body { background: white !important; padding-bottom: 0 !important; } .card { box-shadow: none; } }
    </style>
</head>
<body class="bg-paper text-ink min-h-screen pb-24">

    <header class="no-print bg-white border-b border-line sticky top-0 z-40">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ auth()->check() ? route('dashboard') : route('landing') }}" class="flex items-center gap-2.5">
                <span class="inline-flex w-8 h-8 rounded-lg bg-navy items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-white" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6m-5 0v6.34L5.42 16.1A2.5 2.5 0 0 0 7.6 20h8.8a2.5 2.5 0 0 0 2.18-3.9L14 9.34V3"/></svg>
                </span>
                <div class="leading-tight">
                    <div class="font-bold text-[15px] tracking-tight">{{ auth()->check() ? auth()->user()->company->name : 'BatchDesk' }}</div>
                    @auth<div class="text-[11px] text-muted">Batch · Billing · COA</div>@endauth
                </div>
            </a>
            @auth
            @if(auth()->user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-brand border border-line rounded-lg px-3 py-1.5 hover:bg-paper">Admin</a>
            @endif
            <a href="{{ route('settings.index') }}" class="text-xs font-medium text-muted border border-line rounded-lg px-3 py-1.5 hover:bg-paper">
                <svg class="w-3.5 h-3.5 inline -mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-xs font-medium text-muted border border-line rounded-lg px-3 py-1.5 hover:bg-paper">Sign out</button>
            </form>
            @endauth
        </div>
    </header>

    @if (session('success'))
        <div class="no-print max-w-3xl mx-auto px-4 mt-3">
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg px-4 py-3 text-sm font-medium flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="no-print max-w-3xl mx-auto px-4 mt-3">
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        </div>
    @endif

    {{-- Trial banner --}}
    @auth
    @if(!auth()->user()->is_admin && auth()->user()->company && auth()->user()->company->isTrialActive())
        @php $days = auth()->user()->company->trialDaysLeft(); @endphp
        @if($days <= 10)
        <div class="no-print max-w-3xl mx-auto px-4 mt-3">
            <div class="bg-amber-50 border border-amber-300 rounded-lg px-4 py-2.5 text-sm flex items-center justify-between gap-3">
                <span class="text-amber font-medium">
                    ⏰ Your free trial ends in <strong>{{ $days }} {{ $days === 1 ? 'day' : 'days' }}</strong>.
                </span>
                <a href="https://wa.me/?text=Hi%2C+I+want+to+subscribe+to+BatchDesk" target="_blank" class="text-xs font-bold text-amber underline whitespace-nowrap">Contact us to subscribe →</a>
            </div>
        </div>
        @endif
    @endif
    @endauth

    <main class="max-w-3xl mx-auto px-4 py-5">
        @yield('content')
    </main>

    @auth
    <nav class="no-print fixed bottom-0 inset-x-0 bg-white border-t border-line z-40">
        <div class="max-w-3xl mx-auto grid grid-cols-5 text-center text-[11px] font-medium">
            @php $active = 'text-brand'; $idle = 'text-muted'; @endphp
            <a href="{{ route('dashboard') }}" class="py-2.5 {{ request()->routeIs('dashboard') ? $active : $idle }}">
                <svg class="w-5 h-5 mx-auto mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Home
            </a>
            <a href="{{ route('batches.index') }}" class="py-2.5 {{ request()->routeIs('batches.*') ? $active : $idle }}">
                <svg class="w-5 h-5 mx-auto mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6m-5 0v6.34L5.42 16.1A2.5 2.5 0 0 0 7.6 20h8.8a2.5 2.5 0 0 0 2.18-3.9L14 9.34V3"/></svg>
                Batches
            </a>
            <a href="{{ route('sales.create') }}" class="py-1 -mt-4">
                <div class="mx-auto w-12 h-12 rounded-xl bg-navy text-white flex items-center justify-center shadow-lg shadow-slate-300">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                </div>
                <span class="{{ request()->routeIs('sales.create') ? $active : $idle }}">Invoice</span>
            </a>
            <a href="{{ route('products.index') }}" class="py-2.5 {{ request()->routeIs('products.*') || request()->routeIs('materials.*') ? $active : $idle }}">
                <svg class="w-5 h-5 mx-auto mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 12h6M9 16h6"/></svg>
                Products
            </a>
            <a href="{{ route('customers.index') }}" class="py-2.5 {{ request()->routeIs('customers.*') || request()->routeIs('sales.index') ? $active : $idle }}">
                <svg class="w-5 h-5 mx-auto mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Customers
            </a>
        </div>
    </nav>
    @endauth

</body>
</html>
