<?php
header("Content-type:application/json");
define('__ROOT__', $_SERVER['DOCUMENT_ROOT']);
define('INVALID_REQUEST', 0);
define('UNSUPPORTED_REQUEST', 1);
define('VALID_REQUEST', 2);
define('POST_ADD_RADIO_LOCATION', 3);
define('POST_ADD_RADIO', 4);
define('GET_RADIO_LOCATION', 5);

require_once(__ROOT__ . "/classes/Model.class.php");
require_once(__ROOT__ . "/classes/View.class.php");
require_once(__ROOT__ . "/classes/Controller.class.php");

$reqMethod = $_SERVER['REQUEST_METHOD']; // GET or POST (or any other which would be ignored)
$uri = $_SERVER['REQUEST_URI'];

if ($reqMethod != 'POST' && $reqMethod != 'GET') {
	return;
}

// Connect to DB
$db = new Model('db', 'antonio', 'antonio', 'ngcp_db');
$db->connect();

// Process the request
Controller::processRequest($reqMethod, $uri, $db);

// Respond
View::sendResponse();

// Disconnect from DB
$db->disconnect();
