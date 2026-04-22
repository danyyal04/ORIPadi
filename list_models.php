<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? '';

// List all available models
$ch = curl_init("https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}&pageSize=50");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Status: {$httpCode}\n\n";
$data = json_decode($response, true);

if (isset($data['models'])) {
    echo "Available models that support generateContent:\n";
    foreach ($data['models'] as $model) {
        $methods = $model['supportedGenerationMethods'] ?? [];
        if (in_array('generateContent', $methods)) {
            echo "  ✅ " . $model['name'] . " — " . ($model['displayName'] ?? '') . "\n";
        }
    }
} else {
    echo "Error: " . ($data['error']['message'] ?? $response) . "\n";
}
