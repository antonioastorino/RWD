<?php
class Response {
	private static $code = 200;
	private static $body = null;
	public static function make($code, $body = null) {
		self::$code = $code;
		self::$body = $body;

	}

	public static function send() {
		http_response_code(self::$code);
		echo self::$body;
	}
}