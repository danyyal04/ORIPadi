<?php
// Quick Gemini API connectivity test
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
$model  = $_ENV['GEMINI_MODEL']  ?? 'gemini-2.0-flash';

if (empty($apiKey) || $apiKey === 'your_gemini_api_key_here') {
    echo "ERROR: GEMINI_API_KEY is not set in .env\n";
    exit(1);
}

echo "Testing model: {$model}\n";
echo "Key prefix: " . substr($apiKey, 0, 10) . "...\n\n";

$url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

$payload = json_encode([
    'contents' => [[
        'parts' => [['text' => 'Say "PadiGuard API OK" and nothing else.']]
    ]],
    'generationConfig' => ['maxOutputTokens' => 20]
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT        => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: {$httpCode}\n";

$data = json_decode($response, true);

if ($httpCode === 200) {
    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '(no text)';
    echo "SUCCESS! Gemini replied: {$text}\n";
} else {
    $errMsg = $data['error']['message'] ?? $response;
    echo "FAILED! Error: {$errMsg}\n";
    echo "\nFull response:\n{$response}\n";
}
