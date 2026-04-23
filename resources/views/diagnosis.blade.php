@extends('layouts.app')

@section('title', 'ORIPadi — Instant Padi Health Diagnosis')

@section('content')

    {{-- =====================================================================
    HERO SECTION
    ===================================================================== --}}
    <section class="text-center mb-10 lg:mb-16 pt-4 animate-fade-up">
        <div
            class="inline-flex items-center gap-2 bg-white/70 border border-forest-300 backdrop-blur-md text-forest-800 text-xs font-bold px-4 py-2 rounded-full mb-6 shadow-sm hover:shadow transition-shadow">
            <span class="w-2 h-2 bg-forest-500 rounded-full animate-pulse-slow inline-block"></span>
            Powered by Gemini 2.5 Flash API
        </div>
        <h1
            class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-forest-900 tracking-tighter leading-[1.1] mb-5 drop-shadow-sm">
            <span data-i18n="hero_title_line1">Instant Padi Health</span><br class="hidden sm:block" />
            <span
                class="text-transparent bg-clip-text bg-gradient-to-br from-forest-600 via-forest-500 to-forest-700 drop-shadow-sm"
                data-i18n="hero_title_line2">Diagnosis</span>
        </h1>
        <p class="text-forest-800 font-medium text-base sm:text-lg max-w-xl mx-auto leading-relaxed drop-shadow-sm"
            data-i18n="hero_subtitle">
            Protect your yield with AI-driven insights. No account needed—just snap, analyze, and save your crop.
        </p>
    </section>

    {{-- =====================================================================
    MAIN DASHBOARD — Dynamic Stack Layout
    ===================================================================== --}}
    <div class="max-w-3xl mx-auto flex flex-col gap-6 lg:gap-8 transition-all duration-700 ease-in-out" id="dashboard">

        {{-- ─── MAIN PANEL: Upload + Voice ─────────────────────────────────── --}}
        <div id="leftPanel" class="w-full transition-all duration-700 ease-in-out space-y-6 animate-fade-up delay-200">

            {{-- Upload Card --}}
            <div class="glass-card rounded-3xl p-6 shadow-lg">
                <h2 class="font-bold text-forest-900 text-lg mb-1">📸 <span data-i18n="upload_title">Upload atau Ambil
                        Foto</span></h2>
                <p class="text-sm text-forest-600 mb-4" data-i18n="upload_subtitle">Pilih "Live Scan" untuk imbas, atau muat
                    naik foto.</p>

                {{-- Action Buttons --}}
                <div class="flex gap-3 mb-5">
                    <button type="button" onclick="startLiveScan()"
                        class="flex-1 bg-forest-600 hover:bg-forest-500 border border-forest-500 hover:shadow-lg text-white py-3 rounded-2xl font-bold flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5">
                        <svg class="w-5 h-5 text-forest-100" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        <span data-i18n="btn_live_scan">Live Scan</span>
                    </button>
                    <button type="button" onclick="document.getElementById('imageInput').click()"
                        class="flex-1 bg-white border border-forest-200 hover:bg-forest-50 hover:shadow-md text-forest-700 py-3 rounded-2xl font-bold flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5">
                        <svg class="w-5 h-5 text-forest-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        <span data-i18n="btn_upload">Upload Foto</span>
                    </button>
                </div>

                <input type="file" id="imageInput" name="image" accept="image/*" class="sr-only" />

                {{-- Upload zone / Viewfinder --}}
                <div id="uploadZone"
                    class="upload-zone rounded-2xl border-2 border-dashed border-forest-400 bg-white/40 p-6 text-center relative overflow-hidden min-h-[220px] flex flex-col justify-center items-center">

                    {{-- Live Camera Feed --}}
                    <video id="liveVideo" autoplay playsinline
                        class="hidden absolute inset-0 w-full h-full object-cover z-0"></video>
                    <canvas id="captureCanvas" class="hidden"></canvas>

                    {{-- Scanner Overlay (AR Effect) --}}
                    <div id="scannerOverlay" class="hidden absolute inset-0 z-10 pointer-events-none">
                        <!-- Targeting Brackets -->
                        <div
                            class="absolute top-4 left-4 w-8 h-8 border-t-4 border-l-4 border-forest-400 opacity-70 rounded-tl-lg">
                        </div>
                        <div
                            class="absolute top-4 right-4 w-8 h-8 border-t-4 border-r-4 border-forest-400 opacity-70 rounded-tr-lg">
                        </div>
                        <div
                            class="absolute bottom-4 left-4 w-8 h-8 border-b-4 border-l-4 border-forest-400 opacity-70 rounded-bl-lg">
                        </div>
                        <div
                            class="absolute bottom-4 right-4 w-8 h-8 border-b-4 border-r-4 border-forest-400 opacity-70 rounded-br-lg">
                        </div>

                        <!-- Sweeping Laser -->
                        <div
                            class="absolute top-0 left-0 w-full h-1 bg-forest-400 shadow-[0_0_15px_3px_rgba(74,222,128,0.6)] animate-scanner">
                        </div>
                    </div>

                    {{-- Image preview --}}
                    <img id="imagePreview" src="" alt="Preview foto padi"
                        class="hidden absolute inset-0 w-full h-full object-cover z-0" />

                    {{-- Placeholder (Empty State) --}}
                    <div id="uploadPlaceholder" class="relative z-10 pointer-events-none">
                        <div
                            class="w-16 h-16 bg-white border border-forest-200 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <svg class="w-8 h-8 text-forest-400" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <p class="text-sm text-forest-600" data-i18n="upload_placeholder">Pilih opsyen di atas.</p>
                    </div>

                    {{-- Live Camera Controls --}}
                    <div id="liveControls"
                        class="hidden absolute bottom-4 left-1/2 -translate-x-1/2 z-20 w-fit flex gap-2 justify-center px-4">
                        <button type="button" onclick="captureFrame()"
                            class="bg-white text-forest-700 font-bold px-6 py-2.5 rounded-full shadow-lg border-2 border-forest-500 hover:scale-105 transition-transform flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span> <span
                                data-i18n="btn_snap">Snap</span>
                        </button>
                        <button type="button" onclick="stopLiveScan()"
                            class="bg-black/50 text-white font-semibold px-4 py-2.5 rounded-full backdrop-blur shadow-lg hover:bg-black/70 transition-colors"
                            data-i18n="btn_cancel">
                            Batal
                        </button>
                    </div>

                    {{-- Selected file info overlay --}}
                    <div id="fileInfo"
                        class="hidden absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4 z-10 backdrop-blur-sm">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center gap-2 min-w-0 pr-4">
                                <svg class="w-5 h-5 flex-shrink-0 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span id="fileName" class="text-sm truncate">photo.jpg</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Analyze button --}}
                <button id="analyzeBtn" type="button" disabled onclick="analyzeImage()"
                    class="mt-4 w-full flex items-center justify-center gap-2 bg-forest-700 disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-forest-600
                               text-white font-bold py-3.5 rounded-xl shadow-md transition-all duration-200 hover:shadow-lg disabled:shadow-none text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z" />
                    </svg>
                    <span data-i18n="btn_analyze">Analisis dengan AI</span>
                </button>
            </div>

            {{-- ─── Voice Input Card ──────────────────────────────────────── --}}
            <div class="glass-card rounded-3xl p-6 shadow-lg">
                <h2 class="font-bold text-forest-900 text-base mb-1">🎙️ <span data-i18n="voice_title">Penerangan Suara
                        (Pilihan)</span></h2>
                <p class="text-xs text-forest-600 mb-4" data-i18n="voice_subtitle">Terangkan keadaan tanaman anda dalam
                    Bahasa Melayu. AI akan mengambil kira penerangan ini.</p>

                <div class="flex gap-3 items-start">
                    <button id="micBtn" type="button" onclick="toggleSpeech()" title="Start voice input"
                        class="flex-shrink-0 w-12 h-12 bg-white hover:bg-forest-50 border border-forest-200 text-forest-600 rounded-xl flex items-center justify-center transition-all duration-200 shadow-sm">
                        <svg id="micIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
                        </svg>
                    </button>

                    <textarea id="voiceText" rows="3" data-i18n-placeholder="voice_placeholder"
                        placeholder="Contoh: Daun padi saya menguning di hujung dan ada bintik perang kecil..."
                        class="flex-1 resize-none rounded-xl border border-forest-300 bg-white/60 px-3.5 py-2.5 text-sm text-forest-900 placeholder-forest-400 focus:outline-none focus:ring-2 focus:ring-forest-500 focus:border-transparent transition-all"></textarea>
                </div>

                <p id="micStatus" class="mt-2 text-xs text-forest-600 hidden"></p>
                <p class="mt-2 text-xs text-forest-500" data-i18n="voice_tip">
                    💡 Atau taip teks anda secara terus.
                </p>
            </div>

            {{-- Reset App Button (Only visible during analysis/results) --}}
            <button id="newScanBtn" onclick="resetUI()"
                class="hidden w-full border-2 border-dashed border-forest-400 text-forest-700 bg-white/60 hover:bg-white hover:border-forest-500 backdrop-blur-md shadow-lg font-bold text-sm py-4 rounded-2xl transition-all duration-200"
                data-i18n="btn_new_scan">
                + Analisis Foto Baru
            </button>
        </div>

        {{-- ─── RIGHT PANEL: Results (Default Hidden) ─────────────────────── --}}
        <div id="rightPanel" class="hidden flex-1 min-w-0 w-full transition-all duration-700 opacity-0 translate-y-4">
            {{-- Loading state (shimmer) --}}
            <div id="loadingState" class="hidden glass-card rounded-3xl p-8 shadow-lg space-y-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 bg-forest-200 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-forest-600 animate-spin-slow" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-forest-900 text-sm" data-i18n="loading_title">Menganalisis gambar
                            anda...</p>
                        <p class="text-xs text-forest-600" data-i18n="loading_subtitle">Gemini sedang memproses</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="h-5 shimmer rounded-lg w-3/4"></div>
                    <div class="h-4 shimmer rounded-lg w-full"></div>
                    <div class="h-4 shimmer rounded-lg w-5/6"></div>
                    <div class="h-4 shimmer rounded-lg w-4/6"></div>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-4">
                    <div class="h-24 shimmer rounded-xl"></div>
                    <div class="h-24 shimmer rounded-xl"></div>
                    <div class="h-24 shimmer rounded-xl"></div>
                </div>
            </div>

            {{-- Error state --}}
            <div id="errorState" class="hidden glass-card rounded-3xl p-8 shadow-lg border border-red-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-700" data-i18n="error_title">Ralat Berlaku</h3>
                        <p id="errorMsg" class="text-sm text-red-500 mt-0.5">Sila cuba lagi.</p>
                    </div>
                </div>
                <button onclick="resetUI()" class="mt-2 text-xs text-red-600 underline hover:no-underline"
                    data-i18n="btn_retry">Cuba Semula</button>
            </div>

            {{-- ─── RESULT STATE ─────────────────────────────────────────── --}}
            <div id="resultState" class="hidden space-y-5 result-appear">

                {{-- Diagnosis Summary Card --}}
                <div class="glass-card rounded-3xl p-6 shadow-lg border border-forest-100">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-4">
                            <!-- Radial Confidence Ring -->
                            <div class="relative w-16 h-16 flex items-center justify-center flex-shrink-0">
                                <svg class="transform -rotate-90 w-16 h-16">
                                    <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="5" fill="transparent"
                                        class="text-forest-100" />
                                    <circle id="confidenceRing" cx="32" cy="32" r="28" stroke="currentColor"
                                        stroke-width="5" fill="transparent" stroke-dasharray="176" stroke-dashoffset="176"
                                        stroke-linecap="round"
                                        class="text-forest-500 transition-all duration-1000 ease-out" />
                                </svg>
                                <div class="absolute flex flex-col items-center justify-center">
                                    <span id="confidence" class="text-sm font-bold text-forest-800">—%</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-forest-500 font-medium uppercase tracking-wider mb-0.5"
                                    data-i18n="label_diagnosis">Diagnosis</p>
                                <h2 id="diseaseName" class="text-xl font-extrabold text-forest-900 leading-tight">—</h2>
                                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                    <span id="severityBadge"
                                        class="text-xs font-semibold px-2.5 py-0.5 rounded-full badge-healthy shadow-sm">—</span>
                                </div>
                            </div>
                        </div>

                        {{-- PDF Download button --}}
                        <button id="downloadBtn" onclick="downloadPDF()"
                            class="flex-shrink-0 inline-flex items-center gap-2 bg-white border border-forest-200 text-forest-700 hover:bg-forest-50 hover:border-forest-300 font-semibold text-sm px-4 py-2 rounded-xl shadow-sm transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span data-i18n="btn_download_pdf">Muat Turun PDF</span>
                        </button>
                    </div>
                </div>

                {{-- Reasoning Card --}}
                <div class="glass-card rounded-3xl p-6 shadow-lg border border-forest-400/20">
                    <h3 class="font-bold text-forest-900 text-base mb-3 flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-white border border-forest-100 rounded-lg flex items-center justify-center text-sm shadow-sm">🔬</span>
                        <span data-i18n="label_reasoning">Penaakulan AI</span>
                    </h3>
                    <p id="reasoning" class="text-sm text-forest-800 leading-relaxed">—</p>
                </div>

                {{-- 3-Step Intervention --}}
                <div class="glass-card rounded-3xl p-6 shadow-lg border border-forest-400/20">
                    <h3 class="font-bold text-forest-900 text-base mb-4 flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-white border border-forest-100 rounded-lg flex items-center justify-center text-sm shadow-sm">🌾</span>
                        <span data-i18n="label_intervention">Pelan Intervensi 3 Langkah</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Water --}}
                        <div class="step-card bg-blue-50/80 border border-blue-100 rounded-2xl p-4">
                            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                                <span class="text-lg">💧</span>
                            </div>
                            <p class="text-xs font-bold text-blue-800 uppercase tracking-wide mb-2" data-i18n="label_water">
                                Pengairan</p>
                            <p id="interventionWater" class="text-xs text-blue-700 leading-relaxed">—</p>
                        </div>
                        {{-- Fertilizer --}}
                        <div class="step-card bg-amber-50/80 border border-amber-100 rounded-2xl p-4">
                            <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
                                <span class="text-lg">🌱</span>
                            </div>
                            <p class="text-xs font-bold text-amber-800 uppercase tracking-wide mb-2"
                                data-i18n="label_fertilizer">Baja</p>
                            <p id="interventionFertilizer" class="text-xs text-amber-700 leading-relaxed">—</p>
                        </div>
                        {{-- Treatment --}}
                        <div class="step-card bg-red-50/80 border border-red-100 rounded-2xl p-4">
                            <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center mb-3">
                                <span class="text-lg">💊</span>
                            </div>
                            <p class="text-xs font-bold text-red-800 uppercase tracking-wide mb-2"
                                data-i18n="label_treatment">Rawatan</p>
                            <p id="interventionTreatment" class="text-xs text-red-700 leading-relaxed">—</p>
                        </div>
                    </div>
                </div>

                {{-- Resource Optimization --}}
                <div id="resourceOptimizationCard"
                    class="hidden glass-card rounded-3xl p-6 shadow-lg border border-teal-400/20 bg-teal-50/30">
                    <h3 class="font-bold text-teal-900 text-base mb-3 flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-white border border-teal-100 rounded-lg flex items-center justify-center text-sm shadow-sm">♻️</span>
                        <span data-i18n="label_resource">Pengoptimuman Sumber 💧</span>
                    </h3>
                    <p id="resourceOptimization" class="text-sm text-teal-800 leading-relaxed">—</p>
                </div>

                {{-- Additional Notes --}}
                <div id="additionalNotesCard"
                    class="hidden glass-card rounded-3xl p-6 shadow-lg border border-forest-400/20">
                    <h3 class="font-bold text-forest-900 text-base mb-3 flex items-center gap-2">
                        <span
                            class="w-6 h-6 bg-white border border-forest-100 rounded-lg flex items-center justify-center text-sm shadow-sm">📝</span>
                        <span data-i18n="label_notes">Nota Tambahan</span>
                    </h3>
                    <p id="additionalNotes" class="text-sm text-forest-800 leading-relaxed">—</p>
                </div>

            </div>

        </div>{{-- end right panel --}}
    </div>{{-- end dashboard --}}

