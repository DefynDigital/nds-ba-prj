<?php
/* 
* @package Joomla 3.4 
* Flexible Odometer module
* @copyright Copyright (C) Adrien Roussel https://www.nordmograph.com/extensions
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL 
*/
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldTables extends JFormFieldList {

    /**
     * Element name
     * @access	protected
     * @var		string
     */
    var $_name = 'tables';

	protected function getOptions()
	{
		jimport( 'joomla.database.table' );	
		$db = JFactory::getDBO ();
		$config = JFactory::getConfig();
		$databaseName=$config->get('db');
		
		$q = "SELECT TABLE_NAME 
			FROM INFORMATION_SCHEMA.TABLES
			WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='".$databaseName."' ";
		$db->setQuery($q);
		$tables = $db->loadObjectList ();
		
		$options = array();
		foreach ($tables as $table) {
			$strpos = strpos($table->TABLE_NAME , '_');
			$len = strlen($table->TABLE_NAME) - $strpos;
			$table_TABLE_NAME = '#__'.substr( $table->TABLE_NAME , $strpos +1 , $len);
			$options[] = JHTML::_('select.option', $table_TABLE_NAME, $table_TABLE_NAME );
		}
		return $options;
	}

}