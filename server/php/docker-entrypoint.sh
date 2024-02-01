#!/bin/bash

if [ $# -eq 0 ]; then
    echo "No arguments provided"
else
    # run command with exec to pass control
    echo "Running CMD: $@"
fi

exec "$@"
