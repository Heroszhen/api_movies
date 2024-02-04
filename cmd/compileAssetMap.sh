#! /bin/bash
#compile assert

php bin/console c:c
rm -rf public/assets
# php bin/console sass:build
php bin/console asset-map:compile
rm -rf public/assets/bundles


# #minify js files
# dir=`pwd`
# dir="${dir}/public/assets/"

# #app.js
# for file in `ls -a ${dir}app*.js`;
# do 
#    uglifyjs $file -o  $file -c
# done

# for file in `ls -R ${dir}script/`;
# do 
#     if [[ $file =~ '.js' ]]
#     then
#         path=`find $(pwd) -name $file`
#         uglifyjs $path -o  $path -c
#         echo "${file} : minified"
#     fi
# done