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

class eBoxLog 
{

	/**
	 *  Cookie Name
	 *
	 *  @var  string
	 */
	private $cookieName = "ebox";

	/**
	 *  Maximum age of visitors token id
	 *
	 *  @var  Integer
	 */
	private $expire = 90000000;

	/**
	 *  List of valid event IDs
	 *
     *  1 = Impressions
     *  2 = Closes
     *  3 = Engage
	 */
	private $events = array(1);

    /**
     *  Logs table
     *
     *  @var  string
     */
    private $table = "#__rstbox_logs";
	
    /**
     *  Logs box events to the database
     *
     *  @param   integer  $boxid    The box id
     *  @param   integer  $eventid  Event id
     *
     *  @return  bool     Returns a boolean indicating if the event logged successfully
     */
    function track($boxid, $eventid = 1)
    {
    	// Making sure we have a valid Boxid and Eventid
        if (!$boxid || !$eventid || !in_array($eventid, $this->events))
        {
            return;
        }

        // Return if session is not active
        if (!JFactory::getSession()->isActive())
        {           
        	return;
        }

        // Get visitor's token id
        if (!$visitorID = $this->getToken())
        {
        	return;
        }

        // Everything seems OK. Let's save data to db.
        $data = new stdClass();

        $data->sessionid = JFactory::getSession()->getId();
        $data->user      = JFactory::getUser()->id;
        $data->visitorid = $visitorID;
        $data->box       = $boxid;
        $data->event     = $eventid;
        $data->date      = JFactory::getDate()->toSql();
         
        // Insert the object into the user profile table.
        try {
            $result = JFactory::getDbo()->insertObject($this->table, $data);
            return $result;
        } catch (Exception $e) {
            
        }
    }

    /**
     *  Get a visitor's unique token id, if a token isn't set yet one will be generated.
     *
     *  @param   boolean $forceNew  If true, force a new token to be created
     *  
     *  @return  string  The session token
     */
    function getToken($forceNew = false)
    {
        $token = JFactory::getApplication()->input->cookie->get($this->cookieName, null);

        if ($token === null || $forceNew)
        {
            $token = $this->createToken();
            $this->saveToken($token);
        }

        return $token;
    }

    /**
     *  Create a token-string
     *
     *  @param   integer $length  Length of string
     *
     *  @return  string  Generated token
     */
    private function createToken($length = 8)
    {
        return bin2hex(JCrypt::genRandomBytes($length));
    }

    /**
     *  Saves the cookie to the visitor's browser
     *
     *  @param   string  $value  Cookie Value
     *
     *  @return  void
     */
    private function saveToken($value)
    {
        JFactory::getApplication()->input->cookie->set($this->cookieName, $value, time() + $this->expire, "/");
    }

    /**
     *  Removes old rows from the logs table
     *  Runs every 12 hours with a self-check
     *
     *  @return void
     */
    function clean()
    {
        include_once(JPATH_PLUGINS . '/system/nrframework/helpers/cache.php');

        $hash = MD5("eboxclean");
        $dontRun = NRCache::read($hash, true);

        if ($dontRun)
        {
            return;
        }

        $params = JComponentHelper::getParams('com_rstbox');
        $days = $params->get("statsdays", 180);

        // Removes rows older than $days days
        $db = JFactory::getDbo();
         
        $query = $db->getQuery(true);
        $query
            ->delete($db->quoteName($this->table))
            ->where($db->quoteName('date') . ' < DATE_SUB(NOW(), INTERVAL '.$days.' DAY)');
         
        $db->setQuery($query);
        $db->execute();

        // Write to cache file
        NRCache::write($hash, 1, 720);

        return true;
    }

    /**
     *  Fills dummy data to the database for testing purposes
     *
     *  @param   integer  $rows
     *
     *  @return  void
     */
    function fillData($rows = 100)
    {
        $y = 5;

        for ($i=0; $i < $rows; $i++)
        {
            if ($i % $y == 0)
            {
                $this->getToken(true);
            }

            $box = rand(1,100);
            $event = rand(1,3);

            $this->track($box, $event);
        }
    }
}

?>