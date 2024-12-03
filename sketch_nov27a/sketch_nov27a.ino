#include <WiFi.h>
#include <DHT.h>

// DHT22 setup
#define DHTPIN 9 // Digital pin for DHT22
#define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);

// MQ2 gas and smoke sensor setup
#define MQ2_ANALOG_PIN A0    // Analog pin for MQ2 gas and smoke sensor
#define MQ2_DIGITAL_PIN 8    // Digital pin for MQ2 threshold detection
#define WATER_LEVEL_PIN A1   // Analog pin for water level sensor

// IR sensor setup
#define IR_PIN 10

// Wi-Fi credentials
const char *ssid = "Redmi 10";
const char *password = "taetaetae";

// Server details
const char* server = "192.168.127.68"; // Replace with your server IP
const int port = 80;

// Create WiFiClient object
WiFiClient client;

// Threshold values
const int gasThreshold = 300;   // Threshold for gas/smoke sensor (adjust as needed)
const int waterLevelThreshold = 500; // Threshold for water level sensor (adjust as needed)
const float tempThresholdHigh = 30.0; // High temperature threshold (Celsius)
const float tempThresholdLow = 15.0;  // Low temperature threshold (Celsius)
const float humidityThresholdLow = 40.0; // Low humidity threshold (%)
const float humidityThresholdHigh = 60.0; // High humidity threshold (%)

void connectToWiFi() {
  Serial.println("Connecting to Wi-Fi...");
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("\nConnected to Wi-Fi");
}

void sendSensorData(int gasSmokeLevel, int thresholdStatus, int waterLevel, float temperature, float humidity, int irState) {
  if (client.connect(server, port)) {
    Serial.println("Connected to server");

    // Prepare POST data
    String postData = "gas_smoke_level=" + String(gasSmokeLevel) +
                      "&threshold_status=" + String(thresholdStatus) +
                      "&water_level=" + String(waterLevel) +
                      "&temperature=" + String(temperature) +
                      "&humidity=" + String(humidity) +
                      "&ir_state=" + String(irState);

    // Debugging the POST data
    Serial.println("POST Data Sent to Server:");
    Serial.println(postData);

    // Send HTTP POST request
    client.println("POST /arduino_web/insert_data.php HTTP/1.1");
    client.println("Host: " + String(server));
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.println("Content-Length: " + String(postData.length()));
    client.println(); // End of headers
    client.println(postData); // Send POST data

    // Read server response
    while (client.available()) {
      String response = client.readString();
      Serial.println("Server Response:");
      Serial.println(response);
    }

    client.stop(); // Close connection
  } else {
    Serial.println("Connection to server failed");
  }
}

void setup() {
  Serial.begin(115200);
  dht.begin();
  pinMode(IR_PIN, INPUT);

  // Connect to Wi-Fi
  connectToWiFi();
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    // Read sensor values
    int gasSmokeLevel = analogRead(MQ2_ANALOG_PIN);
    int thresholdStatus = digitalRead(MQ2_DIGITAL_PIN);
    int waterLevel = analogRead(WATER_LEVEL_PIN);
    float temperature = dht.readTemperature();
    float humidity = dht.readHumidity();
    int irState = digitalRead(IR_PIN);

    // Check thresholds for gas/smoke sensor
    if (gasSmokeLevel > gasThreshold) {
      thresholdStatus = 1; // Threshold exceeded
    } else {
      thresholdStatus = 0; // Below threshold
    }

    // Check thresholds for water level sensor
    int waterThresholdStatus = (waterLevel > waterLevelThreshold) ? 1 : 0; // Water level above threshold

    // Check thresholds for temperature and humidity
    int tempThresholdStatus = 0; // Default status is normal
    if (temperature > tempThresholdHigh) {
      tempThresholdStatus = 1; // High temperature
    } else if (temperature < tempThresholdLow) {
      tempThresholdStatus = -1; // Low temperature
    }

    int humidityThresholdStatus = 0; // Default status is normal
    if (humidity < humidityThresholdLow) {
      humidityThresholdStatus = -1; // Low humidity
    } else if (humidity > humidityThresholdHigh) {
      humidityThresholdStatus = 1; // High humidity
    }

    // Debugging sensor values
    Serial.println("Sensor Readings:");
    Serial.print("Gas/Smoke Level (Analog): ");
    Serial.println(gasSmokeLevel);
    Serial.print("Threshold Status (Digital): ");
    Serial.println(thresholdStatus);
    Serial.print("Water Level (Analog): ");
    Serial.println(waterLevel);
    Serial.print("Temperature (C): ");
    Serial.println(temperature);
    Serial.print("Humidity (%): ");
    Serial.println(humidity);
    Serial.print("IR State: ");
    Serial.println(irState);

    if (isnan(temperature) || isnan(humidity)) {
      Serial.println("Failed to read from DHT sensor!");
      return;
    }

    // Send data to server
    sendSensorData(gasSmokeLevel, thresholdStatus, waterLevel, temperature, humidity, irState);
  } else {
    Serial.println("Wi-Fi disconnected, attempting to reconnect...");
    connectToWiFi();
  }

  delay(1000); // Wait 10 seconds before sending again
}
