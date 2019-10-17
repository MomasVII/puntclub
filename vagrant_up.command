#!/bin/bash

CurrDirName="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

printf '      Build: Rare_System_Core\n'
printf '     Version 0.0.6\n'
printf '    Author: Gordon MacK\n'
printf '   Copyright Owner: Raremedia Pty Ltd (Andrew Davidson)\n'
printf '\n'

printf '  ////////////////////////////////////////////////////////////////////////////////////\n'
printf ' ///// YOU ARE ABOUT TO INSTANTIATE/BOOT A VAGRANT VIRTUALBOX VM (vagrant up) ///////\n'
printf '////////////////////////////////////////////////////////////////////////////////////\n'
printf '\n'

cd "$CurrDirName"

printf 'Directory Name / Domain: %s\n' "$CurrDirName"
printf '\n'

printf 'Directory Content:'
printf '\n'

for entry in "$CurrDirName"/*
do
	printf '%s\n' "$entry"
done

printf '\n'
printf '////////////////////////////////////////////////////////////////////////////////////'
printf '\n'

printf 'Are you sure you want to continue? [Y/N]'
read answer
if echo "$answer" | grep -iq "^y" ;then

    printf '\n'
    printf "Running 'vagrant box update'"

	printf '\n'
	printf '////////////////////////////////////////////////////////////////////////////////////'
	printf '\n'

	vagrant box update

	printf '\n'
	printf '////////////////////////////////////////////////////////////////////////////////////'
	printf '\n'

    printf '\n'
    printf "Running 'vagrant up'"

	printf '\n'
	printf '////////////////////////////////////////////////////////////////////////////////////'
	printf '\n'

	vagrant up

	printf '\n'
	printf '////////////////////////////////////////////////////////////////////////////////////'
	printf '\n'

	printf 'Process complete'
	printf '\n'
fi