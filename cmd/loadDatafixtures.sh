#! /bin/bash
#create fixtures

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force

root="`pwd`/public/files"
rm -rf  "${root}/"*

php bin/console doctrine:fixtures:load


