#include <WiFi.h>

char ssid[] = "SSID";    //  your network SSID (name) 
char pass[] = "PASSWORD";    // your network password
int status  = WL_IDLE_STATUS;   // the Wifi radio's status

void setup() {
  // initialize serial:
  Serial.begin(9600);

  // attempt to connect using WPA2 encryption:
  Serial.println("Attempting to connect to WPA network...");
  status = WiFi.begin(ssid, pass);

  // if you're not connected, stop here:
  if ( status != WL_CONNECTED) { 
    Serial.println("Couldn't get a wifi connection");
    while(true);
  } 
  // if you are connected, print out info about the connection:
  else {
    Serial.println("Connected to network");
    IPAddress shieldIP = WiFi.localIP();
    Serial.println(shieldIP);
  }
}

void loop() {
  // do nothing
}
