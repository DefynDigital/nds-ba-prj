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

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class Com_RstBoxInstallerScript extends Com_RstBoxInstallerScriptHelper
{
	public $name = 'RSTBOX';
	public $alias = 'rstbox';
	public $extension_type = 'component';

    private $installedVersion;

    public function onBeforeInstall() 
    {
    	$this->installedVersion = $this->getVersion($this->getInstalledXMLFile());
       
        return true;
    }

	public function onAfterInstall()
	{
		if ($this->install_type == "update") 
        {
            $this->fixBoxes();

            // Remove unwanted columns
            $this->dropUnwantedColumns("rstbox", array(
                "animation",
                "accesslevel",
                "settings"
            ));

            // Remove unwanted tables
            $this->dropUnwantedTables(array(
                "rstbox_menu"
            ));

            // Remove unwanted folders
            $this->deleteFolders(array(
                JPATH_SITE . '/components/com_rstbox',
                JPATH_ADMINISTRATOR . '/components/com_rstbox/helpers/assignments',
                JPATH_ADMINISTRATOR . '/components/com_rstbox/helpers/vendors'
            ));

            // Remove unwanted files
            $this->deleteFiles(array(
                JPATH_SITE . '/media/com_rstbox/js/rstbox.js',
                JPATH_SITE . '/media/com_rstbox/css/rstbox.css'
            ));

            if (version_compare($this->installedVersion, '3.0', 'l'))
            {
                JFactory::getApplication()->enqueueMessage('We are excited to announce the rebranding of your <b>Responsive Scroll Triggered Box</b> to <b>Engage Box</b> <a class="btn btn-success" style="margin-left:10px; position:relative; top:-1px;" href="http://www.tassos.gr/blog/welcome-engage-box" target="_blank">Read More</a>.', 'notice');
            }
        }
	}

    function fixBoxes() {
    	
        $data = $this->fetch("rstbox");

        if (!$data) 
        {
            return;
        }

        foreach ($data as $key => $box)
        {
            // Prepare data
            $object = new stdClass();
            $object->id = $box->id;
            $params = json_decode($box->params);

            if (version_compare($this->installedVersion, '2.5', 'l')) 
            {
                // Fix new verticalalign option
                if ($params->verticalalign == "1")
                {
                    $params->verticalalign = "v";
                }
            }

            if (version_compare($this->installedVersion, '2.6.6', 'l'))
            {
                // Fix new RTL option
                if (isset($params->textdirection))
                {
                    $params->rtl = $params->textdirection == "rtl" ? "1" : "2";
                }
            }

            if (version_compare($this->installedVersion, '2.7.1', 'le'))
            {
                // Vertical Align field is replaced by Align Content field
                if (isset($params->verticalalign))
                {
                    switch ($params->verticalalign)
                    {
                        case 'v':
                            $params->aligncontent = "acm acl";
                            break;
                        case 'h':
                            $params->aligncontent = "act acc";
                            break;    
                        case 'b':
                            $params->aligncontent = "acm acc";
                            break;
                    }
                }

                // Merge Background Overlay and Opacity field and convert it to RGBa format
                if ($hexToRGB = $this->hex2RGB($params->overlay_color, true))
                {
                    $params->overlay_color = "rgba(".$hexToRGB.",".$params->overlay_percent.")";
                    unset($params->overlay_percent);
                }
            }

            $object->params = json_encode($params);
             
            // Update database
            $this->db->updateObject('#__rstbox', $object, 'id');
        }
    }

    function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') 
    {
        $hexStr   = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();

        //If a proper hex code, convert using bitwise operation. No overhead... faster
        if (strlen($hexStr) == 6) 
        {   
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;

        // if shorthand notation, need some string manipulations
        } elseif (strlen($hexStr) == 3)
        {
            
            $rgbArray['red']   = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue']  = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else 
        {
            //Invalid hex color code
            return false;
        }

        // returns the rgb string or the associative array
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; 
    }
}
