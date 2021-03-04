#!/bin/bash
EXTENSIONS=$@

for extension in $EXTENSIONS
do
    if php -m | grep -q $extension; then
        echo "Extension $extension is already installed"
    else
        echo "-- Installing Extension: $extension --"
        docker-php-ext-install $extension
    fi
done