<?php
/* 
* @package Joomla 3.4 
* Flexible Odometer module
* @copyright Copyright (C) Adrien Roussel https://www.nordmograph.com/extensions
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL 
*/
defined('_JEXEC') or die('Restricted access');
class modFlexibleOdometerHelper {	
	static function getCounter($params)
	{
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$mode						= $params->get('mode','expert');
		$expert_query				= $params->get('expert_query');
		$wizard_select				= $params->get('wizard_select', 'SELECT COUNT');
		$wizard_column				= $params->get('wizard_column','id');
		$wizard_table				= $params->get('wizard_table','#__content');
		$wizard_condition_column	= $params->get('wizard_condition_column');
		$wizard_condition_compare	= $params->get('wizard_condition_compare');
		$wizard_condition_value		= $params->get('wizard_condition_value');
		
		if($mode=='expert')
			$q = $expert_query;
		elseif($mode=='wizard')
		{
			$q = $wizard_select;
			$q .= "(".$wizard_column.") ";
			$q .= "FROM ".$wizard_table;
			if($wizard_condition_column && $wizard_condition_compare && $wizard_condition_value)
			{
				$q .= " WHERE ".$wizard_condition_column." ";
				$q .= $wizard_condition_compare." ";
				$q .= $wizard_condition_value;
			}
		}
		$q = str_replace(array('$user->id','[userid]'), $user->id , $q);
		$db->setQuery($q);
		$return = $db->loadResult();
		return $return;	
	}
}