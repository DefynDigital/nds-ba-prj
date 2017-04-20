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

/**
 *  Engage Box Assignments Class
 */
class ebAssignments
{
	/**
	 *  Item
	 *
	 *  @var  object
	 */
	private $item;

	/**
	 *  Item params
	 *
	 *  @var  object
	 */
	private $params;

	/**
	 *  Local assignments list
	 *
	 *  @var  array
	 */
	private $assignments = array(
		"impressions",
		"cookie",
		"offline"
    );

	/**
	 *  Class Constructor
	 *
	 *  @param  object  $item  The object to be checked
	 */
	function __construct($item, $params)
	{
		if (!is_object($item) || !is_object($params))
		{
			return;
		}

		$this->item   = $item;
		$this->params = $params;
	}

	/**
     *  Pass all checks
     *
     *  @return  boolean  Returns true if all assignments pass
     */
    public function passAll()
    {
        // Temporary fix for the cookie assignment check
        // @TODO - Cookie field should be renamed to "assign_cookie"
        $this->params->set("assign_cookie", true);

        $pass = true;

        foreach ($this->assignments as $key => $assignment)
        {
            // Break if not passed
            if (!$pass)
            {
                break;
            }
            
            $method = "pass".$assignment;

            // Skip item if there is no assosiated method
            if (!method_exists($this, $method))
            {
                continue;
            }

            $assign = "assign_".$assignment;

            // Skip item if assignment is missing
            if (!$this->params->exists($assign))
            {
                continue;
            }

            $pass = $this->$method();
        }

        return $pass;
    }

    /**
     *  Pass Check for Offline Mode
     *
     *  @return  bool
     */
    private function passOffline()
    {
        // Skip check if offline mode is disabled
        if (!JFactory::getConfig()->get('offline', false))
        {
            return true;
        }

        $component   = JComponentHelper::getParams('com_rstbox');
        $globalState = $component->get("assign_offline", false);
        $boxState    = $this->params->get("assign_offline", null);

        return is_null($boxState) ? $globalState : $boxState;
    }

    /**
     *  Pass Check for Box Cookie
     *
     *  @return  bool
     */
    private function passCookie()
    {
        // Skip if assignment is disabled
        if ($this->params->get("cookietype") == "never")
        {
            return true;
        }

        // Skip if a Super User is logged in
        if (JFactory::getUser()->authorise('core.admin'))
        {
            return true;
        }

        return RstboxHelper::boxHasCookie($this->item->id) ? false : true;
    }

    /**
     *  Checks box maximum impressions assignment
     *
     *  @return  boolean  Returns true if the assignment passes
     */
    private function passImpressions()
    {
        // Skip if assignment is disabled
        if (!$this->params->get("assign_impressions", false))
        {
            return true;
        }

        $period = $this->params->get("assign_impressions_param_type", "session");
        $limit  = (int) $this->params->get("assign_impressions_list");

        if ($limit == 0)
        {
            return;
        }

        $db = JFactory::getDBO();
        $date = JFactory::getDate();

        $query = $db->getQuery(true);

        $query
            ->select('COUNT(id)')
            ->from($db->quoteName('#__rstbox_logs'))
            ->where($db->quoteName('event') . ' = 1')
            ->where($db->quoteName('box') . ' = ' . $this->item->id);

        if ($period == "session")
        {
            $query->where($db->quoteName('sessionid') . ' = '. $db->quote(JFactory::getSession()->getId()));
        } else
        {
            require_once(__DIR__ . "/log.php");
            $log = new eBoxLog();
            $query->where($db->quoteName('visitorid') . ' = '. $db->quote($log->getToken()));
        }

        switch ($period)
        {
            case 'day':
                $query->where('DATE(date) = ' . $db->quote($date->format("Y-m-d")));
                break;
            case 'week':
                $query->where('YEARWEEK(date) = ' . $date->format("oW"));
                break;
            case 'month':
                $query->where('MONTH(date) = ' . $date->format("m"));
                $query->where('YEAR(date) = ' . $date->format("Y"));
                break;
        }

        $db->setQuery($query);

        $pass = (int) $limit > (int) $db->loadResult();

        return (bool) $pass;
    }
}

?>