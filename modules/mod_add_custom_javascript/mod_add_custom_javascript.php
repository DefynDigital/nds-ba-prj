<?php
/*------------------------------------------------------------------------
# mod_add_custom_javascript - Add Custom Javascript
# ------------------------------------------------------------------------
# version 1.0.0
# author Impression eStudio
# copyright Copyright (C) 2013 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Main code.
$document = JFactory::getDocument();

if (strcmp($params->get('custom_js_file_path'),'')!=0)
{
	ob_start();
	include($params->get('custom_js_file_path'));
	$js = ob_get_contents();
	
	// Remove comments.
	if ($params->get('remove_comments'))
	{
		$js = preg_replace('(// .+)', '', $js);
		$js = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js);
	}
	
	// Minimize.
	if ($params->get('minimize'))
	{
		$js = str_replace(array("\r\n", "\r", "\n", "\t"), '', $js);
		$js = preg_replace('/ +/', ' ', $js);	// Replace multiple spaces with single space.
		$js = trim($js);	// Trim the string of leading and trailing space.
	}
	
	ob_clean();
	$document->addScriptDeclaration($js);
}
?>