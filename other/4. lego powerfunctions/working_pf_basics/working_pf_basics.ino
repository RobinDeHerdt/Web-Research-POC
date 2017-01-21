#include <SPI.h>

#include <WiFi.h>
#include <WiFiClient.h>
#include <WiFiServer.h>
#include <WiFiUdp.h>

#include <PowerFunctions.h>

PowerFunctions pf(8, 0);

char ssid[] = "";
char pass[] = "";
int state   = 0; 

int status = WL_IDLE_STATUS;
WiFiServer server(80);

void setup() {
  Serial.begin(9600);      // initialize serial communication

  if (WiFi.status() == WL_NO_SHIELD) {
    Serial.println("WiFi shield not present");
    while (true);       // don't continue
  }

  // attempt to connect to Wifi network:
  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to Network named: ");
    Serial.println(ssid);                   // print the network name (SSID);

    // Connect to WPA/WPA2 network. Change this line if using open or WEP network:
    status = WiFi.begin(ssid, pass);
    // wait 10 seconds for connection:
    delay(10000);
  }
  server.begin();                           // start the web server on port 80
  printWifiStatus();                       // you're connected now, so print out the status
}

void loop() {
  WiFiClient client = server.available();   // listen for incoming clients

  switch(state)
  {
    case 1:
    {
        // Forward
        pf.combo_pwm(PWM_REV5, PWM_FWD5);
        delay(1000);  
    }
    break;
    case 2:
    {
        // Right
        pf.combo_pwm(PWM_FWD5, PWM_FWD5);
        delay(1000);  
    }
    break;
    case 3:
    {
        // Left
        pf.combo_pwm(PWM_REV5, PWM_REV5);
        delay(1000);  
    }
    break;
    case 4:
    {
        // Backward
        pf.combo_pwm(PWM_FWD5, PWM_REV5);
        delay(1000);  
    }
    break;
    default:
    {
        Serial.println("nothing to do here");
        delay(1000);
    }
    break;
  }

  if (client) {                             // if you get a client,
    Serial.println("new client");           // print a message out the serial port
    String currentLine = "";                // make a String to hold incoming data from the client
    while (client.connected()) {            // loop while the client's connected
      if (client.available()) {             // if there's bytes to read from the client,
        char c = client.read();             // read a byte, then
        Serial.write(c);                    // print it out the serial monitor
        if (c == '\n') {                    // if the byte is a newline character

          // if the current line is blank, you got two newline characters in a row.
          // that's the end of the client HTTP request, so send a response:
          if (currentLine.length() == 0) {
            // HTTP headers always start with a response code (e.g. HTTP/1.1 200 OK)
            // and a content-type so the client knows what's coming, then a blank line:
            client.println("HTTP/1.1 200 OK");
            client.println("Content-type:text/html");
            client.println();

            // the content of the HTTP response follows the header:
            client.print("<a href=\"/forward\">Forward</a><br>");
            client.print("<a href=\"/left\">Left</a><br>");
            client.print("<a href=\"/right\">Right</a><br>");
            client.print("<a href=\"/backward\">Backward</a><br>");

            // The HTTP response ends with another blank line:
            client.println();
            // break out of the while loop:
            break;
          } else {    // if you got a newline, then clear currentLine:
            currentLine = "";
          }
        } else if (c != '\r') {  // if you got anything else but a carriage return character,
          currentLine += c;      // add it to the end of the currentLine
        }

        // Check to see if the client request was "GET /H" or "GET /L":
        if (currentLine.endsWith("GET /forward")) {
          state = 1;
        }
        if (currentLine.endsWith("GET /right")) {
          state = 2;
        }
        if (currentLine.endsWith("GET /left")) {
          state = 3;
        }
        if (currentLine.endsWith("GET /backward")) {
          state = 4;
        }
      }
    }
    client.stop();
  }
}

void printWifiStatus() {
  // print the SSID of the network you're attached to:
  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());

  // print your WiFi shield's IP address:
  IPAddress ip = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ip);

  // print the received signal strength:
  long rssi = WiFi.RSSI();
  Serial.print("signal strength (RSSI):");
  Serial.print(rssi);
  Serial.println(" dBm");
  // print where to go in a browser:
  Serial.print("To see this page in action, open a browser to http://");
  Serial.println(ip);
}
