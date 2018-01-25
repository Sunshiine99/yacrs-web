#!/bin/bash

# Force container rebuild
docker-compose rm -f
docker-compose build

# Run docker compose in test config
docker-compose -f docker-compose.yml -f docker-compose.phpunit.yml up --timeout 1 --no-build -d

# Stop the container and output the logs of the test
docker-compose stop
docker-compose logs web

# Gets exit code from docker
exitCode=$(docker-compose ps -q | xargs docker inspect -f '{{ .Name }} exited with status {{ .State.ExitCode }}' | grep /yacrsweb_web_1 | grep "status [0-9]*" -oh | grep "[0-9]*" -oh)

# Exists script with success/error exit code
exit $exitCode
