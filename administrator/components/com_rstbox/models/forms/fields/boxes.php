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
JFormHelper::loadFieldClass('list');
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_rstbox/' . 'models');

class JFormFieldBoxes extends JFormFieldList
{
    /**
     * Method to get a list of options for a list input.
     *
     * @return    array   An array of JHtml options.
     */
    protected function getOptions()
    {
        $model = JModelLegacy::getInstance('Items', 'RstboxModel', array('ignore_request' => true));
        $model->setState('filter.state', 1);

        $boxes   = $model->getItems();
        $options = array();

        foreach ($boxes as $key => $box)
        {
            $options[] = JHTML::_('select.option', $box->id, $box->name);
        }   

        return array_merge(parent::getOptions(), $options);
    }
}