#!/bin/sh

./scripts/partials/include.sh

rm -rf ./temp

if [ -d "./bin" ]; then
    rm -rf ./bin
fi

if [ -d "./temp" ]; then
    rm -rf ./temp
fi
