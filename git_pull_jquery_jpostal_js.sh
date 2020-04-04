#!/bin/bash -uex

if [ ! -d jquery.jpostal.js ]; then
  git clone git@github.com:ninton/jquery.jpostal.js.git
fi

cd jquery.jpostal.js
git checkout develop
git pull origin develop
