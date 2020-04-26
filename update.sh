git pull
composer install --no-dev --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist
yarn install
yarn run prod
rm -rf ./storage/cache/*