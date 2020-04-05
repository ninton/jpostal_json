#!/bin/bash -uex

if [ -d jquery.jpostal.js ]; then
  rm -rf jquery.jpostal.js
fi
git clone git@github.com:ninton/jquery.jpostal.js.git

cd jquery.jpostal.js
git checkout develop
git pull origin develop
