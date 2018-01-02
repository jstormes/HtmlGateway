#!/usr/bin/env bash

cd /opt/project

if [ ! -d "/opt/project/vendor" ]; then
    composer install
fi

phpunit
