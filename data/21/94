#include <Arduino.h>
#include "Kalman.h"
#include "Acc_Gyro_Routines.h"


Kalman kalmanX; // Create the Kalman instances
Kalman kalmanY;

double roll, pitch;
double rate_x, rate_y;

double kalAngleX, kalAngleY; 
uint32_t timer;

void setup() {

  Serial.begin(9600);


  /* Code section related to Gyro & Acc and Kalman filter */
  /* Acc & Gyro*/
  init_pins();
  calibrateSensors(); 

  kalmanX.setAngle(roll); // Perceber que valores atribuir inicialmente
  kalmanY.setAngle(pitch);

  timer = micros();
}

void loop() {

  /* Code section related to Gyro & Acc and Kalman filter */

  /* Get Acc & Gyro values */
  pitch = getAccXAngle();
  roll = getAccYAngle();
  rate_x = getGyroXRate();
  rate_y = getGyroYRate();

  kalAngleX = kalmanX.getAngle(pitch, rate_x, (double)(micros() - timer) / 1000000);
  kalAngleY = kalmanY.getAngle(roll, rate_y, (double)(micros() - timer) / 1000000);
  timer = micros();
}
