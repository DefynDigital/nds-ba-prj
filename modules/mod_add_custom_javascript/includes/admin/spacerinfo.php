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
defined('_JEXEC') or die('Direct access to this location is not allowed.');

jimport('joomla.form.formfield');

class JFormFieldSpacerinfo extends JFormField 
{
	protected $type = 'Spacerinfo';

	public function getInput()
	{
		$output = "";
		$output .= '<style type="text/css">
		div.panel label.hasTip {
			font-weight: bold;
			color:#333333;
		}
		
		div.panel input[type=text] {
			width:50%;
		}
		
		.pis-info td {
			vertical-align:middle;
		}
		.pis-info td:first-child {
			width:110px;
		}
		.pis-info td:first-child img {
			-moz-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			-webkit-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			-o-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
		}
		</style>';
		
		$output .= '
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="pis-info">
			<tr>
				<td><img src="../modules/mod_add_custom_javascript/includes/images/logo.jpg" width="100" height="100" /></td>
				<td><strong>Name</strong>: Add Custom Javascript <br />
					<strong>Version</strong>: 1.0.0 <br />
					<strong>Author</strong>: Impression eStudio <br />
					<strong>Website</strong>: <a href="http://joomla.impression-estudio.gr" target="_blank">http://joomla.impression-estudio.gr</a> <br />
					<strong>Contact</strong>: <a href="mailto:info@impression-estudio.gr">info@impression-estudio.gr</a> <br />
					<strong>Demo</strong>: <a href="../modules/mod_add_custom_javascript/includes/images/demo.png" target="_blank">Click here</a> <br />
					<strong>Help</strong>: <a href="http://joomla.impression-estudio.gr/add-custom-javascript/index.php" target="_blank">Click here</a>
				</td>
			</tr>
		</table>
		';
		
		return $output;
	}
}
?>