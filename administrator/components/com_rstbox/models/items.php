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

class RstboxModelItems extends JModelList
{
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     *
     * @see        JController
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                //'ordering', 'b.ordering',
                'state', 'b.published',
                'name', 'b.name',
                'search',
                'boxtype','triggermethod',
                'usergroups', 'devices',
                'id', 'b.id',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        // Create a new query object.           
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select some fields from the item table
        $query
            ->select('b.*, count(l.id) as impressions')
            ->from('#__rstbox b');
        
        // Filter State
        $filter = $this->getState('filter.state');
        if (is_numeric($filter))
        {
            $query->where('b.published = ' . ( int ) $filter);
        }
        else if ($filter == '')
        {
            $query->where('( b.published IN ( 0,1,2 ) )');
        }

        // Filter Box Type
        $filter = $this->getState('filter.boxtype');
        if ($filter != '')
        {
            $query->where('b.boxtype = ' . $db->q($filter));
        }

        // Filter the list over the search string if set.
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('b.id = ' . ( int ) substr($search, 3));
            }
            else
            {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where(
                    '( `name` LIKE ' . $search . ' )'
                );
            }
        }

        // Filter Trigger Method
        $filter = $this->getState('filter.triggermethod');
        if ($filter != '')
        {
            $query->where('b.triggermethod = ' . $db->q($filter));
        }  

        // Filter Assigned User Groups
        $filter = $this->getState('filter.usergroups');
        if ($filter != '')
        {
            $query->where('b.params LIKE ' . $db->q('%"%usergroups%":["%' . $filter . '%"]%'));
        }  

        // Filter Assigned Devices
        $filter = $this->getState('filter.devices');
        if ($filter != '')
        {
            $query->where('b.params LIKE ' . $db->q('%"%devices%":["%' . $filter . '%"]%'));
        }

        // Get logs information
        $query->join('LEFT', $db->quoteName('#__rstbox_logs', 'l') . ' ON 
            (' . $db->quoteName('b.id') . ' = ' . $db->quoteName('l.box') . ')');

        // Add the list ordering clause.
        $ordering = $this->getState('list.ordering', 'b.id');
        $query->order($db->escape($ordering) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        $query->group("b.id");

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();

        foreach ($items as $item) {

            $item->params = json_decode($item->params);

            // Prepare Cookie Type
            $cookieType = (isset($item->params->cookietype)) ? $item->params->cookietype : "days";
            $item->params->cookietype = $cookieType;

            // Prepare usergroups
            if (
                !isset($item->params->assign_usergroups_list)
                || is_null($item->params->assign_usergroups_list)
                || (int) $item->params->assign_usergroups == 0)
            {         
                continue;
            }

            $usergroups = implode(",",$item->params->assign_usergroups_list);

            if (!$usergroups) 
            {
                continue;
            }

            $db = JFactory::getDBO();
            $query = $db->getQuery(true);

            $query
                ->select("*")
                ->from("#__usergroups")
                ->where("id in ($usergroups)");
    
            $db->setQuery($query);
            $usergroupsNames = $db->loadObjectList();

            $item->params->assign_usergroupsNames = $usergroupsNames;
        }

        return $items;
    }

    /**
     * Import Method
     * Import the selected items specified by id
     * and set Redirection to the list of items
     */
    function import($model)
    {
        $file = JRequest::getVar('file', '', 'files', 'array');

        if (!is_array($file) || !isset($file['name']))
        {
            $msg = JText::_('NR_PLEASE_CHOOSE_A_VALID_FILE');
            JFactory::getApplication()->redirect('index.php?option=com_rstbox&view=items&layout=import', $msg);
        }

        $ext = explode(".", $file['name']);

        if (!in_array($ext[count($ext) - 1], array("ebox","rstbak")))
        {
            $msg = JText::_('NR_PLEASE_CHOOSE_A_VALID_FILE');
            JFactory::getApplication()->redirect('index.php?option=com_rstbox&view=items&layout=import', $msg);
        }

        jimport('joomla.filesystem.file');
        $publish_all = JFactory::getApplication()->input->getInt('publish_all', 0);

        $data = file_get_contents($file['tmp_name']);

        if (empty($data))
        {
            JFactory::getApplication()->redirect('index.php?option=com_rstbox&view=items', JText::_('File is empty!'));

            return;
        }
        
        $items = json_decode($data, true);
        if (is_null($items))
        {
            $items = array();
        }

        $msg = JText::_('Items saved');

        foreach ($items as $item)
        {
            $item['id'] = 0;
            if ($publish_all == 0)
            {
                unset($item['published']);
            }
            else if ($publish_all == 1)
            {
                $item['published'] = 1;
            }
            $items[] = $item;

            $saved = $model->save($item);

            if ($saved != 1)
            {
                $msg = JText::_('Error Saving Item') . ' ( ' . $saved . ' )';
            }
        }

        JFactory::getApplication()->redirect('index.php?option=com_rstbox&view=items', $msg);
    }

    /**
     * Export Method
     * Export the selected items specified by id
     */
    function export($ids)
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__rstbox')
            ->where('id IN ( ' . implode(', ', $ids) . ' )');
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $string = json_encode($rows);

        $filename = JText::_("COM_RSTBOX") . ' Items';
        if (count($rows) == 1)
        {
            $name = JString::strtolower(html_entity_decode($rows['0']->name));
            $name = preg_replace('#[^a-z0-9_-]#', '_', $name);
            $name = trim(preg_replace('#__+#', '_', $name), '_-');

            $filename = JText::_("COM_RSTBOX") .  ' Item (' . $name . ')';
        }

        // SET DOCUMENT HEADER
        if (preg_match('#Opera(/| )([0-9].[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT']))
        {
            $UserBrowser = "Opera";
        }
        elseif (preg_match('#MSIE ([0-9].[0-9]{1,2})#', $_SERVER['HTTP_USER_AGENT']))
        {
            $UserBrowser = "IE";
        }
        else
        {
            $UserBrowser = '';
        }
        $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
        @ob_end_clean();
        ob_start();

        header('Content-Type: ' . $mime_type);
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        if ($UserBrowser == 'IE')
        {
            header('Content-Disposition: inline; filename="' . $filename . '.ebox"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $filename . '.ebox"');
            header('Pragma: no-cache');
        }

        // PRINT STRING
        echo $string;
        die;
    }

    /**
     * Copy Method
     * Copy all items specified by array cid
     * and set Redirection to the list of items
     */
    function copy($ids, $model)
    {
        foreach ($ids as $id)
        {
            $model->copy($id);
        }

        $msg = JText::sprintf('Items copied', count($ids));
        JFactory::getApplication()->redirect('index.php?option=com_rstbox&view=items', $msg);
    }

    /**
     *  Resets box statistics
     *
     *  @return  void
     */
    function reset($ids)
    {
        $db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
         
        $conditions = array(
            $db->quoteName('box') . ' IN ('.implode(",", $ids).')'
        );
         
        $query->delete($db->quoteName('#__rstbox_logs'));
        $query->where($conditions);
         
        $db->setQuery($query);
        $db->execute();

        $msg = JText::sprintf('COM_RSTBOX_N_ITEMS_RESET_1', count($ids));

        JFactory::getApplication()->redirect('index.php?option=com_rstbox&view=items', $msg);
    }

}