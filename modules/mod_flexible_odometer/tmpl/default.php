<?php
 /*
 * @package Joomla 3.4
 * Flexible Odometer module
 * @copyright Copyright (C) Adrien Roussel https://www.nordmograph.com/extensions
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
$juri 			= JURI::base();
$doc 			= JFactory::getDocument();					
$mode			= $params->get('mode','wizard');
$format			= $params->get('format','d');
$expert_query	= $params->get('expert_query');
$intro_text		= $params->get('intro_text');
$outro_text		= $params->get('outro_text');
$init			= $params->get('init','0');
$fontsize		= $params->get('fontsize','40');
$link			= $params->get('link');
$link_title		= $params->get('link_title');
$incrementer	= $params->get('incrementer','0');
$counter 		= $counter + $incrementer;

echo '<div class="odom_container">';
if($intro_text)
	echo '<div id="flexodo_intro'.$module->id.'" class="flexodo_intro">'.JText::_( $intro_text).'</div>';

if($link)
{
	echo '<a href="'.$link.'" ';
	if($link_title)
		echo ' title="'.$link_title.'" class="hasTooltip" ';
	echo '>';
}
echo '<div id="flex_odometer'.$module->id.'" class="flex_odometer" 
	style="font-size:'.$fontsize.'px;line-height:'. ($fontsize + 10) .'px" >'.$init.'</div>';
if($link)
	echo '</a>';	
echo "<script>";
if($checkviewpoint)
{
	echo "var waypoint = new Waypoint({
	element: document.getElementById('flex_odometer".$module->id."'),
	handler: function() {
	var el".$module->id." = document.querySelector('#flex_odometer".$module->id."');
	od".$module->id." = new Odometer({
		el: el".$module->id.",
		value: 0,
		format: '".$format."',
		theme: '".$theme."'
	});
	od".$module->id.".update(".$counter.")
	},
	offset: '100%'
	})";
}
else
{
	echo "var el".$module->id." = document.querySelector('#flex_odometer".$module->id."');
	od".$module->id." = new Odometer({
	el: el".$module->id.",
	value: 0,
	format: '".$format."',
	theme: '".$theme."'
	});
	od".$module->id.".update(".$counter.")";
}
echo "</script>";

if($outro_text)
	echo '<div id="flexodo_outro'.$module->id.'" class="flexodo_outro">'. JText::_( $outro_text).'</div>';
echo '</div>';