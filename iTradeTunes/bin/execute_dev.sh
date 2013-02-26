#!/bin/bash
#
# This script is used as a wrapper to execute the given PHP
# script in the PROD environment as it simplifies the cron files.
#
# @copyright  2009-2011 CommunitySquared Inc.
# @version    $Id$
# @since      File available since release 1.0.0.0

### CONFIG ###
SCRIPTSDIR="/var/www/vhosts/gogoverde.com/scripts/"
INCLUDE_PATH="/var/www/vhosts/gogoverde.com/lib:."
PHP_PATH="/usr/bin/php"

### CHECKS ###
if [ "$#" -eq 0 ]; then
	echo "Usage: $0 php-script.php [params]"
	exit
fi
SCRIPT=$1

### SCRIPT ###
# Execute the script
$PHP_PATH -d include_path="${INCLUDE_PATH}" ${SCRIPTSDIR}${SCRIPT} $2 $3 $4 $5 $6
