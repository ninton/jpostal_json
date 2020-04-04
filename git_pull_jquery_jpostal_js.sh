#!/bin/bash -ue

if [ ! -d jquery.jpostal.js ]; then
  git clone git@github.com:ninton/jquery.jpostal.js.git
fi

cd jquery.jpostal.js
git checkout master
git pull origin master
