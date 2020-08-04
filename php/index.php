<?php
header("Content-type:application/json");
require_once("models/URIParser.class.php");
require_once("controller/RequestParser.class.php");
require_once("config/RequestType.class.php");
require_once("config/Database.class.php");
require_once("models/DataHandler.class.php");

$method = $_SERVER['REQUEST_METHOD'];
$requestType = null;

if ($method != 'POST' && $method != 'GET') {
	return;
}

function disconnectAndDie($db, $message) {
	$db->disconnect();
	die($message);
}

// Instantiate URI-to-parameter converter
$uriParser = new URIParser();

// Instantiate request parser
$reqParser = new RequestParser();

// Connect to DB
$db = new Database();
$db->connect();
$conn = $db->getConn();

// Instantiate the request handler which  DB
$reqHandler = new DataHandler($conn);

// Convert URI into parameters
$params = $uriParser->uri2params($_SERVER['REQUEST_URI']);

if ($method == 'POST') {
	// Handle POST request
	// If sent as `JSON (application/json)`
	$json = file_get_contents('php://input');
	$payload = json_decode($json, true);
	if ($payload == null) {
		// It was not sent as `JSON (application/json)`
		$payload = $_POST;
	}
	$reqType = $reqParser->getPostRequestType($params, $payload);
} else {
	// Handle GET request
	$reqType = $reqParser->getGetRequestType($params);
}

switch ($reqType) {
	case RequestType::POST_ADD_RADIO:
		$alias = $payload['alias'];
		// Make sure the allowed locations are present
		$locations = null;
		$locations = $payload['allowed_locations'];
		if (!is_array($locations)) {
			disconnectAndDie($db, "Allowed locations must be an array");
		}
		$radio_id = $params[1];
		http_response_code($reqHandler->insertRadio($radio_id, $alias, $locations));
		break;

	case RequestType::POST_ADD_RADIO_LOCATION:
		// Check that the alias is present
		$location = $payload['location'];
		$radio_id = $params[1];
		$reqHandler->setLocation($radio_id, $location);
		break;

	case RequestType::GET_RADIO_LOCATION:
		$location = null;
		$radio_id = $params[1];
		$reqHandler->getLocation($radio_id, $location);
		if (http_response_code() != 404) {
			$json = json_encode(array('location'=> $location));
			echo $json;
		}
		break;

	case RequestType::INVALID_REQUEST:
		disconnectAndDie($db, "Invalid request");

	default:
		disconnectAndDie($db, "Unhandled request type");
}


// Disconnect from DB
$db->disconnect();
