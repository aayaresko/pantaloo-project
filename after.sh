#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.
#
# If you have user-specific configurations you would like
# to apply, you may also create user-customizations.sh,
# which will be run after this script.

composer global require deployer/deployer > /dev/null 2>&1
composer global require deployer/recipes > /dev/null 2>&1
cd code
cp .env.local .env
composer install -o

