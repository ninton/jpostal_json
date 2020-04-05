#!/bin/bash -uex

message=$(date +%Y-%m-%d)

cd jquery.jpostal.js
git checkout develop
git add json/*

nochange=$(git status | grep "nothing to commit" | wc --line)

if [ $nochange -eq 0 ]; then
  git commit -m "update $message"
fi
