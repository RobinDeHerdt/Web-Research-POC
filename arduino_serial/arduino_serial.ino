int incomingByte = 0;

void setup() {
    Serial.begin(9600);
    pinMode(8, OUTPUT);
}

void loop() {
    if (Serial.available() > 0) {
        incomingByte = Serial.read();
          
        Serial.println(char(incomingByte));

        if(char(incomingByte) == 'a')
        {
          digitalWrite(8, HIGH);
        }
        
        if(char(incomingByte) == 'b')
        {
          digitalWrite(8, LOW);
        }
    }
}
