#include <PowerFunctions.h>

// Using IR on pin number 7
// 0 = Channel 1 ; 1 = channel 2 ; ... 
PowerFunctions pf(8, 0);

void setup() {
  // put your setup code here, to run once:
}

void loop() {
  // Both tracks forward:
  pf.combo_pwm(PWM_REV5, PWM_FWD5);
  // Both tracks reverse:
  // pf.combo_pwm(PWM_FWD5, PWM_REV5);
}
