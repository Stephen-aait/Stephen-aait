<?php
/**
 * JSON class,
 *	simple layer to use core function (PHP 5.2), PECL extension (PHP >= 4.3.0) or Pear ServiceJSON
 * ______________________________________________________________
 * @example
 * 	echo JSON::encode(1.23);	// string, 1.23
 *      echo JSON::decode("1.23");	// float, 1.23
 * --------------------------------------------------------------
 * @Compatibility	PHP 5.2 / PHP with json PECL extension / PHP with Pear ServiceJSON
 * @Dependencies	Pear ServiceJSON (file JSON.php) [http://pear.php.net/pepr/pepr-proposal-show.php?id=198]
 * 			if there isn't Pecl extension [http://pecl.php.net/package/json]
 * 			or PHP version is less than 5.2
 * @Author		Andrea Giammarchi
 * @Site		http://www.devpro.it/
 * @Date		2006/11/06
 * @LastMOd		2006/11/19 [faster convertion problems (Pear and Pecl) in range 00-1f]
 * @Version		0.3b
 */
class JSON {

	/**
	 * public "static" method,
	 * 	converts a JSON string into compatible value.
	 *
	 * 	JSON::decode($input:String[, $assoc:Bool]):Mixed
	 *
	 * @param	String		JSON compatible string
	 * @param	Bool		true to convert object into associative arrays
	 * 				false by default
	 * @return	Mixed		Array, Standard Class, String, Float, integer, Boolean or null value.
	 */
	function decode($input, $assoc = 0){
		return JSON::__rawjsondecode($input, $assoc);
	}

	/**
	 * public "static" method,
	 * 	converts generic variable into JSON string.
	 *
	 * 	JSON::encode($input:Mixed):String
	 *
	 * @param	Mixed		Array, Standard Class, String, Float, integer, Boolean or null value to convert
	 * @return	String		JSON string ($input param rappresentation)
	 */
	function encode($input){
		return JSON::__rawjsonencode($input);
	}

	/**
	 * "private static" method,
	 * 	choose json core function or Pear Service to decode
	 */
	function __decode($input, &$assoc){
		if(function_exists('json_decode'))
			$result = json_decode($input, $assoc);
		else {
			require_once('JSON.php');
			$json = new Services_JSON($assoc);
			$result = $json->decode($input);
		}
		return $result;
	}

	/**
	 * "private static" method,
	 * 	choose json core function or Pear Service to encode
	 */
	function __encode($input){
		if(function_exists('json_encode'))
			$result = json_encode($input);
		else {
			require_once('JSON.php');
			$json = new Services_JSON();
			$result = $json->encode($input);
		}
		return JSON::__rawjsonconvert($result);
	}

	/**
	 * "private static" method,
	 * 	checks if JSON string is not an object or an array then returns correct value.
	 */
	function __rawjsondecode(&$input, &$assoc){
		return preg_match('#^(\[[^\a]+?\]|{[^\a]+?})$#', $input) ? JSON::__decode($input, $assoc) : array_shift(JSON::__decode('['.$input.']', $assoc));
	}

	/**
	 * "private static" method,
	 * 	checks if input var is not an object or an array then converts correct value.
         * NOTE: it's quite a non-sense but json_decode doesn't accept primitive variables then
         * this method is "paranoia style"
	 */
	function __rawjsonencode(&$input){
		if(is_array($input) || is_object($input))
			$input = JSON::__encode($input);
		elseif(is_string($input) || is_float($input) || is_int($input) || is_bool($input) || is_null($input))
			$input = substr(JSON::__encode(array($input)), 1, -1);
		else
			$input = 'null';
		return $input;
	}
	
	/**
	 * "private static" method,
	 * 	converts unconverted characters in correct range between 00-1f.
	 */
	function __rawjsonconvert(&$str){
		$f = $r = array();
		foreach(array_merge(range(0, 7), array(11), range(14, 31)) as $v) {
			$f[] = chr($v);
			$r[] = "\\u00".sprintf("%02x", $v);
		}
		return str_replace($f, $r, $str);
	}
}
?>