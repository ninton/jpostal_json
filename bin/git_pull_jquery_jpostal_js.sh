#!/bin/bash -uex

/bin/rm -rf jquery.jpostal.js
# personal access tokenは httpsにしか使えない
git clone https://ninton:$GITHUB_JQUERY_JPOSTAL_TOKEN@github.com/ninton/jquery.jpostal.js.git

cd jquery.jpostal.js
git branch develop
git checkout develop
