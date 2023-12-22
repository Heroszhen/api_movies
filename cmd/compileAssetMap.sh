#! /bin/bash
#compile assert

php bin/console sass:build
php bin/console asset-map:compile
rm -rf public/assets/bundles