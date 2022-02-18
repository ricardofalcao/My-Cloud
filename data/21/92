#include <Arduino.h>
#include "Kalman.h"
#include "Acc_Gyro_Routines.h"


/* Acc & Gyro constants */
const int groundpin = 18;             // analog input pin 4 -- ground
const int powerpin = 19;              // analog input pin 5 -- voltage

const int acc_xpin = A4;              // x-axis accelerometer output
const int acc_ypin = A3;              // y-axis accelerometer output

const int gyro_xpin = A2;             // x-axis gyroscope output
const int gyro_ypin = A1;             // y-axis gyroscope output

int acc_x = 0;                        //Variable to store xpin value
int acc_y = 0;                        //Variable to store ypin value

int gyro_x = 0;                       //Variable to store xpin value
int gyro_y = 0;                       //Variable to store ypin value

double initValues[4];

void init_pins() {
  pinMode(groundpin, OUTPUT);
  pinMode(powerpin, OUTPUT);

  digitalWrite(groundpin, LOW);
  digitalWrite(powerpin, HIGH);
}

void calibrateSensors() {
  // Take the average of 100 readings
  for (uint8_t i = 0; i < 100; i++) { 
    initValues[0] += analogRead(acc_x);
    initValues[1] += analogRead(acc_y);
    initValues[2] += analogRead(gyro_x);
    initValues[3] += analogRead(gyro_y);
   
    delay(10);
  }
    initValues[0] /= 100; // Accelerometer X value
    initValues[1] /= 100; // Accelerometer Y value
    initValues[2] /= 100; // Gyroscope X value
    initValues[3] /= 100; // Gyroscope Y value
}

double getAccXAngle() {
  return ((double)(asin((analogRead(acc_xpin) - initValues[0])*1000/(ACC_SENSIVITY)))*(180/M_PI));
}

double getAccYAngle() {
  return ((double)(asin((analogRead(acc_ypin) - initValues[1])*1000/(ACC_SENSIVITY)))*(180/M_PI));
}

double getGyroXRate() {
  return ((double)(analogRead(gyro_xpin) - initValues[2])/(1000*GYRO_SENSIVITY));
}

double getGyroYRate() {
  return ((double)(analogRead(gyro_ypin) - initValues[3])/(1000*GYRO_SENSIVITY));
}