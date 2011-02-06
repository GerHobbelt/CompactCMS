#!/bin/bash

# append the existing sources.list

#mv /etc/apt/sources.list  source.list.backup
cp sources.list /etc/apt/sources.list.d/ccms-sources.list

apt-get check
apt-get update
apt-get check

