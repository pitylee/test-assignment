#!/bin/sh

if [ $# -eq 0 ]; then
    echo "No arguments provided, defaulting to npm install and run dev"

    # Install dependencies
    npm install --include=dev --legacy-peer-deps

    npm run dev
else
    # run command with exec to pass control
    echo "Running CMD: $@"
fi

exec "$@"
