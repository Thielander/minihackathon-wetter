<?php
$latitude = $_GET['latitude'] ?? 52.5200;
$longitude = $_GET['longitude'] ?? 13.4050;
$timezone =  'Europe/Berlin';

// API URL für Open-Meteo mit Zeitzone
$apiUrl = "https://api.open-meteo.com/v1/forecast?latitude=$latitude&longitude=$longitude&hourly=temperature_2m,wind_speed_10m,precipitation_probability&current_weather=true&timezone=$timezone";

// Abruf der Wetterdaten
$response = file_get_contents($apiUrl);
$weatherData = json_decode($response, true);

// Debugging-Ausgabe in den PHP-Log
error_log(print_r($weatherData, true));

// Überprüfen, ob die Wetterdaten abgerufen wurden
if ($weatherData === NULL) {
    $response = [
        'city' => 'Berlin',
        'temperature' => 'N/A',
        'wind_speed' => 'N/A',
        'precipitation_probability' => 'N/A',
        'useFallback' => true
    ];
} else {
    $currentWeather = $weatherData['current_weather'];
    $response = [
        'city' => 'Ihr Standort',
        'temperature' => $currentWeather['temperature'],
        'wind_speed' => $currentWeather['windspeed'],
        'precipitation_probability' => $currentWeather['precipitation_probability'],
        'useFallback' => false
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
