<?php
abstract class RequestType {
	const INVALID_REQUEST = 0;
	const UNSUPPORTED_REQUEST = 1;
	const VALID_REQUEST = 2;
	const POST_ADD_RADIO_LOCATION = 3;
	const POST_ADD_RADIO = 4;
	const GET_RADIO_LOCATION = 5;
}
