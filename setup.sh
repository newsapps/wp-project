#!/bin/bash
#
# Use this shell script to install any external stuff you need to run this WordPress site
#

# install some python requirements
easy_install pip
pip install fabric
pip install git+http://github.com/facebook/phpsh.git
pip install pyfsevents
