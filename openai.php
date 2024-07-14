<?php
require_once 'vendor/autoload.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Laden der .env-Datei
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// OpenAI API-Key aus der .env-Datei
$apiKey = getenv('OPENAI_API_KEY') ?: $_ENV['OPENAI_API_KEY'] ?: $_SERVER['OPENAI_API_KEY'];

if (!$apiKey) {
    echo json_encode(['error' => 'API key not found']);
    exit();
}

// Wetterdaten aus der URL abrufen
$temperature = $_GET['temperature'];
$wind_speed = $_GET['wind_speed'];
$precipitation_probability = $_GET['precipitation_probability'];

// Abfragen vorbereiten
$clothingPrompt = "Schlage anhand der folgenden Wetterdaten geeignete Kleidung vor: Temperature: {$temperature}°C, Windgeschwindigkeit: {$wind_speed} km/h, Niederschlagswahrscheinlichkeit: {$precipitation_probability}%";
$activityPrompt = "Schlage anhand der folgenden Wetterdaten Aktivitäten für heute vor: Temperature: {$temperature}°C, Windgeschwindigkeit: {$wind_speed} km/h, Niederschlagswahrscheinlichkeit: {$precipitation_probability}%";
// Funktion zur Abfrage der OpenAI API
function queryOpenAI($prompt, $apiKey) {
    $apiUrl = "https://api.openai.com/v1/completions";

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    );

    $data = array(
        "model" => "gpt-3.5-turbo-instruct",
        "prompt" => $prompt,
        "temperature" => 0,
        "max_tokens" => 1000,
        "top_p" => 0.9,
        "frequency_penalty" => 0,
        "presence_penalty" => 0.6
    );

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
        curl_close($ch);
        exit();
    }

    $responseData = json_decode($response, true);
    curl_close($ch);

    if (isset($responseData['choices'][0]['text'])) {
        return trim($responseData['choices'][0]['text']);
    } else {
        return 'Keine Antwort vom Modell';
    }
}

// Abfragen an die OpenAI API senden
$clothingResponse = queryOpenAI($clothingPrompt, $apiKey);
$activityResponse = queryOpenAI($activityPrompt, $apiKey);

// Antwort zusammenstellen
$response = [
    'clothing' => $clothingResponse,
    'activity' => $activityResponse,
];

echo json_encode($response);
?>
