<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

/**
 * Check if the application is running on a local server
 * 
 * @return bool True if running on local server (development environment or localhost)
 */
if (!function_exists('isLocalServer')) {
	function isLocalServer(){
		return (defined('ENVIRONMENT') && ENVIRONMENT === 'development') || 
			   (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'localhost');
	}
}
