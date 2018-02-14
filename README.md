# YACRS
YACRS (Yet Another Class Response System) is a classroom interaction system that allows students to use their own
devices to respond to questions during class.

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
YACRS was originally written by Niall S F Barr (niall.barr@glasgow.ac.uk) for the University of Glasgow. The application
was updated as part of a University of Glasgow Computing Science 3rd Year Group Project. 

To cite this software in a publication please use something like this:

Barr, Niall S. F. (2015). YACRS: Yet Another Class Response System : University of Glasgow. Available at: https://github.com/niallb/YACRS [Accessed dd Mon. yyyy].