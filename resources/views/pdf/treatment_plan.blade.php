<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8"/>
    <title>ORIPadi — Pelan Rawatan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            color: #1a2e1a;
            background: #fff;
            padding: 0;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1a4731 0%, #2a9d5c 100%);
            color: white;
            padding: 28px 36px 24px;
        }
        .header-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .header-logo .icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.2);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .header-logo h1 { font-size: 18pt; font-weight: bold; letter-spacing: -0.5px; }
        .header-sub { font-size: 8.5pt; opacity: 0.8; margin-top: 4px; }
        .header-tagline { font-size: 9pt; opacity: 0.7; margin-top: 2px; }

        /* Generated stamp */
        .stamp {
            background: rgba(255,255,255,0.15);
            border-radius: 6px;
            display: inline-block;
            padding: 4px 12px;
            font-size: 8pt;
            margin-top: 10px;
        }

        /* Body */
        .content { padding: 28px 36px; }

        /* Section title */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1a4731;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #bbf7d0;
        }

        /* Diagnosis box */
        .diagnosis-box {
            background: #f0faf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 22px;
        }
        .disease-name { font-size: 16pt; font-weight: bold; color: #1a4731; margin-bottom: 6px; }
        .meta-row { display: flex; gap: 16px; margin-top: 6px; flex-wrap: wrap; }
        .meta-item { font-size: 8.5pt; }
        .meta-label { color: #6b7280; }
        .meta-value { font-weight: bold; }

        /* Severity badge */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 8pt; font-weight: bold; }
        .badge-healthy  { background: #dcf5e4; color: #1a6439; }
        .badge-low      { background: #fef9c3; color: #854d0e; }
        .badge-moderate { background: #ffedd5; color: #9a3412; }
        .badge-high     { background: #fee2e2; color: #991b1b; }

        /* Reasoning */
        .reasoning-box {
            background: #fafffe;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 22px;
            line-height: 1.65;
            font-size: 9.5pt;
            color: #374151;
        }

        /* Intervention grid */
        .intervention-grid { margin-bottom: 20px; }
        .intervention-row { display: flex; gap: 10px; margin-bottom: 0; }
        .step-card {
            flex: 1;
            border-radius: 8px;
            padding: 12px 14px;
            min-height: 80px;
        }
        .step-icon { font-size: 16pt; margin-bottom: 5px; }
        .step-label { font-size: 7.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 5px; }
        .step-text  { font-size: 8.5pt; line-height: 1.55; }

        .card-water  { background: #eff6ff; border: 1px solid #bfdbfe; }
        .card-water .step-label  { color: #1d4ed8; }
        .card-water .step-text   { color: #1e40af; }

        .card-fertilizer { background: #fffbeb; border: 1px solid #fde68a; }
        .card-fertilizer .step-label { color: #b45309; }
        .card-fertilizer .step-text  { color: #92400e; }

        .card-treatment { background: #fff1f2; border: 1px solid #fecdd3; }
        .card-treatment .step-label { color: #be123c; }
        .card-treatment .step-text  { color: #991b1b; }

        /* Notes */
        .notes-box {
            background: #fffdf0;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 9pt;
            color: #78350f;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Resource Optimization */
        .resource-box {
            background: #f0fdfa;
            border: 1px solid #99f6e4;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 9pt;
            color: #115e59;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 7.5pt;
            color: #9ca3af;
        }
        .footer strong { color: #4b7c59; }

        /* Disclaimer */
        .disclaimer {
            font-size: 7pt;
            color: #d1d5db;
            margin-top: 6px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<div class="header">
    <div class="header-logo">
        <div class="icon">🌿</div>
        <h1>ORIPadi</h1>
    </div>
    <div class="header-sub">Laporan Diagnosis Kesihatan Padi</div>
    <div class="header-tagline">A public utility for Malaysian rice farmers · Track 1: Padi & Plates</div>
    <div class="stamp">📅 Dijana pada: {{ $generated_at }}</div>
</div>

{{-- CONTENT --}}
<div class="content">

    {{-- DIAGNOSIS --}}
    <div class="section-title">Keputusan Diagnosis</div>
    <div class="diagnosis-box">
        <div class="disease-name">{{ $disease_name }}</div>
        <div class="meta-row">
            <div class="meta-item">
                <span class="meta-label">Keparahan: </span>
                <span class="badge
                    @if(strtolower($severity) === 'healthy')   badge-healthy
                    @elseif(strtolower($severity) === 'low')   badge-low
                    @elseif(strtolower($severity) === 'moderate') badge-moderate
                    @else badge-high @endif">
                    {{ $severity }}
                </span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Keyakinan AI: </span>
                <span class="meta-value">{{ $confidence }}%</span>
            </div>
        </div>
    </div>

    {{-- REASONING --}}
    <div class="section-title">Penaakulan AI (Berdasarkan Visual)</div>
    <div class="reasoning-box">{{ $reasoning }}</div>

    {{-- INTERVENTION --}}
    <div class="section-title">Pelan Intervensi 3 Langkah (Konteks Malaysia)</div>
    <div class="intervention-grid">
        <div class="intervention-row">
            <div class="step-card card-water">
                <div class="step-icon">💧</div>
                <div class="step-label">Pengairan (Water)</div>
                <div class="step-text">{{ $intervention_water }}</div>
            </div>
            <div class="step-card card-fertilizer">
                <div class="step-icon">🌱</div>
                <div class="step-label">Baja (Fertilizer)</div>
                <div class="step-text">{{ $intervention_fertilizer }}</div>
            </div>
            <div class="step-card card-treatment">
                <div class="step-icon">💊</div>
                <div class="step-label">Rawatan (Treatment)</div>
                <div class="step-text">{{ $intervention_treatment }}</div>
            </div>
        </div>
    </div>

    @if($resource_optimization)
    {{-- RESOURCE OPTIMIZATION --}}
    <div class="section-title">Pengoptimuman Sumber (Resource Optimization) ♻️</div>
    <div class="resource-box">{{ $resource_optimization }}</div>
    @endif

    @if($additional_notes)
    {{-- ADDITIONAL NOTES --}}
    <div class="section-title">Nota Tambahan</div>
    <div class="notes-box">{{ $additional_notes }}</div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        <strong>ORIPadi</strong> — Didukung oleh Gemini 2.5 Flash · Google AI Studio<br/>
        Laporan ini dijana secara automatik. Sila rujuk pegawai pertanian tempatan untuk pengesahan.<br/>
        <span class="disclaimer">Laporan ini adalah untuk tujuan panduan sahaja dan bukan nasihat pertanian profesional yang mengikat.</span>
    </div>
</div>

</body>
</html>
