#! /bin/bash

rm ./mootools-core.js
rm ./mootools-more.js
rm ./mochaUI.js

pushd .
cd mootools-core
./build > ../mootools-core.js
popd


pushd .
cd mootools-more
./build > ../mootools-more.js
popd


pushd .
cd mochaui
./build > ../mochaUI.js
popd


