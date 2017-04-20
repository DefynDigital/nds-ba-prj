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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Item View
 */
class RstboxViewItem extends JViewLegacy
{
    /**
     * display method of Item view
     * @return void
     */
    public function display($tpl = null) 
    {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
            JFactory::getApplication()->enqueueMessage($errors, 'error');
            return false;
        }

        // Assign the Data
        $this->form     = $form;
        $this->item     = $item;
        $this->isnew    = (!isset($_REQUEST["id"])) ? true : false;
        $this->boxtypes = array_keys(RSTBoxHelper::getBoxTypes());

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() 
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);

        JToolBarHelper::title($isNew ? JText::_('New Box') : JText::_('Edit Box: ' . $this->item->name . " - ". $this->item->id));

        JToolbarHelper::apply('item.apply');
        JToolBarHelper::save('item.save');
        JToolbarHelper::save2new('item.save2new');
        JToolBarHelper::cancel('item.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
    }
}