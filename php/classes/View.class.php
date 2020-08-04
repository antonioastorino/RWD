<?php
class View {
	private static $code = 200;
	private static $body = null;
	public static function makeResponse($code, $body = null) {
		self::$code = $code;
		self::$body = $body;
	}

	public static function sendResponse() {
		http_response_code(self::$code);
		echo self::$body;
	}
}
