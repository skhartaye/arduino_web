#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

// Replace with your network credentials
const char* ssid = "ZTE_2.4G_R4amY7";
const char* password = "k7MbUDQR";

// DHT sensor setup
#define DHTPIN 4          // Pin connected to the DHT22 sensor
#define DHTTYPE DHT22     // DHT 22 (AM2302)
DHT dht(DHTPIN, DHTTYPE);

const char* server = "http://192.168.1.100/arduino_web/upload_data.php";  // Change to your server's IP

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
  
  dht.begin();
}

void loop() {
  // Get temperature and humidity
  float temperature = dht.readTemperature();  // Get temperature in Celsius
  float humidity = dht.readHumidity();        // Get humidity in %

  if (isnan(temperature) || isnan(humidity)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

  // Build the URL with query parameters (temperature and humidity)
  String url = String(server) + "?temperature=" + String(temperature) + "&humidity=" + String(humidity);
  
  // Send the data to the PHP script
  HTTPClient http;
  http.begin(url);  // Specify the URL with query parameters
  int httpCode = http.GET();  // Send the GET request
  
  if (httpCode > 0) {
    Serial.println("Data sent successfully: " + String(httpCode));
  } else {
    Serial.println("Error sending data: " + String(httpCode));
  }

  http.end();  // Close the connection

  delay(60000);  // Wait for a minute before sending the next reading
}
