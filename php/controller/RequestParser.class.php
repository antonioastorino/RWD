<?php
$path = $_SERVER['DOCUMENT_ROOT'] . "/config/RequestType.class.php";
require($path);

class RequestParser {
	public static function getPostRequestType($params, $payload) {
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
	public function getGetRequestType($params) {
		// GET requests must have 2 parameters
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
}
