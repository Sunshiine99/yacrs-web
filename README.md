# YACRS
YACRS (Yet Another Class Response System) is a classroom interaction system that allows students to use their own
devices to respond to questions during class.

This version of the software was developed as part of a University of Glasgow Computing Science Level 3 Team Project.

The team consisted of:
* Chase Condon
* David Southgate
* Michael McGinley
* Nor Albagdadi
* Hristo Ivanov

## Requirements
* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

# How To Run
To run in a development environment execute the following command (maybe as root):
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up
```

* YACRS: 127.0.0.1:4000
* PhpMyAdmin: 127.0.0.1:4001
* MySql 3306: 127.0.0.1:4002

## Copyright and Licence
YACRS is released under the MIT licence