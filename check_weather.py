import sys

import Adafruit_DHT

humidity, temperature = Adafruit_DHT.read_retry(Adafruit_DHT.AM2302, 4)
temperature = temperature * 9/5.0 + 32

if humidity is not None and temperature is not None:
    print('Temp={0:0.1f}*  Humidity={1:0.1f}%'.format(temperature, humidity))
else:
    print('Failed to get reading. Try again!')
    sys.exit(1)
