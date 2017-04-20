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

if (isset($button->show) && !$button->show)
{ 
	return;
}

?>

<a
	<?php if ($button->click == "url") { ?>
		data-ebox-prevent="0"
		data-ebox-cmd="closeKeep"
		target="<?php echo $button->newtab ? "_blank" : "_self" ?>"
		href="<?php echo $button->url ?>"
	<?php } ?>

	<?php if ($button->click == "open") { ?>
		data-ebox-prevent="0"
		data-ebox-cmd="open"
		data-ebox="<?php echo $button->box; ?>"
		href="#"
	<?php } ?>

	<?php if ($button->click == "close") { ?>
		data-ebox-cmd="closeKeep"
		href="#"
	<?php } ?>

	<?php 
		$styles = implode(";", array(
			"background-color:" . $button->background,
			"color:" 	 . $button->color
		));
	?>

	class="ebox-ys-btn"
	style="<?php echo $styles; ?>">
	<?php echo $button->text ?>
</a>




