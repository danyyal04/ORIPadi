<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class PadiAnalysisController extends Controller
{
    /**
     * Display the main diagnostic tool page.
     */
    public function index()
    {
        return view('diagnosis');
    }

    /**
     * Analyze padi leaf image using the configured Gemini model (GEMINI_MODEL in .env).
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // Max 10MB
        ]);

        try {
            $apiKey   = config('services.gemini.api_key');
            $imageFile = $request->file('image');
            $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
            $mimeType  = $imageFile->getMimeType();

            // Build voice context addon if provided
            $voiceContext = '';
            if ($request->filled('voice_context')) {
                $voiceContext = "\n\nAdditional context from farmer (in Bahasa Melayu): \"" . $request->input('voice_context') . "\"";
            }

            $prompt = "You are an expert Malaysian agricultural scientist specialising in padi (rice) cultivation.
Analyze this padi leaf image carefully. Perform the following tasks:

1. Identify the disease or condition (if any). If healthy, state 'Tiada Penyakit (Healthy)'.
2. State your confidence level as a percentage (e.g., 87%).
3. Explain your reasoning based on visual markers such as colour, lesion shape, texture, patterns, or other observable symptoms.
4. Provide a precise 3-step intervention plan tailored for Malaysian agricultural context — specifically covering:
   - Water management (pengairan)
   - Fertilizer application (baja)
   - Treatment / pesticide recommendation (rawatan)
5. Provide a Resource Optimization strategy (how to avoid wastage of water, fertilizer, or money based on the diagnosis).
{$voiceContext}

IMPORTANT: You MUST provide your answers in BOTH English and Bahasa Melayu.

Respond ONLY with valid JSON in this exact structure (no markdown, no code blocks):
{
  \"disease_name_en\": \"Name of disease in English\",
  \"disease_name_ms\": \"Nama penyakit dalam Bahasa Melayu\",
  \"confidence\": 85,
  \"severity\": \"Low|Moderate|High|Healthy\",
  \"reasoning_en\": \"Detailed visual reasoning in English...\",
  \"reasoning_ms\": \"Penaakulan visual terperinci dalam Bahasa Melayu...\",
  \"intervention_water_en\": \"Water management advice in English...\",
  \"intervention_water_ms\": \"Nasihat pengairan dalam Bahasa Melayu...\",
  \"intervention_fertilizer_en\": \"Fertilizer advice in English...\",
  \"intervention_fertilizer_ms\": \"Nasihat baja dalam Bahasa Melayu...\",
  \"intervention_treatment_en\": \"Treatment advice in English...\",
  \"intervention_treatment_ms\": \"Nasihat rawatan dalam Bahasa Melayu...\",
  \"resource_optimization_en\": \"Resource optimization in English...\",
  \"resource_optimization_ms\": \"Pengoptimuman sumber dalam Bahasa Melayu...\",
  \"additional_notes_en\": \"Extra advice in English...\",
  \"additional_notes_ms\": \"Nota tambahan dalam Bahasa Melayu...\"
}";

            // Use GEMINI_MODEL env var or default to gemini-2.0-flash
            $model = config('services.gemini.model', 'gemini-2.0-flash');

            $response = Http::timeout(60)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data'      => $imageData,
                                    ],
                                ],
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.2,
                        'topK'            => 32,
                        'topP'            => 1,
                        'maxOutputTokens' => 8192,  // Increased to prevent JSON truncation
                    ],
                ]
            );

            if ($response->failed()) {
                // Surface the actual Gemini error message for easier debugging
                $errBody = $response->json();
                $errMsg  = $errBody['error']['message'] ?? $response->body();
                return response()->json([
                    'error' => 'Gemini API error (' . $response->status() . '): ' . $errMsg,
                ], 500);
            }

            $body = $response->json();
            $rawText = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Strip markdown code fences
            $rawText = preg_replace('/```json\s*/i', '', $rawText);
            $rawText = preg_replace('/```\s*/i', '', $rawText);
            $rawText = trim($rawText);

            // Try to extract JSON object even if surrounded by extra text
            if (preg_match('/\{[\s\S]*\}/m', $rawText, $matches)) {
                $rawText = $matches[0];
            }

            $result = json_decode($rawText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // JSON truly failed — parse what we can from the raw text
                return response()->json([
                    'disease_name_en'            => 'Analysis Complete',
                    'disease_name_ms'            => 'Analisis Selesai',
                    'confidence'                 => 0,
                    'severity'                   => 'Unknown',
                    'reasoning_en'               => 'Error parsing response.',
                    'reasoning_ms'               => 'Ralat memproses maklum balas.',
                    'intervention_water_en'      => 'Consult your local agricultural officer.',
                    'intervention_water_ms'      => 'Sila rujuk pegawai pertanian tempatan.',
                    'intervention_fertilizer_en' => 'Consult your local agricultural officer.',
                    'intervention_fertilizer_ms' => 'Sila rujuk pegawai pertanian tempatan.',
                    'intervention_treatment_en'  => 'Consult your local agricultural officer.',
                    'intervention_treatment_ms'  => 'Sila rujuk pegawai pertanian tempatan.',
                    'resource_optimization_en'   => 'Follow precise local schedules to optimize resource usage.',
                    'resource_optimization_ms'   => 'Ikut jadual tempatan yang tepat untuk mengoptimumkan penggunaan sumber.',
                    'additional_notes_en'        => '',
                    'additional_notes_ms'        => '',
                ]);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate and download a PDF treatment plan.
     */
    public function downloadPdf(Request $request)
    {
        $data = [
            'disease_name'            => $request->input('disease_name', 'Unknown'),
            'confidence'              => $request->input('confidence', 0),
            'severity'                => $request->input('severity', 'Unknown'),
            'reasoning'               => $request->input('reasoning', ''),
            'intervention_water'      => $request->input('intervention_water', ''),
            'intervention_fertilizer' => $request->input('intervention_fertilizer', ''),
            'intervention_treatment'  => $request->input('intervention_treatment', ''),
            'resource_optimization'   => $request->input('resource_optimization', ''),
            'additional_notes'        => $request->input('additional_notes', ''),
            'generated_at'            => now()->format('d M Y, H:i'),
        ];

        $pdf = Pdf::loadView('pdf.treatment_plan', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download('ORIPadi-Treatment-Plan-' . now()->format('Ymd-His') . '.pdf');
    }
}
