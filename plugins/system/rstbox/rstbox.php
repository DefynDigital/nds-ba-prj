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

/**
 *  Engage Box Render Plugin
 */
class PlgSystemRstBox extends JPlugin
{

    /**
     *  Application Object
     *
     *  @var  object
     */
    protected $app;

    /**
     *  Boxes final HTML layout
     *
     *  @var  string
     */
    private $boxes;

    /**
     *  Component's param object
     *
     *  @var  JRegistry
     */
    private $param;

    /**
     *  The loaded indicator of helper
     *
     *  @var  boolean
     */
    private $init;

    /**
     *  List of valid AJAX tasks
     *
     *  @var  array
     */
    private $validAJAXTasks = array(
        "track"
    );

    /**
     *  Log Object
     *
     *  @var  Object
     */ 
    private $log;

    /**
     *  onAfterDispatch Event
     */
    public function onAfterDispatch()
    {
        // Get Helper
        if (!$this->getHelper())
        {
            return;
        }

        // Fetch all boxes
        if (!$boxes = RSTBoxHelper::getBoxes())
        {
            return;
        }

        if (!$this->param->get("forceloadmedia", false))
        {
            RSTBoxHelper::loadassets(true);
        }

        /* Prepare HTML */
        $html = RSTBoxHelper::renderLayout("rstbox", $boxes);

        if ($this->param->get("preparecontent", true))
        {
            $html = JHtml::_('content.prepare', $html);
        }

        $this->boxes = $html;
    }

    /**
     *  Listening to the onAfterRender event in order to append the boxes to the document
     */
    public function onAfterRender() 
    {
        // Get Helper
        if (!$this->getHelper())
        {
            return;
        }

        // Break if no boxes found
        if (!$html = $this->boxes)
        {
            return;
        }

        // Prepare replacements
        $buffer = $this->app->getBody();
        $closingTag = "</body>";

        if (strpos($buffer, $closingTag))
        {
            // If </body> exists prepend the box HTML
            $buffer = str_replace($closingTag, $html . $closingTag, $buffer);
        } else 
        {
            // If </body> does not exist append to document's end
            $buffer .= $html;
        }
        
        // Set body's final layout
        $this->app->setBody($buffer);
    }

    /**
     *  Method to handle AJAX requests.
     *  If not passed a valid token the request will abort.
     *  
     *  Listening on URL: ?option=com_ajax&format=raw&plugin=rstbox&task=track
     *
     *  @return  JSON result formated in JSON
     */
    function onAjaxRstBox()
    {
        JSession::checkToken("request") or die('Invalid Token');

        // Check if a valid task passed
        $task = $this->app->input->get('task', null);

        if (is_null($task) || !in_array($task, $this->validAJAXTasks))
        {
            return;
        }

        // Result object
        $info = new stdClass();
        $info->status = false;

        // Task Track
        if ($task == "track")
        {
            // Initializes Logger
            $logger = JPATH_ADMINISTRATOR . "/components/com_rstbox/helpers/log.php";

            if (!JFile::exists($logger) || !include_once($logger))
            {
                return;
            }

            $this->log = new eBoxlog();

            $boxid   = $this->app->input->get('box', null, 'INT');
            $eventid = $this->app->input->get('event', 1, 'INT');

            // Track event
            $result = $this->log->track($boxid, $eventid);

            $info->status = $result;
            $info->box = $boxid;
            $info->eventid = $eventid;

            // Housekeeping
            if ($this->log->clean())
            {
                $info->clean = true;
            }
        }

        echo json_encode($info);
    }

    /**
     *  Loads the helper classes of plugin
     *
     *  @return  bool
     */
    private function getHelper()
    {
        // Return if is helper is already loaded
        if ($this->init)    
        {
            return true;
        }

        // Return if we are not in frontend
        if (!$this->app->isSite())
        {
            return false;
        }

        // Return if compnent is not enabled
        $component = JComponentHelper::getComponent('com_rstbox', true);

        if (!$component->enabled)
        {   
            return;
        }

        $this->param = $component->params;

        // Handle the component execution when the tmpl request paramter is overriden
        if (!$this->param->get("executeoutputoverride", false) && $this->app->input->get('tmpl', null, "cmd") != null)
        {
            return false;
        }

        // Handle the component execution when the format request paramter is overriden
        if (!$this->param->get("executeonformat", false) && $this->app->input->get('format', "html", "cmd") != "html")
        {
            return false;
        }

        // Check if Novarain Framework is installed
        if (!JFile::exists(JPATH_PLUGINS . '/system/nrframework/nrframework.php')) 
        {
            return false;
        }

        // Check if Novarain Framework is enabled
        $p = JPluginHelper::getPlugin('system', "nrframework");
        if (!isset($p->name))
        {
            return false;
        }
        
        // Load Novarain Framework Helpers
        require_once JPATH_PLUGINS . '/system/nrframework/helpers/functions.php';
        
        // Return if document type is Feed
        if (NRFrameworkFunctions::isFeed()) 
        {
            return false;
        }

        // Load component's helper file
        $componentHelper = JPATH_ADMINISTRATOR.'/components/com_rstbox/helpers/rstbox.php';
        if (!JFile::exists($componentHelper))
        {
            return false;
        }

        require_once $componentHelper;

        // Initializes Logger and sets a unique cookie identifier
        $logger = JPATH_ADMINISTRATOR . "/components/com_rstbox/helpers/log.php";

        if (JFile::exists($logger) && include_once($logger))
        {
            $this->log = new eBoxlog();
            $this->log->getToken();
        }

        return ($this->init = true);
    }
}
