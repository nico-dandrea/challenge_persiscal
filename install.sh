#!/bin/bash
# Set -e: exit asap if a command exits with a non-zero status
set -e
echo "Checking if Docker is installed in the system"
echo "----------------------------------------------"

if [ -x "$(command -v docker)" ]; then
	echo "Docker is installed in the system"
else
	echo "Docker is not installed in the system"
	exit 1
fi

echo "Installing Laravel Sail and Composer non local dependencies"
echo "-------------------------------------------------------------"

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs

echo "We recommend creating an alias for sail in your .bashrc or .zshrc file"
echo "ALIAS_EXAMPLE:"
echo 'alias sail="sh $([ -f sail ] && echo sail || echo vendor/bin/sail)"'
echo "-----------------------------------------------------------------------"
