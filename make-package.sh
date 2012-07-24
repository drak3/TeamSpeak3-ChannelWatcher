
#!/bin/bash

if ! [ -d build/ ]
then
mkdir build
fi

cd build/

read -p "Version: " VERSION

if ! [ -d $VERSION ]
then
mkdir $VERSION
fi

cd $VERSION

cp -rf ../../* .

rm -rf doc
rm -rf tests

curl -s https://getcomposer.org/installer | php
php composer.phar install

find . -type d -name .git -print0 | xargs -0 -r rm -rf

rm composer.*
rm phpunit.*
rm .travis.yml
rm .gitignore
rm .gitmodules
rm -rf nbproject
rm -rf build
rm -rf storage
rm config/local.php
#delete this script too
rm make-package.sh



zip -r "../devMX TeamSpeak3 Webviewer v$VERSION.zip" .
