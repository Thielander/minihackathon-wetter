document.addEventListener("DOMContentLoaded", function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        });
    } else {
        errorCallback({ message: "Geolocation is not supported by this browser." });
    }

    function successCallback(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);
        fetchWeatherData(latitude, longitude);
    }

    function errorCallback(error) {
        console.error(`Geolocation error: ${error.message}`);
        document.getElementById('placeholder-content').textContent = "Es ist nicht möglich, die aktuelle Position zu ermitteln. Erlaube die Stanortbestimmung um aktuelle Daten zu erhalten. Ohne die Standortbestimmung entgeht die eine KI Kleidungs- und Aktivitätsempfehlung.";
    }

    function fetchWeatherData(latitude, longitude) {
        fetch(`get_weather.php?latitude=${latitude}&longitude=${longitude}`)
            .then(response => response.json())
            .then(data => {
                console.log('Weather data:', data);
                const placeholderHeading = document.getElementById('placeholder-heading');
                const placeholderContent = document.getElementById('placeholder-content');

                if (placeholderHeading && placeholderContent) {
                    placeholderHeading.textContent = `Aktuelles Wetter für deinen Standort`;
                    placeholderContent.innerHTML = `
                        <p>Temperatur: ${parseFloat(data.temperature).toFixed(1)}°C</p>
                        <p>Wind: ${data.wind_speed} km/h</p>
                        <p>Regenwahrscheinlichkeit: ${data.precipitation_probability}%</p>
                        <br>
                        <h2>KI Empfehlung für den heutigen Tag.</h2>
                    `;

                    fetchOpenAIRecommendations(data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('placeholder-content').textContent = 'Fehler beim Abrufen der Wetterdaten.';
            });
    }

    function fetchOpenAIRecommendations(weatherData) {
        console.log('Fetching OpenAI recommendations...');
        fetch(`openai.php?temperature=${weatherData.temperature}&wind_speed=${weatherData.wind_speed}&precipitation_probability=${weatherData.precipitation_probability}`)
            .then(response => response.json())
            .then(data => {
                console.log('OpenAI response:', data);
                const recommendations = document.createElement('div');
                recommendations.innerHTML = `
                    <h4>Kleidungsempfehlung</h4>
                    <p>${formatResponse(data.clothing)}</p>
                    <h4>Aktivitäten</h4>
                    <p>${formatResponse(data.activity)}</p>
                `;
                document.getElementById('placeholder-content').appendChild(recommendations);
            })
            .catch(error => {
                console.error('OpenAI fetch error:', error);
            });
    }

    function formatResponse(response) {
        const lines = response.split(/\d+\.\s/).filter(line => line.trim() !== '');
        return lines.map((line, index) => `<p><strong>${index + 1}.</strong> ${line.trim()}</p>`).join('');
    }
});
