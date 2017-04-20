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
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

class RstboxControllerItems extends JControllerAdmin
{
    /**
     * Proxy for getModel.
     * @since       2.5
     */
    public function getModel($name = 'Item', $prefix = 'RstboxModel', $config = array('ignore_request' => true)) 
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

	/**
	 * Import Method
	 * Set layout to import
	 */
	function import()
	{
		$file = JRequest::getVar('file', '', 'files');

		if (!empty($file))
		{
			if (isset($file['name']))
			{
				// Get the model.
				$model      = $this->getModel('Items');
				$model_item = $this->getModel('Item');
				$model->import($model_item);
			}
			else
			{
				$msg = JText::_('NR_PLEASE_CHOOSE_A_VALID_FILE');
				$this->setRedirect('index.php?option=com_rstbox&view=items&layout=import', $msg);
			}
		}
		else
		{
			$this->setRedirect('index.php?option=com_rstbox&view=items&layout=import');
		}
	}

	/**
	 * Export Method
	 * Export the selected items specified by id
	 */
	function export()
	{
		$ids = JFactory::getApplication()->input->get('cid', array(), 'array');

		// Get the model.
		$model = $this->getModel('Items');
		$model->export($ids);
	}

	/**
	 *  Mimics the copy method
	 */
	function duplicate()
    {
		$this->copy();
    }

	/**
	 * Copy Method
	 * Copy all items specified by array cid
	 * and set Redirection to the list of items
	 */
	function copy()
	{
		$ids = JFactory::getApplication()->input->get('cid', array(), 'array');

		// Get the model.
		$model = $this->getModel('Items');
		$model_item = $this->getModel('Item');

		$model->copy($ids, $model_item);
	}

	/**
	 *  Resets box statistics
	 *
	 *  @return  void
	 */
	function reset()
	{
		$ids = JFactory::getApplication()->input->get('cid', array(), 'array');

		// Get the model.
		$model = $this->getModel('Items');
		$model->reset($ids);
	}

	/**
	 *  Removes box cookie
	 *
	 *  @return  void
	 */
	function removeCookie()
	{
		$ids = JFactory::getApplication()->input->get('cid', array(), 'array');

		foreach ($ids as $key => $id) {
			RSTBoxHelper::boxRemoveCookie($id);
		}

		$this->setRedirect('index.php?option=com_rstbox&view=items');
	}

	/**
	 * Function that allows child controller access to model data
	 * after the item has been deleted.
	 *
	 * @param   JModelLegacy  $model  The data model object.
	 * @param   integer       $ids    The array of ids for items being deleted.
	 *
	 * @return  void
	 */
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
		if (!is_array($ids))
		{
			return;
		}

		// Remove box statistics information
		$db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
        $query
            ->delete($db->quoteName('#__rstbox_logs'))
            ->where($db->quoteName('box') . ' IN (' . implode(",",$ids) . ')');
         
        $db->setQuery($query);
        $db->execute();
	}
}