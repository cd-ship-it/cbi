<?php

/**
 * Status Helper
 * 
 * Provides global functions for status mappings and lookups
 */

if (!function_exists('getInactiveStatusMapping')) {
	/**
	 * Get the inactive status mapping array
	 * 
	 * @return array Mapping of inactive status codes to display names
	 */
	function getInactiveStatusMapping() {
		return [
			1 => 'Inactive',
			2 => 'Guest',
			3 => 'Member',
			4 => 'Pre-member',
			5 => 'Ex-member',
			6 => 'Approval Pending'
		];
	}
}

if (!function_exists('getInactiveStatusText')) {
	/**
	 * Get the display text for an inactive status code
	 * 
	 * @param int|null $inactive The inactive status code
	 * @return string The display text, or empty string if not found
	 */
	function getInactiveStatusText($inactive) {
		$mapping = getInactiveStatusMapping();
		return isset($mapping[$inactive]) ? $mapping[$inactive] : '';
	}
}

if (!function_exists('getInactiveStatusCaseWhen')) {
	/**
	 * Get SQL CASE WHEN statement for inactive status mapping
	 * 
	 * @param string $columnName The column name to use (default: 'inactive')
	 * @param string $alias The alias for the result column (default: 'status')
	 * @return string SQL CASE WHEN statement
	 */
	function getInactiveStatusCaseWhen($columnName = 'inactive', $alias = 'status') {
		$mapping = getInactiveStatusMapping();
		$caseWhen = "CASE {$columnName}";
		
		foreach ($mapping as $code => $text) {
			$caseWhen .= "\n    WHEN {$code} THEN " . "'" . addslashes($text) . "'";
		}
		
		$caseWhen .= "\n    ELSE ''\nEND AS {$alias}";
		
		return $caseWhen;
	}
}

