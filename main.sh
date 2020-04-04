#!/bin/bash -uex

export JPOSTAL_VERSION_TXT=jquery.jpostal.js/version.txt
export JPOSTAL_JSON_DIR=jquery.jpostal.js/json

composer install

./download_zip.sh
./git_pull_jquery_jpostal_js.sh

#php main.php

#cat ${JPOSTAL_JSON_DIR}/000.json
