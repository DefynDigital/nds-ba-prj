<?php

/**
 * @package         Engage Box
 * @version         3.2.0 Pro
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/script.install.helper.php';

class PlgEditorsXtdEngageBoxInstallerScript extends PlgEditorsXtdEngageBoxInstallerScriptHelper
{
	public $name = 'ENGAGEBOX';
	public $alias = 'engagebox';
	public $extension_type = 'plugin';
	public $plugin_folder  = 'editors-xtd';
}
