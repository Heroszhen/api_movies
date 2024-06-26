#! /bin/bash
#create fixtures

root="`pwd`"
rm -rf  "${root}/public/files"*
mkdir "${root}/public/files"
touch "${root}/public/files/.gitkeep"

rm -f "${root}/var/data/movies.db"
touch "${root}/var/data/movies.db"

# php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

php bin/console doctrine:fixtures:load


