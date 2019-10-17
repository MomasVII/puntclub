#!/bin/bash

#change the terminal window size (60 lines tall, 95 characters wide)
printf '\e[8;60;95t'

printf '///////////////////////////////////////////////////////////////////////////\n'
printf '// Framework: CORE\n'
printf '// Build Version 1.1.0\n'
printf '// Type: Automation batch script\n'
printf '// Author: Gordon MacK\n'
printf '///////////////////////////////////////////////////////////////////////////\n'
printf '\n'

printf '///////////////////////////////////////////////////////////////////////////\n'
printf '// 1 - Shutdown a virtual machine\n'
printf '///////////////////////////////////////////////////////////////////////////\n'
printf '\n'

#find absolute directory path
directory_path="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
directory_path="$(dirname "$directory_path")"

#move to correct directory
cd "$directory_path"

#reset directory to plain directory name
directory_name=${PWD##*/}

printf 'Domain / Project / Directory: %s\n' "$directory_name"

printf '\n'
printf '///////////////////////////////////////////////////////////////////////////\n'
printf '\n'

#list the contents of the current dirctory
printf 'Directory Content:'
printf '\n'

for entry in "$directory_path"/*
do
	printf '%s\n' "$entry"
done

printf '\n'
printf '///////////////////////////////////////////////////////////////////////////\n'
printf '\n'

printf 'Running vagrant halt\n'

printf '\n'
printf '///////////////////////////////////////////////////////////////////////////\n'
printf '\n'

vagrant halt

printf '\n'
printf '///////////////////////////////////////////////////////////////////////////\n'
printf '\n'

printf 'Process complete\n'
