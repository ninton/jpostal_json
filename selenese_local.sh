#!/bin/bash -x

if [ ! -f $HOME/selenium/geckodriver ]; then
  echo "not found: $HOME/selenium/geckodriver"
fi


if [ ! -f $HOME/selenium/selenese-runner.jar ]; then
  echo "not found: $HOME/selenium/selenese-runner.jar"
fi

if [ ! -f $HOME/selenium/selenese-runner.jar ]; then
  echo "not found: $HOME/selenium/firefox.sh"
fi


# http://commons.apache.org/proper/commons-lang/download_lang.cgi
# http://ftp.meisei-u.ac.jp/mirror/apache/dist//commons/lang/binaries/commons-lang3-3.5-bin.tar.gz
# cp commons-lang3-3.5*.jar /usr/lib/jvm/java-8-oracle/jre/lib/ext/

function test_run() {
	java \
		-Dwebdriver.gecko.driver=$HOME/selenium/geckodriver \
		-jar ~/selenium/selenese-runner.jar \
		--firefox ~/selenium/firefox.sh \
		--xml-result testResults/ \
		$1
}

test_run selenese/smoke_local.html
