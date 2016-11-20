import sys
import os
import Adafruit_DHT
from datetime import datetime
import csv
from time import sleep
from threading import Thread


#### Logging Settings ####
FILENAME = ""
WRITE_FREQUENCY = 5
DELAY = 60

#### Functions ####
def timed_log():
    while True:
      log_data()
      sleep(DELAY)

def log_data():
  output_string = ",".join(str(value) for value in data)
  batch_data.append(output_string)

def file_setup(filename):
  header  =["timestamp","humidity","temp"]
  
  with open(filename,"w") as f:
      f.write(",".join(str(value) for value in header)+ "\n")

def collect_time():
   return datetime.strftime(datetime.now(), '%Y-%m-%d %H:%M')

def collect_data():
    humidity, temperature = Adafruit_DHT.read_retry(Adafruit_DHT.AM2302, 4)
    if humidity is not None and temperature is not None:
        temperature = temperature * 9/5.0 + 32
    else:
        temperature = None
        humidity = None
    
    return humidity, temperature

def gather_data():
    reading = collect_data()
    
    data = []
    data.append(collect_time())
    
    data.append(reading[0])
    data.append(reading[1])
    
    return data

#### Main Program ####
batch_data = []

if FILENAME == "":
  filename = "/home/pi/data/WeatherLog-" + str(datetime.now().date()) + ".csv"
else:
  filename = FILENAME + "-" + str(datetime.now().date()) + ".csv"

file_setup(filename)

while True:
    data = gather_data()
    print('reading complete')
    log_data()
    if len(batch_data) >= WRITE_FREQUENCY:
        print("Writing to file..")
        with open(filename,"a") as f:
            for line in batch_data:
                f.write(line + "\n")
            batch_data = []
    sleep(DELAY)

# aggregate, print data
#data = [date_time, temperature, humidity]
#print "Data: ",
#print (data)

# get csv ready for new record
#out = open('/home/pi/data.csv', 'a')
#print >> out, ','.join([date_time, str(temperature), str(humidity)])
#out.close()



#if humidity is not None and temperature is not None:
#    print('Temp={0:0.1f}*  Humidity={1:0.1f}%'.format(temperature, humidity))
#else:
#    print('Failed to get reading. Try again!')
#    sys.exit(1)
