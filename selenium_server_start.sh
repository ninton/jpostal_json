#!/bin/bash -x

if [ ! -f $HOME/selenium/selenium-server-standalone-3.141.59.jar ]; then
  echo "not found: $HOME/selenium-server-standalone-3.141.59.jar"
fi

pkill -f ~/selenium/selenium-server-standalone-3.141.59.jar
java -jar ~/selenium/selenium-server-standalone-3.141.59.jar &
pgrep -f ~/selenium/selenium-server-standalone-3.141.59.jar
