<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$lang            = JFactory::getLanguage();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$input           = $app->input;
$user            = JFactory::getUser();
// Detecting Active Variables
$option   = $input->get('option', '');
$view     = $input->get('view', '');
$layout   = $input->get('layout', '');
$task     = $input->get('task', '');
$itemid   = $input->get('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename', ''), ENT_QUOTES, 'UTF-8');
$cpanel   = ($option === 'com_cpanel');

$hidden = JFactory::getApplication()->input->get('hidemainmenu');
?>
<div class="row-fluid">
	<?php $iconmodules = JModuleHelper::getModules('icon');
	if ($iconmodules) : ?>
		<div class="span2">
			<div class="cpanel-links">
				<?php
				// Display the submenu position modules
				foreach ($iconmodules as $iconmodule)
				{
					echo JModuleHelper::renderModule($iconmodule);
				}
				?>
				<div></div><div style="clear:both"></div>
			</div>
		</div>
	<?php endif; ?>
	<div class="span<?php echo ($iconmodules) ? 10 : 12; ?>">
		<?php if ($user->authorise('core.manage', 'com_postinstall')) : ?>
			<div class="row-fluid">
			<div class="content-padding">
			<?php if (!$cpanel) : ?>
	<!-- Subheader -->
	<a class="btn btn-subhead" data-toggle="collapse" data-target=".subhead-collapse"><?php echo JText::_('TPL_WISADMIN_TOOLBAR'); ?>
		<span class="icon-wrench"></span></a>
	<div class="subhead-collapse collapse">
		<div class="subhead">
			<div class="container-fluid">
				<div id="container-collapse" class="container-collapse"></div>
				<div class="row-fluid">
					<div class="span12">
						<jdoc:include type="modules" name="toolbar" style="no" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else : ?>
	<div></div>
<?php endif; ?>
				<?php if ($this->postinstall_message_count): ?>
					<div class="alert alert-info">
					<h4>
						<?php echo JText::_('COM_CPANEL_MESSAGES_TITLE'); ?>
					</h4>
					<p>
						<?php echo JText::_('COM_CPANEL_MESSAGES_BODY_NOCLOSE'); ?>
					</p>
					<p>
						<?php echo JText::_('COM_CPANEL_MESSAGES_BODYMORE_NOCLOSE'); ?>
					</p>
					<p>
						<a href="index.php?option=com_postinstall&amp;eid=700" class="btn btn-primary">
						<?php echo JText::_('COM_CPANEL_MESSAGES_REVIEW'); ?>
						</a>
					</p>
					</div>
				<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="row-fluid">

			<?php
			$spans = 0;

			foreach ($this->modules as $module)
			{
				// Get module parameters
				$params = new Registry;
				$params->loadString($module->params);
				$bootstrapSize = $params->get('bootstrap_size');
				if (!$bootstrapSize)
				{
					$bootstrapSize = 12;
				}
				$spans += $bootstrapSize;
				if ($spans > 12)
				{
					echo '</div><div class="row-fluid">';
					$spans = $bootstrapSize;
				}
				echo JModuleHelper::renderModule($module, array('style' => 'well'));
			}
			?>
		</div>
	</div>
</div>
