#!/bin/bash -uex

/bin/rm -rf jquery.jpostal.js
git clone git@github.com:ninton/jquery.jpostal.js.git

cd jquery.jpostal.js
git branch develop origin/develop
git checkout develop
