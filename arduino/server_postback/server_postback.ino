#include <SPI.h>
#include <WiFi.h>
#include <PowerFunctions.h>

PowerFunctions pf(8, 0);

char ssid[] = "";
char pass[] = "";

int status = WL_IDLE_STATUS;
// if you don't want to use DNS (and reduce your sketch size)
// use the numeric IP instead of the name for the server:
IPAddress server(192, 168, 1, 8); // numeric IP for Google (no DNS)

// Initialize the Ethernet client library
// with the IP address and port of the server
// that you want to connect to (port 80 is default for HTTP):
WiFiClient client;

unsigned long lastConnectionTime = 0;
const unsigned long postingInterval = 10L * 1000L;

void setup() {
  //Initialize serial and wait for port to open:
  Serial.begin(9600);
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }

  // check for the presence of the shield:
  if (WiFi.status() == WL_NO_SHIELD) {
    Serial.println("WiFi shield not present");
    // don't continue:
    while (true);
  }

  String fv = WiFi.firmwareVersion();
  if (fv != "1.1.0") {
    Serial.println("Please upgrade the firmware");
  }

  // attempt to connect to Wifi network:
  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to SSID: ");
    Serial.println(ssid);
    // Connect to WPA/WPA2 network. Change this line if using open or WEP network:
    status = WiFi.begin(ssid, pass);

    // wait 10 seconds for connection:
    delay(4500);
  }
  Serial.println("Connected to wifi");
  printWifiStatus();
  }

void loop() {
  int instructionsArray[100];
  int count       = 0;
  
  bool begin      = false;
  bool firstDigit = true;
  
  String response = "";
  
  while (client.available()) {
    char c = client.read();

    if (c == '[') {
      begin = true;
    }

    if (begin && isDigit(c))
    {
      if(firstDigit)
      {
        if(c == 48)
        {
          firstDigit = false;
        }
        else
        {
          break;
        }
      }
      
      instructionsArray[count] = c;
      Serial.println(c);
      count++;
      switch(c)
      {
        // 1
        case 49:
        {
            // Forward
            pf.combo_pwm(PWM_REV5, PWM_FWD5);
            Serial.println("Forward!");
            delay(1000);
        }
        break;
        // 2
        case 50:
        {
            // Right
            pf.combo_pwm(PWM_FWD5, PWM_FWD5); 
            Serial.println("Spin to the right!");
            delay(900);
        }
        break;
        // 3
        case 51:
        {
            // Left
            pf.combo_pwm(PWM_REV5, PWM_REV5); 
            Serial.println("Spin to the left!");
            delay(900);
        }
        break;
        // 4
        case 52:
        {
            // Backward
            pf.combo_pwm(PWM_FWD5, PWM_REV5); 
            Serial.println("Back up!");
            delay(1000);
            pf.combo_pwm(PWM_BRK, PWM_BRK);
        }
        break;
        // 5
        case 53:
        {
            // Right slow turn
            pf.single_pwm(RED, PWM_FWD5);
            Serial.println("Slow right turn!");
            delay(1000);
        }
        break;
        // 6
        case 54:
        {
            // Left slow turn
            pf.single_pwm(BLUE, PWM_REV5);
            Serial.println("Slow left turn!");
            delay(1000);
            pf.single_pwm(BLUE, PWM_BRK);
        }
        break;
        default:
        {
            Serial.println("Nothing to do here");
            delay(1000);
        }
        break;
      }
    }
    
    if (c == ']') {
      break;
    }
    
    delay(1);
  }

  
  if (millis() - lastConnectionTime > postingInterval) {
    httpRequest();
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
}

void httpRequest() {
    client.stop();
  
    Serial.println("\nStarting connection to server...");
    if (client.connect(server, 80)) {
      Serial.println("connected to server");
      client.println("GET / HTTP/1.0");
      client.println("Connection: close");
      client.println();

      lastConnectionTime = millis();
    } 
    else {
      Serial.println("connection failed");
    }
}
