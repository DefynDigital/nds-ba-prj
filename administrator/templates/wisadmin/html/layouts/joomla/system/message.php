<?php
/**
 * @package     Joomla.Administrator.Template
 * @subpackage  Templates.wisadmin
 * @copyright   Copyright (C) 2016 JoomlaTema.Net. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */
defined('_JEXEC') or die;

$msgList = $displayData['msgList'];

$alert = array('error' => 'alert-error', 'warning' => '', 'notice' => 'alert-info', 'message' => 'alert-success');
?>
<div id="system-message-container">
	<?php if (is_array($msgList) && $msgList) : ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?php foreach ($msgList as $type => $msgs) : ?>
			<div class="alert <?php echo $alert[$type]; ?>">
				<h4 class="alert-heading"><?php echo JText::_($type); ?></h4>
				<?php if ($msgs) : ?>
					<?php foreach ($msgs as $msg) : ?>
						<p class="alert-message"><?php echo $msg; ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
