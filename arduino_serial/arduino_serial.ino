#include <PowerFunctions.h>

PowerFunctions pf(8, 0);

int incomingByte = 0;

void setup() {
  Serial.begin(9600);
}

void loop() {
  bool begin      = false;
  String response = "";
  int count       = 0;
  int instructionsArray[100];
  
  while (Serial.available()) {
    char c = Serial.read();

    delay(1);
    
    if (c == '[') {
      begin = true;
    }
    
    if (begin && isDigit(c))
    {
      instructionsArray[count] = c;
      count++;
      switch(c)
      {
        // 1
        case '1':
        {
            // Forward
            pf.combo_pwm(PWM_REV5, PWM_FWD5);
            Serial.println("Forward!");
            delay(1000);
        }
        break;
        // 2
        case '2':
        {
            // Right
            pf.combo_pwm(PWM_FWD5, PWM_FWD5); 
            Serial.println("Spin to the right!");
            delay(900);
        }
        break;
        // 3
        case '3':
        {
            // Left
            pf.combo_pwm(PWM_REV5, PWM_REV5); 
            Serial.println("Spin to the left!");
            delay(900);
        }
        break;
        // 4
        case '4':
        {
            // Backward
            pf.combo_pwm(PWM_FWD5, PWM_REV5); 
            Serial.println("Back up!");
            delay(1000);
            pf.combo_pwm(PWM_BRK, PWM_BRK);
        }
        break;
        // 5
        case '5':
        {
            // Right slow turn
            pf.single_pwm(RED, PWM_FWD5);
            Serial.println("Slow right turn!");
            delay(1000);
        }
        break;
        // 6
        case '6':
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
}
