#!/bin/bash

# fetch latest list of packages from repositories
apt-get update

# upgrade/update all packages currently installed on the system, forcibly upgrading EVERYTHING
apt-get dist-upgrade --ignore-hold

for pkg in `grep -v -h '#' install_vps_everything.txt | tr -s '[\r\n]' ' ' ` ; do
	# skip the newlines produced by the for+grep combo: only do real packages!
	if test "$pkg" '>' 'a' ; then 
		echo --- apt-get install [$pkg]
		apt-get install $pkg
	fi
done

# install 7zip
./install-7zip.sh

# install the packages we need for CompactCMS:
./install_apache_et_al.sh

# and clean up the packages which were only installed temporarily during this run
apt-get -y autoremove
