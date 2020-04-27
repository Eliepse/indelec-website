#!/bin/sh

echo ""
echo "Pulling repository"
git pull

echo ""
echo "Removing vendors (js|php)"
rm -rf ./node_modules
rm -rf ./vendor

echo ""
echo "Installing back-end packages"
composer install --ansi --no-dev --no-interaction --profile --no-scripts --no-suggest --no-progress --prefer-dist --optimize-autoloader

echo ""
echo "Installing front-end packages"
yarn install

echo ""
echo "Building front-end files"
yarn run prod

echo ""
echo "Removing cache"
rm -rf ./storage/cache/*
