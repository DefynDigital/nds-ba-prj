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

$box     = $displayData;
$yesno   = $box->params->get("yesno");
$buttons = array($yesno->yes, $yesno->no);

JFactory::getDocument()->addStyleSheet(JURI::base(true) . "/plugins/engagebox/yesno/media/styles.css");

?>

<div class="ebox-yes-no">
	<div class="ebox-yn-text">
		<?php if (!empty($yesno->headline)) { ?>

		<?php 
			$headlineStyles = implode(";", array(
				"font-size:" . $yesno->headlinesize . "px",
				"color:" 	 . $yesno->headlinecolor
			));
		?>

		<div class="ebox-yn-headline" style="<?php echo $headlineStyles ?>">
			<?php echo $yesno->headline; ?>
		</div>
		<?php } ?>
	</div>
	
	<div class="ebox-ys-buttons">
		<?php 
			foreach ($buttons as $key => $button)
			{
				include(__DIR__ . "/button.php");
			}
		?>
	</div>

	<?php if (!empty($yesno->footer)) { 
		$footerStyles = implode(";", array(
			"font-size:" . $yesno->footersize . "px",
			"color:" 	 . $yesno->footercolor
		));
	?>
	<div class="ebox-ys-footer" style="<?php echo $footerStyles ?>">
		<?php echo $yesno->footer; ?>
	</div>
	<?php } ?>
</div>
