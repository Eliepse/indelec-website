echo "Pulling repository"
git pull

echo "Removing vendors (js|php)"
rm -rf ./node_modules
rm -rf ./vendor

echo "Installing back-end packages"
composer install --no-dev --no-interaction --no-scripts --no-suggest --no-progress --prefer-dist

echo "Installing front-end packages"
yarn install

echo "Building front-end files"
yarn run prod

echo "Removing cache"
rm -rf ./storage/cache/*