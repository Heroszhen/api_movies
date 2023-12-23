#! /bin/bash
#compile assert

rm -rf public/assets
php bin/console sass:build
php bin/console asset-map:compile
rm -rf public/assets/bundles