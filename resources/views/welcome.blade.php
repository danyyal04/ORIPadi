@extends('layouts.app')

@section('title', 'ORIPadi — Instant Padi Health Diagnosis')
@section('body_class', 'bg-forest-50 min-h-screen text-forest-900 antialiased overflow-x-hidden')
@section('main_class', 'w-full h-full relative p-0 m-0')

@section('hide_footer', true)

@section('content')

    {{-- ══════════════════════════════════════════════════════
    PAGE TRANSITION OVERLAY — matches scan page bg (#F5F8ED)
    ══════════════════════════════════════════════════════ --}}
    <div id="transitionOverlay" class="fixed inset-0 z-[9999] pointer-events-none opacity-0"
        style="background: #F5F8ED; transition: opacity 0.55s cubic-bezier(0.4,0,0.2,1);">
        <div class="absolute inset-0 flex flex-col items-center justify-center gap-5">
            {{-- Logo --}}
            <div class="flex items-center gap-3 opacity-0" id="overlayLogo"
                style="animation: none; transition: opacity 0.3s ease 0.25s;">
                <div class="w-11 h-11 bg-forest-600 rounded-xl flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20c9 0 12-8 12-8-2 2-4 3-6 3a6 6 0 0 1-6-6c0-3.31 2.69-6 6-6s6 2.69 6 6c0 1.39-.47 2.67-1.24 3.69A10 10 0 0 0 17 8z" />
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold text-forest-900 text-lg leading-none tracking-tight">ORI<span
                            class="text-forest-500">Padi</span></p>
                    <p class="text-forest-500 text-xs font-medium tracking-widest uppercase mt-0.5"
                        data-i18n="overlay_loading">Loading Scanner…</p>
                </div>
            </div>
            {{-- Progress bar --}}
            <div class="w-48 h-1 bg-forest-100 rounded-full overflow-hidden opacity-0" id="overlayBar"
                style="transition: opacity 0.3s ease 0.3s;">
                <div class="h-full bg-forest-500 rounded-full" id="overlayProgress"
                    style="width:0%; transition: width 0.5s cubic-bezier(0.4,0,0.2,1) 0.35s;"></div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════
    HERO — Full viewport with paddy image card
    ══════════════════════════════════════════════════════ --}}
    <section class="relative bg-forest-50 overflow-hidden min-h-[calc(100vh-4rem)] flex flex-col justify-center">

        {{-- Soft background blobs --}}
        <div
            class="absolute top-0 left-0 w-[700px] h-[700px] bg-forest-200/50 rounded-full blur-[140px] -translate-x-1/3 -translate-y-1/4 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 right-0 w-[700px] h-[700px] bg-forest-300/35 rounded-full blur-[130px] translate-x-1/3 translate-y-1/4 pointer-events-none">
        </div>
        <div
            class="absolute top-1/2 left-1/2 w-[500px] h-[500px] bg-forest-100/60 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 pointer-events-none">
        </div>

        <div
            class="relative max-w-7xl mx-auto w-full px-5 sm:px-10 lg:px-16 py-12 lg:py-16 flex flex-col lg:flex-row items-center gap-12 lg:gap-8">

            {{-- ── LEFT TEXT ─────────────────────────────── --}}
            <div class="w-full lg:w-[48%] flex flex-col z-10" style="animation: fadeUp 0.9s ease-out 0.1s both;">

                {{-- Badge --}}
                <div
                    class="inline-flex items-center gap-2 bg-forest-100 border border-forest-200 text-forest-700 text-xs font-bold px-4 py-1.5 rounded-full mb-6 w-fit shadow-sm">
                    <span class="w-1.5 h-1.5 bg-forest-500 rounded-full animate-pulse"></span>
                    <span data-i18n="badge">Powered by Gemini 2.0 Flash AI</span>
                </div>

                {{-- Headline --}}
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-forest-900 leading-[1.0] tracking-tighter mb-6">
                    <span data-i18n="hero_line1">Protect Your</span><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-forest-500 to-forest-700"
                        data-i18n="hero_line2">
                        Paddy Crop
                    </span><br>
                    <span class="text-4xl sm:text-5xl lg:text-6xl text-forest-400 font-black" data-i18n="hero_line3">with
                        AI</span>
                </h1>

                {{-- Subtext --}}
                <p class="text-forest-600 text-base sm:text-lg leading-relaxed mb-8 max-w-md font-medium"
                    data-i18n="hero_sub">
                    Snap a photo of your padi plant and get an instant AI diagnosis with a personalised treatment plan — no
                    account needed.
                </p>

                {{-- CTA Row --}}
                <div class="flex flex-wrap items-center gap-4 mb-10">
                    <a href="{{ route('scan') }}" onclick="triggerTransition(event, '{{ route('scan') }}')"
                        class="group inline-flex items-center gap-3 bg-forest-600 hover:bg-forest-700 text-white font-extrabold text-base px-8 py-4 rounded-full shadow-xl shadow-forest-600/30 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-forest-600/40">
                        <span data-i18n="cta_main">Get Started Free</span>
                        <span
                            class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors flex-shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </a>
                    <a href="#how"
                        class="text-forest-600 hover:text-forest-800 font-semibold text-sm flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path stroke-linecap="round" d="M12 8v4l2 2" />
                        </svg>
                        <span data-i18n="cta_secondary">How it works</span>
                    </a>
                </div>

                {{-- Trust stats --}}
                <div class="flex items-center gap-6 flex-wrap">
                    <div>
                        <p class="text-forest-900 font-extrabold text-xl leading-none">10+</p>
                        <p class="text-forest-500 text-xs font-medium tracking-wide mt-0.5" data-i18n="stat1">Diseases
                            Detected</p>
                    </div>
                    <div class="w-px h-8 bg-forest-200"></div>
                    <div>
                        <p class="text-forest-900 font-extrabold text-xl leading-none">~3s</p>
                        <p class="text-forest-500 text-xs font-medium tracking-wide mt-0.5" data-i18n="stat2">Scan Time</p>
                    </div>
                    <div class="w-px h-8 bg-forest-200"></div>
                    <div>
                        <p class="text-forest-900 font-extrabold text-xl leading-none" data-i18n="stat3_val">Free</p>
                        <p class="text-forest-500 text-xs font-medium tracking-wide mt-0.5" data-i18n="stat3">No Account</p>
                    </div>
                </div>
            </div>

            {{-- ── RIGHT: PADDY IMAGE ────────────────────── --}}
            <div class="w-full lg:w-[52%] relative z-10 flex justify-center lg:justify-end items-stretch self-stretch"
                style="animation: fadeUp 0.9s ease-out 0.3s both;">

                {{-- Decorative dot grid --}}
                <div class="absolute top-4 left-0 grid grid-cols-4 gap-2 opacity-40 z-0 hidden lg:grid">
                    @for($i = 0; $i < 16; $i++)
                        <div class="w-1.5 h-1.5 bg-forest-400 rounded-full"></div>
                    @endfor
                </div>

                {{-- Image container --}}
                <div class="relative w-full max-w-lg lg:max-w-none flex flex-col">
                    {{-- Accent block behind --}}
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-forest-200 rounded-[2rem] z-0 hidden lg:block">
                    </div>

                    {{-- Main image --}}
                    <div
                        class="relative z-10 rounded-[2rem] overflow-hidden shadow-2xl shadow-forest-900/20 border-4 border-white flex-1">
                        <img src="/images/paddy_hero.png" alt="Lush green paddy rice field"
                            class="w-full h-full object-cover object-center hover:scale-105 transition-transform duration-700 ease-in-out"
                            style="min-height: 420px; max-height: 600px;" />
                        {{-- Gradient bottom fade --}}
                        <div
                            class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-forest-900/40 to-transparent">
                        </div>
                        {{-- Floating label on image --}}
                        <div class="absolute bottom-5 left-5 right-5 flex items-center justify-between">
                            <div class="bg-white/90 backdrop-blur-md rounded-xl px-4 py-2 shadow-md">
                                <p class="text-forest-800 font-bold text-sm" data-i18n="img_label">Paddy Health Scanner</p>
                                <p class="text-forest-500 text-xs font-medium" data-i18n="img_sub">AI-Powered Diagnosis</p>
                            </div>
                            <div class="w-10 h-10 bg-forest-600 rounded-full flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                    <path
                                        d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A4.49 4.49 0 0 0 8 20c9 0 12-8 12-8-2 2-4 3-6 3a6 6 0 0 1-6-6c0-3.31 2.69-6 6-6s6 2.69 6 6c0 1.39-.47 2.67-1.24 3.69A10 10 0 0 0 17 8z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
    HOW IT WORKS
    ══════════════════════════════════════════════════════ --}}
    <section id="how" class="bg-white py-20 px-5 sm:px-10 lg:px-16">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14">
                <p class="text-forest-500 text-xs font-bold uppercase tracking-widest mb-2" data-i18n="how_eyebrow">Simple &
                    Fast</p>
                <h2 class="text-3xl sm:text-4xl font-black text-forest-900 tracking-tight" data-i18n="how_title">How It
                    Works</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div
                    class="relative bg-forest-50 border border-forest-100 rounded-3xl p-7 hover:shadow-lg hover:border-forest-200 transition-all duration-200 group">
                    <div
                        class="w-12 h-12 bg-forest-100 group-hover:bg-forest-200 rounded-2xl flex items-center justify-center text-2xl mb-5 transition-colors">
                        📸</div>
                    <p class="text-forest-400 text-xs font-bold uppercase tracking-widest mb-1" data-i18n="step1_num">Step 1
                    </p>
                    <h3 class="text-forest-900 font-extrabold text-lg mb-2" data-i18n="step1_title">Snap a Photo</h3>
                    <p class="text-forest-600 text-sm leading-relaxed" data-i18n="step1_desc">Upload or live-scan your padi
                        crop. Works with any phone camera.</p>
                </div>
                <div
                    class="relative bg-forest-50 border border-forest-100 rounded-3xl p-7 hover:shadow-lg hover:border-forest-200 transition-all duration-200 group">
                    <div
                        class="w-12 h-12 bg-forest-100 group-hover:bg-forest-200 rounded-2xl flex items-center justify-center text-2xl mb-5 transition-colors">
                        🤖</div>
                    <p class="text-forest-400 text-xs font-bold uppercase tracking-widest mb-1" data-i18n="step2_num">Step 2
                    </p>
                    <h3 class="text-forest-900 font-extrabold text-lg mb-2" data-i18n="step2_title">AI Diagnoses</h3>
                    <p class="text-forest-600 text-sm leading-relaxed" data-i18n="step2_desc">Gemini AI identifies the
                        disease and severity in under 3 seconds.</p>
                </div>
                <div
                    class="relative bg-forest-50 border border-forest-100 rounded-3xl p-7 hover:shadow-lg hover:border-forest-200 transition-all duration-200 group">
                    <div
                        class="w-12 h-12 bg-forest-100 group-hover:bg-forest-200 rounded-2xl flex items-center justify-center text-2xl mb-5 transition-colors">
                        📋</div>
                    <p class="text-forest-400 text-xs font-bold uppercase tracking-widest mb-1" data-i18n="step3_num">Step 3
                    </p>
                    <h3 class="text-forest-900 font-extrabold text-lg mb-2" data-i18n="step3_title">Get Your Plan</h3>
                    <p class="text-forest-600 text-sm leading-relaxed" data-i18n="step3_desc">Receive a full treatment plan
                        for water, fertiliser & pest control.</p>
                </div>
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('scan') }}" onclick="triggerTransition(event, '{{ route('scan') }}')"
                    class="inline-flex items-center gap-2 bg-forest-600 hover:bg-forest-700 text-white font-bold text-base px-8 py-4 rounded-full shadow-lg shadow-forest-600/25 transition-all duration-200 hover:scale-105">
                    <span data-i18n="cta_bottom">Try It Now — It's Free</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════
    FOOTER
    ══════════════════════════════════════════════════════ --}}
    <footer class="bg-forest-50 border-t border-forest-200/60 py-8 px-5 text-center">
        <p class="text-forest-500 text-sm">
            <span class="text-forest-800 font-bold">ORIPadi</span> &mdash; <span data-i18n="footer_text">A public tool for
                Malaysian rice farmers</span> &bull;
            <span class="text-forest-400 text-xs">Build with AI 2026</span>
        </p>
    </footer>

    {{-- ══════════════════════════════════════════════════════
    STYLES & TRANSITION JS
    ══════════════════════════════════════════════════════ --}}
    <style>
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(32px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [style*="animation: fadeUp"] {
            opacity: 0;
        }
    </style>

    <script>
        // ─── i18n dictionary ───────────────────────────────────────────────
        const landingI18n = {
            en: {
                badge: 'Powered by Gemini 2.0 Flash AI',
                hero_line1: 'Protect Your',
                hero_line2: 'Paddy Crop',
                hero_line3: 'with AI',
                hero_sub: 'Snap a photo of your padi plant and get an instant AI diagnosis with a personalised treatment plan — no account needed.',
                cta_main: 'Get Started Free',
                cta_secondary: 'How it works',
                stat1: 'Diseases Detected',
                stat2: 'Scan Time',
                stat3_val: 'Free',
                stat3: 'No Account',
                img_label: 'Paddy Health Scanner',
                img_sub: 'AI-Powered Diagnosis',
                overlay_loading: 'Loading Scanner…',
                how_eyebrow: 'Simple & Fast',
                how_title: 'How It Works',
                step1_num: 'Step 1',
                step1_title: 'Snap a Photo',
                step1_desc: 'Upload or live-scan your padi crop. Works with any phone camera.',
                step2_num: 'Step 2',
                step2_title: 'AI Diagnoses',
                step2_desc: 'Gemini AI identifies the disease and severity in under 3 seconds.',
                step3_num: 'Step 3',
                step3_title: 'Optimize Your Resources',
                step3_desc: 'Receive a full treatment plan that minimizes wastage of water, fertiliser & pest control.',
                cta_bottom: "Try It Now — It's Free",
                footer_text: 'A public tool for Malaysian rice farmers',
            },
            ms: {
                badge: 'Dikuasakan oleh Gemini 2.0 Flash AI',
                hero_line1: 'Lindungi',
                hero_line2: 'Tanaman Padi',
                hero_line3: 'dengan AI',
                hero_sub: 'Ambil gambar pokok padi anda dan dapatkan diagnosis AI segera dengan pelan rawatan yang diperibadikan — tiada akaun diperlukan.',
                cta_main: 'Mulakan Percuma',
                cta_secondary: 'Cara ia berfungsi',
                stat1: 'Penyakit Dikesan',
                stat2: 'Masa Imbasan',
                stat3_val: 'Percuma',
                stat3: 'Tiada Akaun',
                img_label: 'Pengimbas Kesihatan Padi',
                img_sub: 'Diagnosis Berkuasa AI',
                overlay_loading: 'Memuatkan Pengimbas…',
                how_eyebrow: 'Mudah & Pantas',
                how_title: 'Cara Ia Berfungsi',
                step1_num: 'Langkah 1',
                step1_title: 'Ambil Gambar',
                step1_desc: 'Muat naik atau imbas langsung tanaman padi anda. Berfungsi dengan mana-mana kamera telefon.',
                step2_num: 'Langkah 2',
                step2_title: 'AI Mendiagnosis',
                step2_desc: 'Gemini AI mengenal pasti penyakit dan tahap keterukan dalam masa kurang 3 saat.',
                step3_num: 'Langkah 3',
                step3_title: 'Optimumkan Sumber Asas',
                step3_desc: 'Terima pelan rawatan penuh yang mengurangkan pembaziran sumber air, baja & kawalan perosak.',
                cta_bottom: 'Cuba Sekarang — Percuma',
                footer_text: 'Alat awam untuk petani padi Malaysia',
            }
        };

        function applyLandingLanguage(lang) {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (landingI18n[lang] && landingI18n[lang][key]) {
                    el.textContent = landingI18n[lang][key];
                }
            });
            const btn = document.getElementById('langToggleLabel');
            if (btn) btn.textContent = (lang === 'en') ? 'BM' : 'EN';
            localStorage.setItem('padiguard_lang', lang);
        }

        window.toggleLanguage = function () {
            const current = localStorage.getItem('padiguard_lang') || 'en';
            applyLandingLanguage(current === 'en' ? 'ms' : 'en');
        };

        // Apply saved language on load
        document.addEventListener('DOMContentLoaded', () => {
            const saved = localStorage.getItem('padiguard_lang') || 'en';
            applyLandingLanguage(saved);
        });

        function triggerTransition(e, href) {
            e.preventDefault();
            const overlay = document.getElementById('transitionOverlay');
            const logo = document.getElementById('overlayLogo');
            const bar = document.getElementById('overlayBar');
            const progress = document.getElementById('overlayProgress');

            // Fade overlay in
            overlay.style.opacity = '1';
            overlay.style.pointerEvents = 'all';

            // Reveal logo and bar after overlay appears
            setTimeout(() => {
                logo.style.opacity = '1';
                bar.style.opacity = '1';
                progress.style.width = '100%';
            }, 80);

            // Navigate after animation completes
            setTimeout(() => { window.location.href = href; }, 800);
        }
    </script>

@endsection