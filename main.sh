#!/bin/bash

KEN_ALL_ZIP_URL="https://www.post.japanpost.jp/zipcode/dl/kogaki/zip/ken_all.zip"
JIGYOSYO_ZIP_URL="https://www.post.japanpost.jp/zipcode/dl/jigyosyo/zip/jigyosyo.zip"

function download_zip () {
    cd zips
    rm -rf *
    
    curl "$KEN_ALL_ZIP_URL" --output ken_all.zip
    unzip ken_all.zip
    iconv --from-code=SJIS-win --to-code=UTF-8  --output=KEN_ALL_UTF8.CSV  KEN_ALL.CSV

    curl "$JIGYOSYO_ZIP_URL" --output jigyosyo.zip
    unzip jigyosyo.zip
    iconv --from-code=SJIS-win --to-code=UTF-8  --output=JIGYOSYO_UTF8.CSV  JIGYOSYO.CSV

    cd ..
    ls -al zips
}

function init_config () {
    if [ ! -f config.sh  ]; then
        cp config.sample.sh config.sh
    fi
}

download_zip
init_config
source config.sh
php main.php
cat ${JPOSTAL_JSON_DIR}/000.json
