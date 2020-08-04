<?php
$path = $_SERVER['DOCUMENT_ROOT'] . "/common/RequestType.class.php";
$path = $_SERVER['DOCUMENT_ROOT'] . "/common/Response.class.php";
require($path);

class RequestHandler {

	// Given parameters and received payload, determine which type of POST request is received
	private static function retrievePostRequestType($params, $payload) {
		// POST requests must have a payload and at least 2 parameters
		if ($payload == null || $params == null) {
			return RequestType::INVALID_REQUEST;
		}
		// Remove non-empty parameters
		$params = array_filter($params, 'strlen');
		$paramCount = count($params);

		if ($params[0] == 'radios') {
			switch ($paramCount) {
				case 2:
					// Check that the alias and locations are present
					if (isset($payload['alias']) && isset($payload['allowed_locations'])) {
						return RequestType::POST_ADD_RADIO;
					}
				case 3:
					if ($params[2] == 'location' && isset($payload['location'])) {
						return RequestType::POST_ADD_RADIO_LOCATION;
					}
				default:
					return RequestType::INVALID_REQUEST;
			}
		}
		return RequestType::UNSUPPORTED_REQUEST;
	}

	// Given parameters and received payload, determine which type of GET request is received
	private static function retrieveGetRequestType($params) {
		// GET requests must have 3 parameters
		// Remove non-empty parameters
		$params = array_filter($params, 'strlen');
		$paramCount = count($params);

		if ($params[0] == 'radios') {
			switch ($paramCount) {
				case 3:
					if ($params[2] == 'location') {
						return RequestType::GET_RADIO_LOCATION;
					}
				default:
					return RequestType::INVALID_REQUEST;
			}
		}
		return RequestType::UNSUPPORTED_REQUEST;
	}

	// Process the request and populate the response
	public static function process($reqMethod, $params, $dataHandler) {
		$reqType = 0;
		if ($reqMethod == 'POST') {
			// Handle POST request
			// Check if sent as `JSON (application/json)`
			$json = file_get_contents('php://input');
			$payload = json_decode($json, true);

			// If not sent as `JSON (application/json), retrieve payload from $_POST
			if ($payload == null) {
				$payload = $_POST;
			}
			$reqType = self::retrievePostRequestType($params, $payload);
		} else {
			// Handle GET request
			$reqType = self::retrieveGetRequestType($params);
		}

		// Generate response
		switch ($reqType) {
			case RequestType::POST_ADD_RADIO:
				$alias = $payload['alias'];
				// Make sure the allowed locations are present
				$locations = null;
				$locations = $payload['allowed_locations'];
				if (!is_array($locations)) {
					Response::make(403, "Allowed locations must be an array");
					break;
				}
				$radio_id = $params[1];
				Response::make(
					$dataHandler->insertRadio($radio_id, $alias, $locations)
				);
				break;

			case RequestType::POST_ADD_RADIO_LOCATION:
				// Check that the alias is present
				$location = $payload['location'];
				$radio_id = $params[1];
				Response::make($dataHandler->setLocation($radio_id, $location));
				break;

			case RequestType::GET_RADIO_LOCATION:
				$location = null;
				$radio_id = $params[1];
				$response_code = $dataHandler->getLocation($radio_id, $location);
				if ($response_code == 200) {
					$json = json_encode(array('location' => $location));
					Response::make(200, $json);
				}
				break;

			case RequestType::INVALID_REQUEST:
				Response::make(404, "Invalid request");
				break;

			default:
				Response::make(403, "Unhandled request type");
		}
	}
}
