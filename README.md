# Minihackathon Wetter

Dieses Projekt zeigt aktuelle Wetterdaten basierend auf der Geolocation des Benutzers an und bietet zusätzlich Kleider- und Aktivitätsempfehlungen basierend auf den Wetterdaten. Es nutzt die Open-Meteo API für Wetterdaten und die OpenAI API für Empfehlungen.

## Installation

### Voraussetzungen

- Composer

### Schritte

1. **Repository klonen**

   ```bash
   git clone https://github.com/Thielander/minihackathon-wetter.git
   cd minihackathon-wetter
    ```

2. **Umgebungsvariablen einrichten**
    Benenne die .env.example in .env um und trage deinen openai key ein
    ```bash
    OPENAI_API_KEY=dein-openai-api-schluessel
    ```

3. **Abhängigkeiten installieren**
    ```bash
    composer install
    ```