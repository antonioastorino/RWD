<?php
	class URIParser {
		public static function uri2params($uri = null){
			$retVal = null;
			if ($uri != null) {
				$retVal = explode('/', substr($uri, 1));
			}
			return $retVal;
		}
	}