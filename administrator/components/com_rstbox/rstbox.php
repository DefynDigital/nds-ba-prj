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
jimport('joomla.filesystem.file');

$app = JFactory::getApplication();

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_rstbox'))
{
	$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
	return;
}

require_once JPATH_PLUGINS . '/system/nrframework/helpers/functions.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/rstbox.php';

NRFrameworkFunctions::loadLanguage('com_rstbox');

// Do some checks
if (!isFrameworkInstalled()) {
	$app->enqueueMessage(JText::_('Novarain Framework plugin is not installed. Engage Box cannot function.'), 'error');
}

if (pluginIsDisabled("nrframework")) {
	$app->enqueueMessage(JText::_('Novarain Framework plugin is not enabled. Engage Box cannot function.'), 'notice');
}

if (pluginIsDisabled("rstbox")) {
	$app->enqueueMessage(JText::_('Engage Box plugin is not enabled.'), 'notice');
}

if (!pluginIsDisabled("cache")) {
	$app->enqueueMessage(JText::_('The <b>Page Cache</b> plugin is enabled. This may cause unexpected behavior. Consider using the <b>Conservative Caching</b> instead.'), 'warning');
}

// Load component's CSS/JS files
RSTBoxHelper::loadassets();

// Perform the Request task
$controller = JControllerLegacy::getInstance('Rstbox');
$controller->execute($app->input->get('task'));
$controller->redirect();

// Custom functions
function pluginIsDisabled($plugin) 
{
	$p = JPluginHelper::getPlugin('system', $plugin);

	if (!isset($p->name)) {
		return true;
	}	

	return false;
}

function isFrameworkInstalled() 
{
	jimport('joomla.filesystem.file');

	if (!JFile::exists(JPATH_PLUGINS . '/system/nrframework/nrframework.php')) {
		return false;
	}

	return true;
}