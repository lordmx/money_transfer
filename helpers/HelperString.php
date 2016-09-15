<?php

namespace helpers;

class HelperString
{
	/**
	 * @param string $string
	 * @return string
	 */
	public static function toUnderscore($string)
	{
		$matches = [];	
		$regexp = '#([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)#';
		preg_match_all($regexp, $string, $matches);
		$parts = [];

		foreach ($matches[0] as $match) {
			if ($match == strtoupper($match)) {
				$match = strtolower($match);
			} else {
				$match = lcfirst($match);
			}

			$parts[] = $match;
		}

		return implode('_', $parts);
	}

	/**
	 * @param string
	 * @return string
	 */
	public static function toCamelCase($string)
	{
		$parts = explode('_', $string);

		foreach ($parts as $i => $part) {
			if ($part != strtoupper($part)) {
				$part = ucfirst(strtolower($part));
			}

			$parts[$i] = $part;
		}

		return lcfirst(implode('', $parts));
	}
}