#!/bin/sh -ue

cd jpostal_json.docker
sudo docker-compose build
sudo docker-compose up -d
cd ..
