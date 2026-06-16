<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="BatchDesk — batch production, GST invoicing and QR-verified Certificates of Analysis in one affordable tool for small manufacturers.">
    <title>BatchDesk — Batch · Billing · QR-Verified COA for Small Manufacturers</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink: '#0F172A',
                        paper: '#F6F8FA',
                        navy: '#13405E',
                        brand: '#0E7490',
                        line: '#E2E8F0',
                        muted: '#64748B',
                        pass: '#047857',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'monospace']
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: Inter, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .btn-primary {
            background: #13405E;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        .btn-primary:hover {
            background: #0F3450;
        }

        .btn-accent {
            background: #0E7490;
            color: #fff;
            font-weight: 600;
            border-radius: 0.5rem;
        }

        .btn-accent:hover {
            background: #0C6379;
        }
    </style>
</head>

<body class="bg-white text-ink">

    <!-- ===== NAV ===== -->
    <header class="border-b border-line sticky top-0 bg-white/95 backdrop-blur z-50">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5">
                <span class="inline-flex w-9 h-9 rounded-lg bg-navy items-center justify-center">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M9 3h6m-5 0v6.34L5.42 16.1A2.5 2.5 0 0 0 7.6 20h8.8a2.5 2.5 0 0 0 2.18-3.9L14 9.34V3" />
                    </svg>
                </span>
                <span class="font-bold text-lg tracking-tight">BatchDesk</span>
            </a>
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-muted">
                <a href="#how-it-works" class="hover:text-ink">How it works</a>
                <a href="#features" class="hover:text-ink">Features</a>
                <a href="#comparison" class="hover:text-ink">vs. Others</a>
                <a href="#pricing" class="hover:text-ink">Pricing</a>
            </nav>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('login') }}"
                    class="text-sm font-semibold px-4 py-2 rounded-lg border border-line hover:bg-paper">Sign in</a>
                <a href="{{ route('register') }}" class="btn-accent text-sm px-4 py-2">Start free trial</a>
            </div>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="bg-paper border-b border-line">
        <div class="max-w-6xl mx-auto px-4 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div
                    class="inline-flex items-center gap-2 text-[12px] font-semibold text-brand bg-cyan-50 border border-cyan-200 rounded-full px-3 py-1 mb-5">
                    Built for small manufacturers in India
                </div>
                <h1 class="font-extrabold text-4xl md:text-5xl tracking-tight leading-[1.1]">
                    Batch production, GST billing &amp; quality certificates —
                    <span class="text-brand">in one tool.</span>
                </h1>
                <p class="text-muted text-lg mt-5 leading-relaxed">
                    Stop juggling a production register, a billing app and Word templates.
                    BatchDesk records every batch, prints batch numbers on your GST invoices,
                    and generates a <strong class="text-ink">QR-verified Certificate of Analysis</strong> in one click.
                </p>
                <div class="flex flex-wrap items-center gap-3 mt-7">
                    <a href="{{ route('register') }}" class="btn-primary px-6 py-3.5 text-base">Start 30-day free
                        trial</a>
                    <a href="#how-it-works"
                        class="px-6 py-3.5 text-base font-semibold border border-line rounded-lg hover:bg-white">See how
                        it works</a>
                </div>
                <div class="text-[13px] text-muted mt-4">No credit card required · Set up in 10 minutes · Works on any
                    phone or computer</div>
            </div>

            <!-- COA mock visual -->
            <div class="relative">
                <div class="bg-white border border-line rounded-xl shadow-xl shadow-slate-200 p-6">
                    <div class="text-center border-b-2 border-navy pb-3">
                        <div class="font-bold text-navy">Shree Chem Industries</div>
                        <div class="text-[10px] text-muted">Ahmedabad · GSTIN: 24XXXXX · Lic: GJ-1234</div>
                        <div class="font-bold text-[11px] mt-2 tracking-[0.25em] text-brand uppercase">Certificate of
                            Analysis</div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-0.5 text-[11px] py-2.5 border-b border-line">
                        <div class="flex justify-between"><span class="text-muted">Product</span><span
                                class="font-semibold">Citric Acid IP</span></div>
                        <div class="flex justify-between"><span class="text-muted">Batch</span><span
                                class="font-mono font-semibold">B-260612-01</span></div>
                        <div class="flex justify-between"><span class="text-muted">Mfg</span><span
                                class="font-semibold">12 Jun 2026</span></div>
                        <div class="flex justify-between"><span class="text-muted">Exp</span><span
                                class="font-semibold">11 Jun 2028</span></div>
                    </div>
                    <table class="w-full text-[11px] mt-2.5">
                        <thead>
                            <tr class="text-left text-[9px] uppercase text-white bg-navy">
                                <th class="py-1.5 px-2">Parameter</th>
                                <th class="py-1.5 px-2">Spec</th>
                                <th class="py-1.5 px-2">Result</th>
                                <th class="py-1.5 px-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-line">
                                <td class="py-1.5 px-2 font-medium">Assay</td>
                                <td class="px-2 text-muted">99.5–100.5%</td>
                                <td class="px-2 font-semibold">99.8%</td>
                                <td class="px-2 text-pass font-bold text-[10px]">PASS</td>
                            </tr>
                            <tr class="border-b border-line">
                                <td class="py-1.5 px-2 font-medium">pH (1% sol.)</td>
                                <td class="px-2 text-muted">1.8–2.2</td>
                                <td class="px-2 font-semibold">2.0</td>
                                <td class="px-2 text-pass font-bold text-[10px]">PASS</td>
                            </tr>
                            <tr class="border-b border-line">
                                <td class="py-1.5 px-2 font-medium">Moisture</td>
                                <td class="px-2 text-muted">NMT 0.5%</td>
                                <td class="px-2 font-semibold">0.2%</td>
                                <td class="px-2 text-pass font-bold text-[10px]">PASS</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex items-end justify-between mt-4">
                        <div class="text-[10px]">
                            <div class="font-semibold">R. Patel</div>
                            <div class="border-t border-slate-400 w-24 text-muted">Approved by</div>
                        </div>
                        <div class="text-center">
                            <svg class="w-14 h-14 border border-line rounded p-1" viewBox="0 0 29 29">
                                <rect width="29" height="29" fill="#fff" />
                                <path fill="#0F172A"
                                    d="M0 0h7v7H0zM2 2h3v3H2zM9 0h2v2H9zM13 0h2v3h-2zM17 1h2v2h-2zM22 0h7v7h-7zM24 2h3v3h-3zM9 4h3v2H9zM16 4h2v3h-2zM0 9h2v2H0zM4 9h3v2H4zM9 9h2v3H9zM13 8h3v3h-3zM18 9h3v2h-3zM24 9h2v2h-2zM27 9h2v3h-2zM1 13h3v2H1zM6 13h2v3H6zM11 13h3v2h-3zM16 13h2v2h-2zM20 13h3v3h-3zM25 13h2v2h-2zM0 17h2v3H0zM4 17h3v2H4zM9 17h3v3H9zM14 18h3v2h-3zM19 17h2v2h-2zM23 17h3v3h-3zM27 18h2v2h-2zM0 22h7v7H0zM2 24h3v3H2zM9 22h2v3H9zM13 21h2v3h-2zM16 23h3v2h-3zM22 22h2v2h-2zM25 22h4v2h-4zM21 25h3v2h-3zM25 26h3v3h-3zM13 26h3v3h-3zM9 27h2v2H9z" />
                            </svg>
                            <div class="text-[8px] text-muted mt-0.5">Scan to verify</div>
                        </div>
                    </div>
                </div>
                <div
                    class="absolute -bottom-4 -right-3 bg-emerald-50 border border-emerald-300 rounded-lg px-3.5 py-2 shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4 text-pass" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>
                    <span class="text-[12px] font-bold text-pass">Certificate Verified</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROBLEM ===== -->
    <section class="max-w-6xl mx-auto px-4 py-16 md:py-20">
        <div class="text-center max-w-2xl mx-auto">
            <h2 class="font-bold text-3xl tracking-tight">The old way is costing you hours every day</h2>
            <p class="text-muted mt-3">If you manufacture in batches — pharma, ayurvedic, cosmetics, food, chemicals —
                this probably looks familiar.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-5 mt-10">
            <div class="border border-line rounded-xl p-6">
                <div class="font-semibold text-lg">Production lives in registers &amp; Excel</div>
                <p class="text-sm text-muted mt-2 leading-relaxed">Batch records, raw material lots and expiry dates
                    are scattered across notebooks and spreadsheets. When an auditor or buyer asks "which lots went into
                    this batch?", it takes hours to answer.</p>
            </div>
            <div class="border border-line rounded-xl p-6">
                <div class="font-semibold text-lg">COAs are typed by hand in Word</div>
                <p class="text-sm text-muted mt-2 leading-relaxed">Industry studies show a single Certificate of
                    Analysis takes 30–90 minutes to prepare manually — copying results into templates, double-checking
                    numbers, fixing formatting. Every. Single. Batch.</p>
            </div>
            <div class="border border-line rounded-xl p-6">
                <div class="font-semibold text-lg">Billing software doesn't know batches</div>
                <p class="text-sm text-muted mt-2 leading-relaxed">Generic billing apps can't track batch numbers,
                    expiry or test results — so the same data gets typed twice, and invoices go out without the batch
                    traceability your buyers expect.</p>
            </div>
        </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section id="how-it-works" class="bg-paper border-y border-line">
        <div class="max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="font-bold text-3xl tracking-tight">From production to certificate in under 2 minutes</h2>
                <p class="text-muted mt-3">Set up your product once. After that, every batch follows the same simple
                    flow.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-5 mt-12">
                @php $steps = [['1', 'Define your product & specification', 'Create the product and list its quality parameters once — pH, Assay, Moisture, whatever applies. This becomes the template for every COA.'], ['2', 'Receive raw material lots', 'Log supplier lots with quantities and expiry. Takes seconds, and builds your traceability chain automatically.'], ['3', 'Create a batch', 'Enter batch number, quantity and dates, and link the material lots consumed. The batch starts in Testing status.'], ['4', 'Enter test results', 'The specification is pre-loaded — just type the results and mark Pass/Fail. All passing? The batch is auto-released.'], ['5', 'Generate the COA', 'One click produces a professional certificate with your letterhead, results table, signatories and a verification QR code.'], ['6', 'Invoice with batch numbers', 'Select released batches, and the GST invoice prints batch numbers automatically. Send invoice + COA links on WhatsApp together.']]; @endphp
                @foreach ($steps as $s)
                    <div class="bg-white border border-line rounded-xl p-6">
                        <div
                            class="w-8 h-8 rounded-lg bg-navy text-white font-bold flex items-center justify-center text-sm">
                            {{ $s[0] }}</div>
                        <div class="font-semibold mt-3">{{ $s[1] }}</div>
                        <p class="text-sm text-muted mt-1.5 leading-relaxed">{{ $s[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== QR VERIFICATION ===== -->
    <section id="verification" class="max-w-6xl mx-auto px-4 py-16 md:py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div
                    class="inline-flex items-center gap-2 text-[12px] font-semibold text-brand bg-cyan-50 border border-cyan-200 rounded-full px-3 py-1 mb-4">
                    The BatchDesk difference</div>
                <h2 class="font-bold text-3xl tracking-tight">Certificates your buyers can actually trust</h2>
                <p class="text-muted mt-4 leading-relaxed">
                    Forged and copy-pasted COAs are a documented problem in the industry — regulators have
                    issued warning letters to suppliers for exactly this. A printed certificate can be
                    edited by anyone with Word.
                </p>
                <p class="text-muted mt-3 leading-relaxed">
                    Every BatchDesk COA carries a unique QR code. When your buyer scans it, they see the
                    original test results on a secure verification page — issued by your company, with
                    timestamps. <strong class="text-ink">If a certificate has been altered, the mismatch is instantly
                        visible.</strong>
                </p>
                <ul class="mt-5 space-y-2.5 text-sm">
                    <li class="flex gap-2.5"><svg class="w-5 h-5 text-pass shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg><span><strong>Win buyer confidence</strong> — verifiable quality builds repeat
                            business</span></li>
                    <li class="flex gap-2.5"><svg class="w-5 h-5 text-pass shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg><span><strong>Stand out from competitors</strong> still sending plain Word
                            documents</span></li>
                    <li class="flex gap-2.5"><svg class="w-5 h-5 text-pass shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg><span><strong>No extra work</strong> — the QR is added automatically to every
                            certificate</span></li>
                </ul>
            </div>
            <div class="bg-navy rounded-2xl p-8 text-white">
                <div class="text-[11px] uppercase tracking-[0.2em] text-cyan-300 font-bold">How verification works
                </div>
                <div class="mt-5 space-y-4">
                    <div class="flex gap-4 items-start">
                        <div
                            class="w-7 h-7 rounded-full bg-white/15 flex items-center justify-center text-sm font-bold shrink-0">
                            1</div>
                        <div>
                            <div class="font-semibold">You generate a COA</div>
                            <div class="text-sm text-slate-300">A unique, unguessable verification link is created for
                                the batch.</div>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div
                            class="w-7 h-7 rounded-full bg-white/15 flex items-center justify-center text-sm font-bold shrink-0">
                            2</div>
                        <div>
                            <div class="font-semibold">Your buyer scans the QR</div>
                            <div class="text-sm text-slate-300">On any phone camera — no app or login needed.</div>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div
                            class="w-7 h-7 rounded-full bg-white/15 flex items-center justify-center text-sm font-bold shrink-0">
                            3</div>
                        <div>
                            <div class="font-semibold">They see the original record</div>
                            <div class="text-sm text-slate-300">Product, batch, results and release status — straight
                                from your account, impossible to forge on paper.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES ===== -->
    <section id="features" class="bg-paper border-y border-line">
        <div class="max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="font-bold text-3xl tracking-tight">Everything a small manufacturer needs. Nothing you don't.
                </h2>
                <p class="text-muted mt-3">Big ERPs take months to implement and cost lakhs. BatchDesk works on day
                    one.</p>
            </div>
            <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-5 mt-10">
                @php$features = [
                        [
                            'Batch-wise inventory',
                            'Every batch tracked with quantity, manufacturing and expiry dates. Only quality-released stock can be sold — enforced automatically.',
                        ],
                        [
                            'Raw material traceability',
                            'Supplier lot → batch → customer. The full chain is recorded, so recall questions are answered in seconds, not days.',
                        ],
                        [
                            'One-click COA',
                            'Professional certificates with your letterhead, specification table, results, signatories and verification QR.',
                        ],
                        [
                            'GST invoicing',
                            'CGST/SGST auto-split, batch numbers printed on every invoice, HSN codes, customer GSTIN — audit-ready.',
                        ],
                        [
                            'Split & credit payments',
                            'Cash + UPI + bank on the same invoice, partial payments, and a live view of every outstanding balance.',
                        ],
                        [
                            'WhatsApp sharing',
                            'Send the invoice and COA verification links to your buyer in one tap — no printing, no scanning, no email attachments.',
                        ],
                        [
                            'Expiry alerts',
                            'The dashboard warns you 45 days before any batch expires, so stock moves before it becomes a write-off.',
                        ],
                        [
                            'Works everywhere',
                            'Mobile-first web app — use it at the factory, in the office or on the road. No installation required.',
                        ],
                        [
                            'Multi-user ready',
                            'Your QC person enters results, your accountant raises invoices — everyone works in the same system.',
                        ],
                ]; @endphp
                @foreach ($features as $f)
                    <div class="bg-white border border-line rounded-xl p-5">
                        <div class="font-semibold">{{ $f[0] }}</div>
                        <p class="text-sm text-muted mt-1.5 leading-relaxed">{{ $f[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== WHO IT'S FOR ===== -->
    <section class="bg-paper border-y border-line">
        <div class="max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="font-bold text-3xl tracking-tight">Is BatchDesk right for you?</h2>
                <p class="text-muted mt-3">We built this for one specific type of business. Read this before you sign
                    up.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-10">
                {{-- FOR --}}
                <div class="bg-white border border-emerald-200 rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span
                            class="w-7 h-7 rounded-full bg-emerald-100 text-pass flex items-center justify-center font-bold text-sm">✓</span>
                        <h3 class="font-bold text-lg text-pass">BatchDesk is built for you if...</h3>
                    </div>
                    <ul class="space-y-3 text-sm">
                        @php $forItems = [['You <strong>manufacture</strong> your own products', 'Soap, chemicals, ayurvedic, cosmetics, food items — anything you make yourself in-house.'], ['Your buyers ask for a <strong>Certificate of Analysis</strong>', 'Pharma distributors, export buyers, supermarkets — they want proof that your batch was tested before they accept it.'], ['You track <strong>batch numbers and expiry dates</strong>', 'Your invoice needs to say which batch went to which customer, and when it expires.'], ['You are a <strong>small or mid-size manufacturer</strong>', '5 to 100 employees. You can\'t afford a ₹10 lakh ERP, but you\'ve outgrown Excel and WhatsApp.'], ['Your team uses <strong>phones more than computers</strong>', 'The factory floor runs on mobile. Your software should too.']]; @endphp
                        @foreach ($forItems as $item)
                            <li class="flex gap-3">
                                <svg class="w-4 h-4 text-pass shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                <span><span class="font-medium">{!! $item[0] !!}</span> —
                                    {{ $item[1] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- NOT FOR --}}
                <div class="bg-white border border-line rounded-2xl p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span
                            class="w-7 h-7 rounded-full bg-slate-100 text-muted flex items-center justify-center font-bold text-sm">✕</span>
                        <h3 class="font-bold text-lg text-ink">BatchDesk is NOT for you if...</h3>
                    </div>
                    <ul class="space-y-3 text-sm">
                        @php $notForItems = [['You are a <strong>trader or distributor</strong>', 'You buy ready-made products from manufacturers and resell them. You need a distribution or wholesale software — not BatchDesk.'], ['You run a <strong>retail pharmacy or medical store</strong>', 'You dispense medicines to patients. BatchDesk doesn\'t handle prescriptions, retail POS or drug schedules.'], ['You need a <strong>full ERP</strong> with HR, payroll and accounts', 'BatchDesk covers production, quality and billing. If you need a complete enterprise system, look at PharmaCloud or SAP.'], ['You manufacture <strong>without any quality testing</strong>', 'BatchDesk\'s value comes from the QR-verified COA. If your buyers never ask for certificates, the tool is overkill for you.']]; @endphp
                        @foreach ($notForItems as $item)
                            <li class="flex gap-3">
                                <svg class="w-4 h-4 text-muted shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M18 6 6 18M6 6l12 12" />
                                </svg>
                                <span><span class="font-medium">{!! $item[0] !!}</span> —
                                    {{ $item[1] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Industry pills --}}
            <div class="text-center">
                <div class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Industries we serve</div>
                <div class="flex flex-wrap justify-center gap-2.5">
                    @foreach (['Pharmaceutical & API', 'Ayurvedic & Herbal', 'Cosmetics & Personal Care', 'Food & FSSAI', 'Specialty Chemicals', 'Soaps & Detergents', 'Nutraceuticals', 'Paints & Coatings', 'Essential Oils', 'Agro Chemicals'] as $industry)
                        <span
                            class="text-sm font-medium border border-line rounded-full px-4 py-1.5 bg-white">{{ $industry }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- ===== COMPARISON ===== -->
    <section id="comparison" class="max-w-6xl mx-auto px-4 py-16 md:py-20">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="font-bold text-3xl tracking-tight">How BatchDesk compares to existing software</h2>
            <p class="text-muted mt-3">Most pharma software in India is built for traders, distributors or large
                enterprises. BatchDesk is built for the manufacturer who actually makes the product.</p>
        </div>

        {{-- Key difference callout --}}
        <div class="bg-navy text-white rounded-2xl p-6 mb-8 grid md:grid-cols-3 gap-6 text-center">
            <div class="border-r border-white/10 pr-6">
                <div class="text-2xl font-bold">DrugPlus / PdisPlus</div>
                <div class="text-slate-300 text-sm mt-1">(Soham ERP)</div>
                <div class="mt-3 text-sm leading-relaxed text-slate-300">For <strong class="text-white">traders &amp;
                        distributors</strong> who buy ready-made pharma products and resell them. No production, no
                    testing, no COA.</div>
            </div>
            <div class="border-r border-white/10 px-6">
                <div class="text-2xl font-bold">PharmaCloud</div>
                <div class="text-slate-300 text-sm mt-1">(Soham ERP)</div>
                <div class="mt-3 text-sm leading-relaxed text-slate-300">For <strong class="text-white">mid-to-large
                        manufacturers</strong>. Costs ₹17 lakh+ to set up. Requires IT team. Takes months to implement.
                </div>
            </div>
            <div class="pl-6">
                <div class="text-2xl font-bold text-cyan-300">BatchDesk</div>
                <div class="text-cyan-200 text-sm mt-1">That's us</div>
                <div class="mt-3 text-sm leading-relaxed text-slate-300">For <strong class="text-white">small
                        manufacturers</strong> who make their own products. COA included. ₹1,499/month. Ready in 10
                    minutes.</div>
            </div>
        </div>

        {{-- Feature comparison table --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-paper border-b border-line text-left">
                            <th class="px-5 py-3.5 font-semibold w-1/3">Feature</th>
                            <th class="px-5 py-3.5 font-semibold text-center">DrugPlus<br><span
                                    class="text-[11px] font-normal text-muted">Trader software</span></th>
                            <th class="px-5 py-3.5 font-semibold text-center">PharmaCloud<br><span
                                    class="text-[11px] font-normal text-muted">Large ERP</span></th>
                            <th class="px-5 py-3.5 font-semibold text-center bg-cyan-50 text-brand">BatchDesk<br><span
                                    class="text-[11px] font-normal text-brand/70">Small mfr. tool</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rows = [
                                [
                                    'Who it serves',
                                    'Bulk drug traders',
                                    'Mid-large pharma companies',
                                    'Small manufacturers (5–100 staff)',
                                ],
                                ['Batch production entry', false, true, true],
                                ['Raw material traceability', false, true, true],
                                ['Test results entry', false, true, true],
                                ['Certificate of Analysis', false, true, true],
                                ['QR-verified COA', false, false, true],
                                ['GST billing', true, true, true],
                                ['Mobile app / PWA', false, false, true],
                                ['Self sign-up online', false, false, true],
                                ['Setup time', 'Days (sales call needed)', 'Months + IT team', '10 minutes'],
                                ['Starting price', 'Call for pricing', '₹17 lakh+ setup', '₹1,499 / month'],
                            ];
                        @endphp
                        @foreach ($rows as $i => $row)
                            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-paper/50' }} border-b border-line/60">
                                <td class="px-5 py-3 font-medium text-ink">{{ $row[0] }}</td>
                                @foreach ([$row[1], $row[2], $row[3]] as $j => $val)
                                    <td class="px-5 py-3 text-center {{ $j === 2 ? 'bg-cyan-50/50' : '' }}">
                                        @if ($val === true)
                                            <svg class="w-5 h-5 text-pass mx-auto" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>
                                        @elseif ($val === false)
                                            <svg class="w-5 h-5 text-muted/40 mx-auto" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 6 6 18M6 6l12 12" />
                                            </svg>
                                        @else
                                            <span
                                                class="{{ $j === 2 ? 'font-semibold text-brand' : 'text-muted' }}">{{ $val }}</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 bg-paper text-[11px] text-muted">
                DrugPlus and PharmaCloud are products of Soham ERP Solutions Pvt. Ltd., Ahmedabad. Comparison based on
                publicly available information. BatchDesk is not affiliated with Soham ERP.
            </div>
        </div>

        <div class="mt-8 bg-amber-50 border border-amber-200 rounded-xl p-5 text-sm">
            <div class="font-semibold text-amber mb-1">The gap no one is filling</div>
            <p class="text-slate-700 leading-relaxed">A small soap, chemical or ayurvedic manufacturer in India has two
                options today: spend lakhs on an ERP meant for 500-person companies, or continue typing COAs manually in
                Word every day. BatchDesk exists because neither option is acceptable. You deserve a tool built
                specifically for you — at a price that makes sense.</p>
        </div>
    </section>

    <!-- ===== FREE COA OBJECTION ===== -->
    <section class="max-w-6xl mx-auto px-4 pb-10">
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-7 grid md:grid-cols-2 gap-8 items-center">
            <div>
                <div class="text-xs font-bold text-muted uppercase tracking-widest mb-3">Common question</div>
                <h3 class="font-bold text-2xl tracking-tight text-ink">Why not just use a free COA tool?</h3>
                <p class="text-muted mt-3 text-sm leading-relaxed">
                    Free COA generators create the certificate — but they don't track your batches,
                    link to your GST invoices, or let your buyers verify authenticity with a QR scan.
                </p>
                <p class="text-muted mt-2 text-sm leading-relaxed">
                    You'd still need a separate billing app and a manual production register.
                    That's three different tools, three places to enter the same data.
                </p>
            </div>
            <div class="bg-white border border-line rounded-xl p-5 space-y-3">
                <div class="text-xs font-bold text-muted uppercase tracking-wide mb-1">Free COA tool + Billing app
                </div>
                <div class="flex justify-between text-sm border-b border-line pb-2">
                    <span class="text-muted">Free COA generator</span>
                    <span class="font-semibold">₹0 – ₹3,000/year</span>
                </div>
                <div class="flex justify-between text-sm border-b border-line pb-2">
                    <span class="text-muted">Separate billing app (Vyapar etc.)</span>
                    <span class="font-semibold">₹1,500/year</span>
                </div>
                <div class="flex justify-between text-sm border-b border-line pb-2">
                    <span class="text-muted">Manual batch register (time cost)</span>
                    <span class="font-semibold text-danger">30–90 min/batch</span>
                </div>
                <div class="flex justify-between text-sm border-b border-line pb-2">
                    <span class="text-muted">QR verification for buyers</span>
                    <span class="font-semibold text-danger">Not available</span>
                </div>
                <div class="pt-1">
                    <div class="text-xs font-bold text-muted uppercase tracking-wide mb-2">BatchDesk — everything above
                        replaced</div>
                    <div class="flex justify-between text-sm">
                        <span class="font-semibold text-ink">All in one tool</span>
                        <span class="font-bold text-brand">₹1,499/month</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRICING ===== -->
    <section id="pricing" class="bg-paper border-y border-line">
        <div class="max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="font-bold text-3xl tracking-tight">Simple, honest pricing</h2>
                <p class="text-muted mt-3">One plan. Every feature. A fraction of what a LIMS or ERP costs.</p>
            </div>
            <div
                class="max-w-md mx-auto mt-10 bg-white border-2 border-brand rounded-2xl p-8 text-center shadow-lg shadow-cyan-100">
                <div class="text-[11px] uppercase tracking-[0.2em] text-brand font-bold">BatchDesk Standard</div>
                <div class="mt-3"><span class="font-extrabold text-5xl tracking-tight">₹1,499</span><span
                        class="text-muted">/month</span></div>
                <div class="text-sm text-muted mt-1">or ₹14,999/year — 2 months free</div>
                <ul class="text-sm text-left mt-6 space-y-2.5">
                    @foreach (['Unlimited batches, COAs & invoices', 'Unlimited products & customers', 'QR verification included', 'Up to 5 team members', 'WhatsApp sharing', 'All future updates'] as $item)
                        <li class="flex gap-2.5"><svg class="w-5 h-5 text-pass shrink-0" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>{{ $item }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="btn-primary block w-full py-3.5 mt-7 text-base">Start 30-day
                    free trial</a>
                <div class="text-[12px] text-muted mt-3">No credit card required. Cancel anytime.</div>
            </div>
            <p class="text-center text-sm text-muted mt-6">For comparison: a typical LIMS starts at ₹10,000+/month, and
                manufacturing ERPs cost lakhs to implement.</p>
        </div>
    </section>

    <!-- ===== FAQ ===== -->
    <section class="max-w-3xl mx-auto px-4 py-16">
        <h2 class="font-bold text-3xl tracking-tight text-center">Frequently asked questions</h2>
        <div class="mt-8 space-y-3">
            @php$faqs = [
                    [
                        'Do I need any technical knowledge to use BatchDesk?',
                        'No. If you can use WhatsApp, you can use BatchDesk. Setup takes about 10 minutes: enter your company details, add a product with its specification, and you are ready to create your first batch.',
                    ],
                    [
                        'What exactly is a Certificate of Analysis (COA)?',
                        'A COA is a document proving that a specific batch was tested and meets its quality specifications. Buyers in pharma, food, chemicals and cosmetics routinely require a COA with every batch they purchase.',
                    ],
                    [
                        'Can my buyers verify a COA without an account?',
                        'Yes. The QR code on every certificate opens a public verification page — any phone camera works, no app or login needed.',
                    ],
                    [
                        'Does it work on mobile?',
                        'Yes. BatchDesk is mobile-first — it works in any browser on any phone, tablet or computer, and can be added to your home screen like an app.',
                    ],
                    [
                        'What happens after the free trial?',
                        'You can subscribe for ₹1,499/month (or ₹14,999/year). Your data stays intact. If you choose not to continue, you can export your records first.',
                    ],
                    [
                        'Is my data safe and private?',
                        'Each company\'s data is fully isolated — your products, batches, customers and prices are visible only to your team members.',
                    ],
            ]; @endphp
            @foreach ($faqs as $faq)
                <details class="border border-line rounded-xl px-5 py-4 bg-white group">
                    <summary class="font-semibold cursor-pointer list-none flex justify-between items-center">
                        {{ $faq[0] }}
                        <span
                            class="text-muted group-open:rotate-45 transition-transform text-xl leading-none">+</span>
                    </summary>
                    <p class="text-sm text-muted mt-3 leading-relaxed">{{ $faq[1] }}</p>
                </details>
            @endforeach
        </div>
    </section>

    <!-- ===== FINAL CTA ===== -->
    <section class="bg-navy text-white">
        <div class="max-w-6xl mx-auto px-4 py-16 text-center">
            <h2 class="font-bold text-3xl md:text-4xl tracking-tight">Your next batch deserves better than Excel and
                Word.</h2>
            <p class="text-slate-300 mt-3 max-w-xl mx-auto">Create your company account, set up your first product, and
                generate a QR-verified COA today — free for 30 days.</p>
            <div class="flex justify-center gap-3 mt-8">
                <a href="{{ route('register') }}" class="btn-accent px-7 py-3.5 text-base">Start free trial</a>
                <a href="{{ route('login') }}"
                    class="px-7 py-3.5 text-base font-semibold border border-white/30 rounded-lg hover:bg-white/10">Sign
                    in</a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="border-t border-line">
        <div
            class="max-w-6xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-muted">
            <div class="flex items-center gap-2">
                <span class="inline-flex w-6 h-6 rounded bg-navy items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M9 3h6m-5 0v6.34L5.42 16.1A2.5 2.5 0 0 0 7.6 20h8.8a2.5 2.5 0 0 0 2.18-3.9L14 9.34V3" />
                    </svg>
                </span>
                <span class="font-semibold text-ink">BatchDesk</span>
                <span>· Batch · Billing · COA</span>
            </div>
            <div class="flex gap-6">
                <a href="#how-it-works" class="hover:text-ink">How it works</a>
                <a href="#pricing" class="hover:text-ink">Pricing</a>
                <a href="{{ route('login') }}" class="hover:text-ink">Sign in</a>
            </div>
            <div>© {{ date('Y') }} BatchDesk</div>
        </div>
    </footer>

</body>

</html>
