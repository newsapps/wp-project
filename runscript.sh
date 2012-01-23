#!/bin/bash
# runscript.sh
# Find and run a WordPress script

E_BADARGS=65

if [ ! -n "$1" ]
then
  echo "Usage: `basename $0` script_name script_arg1 script_arg2 etc."
  exit $E_BADARGS
fi

# Change to this project dir
cd `dirname "$0"`
this_dir=`pwd`

# TODO: Search active plugin directories for the script
# Look for the script in a couple project-wide directories
if [ -f wp-scripts/"$1".php ]
then
    # This is a project-specific script
    scriptname=wp-scripts/"$1".php
elif [ -f tools/wp-scripts/"$1".php ]
then
    # This is a generic script
    scriptname=tools/wp-scripts/"$1".php
fi

# Setup our PHP path - we want this top-level directory to checked
php_path=$this_dir:`php -r "echo get_include_path();"`

# knock the first item off the arguments array
shift

# run the script
php -d include_path=$php_path $scriptname "$@"
