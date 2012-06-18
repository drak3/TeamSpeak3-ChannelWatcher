#!/bin/bash

if [ -d build/ ]
then
mkdir build
fi

cd build/

read -p "Version:" VERSION

if [-d $VERSION ]
then
mkdir $VERSION
fi

cp -rf ../../* .

rm -rf doc
rm -rf tests

wget getcomposer.org/composer.phar
php composer.phar install

find . -type d -name .git -print0 | xargs -0 -r rm -rf

rm composer*
rm phpunit*
rm -r .travis*
rm -r .git*
rm -rf nbproject

#delete this script too
rm make-package.sh


cd ../

zip -r "devMX TeamSpeak3 Webviewer v$version.zip" .
