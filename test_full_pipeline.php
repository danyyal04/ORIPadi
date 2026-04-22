<?php
// Full pipeline test — sends a tiny green image to Gemini and checks JSON parse
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
$model  = $_ENV['GEMINI_MODEL']  ?? 'gemini-2.5-flash';

// Create a tiny 10x10 green test image (simulates a leaf photo)
$img = imagecreatetruecolor(10, 10);
imagefill($img, 0, 0, imagecolorallocate($img, 60, 140, 60));
ob_start();
imagejpeg($img);
$jpegData = ob_get_clean();
imagedestroy($img);
$imageData = base64_encode($jpegData);

$prompt = 'You are an expert Malaysian agricultural scientist. Analyze this padi leaf image.
IMPORTANT: Respond ONLY with valid JSON, no markdown, no code blocks:
{"disease_name":"Name","confidence":85,"severity":"Healthy","reasoning":"reasoning","intervention_water":"water","intervention_fertilizer":"fertilizer","intervention_treatment":"treatment","additional_notes":"notes"}';

$payload = json_encode([
    'contents' => [[
        'parts' => [
            ['inline_data' => ['mime_type' => 'image/jpeg', 'data' => $imageData]],
            ['text' => $prompt],
        ]
    ]],
    'generationConfig' => ['temperature' => 0.2, 'maxOutputTokens' => 8192]
]);

$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 60,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data    = json_decode($response, true);
$rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

echo "HTTP: {$httpCode}\n";
echo "Raw response length: " . strlen($rawText) . " chars\n";
echo "Raw text:\n{$rawText}\n\n";

// Apply same extraction logic as controller
$rawText = preg_replace('/```json\s*/i', '', $rawText);
$rawText = preg_replace('/```\s*/i', '', $rawText);
$rawText = trim($rawText);
if (preg_match('/\{[\s\S]*\}/m', $rawText, $matches)) {
    $rawText = $matches[0];
}

$result = json_decode($rawText, true);

if (json_last_error() === JSON_ERROR_NONE) {
    echo "✅ JSON parsed successfully!\n";
    echo "  Disease:    " . $result['disease_name'] . "\n";
    echo "  Confidence: " . $result['confidence']   . "%\n";
    echo "  Severity:   " . $result['severity']     . "\n";
} else {
    echo "❌ JSON parse failed: " . json_last_error_msg() . "\n";
    echo "  Text was: " . substr($rawText, 0, 300) . "\n";
}
