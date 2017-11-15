# YACRS
Web based classroom response software

## Requirements
* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)
* Currently requires an LDAP host for authentication. (LTI alternative coming soon)

# How To Run
To run in a development environment execute the following command (maybe as root):
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up
```

* YACRS: 127.0.0.1:4000
* PhpMyAdmin: 127.0.0.1:4001
* MySql 3306: 127.0.0.1:4002

## Copyright and Licence
YACRS is Copyright (c) 2013-2015, The University of Glasgow and is written by 
Niall S F Barr (niall.barr@glasgow.ac.uk)

Licensed under the Apache License http://www.apache.org/licenses/LICENSE-2.0

Some files distributed with YACRS have other copyright and/or licensing.
* Files in the corelib folder are Copyright (c) 2005-2015 Niall S F Barr
* PHP files in the lib/PHP_Word_Cloud-master folder are Copyright (c) 2010-2011 dreamcraft.ch, were obtained from https://github.com/sixty-nine/PHP_Word_Cloud and are licensed under the MIT license.
* ttf files in the lib/PHP_Word_Cloud-master folder were obtained from https://fedorahosted.org/liberation-fonts/ and are Licensed under the SIL Open Font License, Version 1.1. 
* Files in the phpqrcode folder are Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm> and Licensed under the LGPL licence version 3. 

<hr/>
To cite this software in a publication please use something like this:

Barr, Niall S. F. (2015). YACRS: Yet Another Class Response System : University of Glasgow. Available at: https://github.com/niallb/YACRS [Accessed dd Mon. yyyy].