@endsection

@push('scripts')
    <script>
        // =====================================================================
        //  i18n — Bilingual EN / BM
        // =====================================================================
        const i18n = {
            ms: {
                hero_title_line1: 'Kesihatan Padi Diagnosis Segera',
                hero_title_line2: 'Diagnosis',
                hero_subtitle: 'Lindungi hasil tuaian dengan AI. Tiada akaun diperlukan—snap, analisis, dan selamatkan tanaman anda.',
                upload_title: 'Upload atau Ambil Foto',
                upload_subtitle: 'Pilih "Live Scan" untuk imbas, atau muat naik foto.',
                btn_live_scan: 'Live Scan',
                btn_upload: 'Upload Foto',
                upload_placeholder: 'Pilih pilihan di atas.',
                btn_snap: 'Snap',
                btn_cancel: 'Batal',
                btn_analyze: 'Analisis dengan AI',
                voice_title: 'Penerangan Suara (Pilihan)',
                voice_subtitle: 'Terangkan keadaan tanaman anda dalam Bahasa Melayu. AI akan mengambil kira penerangan ini.',
                voice_placeholder: 'Contoh: Daun padi saya menguning di hujung dan ada bintik perang kecil...',
                voice_tip: '💡 Atau taip teks anda secara terus.',
                btn_new_scan: '+ Analisis Foto Baru',
                loading_title: 'Menganalisis gambar anda...',
                loading_subtitle: 'Gemini sedang memproses',
                error_title: 'Ralat Berlaku',
                btn_retry: 'Cuba Semula',
                label_diagnosis: 'Diagnosis',
                label_confidence: 'Keyakinan:',
                btn_download_pdf: 'Muat Turun PDF',
                label_reasoning: 'Penaakulan AI',
                label_intervention: 'Pelan Intervensi 3 Langkah',
                label_water: 'Pengairan',
                label_fertilizer: 'Baja',
                label_treatment: 'Rawatan',
                label_resource: 'Pengoptimuman Sumber 💧',
                label_notes: 'Nota Tambahan',
            },
            en: {
                hero_title_line1: 'Instant Padi Health',
                hero_title_line2: 'Diagnosis',
                hero_subtitle: 'Protect your yield with AI-driven insights. No account needed—just snap, analyze, and save your crop.',
                upload_title: 'Upload or Take a Photo',
                upload_subtitle: 'Choose "Live Scan" to scan, or upload a photo.',
                btn_live_scan: 'Live Scan',
                btn_upload: 'Upload Photo',
                upload_placeholder: 'Choose an option above.',
                btn_snap: 'Snap',
                btn_cancel: 'Cancel',
                btn_analyze: 'Analyze with AI',
                voice_title: 'Voice Description (Optional)',
                voice_subtitle: 'Describe your crop condition. The AI will take this into account.',
                voice_placeholder: 'Example: My rice leaves are yellowing at the tips with small brown spots...',
                voice_tip: '💡 Or type your description directly.',
                btn_new_scan: '+ Analyze New Photo',
                loading_title: 'Analyzing your image...',
                loading_subtitle: 'Gemini is processing',
                error_title: 'An Error Occurred',
                btn_retry: 'Try Again',
                label_diagnosis: 'Diagnosis',
                label_confidence: 'Confidence:',
                btn_download_pdf: 'Download PDF',
                label_reasoning: 'AI Reasoning',
                label_intervention: '3-Step Intervention Plan',
                label_water: 'Irrigation',
                label_fertilizer: 'Fertilizer',
                label_treatment: 'Treatment',
                label_resource: 'Resource Optimization 💧',
                label_notes: 'Additional Notes',
            }
        };

        let currentLang = localStorage.getItem('padiguard_lang') || 'ms';

        function applyLanguage(lang) {
            currentLang = lang;
            localStorage.setItem('padiguard_lang', lang);
            const dict = i18n[lang];

            // Swap text nodes
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key] !== undefined) el.textContent = dict[key];
            });

            // Swap placeholder attributes
            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (dict[key] !== undefined) el.placeholder = dict[key];
            });

            // Update toggle button label
            const label = document.getElementById('langToggleLabel');
            if (label) label.textContent = lang === 'ms' ? 'EN' : 'BM';

            // If there's an active AI result, re-render it in the new language!
            if (typeof currentResult !== 'undefined' && currentResult) {
                renderResult(currentResult);
            }
        }

        window.toggleLanguage = function () {
            applyLanguage(currentLang === 'ms' ? 'en' : 'ms');
        };

        // Apply on page load
        document.addEventListener('DOMContentLoaded', () => applyLanguage(currentLang));
    </script>
    <script>
        // =====================================================================
        //  State
        // =====================================================================
        let selectedFile = null;
        let currentResult = null;
        let recognition = null;
        let isRecording = false;
        let liveStream = null; // To hold the media stream

        // =====================================================================
        //  File / Upload Logic
        // =====================================================================
        const imageInput = document.getElementById('imageInput');
        const uploadZone = document.getElementById('uploadZone');
        const analyzeBtn = document.getElementById('analyzeBtn');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            selectedFile = file;

            // Hide placeholder/camera overlay
            document.getElementById('uploadPlaceholder').classList.add('hidden');
            stopLiveScan(); // Stops the camera if it's currently running

            // Show filename
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileInfo').classList.remove('hidden');

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById('imagePreview');
                img.src = e.target.result;
                img.classList.remove('hidden');
            };
            reader.readAsDataURL(file);

            analyzeBtn.disabled = false;
            analyzeBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
            analyzeBtn.classList.add('hover:bg-forest-600', 'hover:shadow-lg', 'hover:-translate-y-0.5');
        });

        // Drag & Drop
        uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                const dt = new DataTransfer();
                dt.items.add(file);
                imageInput.files = dt.files;
                imageInput.dispatchEvent(new Event('change'));
            }
        });

        // =====================================================================
        //  Live Camera (Scan) Logic
        // =====================================================================
        function startLiveScan() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert("Penyemak imbas anda tidak menyokong fungsi kamera langsung.");
                return;
            }

            // Hide placeholders and any existing photo
            document.getElementById('uploadPlaceholder').classList.add('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('fileInfo').classList.add('hidden');

            const video = document.getElementById('liveVideo');
            video.classList.remove('hidden');
            document.getElementById('liveControls').classList.remove('hidden');
            const overlay = document.getElementById('scannerOverlay');
            if (overlay) overlay.classList.remove('hidden');

            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
                .then(stream => {
                    liveStream = stream;
                    video.srcObject = stream;
                })
                .catch(err => {
                    alert("Ralat kamera: " + err.message);
                    stopLiveScan();
                });
        }

        function stopLiveScan() {
            if (liveStream) {
                liveStream.getTracks().forEach(track => track.stop());
                liveStream = null;
            }
            document.getElementById('liveVideo').classList.add('hidden');
            document.getElementById('liveControls').classList.add('hidden');
            const overlay = document.getElementById('scannerOverlay');
            if (overlay) overlay.classList.add('hidden');

            // If no file was selected/captured, show placeholder again
            if (!selectedFile) {
                document.getElementById('uploadPlaceholder').classList.remove('hidden');
            }
        }

        function captureFrame() {
            const video = document.getElementById('liveVideo');
            const canvas = document.getElementById('captureCanvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert to file
            canvas.toBlob(blob => {
                selectedFile = new File([blob], "scanned_padi.jpg", { type: "image/jpeg" });

                // Show preview
                const img = document.getElementById('imagePreview');
                img.src = canvas.toDataURL("image/jpeg");
                img.classList.remove('hidden');

                stopLiveScan();

                // Update UI info
                document.getElementById('fileName').textContent = "Hasil Imbasan Kamera";
                document.getElementById('fileInfo').classList.remove('hidden');

                // Enable Analyze button
                analyzeBtn.disabled = false;
                analyzeBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                analyzeBtn.classList.add('hover:bg-forest-600', 'hover:shadow-lg', 'hover:-translate-y-0.5');
            }, "image/jpeg", 0.9);
        }

        // =====================================================================
        //  Analyze
        // =====================================================================
        async function analyzeImage() {
            if (!selectedFile) return;

            showLoading();

            const formData = new FormData();
            formData.append('image', selectedFile);
            formData.append('lang', currentLang); // Tell AI which language to use
            const vc = document.getElementById('voiceText').value.trim();
            if (vc) formData.append('voice_context', vc);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const res = await fetch('/analyze', { method: 'POST', body: formData });
                const data = await res.json();

                if (!res.ok || data.error) throw new Error(data.error || 'Ralat pelayan');

                currentResult = data;
                renderResult(data);
            } catch (err) {
                showError(err.message);
            }
        }

        function renderResult(d) {
            const lang = currentLang; // 'ms' or 'en'

            document.getElementById('diseaseName').textContent = d[`disease_name_${lang}`] || 'Tidak Diketahui';

            // Animate Radial Confidence Ring
            const confValue = d.confidence || 0;
            document.getElementById('confidence').textContent = confValue + '%';
            const ring = document.getElementById('confidenceRing');
            if (ring) {
                // circumference is 176
                const offset = 176 - (176 * confValue / 100);
                // Add a tiny delay so the CSS transition has time to trigger after un-hiding
                setTimeout(() => {
                    ring.style.strokeDashoffset = offset;

                    // Adjust color based on confidence
                    ring.classList.remove('text-forest-500', 'text-amber-500', 'text-red-500');
                    if (confValue >= 80) ring.classList.add('text-forest-500');
                    else if (confValue >= 50) ring.classList.add('text-amber-500');
                    else ring.classList.add('text-red-500');
                }, 50);
            }

            document.getElementById('reasoning').textContent = d[`reasoning_${lang}`] || '—';
            document.getElementById('interventionWater').textContent = d[`intervention_water_${lang}`] || '—';
            document.getElementById('interventionFertilizer').textContent = d[`intervention_fertilizer_${lang}`] || '—';
            document.getElementById('interventionTreatment').textContent = d[`intervention_treatment_${lang}`] || '—';

            // Severity badge
            const badge = document.getElementById('severityBadge');
            const severity = (d.severity || 'unknown').toLowerCase();

            // Set text based on language
            let severityText = d.severity || '—';
            if (lang === 'ms') {
                if (severity === 'healthy') severityText = 'Sihat';
                else if (severity === 'low') severityText = 'Rendah';
                else if (severity === 'moderate') severityText = 'Sederhana';
                else if (severity === 'high') severityText = 'Tinggi';
            }
            badge.textContent = severityText;

            badge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full ';
            if (severity === 'healthy') badge.className += 'badge-healthy';
            else if (severity === 'low') badge.className += 'badge-low';
            else if (severity === 'moderate') badge.className += 'badge-moderate';
            else badge.className += 'badge-high';

            // Resource Optimization
            if (d[`resource_optimization_${lang}`]) {
                document.getElementById('resourceOptimization').textContent = d[`resource_optimization_${lang}`];
                document.getElementById('resourceOptimizationCard').classList.remove('hidden');
            } else {
                document.getElementById('resourceOptimizationCard').classList.add('hidden');
            }

            // Additional notes
            if (d[`additional_notes_${lang}`]) {
                document.getElementById('additionalNotes').textContent = d[`additional_notes_${lang}`];
                document.getElementById('additionalNotesCard').classList.remove('hidden');
            } else {
                document.getElementById('additionalNotesCard').classList.add('hidden');
            }

            hideAll();
            document.getElementById('resultState').classList.remove('hidden');

            // ── Reset analyze button back to ready state ──
            analyzeBtn.disabled = false;
            const reanalyzeText = lang === 'en' ? 'Analyze Again' : 'Analisis Semula';
            analyzeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/>
        </svg> ${reanalyzeText}`;
        }

        // =====================================================================
        //  PDF Download
        // =====================================================================
        function downloadPDF() {
            if (!currentResult) return;

            // Map the current language's results to the standard keys the PDF expects
            const lang = currentLang;
            const pdfData = {
                disease_name: currentResult[`disease_name_${lang}`] || 'Unknown',
                confidence: currentResult.confidence,
                severity: currentResult.severity,
                reasoning: currentResult[`reasoning_${lang}`] || '',
                intervention_water: currentResult[`intervention_water_${lang}`] || '',
                intervention_fertilizer: currentResult[`intervention_fertilizer_${lang}`] || '',
                intervention_treatment: currentResult[`intervention_treatment_${lang}`] || '',
                resource_optimization: currentResult[`resource_optimization_${lang}`] || '',
                additional_notes: currentResult[`additional_notes_${lang}`] || '',
            };

            const params = new URLSearchParams(pdfData);
            window.location.href = '{{ route("download.pdf") }}?' + params.toString();
        }

        // =====================================================================
        //  UI state helpers
        // =====================================================================
        function showLoading() {
            hideAll();

            // Dynamic Layout Expansion (Subtle width increase)
            const dash = document.getElementById('dashboard');
            const rp = document.getElementById('rightPanel');

            dash.classList.replace('max-w-3xl', 'max-w-4xl');

            rp.classList.remove('hidden');
            // slight delay for fluid slide animation
            setTimeout(() => {
                rp.classList.remove('opacity-0', 'translate-y-4');
                rp.classList.add('opacity-100', 'translate-y-0');
            }, 50);

            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('newScanBtn').classList.remove('hidden');
            analyzeBtn.disabled = true;
            analyzeBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Menganalisis...`;
        }
        function showError(msg) {
            hideAll();
            document.getElementById('errorMsg').textContent = msg;
            document.getElementById('errorState').classList.remove('hidden');
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/> </svg> Analisis dengan AI';
        }
        function hideAll() {
            ['loadingState', 'errorState', 'resultState'].forEach(id =>
                document.getElementById(id).classList.add('hidden'));
        }
        function resetUI() {
            // Contract to 1 column gracefully
            const dash = document.getElementById('dashboard');
            const lp = document.getElementById('leftPanel');
            const rp = document.getElementById('rightPanel');

            rp.classList.remove('opacity-100', 'translate-y-0');
            rp.classList.add('opacity-0', 'translate-y-4');

            setTimeout(() => {
                rp.classList.add('hidden');
                dash.classList.replace('max-w-4xl', 'max-w-3xl');

                hideAll();

                // Reset upload area
                stopLiveScan();
                selectedFile = null;
                currentResult = null;
                imageInput.value = '';
                document.getElementById('uploadPlaceholder').classList.remove('hidden');
                document.getElementById('imagePreview').classList.add('hidden');
                document.getElementById('imagePreview').src = '';
                document.getElementById('fileInfo').classList.add('hidden');
                document.getElementById('voiceText').value = '';
                document.getElementById('additionalNotesCard').classList.add('hidden');
                document.getElementById('newScanBtn').classList.add('hidden');

                // Reset gauge
                const ring = document.getElementById('confidenceRing');
                if (ring) ring.style.strokeDashoffset = 176;
                document.getElementById('confidence').textContent = '—%';

                analyzeBtn.disabled = true;
                analyzeBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/> </svg> Analisis dengan AI';
            }, 400); // match transition duration
        }

        // =====================================================================
        //  Web Speech API — Bahasa Melayu
        // =====================================================================
        function toggleSpeech() {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                alert('Pelayar anda tidak menyokong Web Speech API. Sila gunakan Chrome atau Edge.');
                return;
            }

            if (isRecording) {
                recognition.stop();
                return;
            }

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'ms-MY';       // Bahasa Melayu
            recognition.interimResults = true;
            recognition.maxAlternatives = 1;
            recognition.continuous = true;

            recognition.onstart = () => {
                isRecording = true;
                const btn = document.getElementById('micBtn');
                const stat = document.getElementById('micStatus');
                btn.classList.add('bg-forest-600', 'text-white', 'mic-recording', 'border-transparent');
                btn.classList.remove('bg-white', 'text-forest-600', 'border-forest-200');
                stat.textContent = '🔴 Sedang merakam... (klik untuk berhenti)';
                stat.classList.remove('hidden');
            };

            recognition.onresult = (event) => {
                let transcript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    transcript += event.results[i][0].transcript;
                }
                document.getElementById('voiceText').value = transcript;
            };

            recognition.onerror = (event) => {
                document.getElementById('micStatus').textContent = '⚠️ Ralat: ' + event.error;
                stopRecording();
            };

            recognition.onend = () => stopRecording();

            recognition.start();

            function stopRecording() {
                isRecording = false;
                const btn = document.getElementById('micBtn');
                const stat = document.getElementById('micStatus');
                btn.classList.remove('bg-forest-600', 'text-white', 'mic-recording', 'border-transparent');
                btn.classList.add('bg-white', 'text-forest-600', 'border-forest-200');
                stat.textContent = '✅ Rakaman selesai.';
                setTimeout(() => stat.classList.add('hidden'), 2500);
            }
        }
    </script>
@endpush