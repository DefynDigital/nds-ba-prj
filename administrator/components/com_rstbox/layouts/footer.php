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
$ver = NRFrameworkFunctions::getExtensionVersion("com_rstbox");

?>

<div id="rstboxfooter" class="center">

    <?php echo JText::_('COM_RSTBOX') . " v" . $ver ?>
    <span style="font-size:10px; position:relative; top:-1px;">(Former Responsive Scroll Triggered Box)</span>
    <br>

    <?php if ($this->config->get("showcopyright", true)) { ?>
        <div class="footer_review">
            <?php echo JText::_("NR_LIKE_THIS_EXTENSION") ?>
            <a href="https://extensions.joomla.org/extensions/extension/style-a-design/popups-a-iframes/engage-box" target="_blank"><?php echo JText::_("NR_LEAVE_A_REVIEW") ?></a> 
            <a href="https://extensions.joomla.org/extensions/extension/style-a-design/popups-a-iframes/engage-box" target="_blank" class="stars"><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span><span class="icon-star"></span></a>
        </div>

        <Br>&copy; <?php echo JText::sprintf('NR_COPYRIGHT', date("Y")) ?><br>
        <?php echo JText::_("NR_NEED_SUPPORT") ?> 

    	<a href="http://www.tassos.gr/joomla-extensions/responsive-scroll-triggered-box-for-joomla/docs" target="_blank"><?php echo JText::_("NR_READ_DOCUMENTATION") ?></a> or
        <a href="http://www.tassos.gr/contact?s=BackEndSupport-<?php echo $ver ?>" target="_blank"><?php echo JText::_("NR_DROP_EMAIL") ?></a>
    <?php } ?>
</div>