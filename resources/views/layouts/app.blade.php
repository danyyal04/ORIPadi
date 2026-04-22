<!DOCTYPE html>
<html lang="ms" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#1a4731" />
    <meta name="description"
        content="ORIPadi — Instant AI-powered padi disease diagnosis for Malaysian farmers. No account needed." />
    <meta name="keywords" content="padi, rice, disease, Malaysia, AI, diagnosis, pertanian" />

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json" />
    <link rel="apple-touch-icon" href="/icons/icon-192.png" />

    {{-- Open Graph --}}
    <meta property="og:title" content="ORIPadi — Instant Padi Health Diagnosis" />
    <meta property="og:description"
        content="AI-driven padi disease diagnosis. No account needed — just snap, analyze, and save your crop." />
    <meta property="og:type" content="website" />

    <title>@yield('title', 'ORIPadi — Instant Padi Health Diagnosis')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        forest: {
                            50: '#F5F8ED',
                            100: '#EBF4DD', // Light cream-sage (User Top)
                            200: '#CBE0B9',
                            300: '#ABC798',
                            400: '#8BA888', // Soft sage (User Second)
                            500: '#718D6E',
                            600: '#546E58', // Deep moss (User Third)
                            700: '#435B47',
                            800: '#344146', // Dark slate/teal (User Bottom)
                            900: '#263135',
                            950: '#151D20',
                        },
                        gold: {
                            400: '#f0b429',
                            500: '#de9e0e',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.4s ease-out forwards',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4,0,0.6,1) infinite',
                        'spin-slow': 'spin 2s linear infinite',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideUp: { '0%': { opacity: '0', transform: 'translateY(16px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        floating: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                    },
                    backdropBlur: { xs: '2px' },
                }
            }
        }
    </script>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        /* Premium Authentic Mid-Tone Natural Background */
        .bg-gradient-padi {
            background-color: #F5F8ED;
            background-image:
                linear-gradient(to bottom, rgba(235, 244, 221, 0.6) 0%, rgba(245, 248, 237, 0.95) 100%),
                url('/images/padi-bg-light.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #151D20;
        }

        /* Sleek Light Nature Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(28px) saturate(120%);
            -webkit-backdrop-filter: blur(28px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 40px rgba(42, 157, 92, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
        }

        .glass-card:hover {
            box-shadow: 0 24px 50px rgba(42, 157, 92, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        /* Upload zone hover */
        .upload-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #2a9d5c;
            background: rgba(42, 157, 92, 0.06);
            transform: scale(1.01);
        }

        /* Severity badge colours */
        .badge-healthy {
            background: #dcf5e4;
            color: #1a6439;
        }

        .badge-low {
            background: #fef9c3;
            color: #854d0e;
        }

        .badge-moderate {
            background: #ffedd5;
            color: #9a3412;
        }

        .badge-high {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f0faf4;
        }

        ::-webkit-scrollbar-thumb {
            background: #87d5a8;
            border-radius: 3px;
        }

        /* Mic recording pulse */
        @keyframes micPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(42, 157, 92, 0.4);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(42, 157, 92, 0);
            }
        }

        .mic-recording {
            animation: micPulse 1.2s ease-in-out infinite;
        }

        /* Result card animation */
        .result-appear {
            animation: slideUp 0.45s ease-out forwards;
        }

        /* Step card */
        .step-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .step-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(26, 71, 49, 0.12);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1.2s ease-out forwards;
        }

        .animate-fade-up {
            opacity: 0;
            animation: fadeUp 1.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        .delay-400 {
            animation-delay: 400ms;
        }

        .delay-500 {
            animation-delay: 500ms;
        }

        .delay-600 {
            animation-delay: 600ms;
        }

        .delay-800 {
            animation-delay: 800ms;
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .6;
                transform: scale(1.1);
            }
        }

        .shimmer {
            background: linear-gradient(90deg, #f0faf4 25%, #bbf7d0 50%, #f0faf4 75%);
            background-size: 1000px 100%;
            animation: shimmer 1.6s infinite linear;
        }
    </style>
    @stack('head')
</head>

<body class="@yield('body_class', 'bg-gradient-padi min-h-screen text-gray-800 antialiased')">

    @unless(View::hasSection('hide_header'))
        {{-- HEADER --}}
        <header
            class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-forest-200/50 shadow-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5 group flex-shrink-0">
                    <div
                        class="w-8 h-8 bg-forest-600 rounded-lg flex items-center justify-center shadow-sm group-hover:bg-forest-500 transition-colors">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20c9 0 12-8 12-8-2 2-4 3-6 3a6 6 0 0 1-6-6c0-3.31 2.69-6 6-6s6 2.69 6 6c0 1.39-.47 2.67-1.24 3.69A10 10 0 0 0 17 8z" />
                        </svg>
                    </div>
                    <span class="font-bold text-forest-900 text-base tracking-tight">ORI<span
                            class="text-forest-600">Padi</span></span>
                </a>

                {{-- Center Nav Links --}}
                <div class="hidden md:flex items-center gap-7 text-sm font-semibold text-forest-700">
                    <a href="/" class="hover:text-forest-600 transition-colors">Home</a>
                    <a href="/#how" class="hover:text-forest-600 transition-colors">How It Works</a>
                    <a href="{{ route('scan') }}" class="hover:text-forest-600 transition-colors">Scan</a>
                </div>

                {{-- Right Side Controls --}}
                <div class="flex items-center gap-2.5">
                    {{-- Language Toggle --}}
                    <button id="langToggleBtn" onclick="window.toggleLanguage && window.toggleLanguage()"
                        title="Switch language / Tukar bahasa"
                        class="inline-flex items-center gap-1.5 bg-white border border-forest-300 text-forest-700 hover:bg-forest-50 hover:border-forest-400 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm transition-all duration-200 select-none">
                        <svg class="w-3.5 h-3.5 text-forest-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m5 8 6 6M4 14l6-6 2-2M2 5h12M7 2h1m6 16-1.872-3.818M15 19l2-4 2 4m-3.382-.764h2.764" />
                        </svg>
                        <span id="langToggleLabel">BM</span>
                    </button>

                    {{-- Build with AI 2026 badge --}}
                    <span
                        class="hidden sm:inline-flex items-center gap-1.5 bg-forest-100 border border-forest-200 text-forest-800 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                        <svg class="w-3 h-3 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Build with AI 2026
                    </span>
                </div>
            </div>
        </header>
    @endunless

    {{-- MAIN CONTENT --}}
    <main class="@yield('main_class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12')">
        @yield('content')
    </main>

    @unless(View::hasSection('hide_footer'))
        {{-- FOOTER --}}
        <footer
            class="border-t border-forest-200/60 mt-12 py-8 relative z-10 w-full mb-[50px] lg:mb-0 animate-fade-up delay-600">
            <div class="max-w-7xl mx-auto px-4 text-center text-sm text-forest-700 space-y-1">
                <p>
                    <span class="text-forest-900 font-bold">ORI</span>Padi — A public utility for Malaysian rice
                    farmers
                </p>
                <p class="text-xs text-forest-500">Track 1: Padi & Plates (Agrotech & Food Security) | Build with AI 2026
                </p>
            </div>
        </footer>
    @endunless

    @stack('scripts')

    {{-- Service Worker Registration --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.warn('SW error:', err));
            });
        }
    </script>
</body>

</html>