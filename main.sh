#!/bin/bash -uex

whoami
composer --version
docker-compose --version
xvfb-run --help


export JPOSTAL_VERSION_TXT=jquery.jpostal.js/version.txt
export JPOSTAL_JSON_DIR=jquery.jpostal.js/json

composer install

./download_zip.sh
./git_pull_jquery_jpostal_js.sh

php main.php
cat jquery.jpostal.js/json/000.json

./docker-compose_up.sh
./selenium_server_start.sh
./selenese_local.sh
./selenium_server_stop.sh
./docker-compose_down.sh

errcnt=$(grep ERROR testResults/TEST-smoke_local.xml | wc --line)
echo errcnt=$errcnt

if [ $errcnt -eq 0 ]; then
  ./git_commit_jquery_jpostal_js.sh
fi

exit $errcnt
