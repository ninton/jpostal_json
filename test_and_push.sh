#!/bin/bash -uex

docker-compose --version
xvfb-run --help

./bin/docker-compose_down.sh
./bin/docker-compose_up.sh
selenium_server_start.sh
selenese_run.sh selenese/smoke_local.html
selenium_server_stop.sh
./bin/docker-compose_down.sh

errcnt=$(grep ERROR testResults/TEST-smoke_local.xml | wc --line)
echo errcnt=$errcnt

if [ $errcnt -eq 0 ]; then
  ./bin/git_commit_jquery_jpostal_js.sh
  ./bin/git_push_jquery_jpostal_js.sh
fi

exit $errcnt
