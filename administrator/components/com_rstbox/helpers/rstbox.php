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
 
class RstboxHelper 
{
    public static $prefix = "rstbox-";
    public static $assdir = "components/com_rstbox/assets/";

    /**
     *  Returns all available box types
     *
     *  @return  array
     */
    public static function getBoxTypes()
    {
        // Trigger all Engage Box plugins
        JPluginHelper::importPlugin('engagebox');
        $dispatcher = JEventDispatcher::getInstance();

        // Get a list with all available services
        $types = array();
        $dispatcher->trigger('onEngageBoxTypes', array(&$types));
        asort($types);

        return array_unique($types);
    }

    /**
     *  Returns permissions
     *
     *  @return  object
     */
    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject;
        $assetName = 'com_rstbox';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action)
        {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

    public static function SessionStartTime() {
        $session = JFactory::getSession();
        
        $var = 'starttime';
        $sessionStartTime = $session->get($var);

        if (!$sessionStartTime) {
            $date = NRFrameworkFunctions::dateTimeNow();
            $session->set($var, $date);
        }

        return $session->get($var);
    }

    public static function renderField($field, $blank = false) {

        if (!$field) {
            return;
        }

        $fieldName = NRFrameworkFunctions::clean(str_replace("jform","",$field->name));

        if ($blank) {
            return $field->label.$field->input;
        } else {
            $classes = array("control-group","clearfix",$fieldName);
            
            $html[] = '<div class="'.implode(" ", $classes).'">';
            $html[] = '<div class="control-label">'.$field->label.'</div>';
            $html[] = '<div class="controls">'.$field->input.'</div></div>';

            return implode(" ", $html);
        }
    }

    public static function renderFormFields($fieldset) {
        $html = "";

        foreach ($fieldset as $field) {
            $html .= self::renderField($field);
        }

        return $html;
    }

    public static function renderLayout($file, $displayData) {
        $layout = new JLayoutFile($file, null, array('debug' => false, 'client' => 1, 'component' => 'com_rstbox'));
        return $layout->render($displayData);
    }

    public static function boxRemoveCookie($id)
    {
        $cookie = "rstbox_" . md5(JPATH_SITE) . "_" . $id;
        JFactory::getApplication()->input->cookie->set($cookie, null, time() - 1, "/");
    }

    public static function boxHasCookie($id) {
        
        if (!$id) {
            return;
        }

        $cookie = "rstbox_" . md5(JPATH_SITE) . "_" . $id;
        $cookieValue = JFactory::getApplication()->input->cookie->get($cookie);

        if ($cookieValue) {
            return true;
        }

        return false;
    }

    public static function checkPublishingAssignments(&$boxes)
    {
        if (!$boxes)
        {
            return;
        }

        // Load Framework based publishing assignments
        require_once(JPATH_PLUGINS . "/system/nrframework/helpers/assignments.php");
        $assignments = new nrFrameworkAssignmentsHelper();

        // Load local publishing assignments
        require_once(__DIR__ . "/assignments.php");
        //$localAssignments = new ebAssignments();

        foreach ($boxes as $key => $box)
        {
            $params = new JRegistry($box->params);

            // Check local assignments
            $localAssignments = new ebAssignments($box, $params);
            $pass = $localAssignments->passAll();

            // If testmode is enabled disable the User Groups assignment
            if ($box->testmode)
            {
                $params->set("assign_usergroups", "0");
                $box->params = $params->toString();
            }

            // Check global assignments only if local assignments passed
            if ($pass)
            {
                $pass = $assignments->passAll($box, $params->get("assignmentMatchingMethod", "and"));        
            }  

            if (!$pass) {
                unset($boxes[$key]);
            }
        }
    }

    public static function getBoxes() {

        $user   = JFactory::getUser();
        $isRoot = $user->authorise('core.admin');
        $cParam = JComponentHelper::getParams('com_rstbox');

        $query = "select b.* from #__rstbox b ";
        $query .= "where b.published = 1 ";
        if (!$isRoot) { 
            $query .= " AND b.testmode=0"; 
        }

        $db = JFactory::getDBO();
        $db->setQuery($query);
        $boxes = $db->loadObjectList();

        self::checkPublishingAssignments($boxes);

        JPluginHelper::importPlugin('engagebox');
        $dispatcher = JEventDispatcher::getInstance();

        if (is_array($boxes)) {
            foreach ($boxes as $box)
            {
                $box->params = new JRegistry($box->params);
                $settings = new stdClass();

                //$box->content = self::renderLayout($box->boxtype, $box);
                $box->content = $dispatcher->trigger('onEngageBoxTypeRender', array($box));
                $box->content = implode(" ", $box->content);

                /* Classes */
                $classes = array(
                    "rstbox_".$box->position,
                    "rstbox_".$box->boxtype,
                    $box->params->get("classsuffix", ""),
                    self::prefixClass($box->params->get("aligncontent")),
                    $box->params->get("boxshadow", "1") != "none" ? "rstbox_shd_".$box->params->get("boxshadow", "1") : false,
                    "form".ucfirst($box->params->get("formorient", "ver"))
                );

                /* CSS */
                $style = array(
                    "max-width:".$box->params->get("width"),
                    "height:".$box->params->get("height"),
                    "background-color:".$box->params->get("backgroundcolor"),
                    "color:".$box->params->get("textcolor"),
                    "border:". $box->params->get("bordertype", "solid") . " " . $box->params->get("borderwidth", "15px") . " " . $box->params->get("bordercolor", "#000"),
                    "border-radius:".$box->params->get("borderradius", "0px"),
                    "padding:".$box->params->get("padding", "20px"),
                    //"margin:".$box->params->get("margin", "0"),
                    "z-index:".$box->params->get("zindex", "99999")
                );

                // Background Image
                if ($box->params->get("bgimage", false))
                {
                    $bgImage = array(
                        "background-image: url(".JURI::root() . $box->params->get("bgimagefile").")",
                        "background-repeat:".strtolower($box->params->get("bgrepeat")),
                        "background-size:".strtolower($box->params->get("bgsize")),
                        "background-position:".strtolower($box->params->get("bgposition"))
                    );

                    $style = array_merge($style, $bgImage);
                }

                /* Background Overlay */
                if ($box->params->get("overlay", false)) 
                {
                    $bgOverlay = array(
                        $box->params->get("overlay_color"),
                        $box->params->get("overlayclick")
                    );

                    $settings->overlay = implode(":", $bgOverlay);
                }

                /* Other Settings */
                $settings->delay             = $box->params->get("triggerdelay");
                $settings->transitionin      = $box->params->get("animationin", "rstbox.slideUpIn");
                $settings->transitionout     = $box->params->get("animationout", "rstbox.slideUpOut");
                $settings->duration          = $box->params->get("duration", "400");
                $settings->autohide          = $box->params->get("autohide", 0);
                $settings->closeopened       = $box->params->get("closeopened", false);
                $settings->preventpagescroll = $box->params->get("preventpagescroll", "2") == "2" ? $cParam->get("preventpagescroll", false) : $box->params->get("preventpagescroll");
                $settings->log               = is_null($box->params->get("stats", null)) ? $cParam->get("stats", 1) : $box->params->get("stats");
                $settings->testmode          = $box->testmode;
                $settings->autoclose         = ($box->params->get("autoclose", false) && $box->params->get("autoclosevalue") > 0) ? $box->params->get("autoclosevalue") : false;

                if ($box->triggermethod == "userleave") { 
                    $settings->exitdelay = $box->params->get("exitdelay",0);
                    $settings->exittimer = $box->params->get("exittimer",1000);
                }

                if ($box->triggermethod == "onclick") { 
                    $settings->triggerelement = $box->params->get("triggerelement");
                    $settings->triggerpreventdefault = $box->params->get("preventdefault", 0);
                }

                /* Box Trigger Attribute */
                $trigger = $box->triggermethod;

                if ($box->triggermethod == "pageheight") { 
                    $trigger .= ":".$box->params->get("triggerpercentage"); 
                }

                if (in_array($box->triggermethod, array("element"))) { 
                    $trigger .= ":".$box->params->get("triggerelement"); 
                }

                if ($box->triggermethod == "elementHover") 
                { 
                    $trigger .= ":".$box->params->get("triggerelement").":".$box->params->get("triggerdelay"); 
                    $box->params->set("triggerdelay", 0);
                }

                /* Trigger by Location Hash */
                if (!in_array($box->triggermethod, array("pageready", "pageload")) && $box->params->get("hashtag"))
                {
                    $settings->triggerbyhash = $box->params->get("hashtag");
                }

                // Close Button Classes
                $closeButtonClasses = array(
                    "rstbox-close",
                    "rstbox_clbtn_".$box->params->get("closebutton_style", "default")
                );

                $rtl = $box->params->get("rtl", "2") == "2" ? $cParam->get("rtl", false) : $box->params->get("rtl");

                if ($rtl)
                {
                    $classes[] = "rstboxRTL";
                }

                // HTML Attributes
                $box->HTMLattributes = implode(" ",
                    array(
                        'id="rstbox_'.$box->id.'"',
                        ($rtl) ? 'dir="rtl"' : "",
                        'class="rstbox '.implode(" ",$classes).'"',
                        'data-settings=\''.json_encode($settings).'\'',
                        'data-trigger="'.$trigger.'"',
                        'data-cookietype="'.$box->params->get("cookietype", "days").'"',
                        'data-cookie="'.$box->cookie.'"',
                        'data-title="'.$box->name.'"',
                        'style="'.implode(";", $style).'"'
                    )
                );

                $box->closebuttonclasses = implode(" ", $closeButtonClasses);
            }
        }

        return $boxes;
    }

    public static function prefixClass($classes)
    {

        if (empty($classes) || is_null($classes))
        {
            return;
        }

        $arr = $classes;

        if (!is_array($classes))
        {
            $arr = explode(" ", $classes);
        }

        foreach ($arr as $key => $value) {
            $arr[$key] = self::$prefix . $value;
        }

        return implode(" ", $arr);
    }

    public static function pretty($str) {
        $a = $str;
        $a = ucfirst(str_replace("_", " ", $a));
        return $a;
    }

    public static function getVersion()
    {
        return NRFrameworkFunctions::getExtensionVersion("com_rstbox");
    }

    /**
     *  Loads front-end and back-end component media files
     *
     *  @param   boolean  $front  [description]
     *
     *  @return  void
     */
    public static function loadassets($front = false)
    {

        $params = JComponentHelper::getParams('com_rstbox');
        $doc = JFactory::getDocument();

        if ($params->get("loadjQuery", true))
        {
            JHtml::_('jquery.framework');
        }

        if ($front)
        {
            $path = JURI::root(true) .'/media/com_rstbox/';

            if ($params->get("loadCSS", true))
            {
                $doc->addStyleSheet($path .'css/engagebox.css?v='.self::getVersion());
            }

            if ($params->get("loadVelocity", true))
            {
                $doc->addScript($path.'js/velocity.js?v='.self::getVersion());
                $doc->addScript($path.'js/velocity.ui.js?v='.self::getVersion());
            }

            $doc->addScript($path.'js/engagebox.js?v='.self::getVersion());
        } else
        {
            $path = JURI::root(true)."/administrator/".self::$assdir;
            $doc->addStyleSheet($path.'css/styles.css?v='.self::getVersion());
        }
    }
}