#!/bin/sh -ue

cd docker
sudo docker-compose build
sudo docker-compose up -d
cd ..
