#! /bin/bash

pushd .
mkdir /web
cd /web

mkdir vps1122
cd vps1122

git clone git://github.com/GerHobbelt/CompactCMS.git .
#git pull --all
rm -rf .git
rm .gitignore

find ./ -exec chown www-data.www-data "{}" \;


popd

