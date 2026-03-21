#!/bin/bash
set -e

# Clear existing lock files or state if needed
# touch storage/logs/laravel.log

# Start the application
exec "$@"
