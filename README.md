Smart Kitchen System
Project Overview
This project is a Smart Kitchen System that utilizes various sensors (gas, motion, IR, temperature, flame, etc.) connected to an Arduino R4 to monitor different aspects of the kitchen environment. The data collected by the sensors is sent to a MySQL database via XAMPP running locally. The system aims to provide real-time monitoring and control for various kitchen appliances, such as an exhaust fan, based on sensor readings.

Components Used
Hardware
Arduino R4: Microcontroller used to manage sensor data and control actions based on sensor inputs.
Gas Sensor: Detects the presence of gases (e.g., methane or LPG) for safety.
Temperature Sensor (DHT22): Measures the temperature in the kitchen.
Motion Sensor (PIR): Detects motion, for example, to detect human presence in the kitchen.
Flame Sensor: Detects open flames for fire hazard prevention.
IR Sensor: Used for object detection or motion sensing with simple 0 (OFF) or 1 (ON) states.
Exhaust Fan: A fan that can be controlled via the Arduino R4 to activate when certain conditions (e.g., high gas concentration) are met.
XAMPP: Local web server for hosting a PHP/MySQL application to manage sensor data and display real-time updates on a dashboard.
Software
Arduino IDE: For writing and uploading code to the Arduino R4.
XAMPP: Provides a local server with PHP, MySQL, and Apache to store and manage data.
PHP: For handling the interaction between the Arduino and the database.
MySQL: Database to store sensor readings and sensor states.
Features
Real-time Sensor Data: Sensor data (e.g., gas level, motion status, temperature, flame presence) is collected and stored in a MySQL database.
Web Dashboard: The data can be viewed on a web interface using XAMPP that displays real-time updates for monitoring purposes.
Exhaust Fan Control: The system can control the exhaust fan using the Arduino R4, based on conditions such as high gas levels or detected flame.
Data Logging: All sensor data is logged in the database for historical analysis.
Setup Instructions
Hardware Setup
Connect Sensors:
Connect the Gas Sensor, Temperature Sensor, Motion Sensor, Flame Sensor, and IR Sensor to the Arduino R4 following the pin configurations in your Arduino sketch.
Connect the Exhaust Fan to an appropriate output pin on the Arduino R4, with a MOSFET or transistor for switching it on/off.
Power Supply:
Ensure all components are powered correctly, with a stable 5V supply for sensors and a suitable power supply for the exhaust fan.
Software Setup
XAMPP Setup:

Install XAMPP (if not already installed) and start the Apache and MySQL services.
Create a new database named smart_kitchen in phpMyAdmin or through a MySQL query.
Import the provided SQL schema to create the necessary tables for storing sensor data.
Arduino Code:

Open the Arduino IDE and upload the sketch to the Arduino R4.
The code should include logic for reading data from the sensors and sending it to the XAMPP server via HTTP requests.

further improvement in later dates adding iot auto open of fans when gas or smoke becomes danger level
        waterlevel warning buzzer and led alert can be turned off and on using the switch in the dashboard interface
        ir  auto open led(fire place) when person detected
        flame warning alert when flame is too big  buzzer and led can be turned off
        dht if too hot or too humid auto open ventilation (led as stand up) can be turned off

further improvement can be changed when budget or time is small
        
        
