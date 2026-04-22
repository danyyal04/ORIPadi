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

            $lang = $request->input('lang', 'ms');
            $languageInstruction = ($lang === 'en')
                ? "IMPORTANT: You MUST write your reasoning, intervention steps, resource optimization, and notes entirely in English."
                : "IMPORTANT: You MUST write your reasoning, intervention steps, resource optimization, and notes entirely in Bahasa Melayu.";

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

{$languageInstruction}

IMPORTANT: Respond ONLY with valid JSON in this exact structure (no markdown, no code blocks):
{
  \"disease_name\": \"Name of disease in English (Nama dalam Bahasa Melayu)\",
  \"confidence\": 85,
  \"severity\": \"Low|Moderate|High|Healthy\",
  \"reasoning\": \"Detailed visual reasoning...\",
  \"intervention_water\": \"Specific water management advice...\",
  \"intervention_fertilizer\": \"Specific fertilizer advice...\",
  \"intervention_treatment\": \"Specific treatment/pesticide advice...\",
  \"resource_optimization\": \"Specific actionable advice on saving resources...\",
  \"additional_notes\": \"Any extra advice for Malaysian farmers...\"
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
                // Try to extract key fields with regex as last resort
                preg_match('/"disease_name"\s*:\s*"([^"]+)"/', $rawText, $dn);
                preg_match('/"confidence"\s*:\s*(\d+)/',         $rawText, $cf);
                preg_match('/"severity"\s*:\s*"([^"]+)"/',      $rawText, $sv);
                preg_match('/"reasoning"\s*:\s*"([^"]+)"/',     $rawText, $rs);
                preg_match('/"intervention_water"\s*:\s*"([^"]+)"/',      $rawText, $iw);
                preg_match('/"intervention_fertilizer"\s*:\s*"([^"]+)"/', $rawText, $if_);
                preg_match('/"intervention_treatment"\s*:\s*"([^"]+)"/',  $rawText, $it);
                preg_match('/"resource_optimization"\s*:\s*"([^"]+)"/',  $rawText, $ro);
                preg_match('/"additional_notes"\s*:\s*"([^"]+)"/',        $rawText, $an);

                return response()->json([
                    'disease_name'            => $dn[1]  ?? 'Analysis Complete',
                    'confidence'              => (int)($cf[1] ?? 0),
                    'severity'                => $sv[1]  ?? 'Unknown',
                    'reasoning'               => $rs[1]  ?? $rawText,
                    'intervention_water'      => $iw[1]  ?? 'Consult your local agricultural officer.',
                    'intervention_fertilizer' => $if_[1] ?? 'Consult your local agricultural officer.',
                    'intervention_treatment'  => $it[1]  ?? 'Consult your local agricultural officer.',
                    'resource_optimization'   => $ro[1]  ?? 'Follow precise local schedules to optimize resource usage.',
                    'additional_notes'        => $an[1]  ?? '',
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
