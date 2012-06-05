#!/bin/bash

cd ../Build

rm -rf *

cp -rf ../TeamSpeak3-ChannelWatcher/* .

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

read -p "Version:" version

zip -r "devMX TeamSpeak3 Webviewer v$version.zip" .