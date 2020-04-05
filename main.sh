#!/bin/bash -uex

whoami
composer --version

export JPOSTAL_VERSION_TXT=jquery.jpostal.js/version.txt
export JPOSTAL_JSON_DIR=jquery.jpostal.js/json

composer install

./bin/download_zip.sh
./bin/git_pull_jquery_jpostal_js.sh

php main.php
cat jquery.jpostal.js/json/000.json
