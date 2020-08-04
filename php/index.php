<?php
header("Content-type:application/json");
require_once("models/URIParser.class.php");
require_once("controller/RequestHandler.class.php");
require_once("common/RequestType.class.php");
require_once("config/Database.class.php");
require_once("models/DataHandler.class.php");
require_once("common/Response.class.php");

$reqMethod = $_SERVER['REQUEST_METHOD'];
$requestType = null;

if ($reqMethod != 'POST' && $reqMethod != 'GET') {
	return;
}

// Instantiate URI-to-parameter converter
$uriParser = new URIParser();

// Instantiate request parser
$reqParser = new RequestHandler();

// Connect to DB
$db = new Database();
$db->connect();
$conn = $db->getConn();

// Instantiate the request handler which  DB
$dataHandler = new DataHandler($conn);

// Convert URI into parameters
$params = $uriParser->uri2params($_SERVER['REQUEST_URI']);

$reqParser->process($reqMethod, $params, $dataHandler);

Response::send();


// Disconnect from DB
$db->disconnect();
