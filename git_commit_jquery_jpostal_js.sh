#!/bin/bash -uex

message=$(date +%Y-%m-%d)

cd jquery.jpostal.js
git add json/*
git status
git commit -m "update $message"
