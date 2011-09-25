<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		StudyMonkey Team
 */

// ------------------------------------------------------------------------

/**
 * string2uri
 *
 * Converts a regular string into a lowercase uri-style string with
 * dashes for spaces.
 *
 * @access  public
 * @param   string
 * @return  string
 */
if ( ! function_exists( 'string2uri' ) )
{
	function string2uri( $input_string )
	{
        return strtolower( str_replace( ' ', '-', $input_string ) );
	}
}

// ------------------------------------------------------------------------

/**
 * uri2string
 *
 * Converts a uri-style string into a regular string by replacing dashes with
 * spaces.  This is helpful for finding the original string from the DB.
 *
 * @access  public
 * @param   string
 * @return  string
 */
if ( ! function_exists( 'uri2string' ) )
{
	function uri2string( $input_string )
	{
        return str_replace( '-', ' ', $input_string );
	}
}

/* End of file studymonkey_helper.php */
/* Location: ./application/helpers/studymonkey_helper.php */