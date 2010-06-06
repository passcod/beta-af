<?php

$cap = recaptcha_get_html($publickey, $error);

/**
 * Builds the drop-down <select> for a named field, for use on the UI.
 * 
 * @param string $name The name of the field (see $opts)
 * @param array $data This must be $msg
 * @return string The <select> tag for this field.
 */
function tdField($name, $data)
{
	$ret = '<select class="opt" name="'.$name.'" onchange="get_sum()">';
	for ($i=0;$i<10;$i++) {
		$ret .= '<option value="'.$i.'"';
		if ( $i == $data[$name] )
		{
			$ret .= ' selected="selected"';
		}
		$ret .= '>&nbsp;&nbsp;&nbsp;'.$i.'</option><!-- '.$data[$name].'-->';
	}
	$ret .= '</select>';
	return $ret;
}

/**
 * Returns a javascript array from a unidimensional php array.
 * A number of tabs can be appended before each line for formatting's sake
 *
 * @param array $arr Source PHP array
 * @param string $name Name of the javascript array
 * @param int $tabs Number of tabs to be appended before each line.
 * @return string
 */
function arr2js($arr, $name, $tabs = 0)
{
	$t = str_repeat("\t", $tabs);
	$ret = $t.'var '.$name." = Array();\n";
	foreach ( $arr as $key => $val )
	{
		if ( is_string($val) ) { $ret .= $t.$name.'['.$key.'] = "'.$val."\";\n"; } else { $ret .= $t.$name.'['.$key.'] = '.$val.";\n"; }
	}
	return $ret;
}

/**
 * Gets a POST value.
 * Returns an empty string if the POST value is empty.
 *
 * @param string|int $key The name/key of the POST variable to be retrieved.
 * @return mixed
 */
function getPost($key) { if ( empty($_POST[$key]) ) { return ''; } else { return $_POST[$key]; } }

/**
 * Gets a value as requested by the template and returns the proper values,
 * minimizing further (duplicate) processing, and simplifying the template.
 * This function makes heavy use of the above { @link getPost() }.
 *
 * @param string $name The name of the value to be retrieved
 * @return mixed Bool for payment, string for all others
 */
function getValue($name)
{
	if ( $name == 'cheque' )
	{
		if ( getPost('payment') == 'cheque' )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else if ( $name == 'direct' )
	{
		if ( getPost('payment') == 'direct' )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else if ( $name == 'onload' )
	{
		if ( !$done )
		{
			return ''; //'onload="javascript:js_is_on();"';
		}
		else
		{
			return '';
		}
	}
	else
	{
		return getPost($name);
	}
}


include('template.html');

?>