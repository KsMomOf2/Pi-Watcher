#!/bin/bash

echo '<h1>hello</h1>'

if [ -f "images/security/timelapse.mp4" ]
then
    sudo rm images/security/timelapse.mp4
    sudo rm -rf images/security/thumbs
fi

#sudo ffmpeg -r 10 -i images/security/image%04d.jpg -c:v libx264 -crf 15 -pix_fmt yuv420p images/security/timelapse.mp4
ffmpeg -y -r 10 -i images/security/image%04d.jpg -c:v libx264 -crf 15 -pix_fmt  yuv420p images/security/timelapse.mp4 </dev/null >/dev/null 2>/var/log/ffmpeg.log &



