# RWD - REST with Docker
## User instructions
Under the assumption that your system has already `Docker` installed:

- Clone or download the project
- Change dir to `NGCP-challenge/`
- Run `docker-compose up`

This will create 2 containers:
- `mysql57` (database)
- `php73` (api)

In addition, a database called `ngcp_db` is created and stored in `NGCP-challenge/mysql/data/`, which maps the folder `/var/lib/mysql` in `mysql57` container. This database contains two empty tables fulfilling the requirements in this challenge. To access this database, e.g. using MySQL Workbench or SQuirreL SQL (see [this link](https://snapcraft.io/install/squirrelsql/ubuntu)), use:
- username: antonio
- password: antonio
- port: 6033

GET and POST requests can be made by connecting to port 8080, e.g. by using Postman.

> NOTE: This project is supposed to be OS agnostic. However, it has been tested only on Ubuntu 18.04.
