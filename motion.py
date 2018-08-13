import RPi.GPIO as GPIO
from picamera import PiCamera
import time

pin = 12
pic = 1
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(pin, GPIO.IN)

camera = PiCamera()
folder = '/var/www/html/images/security/'

while pic < 1000:

   if GPIO.input(pin) == 0:
      time.sleep(0.5)
   else:
      print ("PIR triggered " + str(pic))
      print (time.strftime("%a %b %d %Y"))
      for i in range(5):
         camera.capture(folder + 'image%04d.jpg' % pic)
         time.sleep(1)
         pic = pic + 1

##   while GPIO.input(pin) == 1:  # changed this line ....... 
##      time.sleep(0.5)
