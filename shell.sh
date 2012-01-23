#!/bin/bash

# PHPSH hides all errors and output from includes, so here we
# lookup the php error_log file location (which you should 
# configure in your php.ini) and we have it outputed to the 
# screen while PHPSH is running.

log_path=`php -r "echo ini_get('error_log');"`
tail -n 0 -f $log_path &
phpsh tools/cli-load.php
killall tail
