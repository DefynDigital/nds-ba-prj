<?php
/*------------------------------------------------------------------------
# plg_addcustomjavascript - Add Custom Javascript
# ------------------------------------------------------------------------
# version 1.0.0
# author Impression eStudio
# copyright Copyright (C) 2013 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemAddCustomJavascript extends JPlugin
{	
	function plgSystemAddCustomJavascript( &$subject, $params )
	{
		parent::__construct($subject, $params);

	}
	
	function onBeforeCompileHead()
	{
		$document = JFactory::getDocument();

		if (strcmp(substr(JURI::base(), -15), "/administrator/")!=0)	// Apply styles only to front-end.
		{
			if (strcmp($this->params->get('custom_javascript_file_path'),'')!=0)
			{
				$custom_javascript_file_path = $this->params->get('custom_javascript_file_path');
				ob_start();
				include($custom_javascript_file_path);
				$js = ob_get_contents();
				
				// Remove comments.
				if ($this->params->get('remove_comments'))
				{
					$js = preg_replace('(// .+)', '', $js);
					$js = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js);
				}
				
				// Minimize.
				if ($this->params->get('minimize'))
				{
					$js = str_replace(array("\r\n", "\r", "\n", "\t"), '', $js);
					$js = preg_replace('/ +/', ' ', $js);	// Replace multiple spaces with single space.
					$js = trim($js);		// Trim the string of leading and trailing space.
				}
				
				$document->addScriptDeclaration($js);
				ob_end_clean();
			}
		}
	}
}
