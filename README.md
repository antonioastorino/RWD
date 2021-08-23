# RWD - REST with Docker
## Description
A simple REST API for handling of devices in a radio system. 

A device profile should be a record containing the following information:

- Radio ID (a unique integer)
- Radio alias (a string name)
- List of allowed locations (each location being a simple string id)

Additionally each device has a location (a string), that initially is set to undefined.

The API allows the following:

### Storage of radio profiles
```
POST /radios/{id}
```
JSON Payload:

```json
{
"alias": "<alias>",
"allowed_locations": ["<location1>", "<location2>", ... ]
}
```

### Setting a location of a radio that is accepted if the location is on the radio’s list of allowed locations and rejected otherwise. If location change is rejected the radio’s location remains the last accepted location

```
POST /radios/{id}/location	
```
JSON Payload:

```json
{
"location": "<new_location>"
}
```

Returns `200 OK` for valid location
Returns `403 FORBIDDEN` for invalid location

### Retrieval of a radio’s location

```
GET /radios/{id}/location
```

Returns `200 OK` with location in JSON form following the schema:
```json
{
"location": "<current_location>"
}
```

Returns `404 NOT FOUND` if no location exists

## Example use cases: Scenario 1

### Create a radio profile with ID: 100, Alias: “Radio100”, Allowed Locations: [“CPH-1”, “CPH-2”]
```
POST /radios/100 
```
Payload: 
```json
{ "alias": "Radio100", "allowed_locations": ["CPH-1", "CPH-2"] }
```

### Create a radio profile with ID: 101, Alias: "Radio101", Allowed Locations: ["CPH-1", "CPH-2", "CPH-3"]
```
POST /radios/101
```

Payload: 
```json
{ "alias": "Radio101", "allowed_locations": ["CPH-1", "CPH-2", "CPH-3"] }
```

### Set location of radio 100 to "CPH-1" 
```
POST /radios/100/location
```
Payload:
```json
{ "location": "CPH-1" }
```
Return: `200 OK`

### Set location of radio 101 to "CPH-3" (accepted)
```
POST /radios/101/location
```
Payload: 
```json
{ "location": "CPH-3" }
```
Return: `200 OK`

### Set location of radio 100 to "CPH-3" (denied)
```
POST /radios/100/location
```
Payload:
```json
{ "location": "CPH-3" }
```
Return: `403 FORBIDDEN`

### Retrieve location of radio 101 (returns "CPH-3")
```
GET /radios/101/location
```
Return: 
`200 OK`
```json
{ "location": "CPH-3" }
```

### Retrieve location of radio 100 (returns "CPH-1")
```
GET /radios/100/location
```
Return:
`200 OK`
```json
{ "location": "CPH-1" }
```

## Example use cases: Scenario 2

### Create a radio profile with ID: 102, Alias: “Radio102”, Allowed Locations: [“CPH-1”, “CPH-3”]
```
POST /radios/102
```
Payload:
```json
{ "alias": "Radio102", "allowed_locations": ["CPH-1", "CPH-3"] }
```

### Retrieve location of radio 102 (returns undefined/unknown)
```
GET /radios/102/location
```
Return: `404 NOT FOUND`

## Installation
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
