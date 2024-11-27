#include <WiFi.h>
#include <DHT.h>

#define DHTPIN 4  // Pin where the DHT sensor is connected
#define DHTTYPE DHT22  // DHT 22 (AM2302)
DHT dht(DHTPIN, DHTTYPE);

const char *ssid = "ZTE_2.4G_R4amY7";  // Wi-Fi SSID
const char *password = "k7MbUDQR";     // Wi-Fi password

WiFiClient client;

void setup() {
  Serial.begin(115200);  // Start serial communication
  dht.begin();           // Initialize the DHT sensor

  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  delay(5000);  // Wait 5 seconds between readings

  // Read data from DHT sensor
  float humidity = dht.readHumidity();
  float temperature = dht.readTemperature();

  if (isnan(humidity) || isnan(temperature)) {
    Serial.println("Failed to read from DHT sensor");
    return;
  }

  // Print sensor data
  Serial.print("Temperature: ");
  Serial.print(temperature);
  Serial.print(" °C, Humidity: ");
  Serial.print(humidity);
  Serial.println(" %");

  // Send the data to the server
  if (client.connect("192.168.1.16", 80)) {  // Replace with your local IP address
    String postData = "temperature=" + String(temperature) + "&humidity=" + String(humidity);
    client.println("POST /arduino_web/upload_data.php HTTP/1.1");
    client.println("Host: 192.168.1.16");  // Replace with your local IP address
    client.println("Content-Type: application/x-www-form-urlencoded");
    client.print("Content-Length: ");
    client.println(postData.length());
    client.println();
    client.print(postData);

    // Wait for a response
    while (client.available()) {
      String line = client.readStringUntil('\r');
      Serial.println(line);  // Print the server's response
    }

    client.stop();
  } else {
    Serial.println("Connection failed");
  }
}
