# NGCP Challenge
## User instructions
Under the assumption that your system has already `Docker` installed:

- Clone or download the project
- Change dir to `NGCP-challenge/`
- Run `docker-compose up`

If MySQL throws an error, re-run `docker-compose up`.

A database called `ngcp_db` is created and stored in `NGCP-challenge/mysql/data/`, which maps the `mysql57` folder `/var/lib/mysql`, the default folder where MySQL stores data. To access this database, i.e. using MySQL Workbench, use:
- username: antonio
- password: antonio
- port: 6033

The web server is running on the image `php73`. GET and POST requested can be made by connecting to port 8080.

> NOTE: This project should be OS agnostic. However, it has been tested only on Ubuntu 18.04 VMs.

## Description
---
Create a simple service for handling of devices in a Motorola radio system. 

A device profile should be a record containing the following information:

- Radio ID (a unique integer)
- Radio alias (a string name)
- List of allowed locations (each location being a simple string id) (see [this link](https://stackoverflow.com/questions/17371639/how-to-store-arrays-in-mysql))
- Location (a string), that initially is set to undefined

The service should fulfill the following requirements:
 
#### POST /radios/{id}
- Implement a REST API that allows the following:
	- Storage of radio profiles
	- Payload should be JSON following this schema:

```
{
	“alias”: string,
	“allowed_locations”: array<string>
}
```

#### POST /radios/{id}/location
Setting a location of a radio that is accepted if the location is on the radio’s list of allowed locations and rejected otherwise. If location change is rejected radio’s location remains the last accepted location

- Payload should be JSON following this schema:

```
{
“location”: string
}
```
- Returns 200 OK for valid location
- Returns 403 FORBIDDEN for invalid location

#### GET /radios/{id}/location
Retrieval of a radio’s location

- Returns 200 OK with location in JSON form following the schema:

```
{
“location”: string
}
```
- Returns 404 NOT FOUND if no location exists

### Notes
---
- How the service stores data is up to you. 
- You can also use any programming language you want 
- Bonus points if you include a Dockerfile with all the dependencies that allows us to run your code inside a Docker container
- Your code should be uploaded to your GitHub account

### Example use cases
---
#### Scenario 1:
Create a radio profile with
- ID: 100
- Alias: “Radio100”
- Allowed Locations: [“CPH-1”, “CPH-2”]

```
POST /radios/100
Payload: { "alias": "Radio100", "allowed_locations": ["CPH-1", "CPH-2"] }
```

Create a radio profile with
- ID: 101
- Alias: “Radio101”
- Allowed Locations: [“CPH-1”, “CPH-2”, “CPH-3”]

```
POST /radios/101
Payload: { "alias": "Radio101", "allowed_locations": ["CPH-1", "CPH-2", "CPH-3"] }
```

Set location of radio 100 to “CPH-1”

```
POST /radios/100/location
Payload: { "location": "CPH-1" }
Return: 200 OK
```

Set location of radio 101 to “CPH-3” (accepted)

```
POST /radios/101/location
Payload: { "location": "CPH-3" }
Return: 200 OK
```

Set location of radio 100 to “CPH-3” (denied)

```
POST /radios/100/location
Payload: { "location": "CPH-3" }
Return: 403 FORBIDDEN
```

Retrieve location of radio 101 (returns “CPH-3”)

```
GET /radios/101/location
Return: 200 OK { “location”: “CPH-3” }
```

Retrieve location of radio 100 (returns “CPH-1”)

```
GET /radios/100/location
Return: 200 OK { “location”: “CPH-1” }
```

#### Scenario 2:

Create a radio profile with

- ID: 102
- Alias: “Radio102”
- Allowed Locations: [“CPH-1”, “CPH-3”]

```
POST /radios/102 
Payload: { "alias": "Radio102", "allowed_locations": ["CPH-1", "CPH-3"] }
```

Retrieve location of radio 102 (returns undefined/unknown)

```
GET /radios/102/location
Return: 404 NOT FOUND
```

### Notes
> In order to parse the path as GET/POST paramenters, the `RewriteRule` option in `.htaccess` file is used (more details at [this link](https://stackoverflow.com/questions/15655313/handling-urls-with-a-rest-api)).

> Allowing rules to be overridden is done by adding the following lines in `/etc/apache2/sites-available/ubuntu-docker.conf`(see [this link](https://askubuntu.com/questions/422027/mod-rewrite-is-enabled-but-not-working)) 
`<Directory "/var/www/html/">
  AllowOverride All
</Directory>
`