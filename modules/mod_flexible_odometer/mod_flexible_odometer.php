<?php 
/* 
* @package Joomla 3.4 
* Flexible Odometer module
* @copyright Copyright (C) Adrien Roussel https://www.nordmograph.com/extensions
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL 
*/
defined('_JEXEC') or die('Restricted access');
$juri = JURI::base();
$doc = JFactory::getDocument();
$theme			= $params->get('theme','default');
$checkviewpoint	= $params->get('checkviewpoint',1);
$mode			= $params->get('mode','wizard');
$doc->addStylesheet($juri.'modules/mod_flexible_odometer/css/style.css');
$doc->addStylesheet($juri.'modules/mod_flexible_odometer/css/odometer-theme-'.$theme.'.css');
if($checkviewpoint)
	$doc->addScript($juri.'modules/mod_flexible_odometer/js/noframework.waypoints.min.js');	
$doc->addScript($juri.'modules/mod_flexible_odometer/js/odometer.min.js');	
$counter = 0;
if($mode!='none')
{				
	require_once( dirname(__FILE__).'/helper.php' );
	$counter = modFlexibleOdometerHelper::getCounter($params);
}
require(JModuleHelper::getLayoutPath('mod_flexible_odometer'));?>