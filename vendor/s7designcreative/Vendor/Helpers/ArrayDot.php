<?php
namespace S7D\Vendor\Helpers;

class ArrayDot {

	public static function get($array, $key, $default = null) {
		if (!empty($key)) {
			$keys = explode('.', $key);
			foreach ($keys as $key) {
				if (isset($array[$key])) {
					$array = $array[$key];
				} else {
					return $default;
				}
			}
		}
		return $array;
	}
}