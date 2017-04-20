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

$updateSitesFile = JPATH_SITE . '/plugins/system/nrframework/helpers/updatesites.php';

if (JFile::exists($updateSitesFile))
{
	require_once $updateSitesFile;
	$upds = new NRUpdateSites();
	$downloadKey = $upds->getDownloadKey();
} else {
	$downloadKey = false;
}

$docHome = "http://www.tassos.gr/joomla-extensions/responsive-scroll-triggered-box-for-joomla/";

?>

<div class="row-fluid">
	<span class="span8">
		<div class="clearfix">
			<h3><?php echo JText::_("COM_RSTBOX_BOXMANAGER") ?></h3>
			<ul class="nr-icons clearfix">
				<li>
					<a href="<?php echo JURI::base() ?>index.php?option=com_rstbox&view=items&task=item.add">
						<span class="nr-img icon-new"></span>
						<span class="nr-icon-text"><?php echo JText::_("NR_NEW") ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo JURI::base() ?>index.php?option=com_rstbox&view=items">
						<span class="nr-img icon-list"></span>
						<span class="nr-icon-text"><?php echo JText::_("NR_LIST") ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo JURI::base() ?>index.php?option=com_rstbox&view=items&layout=import">
						<span class="nr-img icon-box-add"></span>
						<span class="nr-icon-text"><?php echo JText::_("NR_IMPORT") ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo JURI::base() ?>index.php?option=com_config&view=component&component=com_rstbox&path=&return=<?php echo MD5(JURI::base()."index.php?option=com_rstbox") ?>">
						<span class="nr-img icon-options"></span>
						<span class="nr-icon-text"><?php echo JText::_("JOPTIONS") ?></span>
					</a>
				</li>
			</ul>
		</div>
		<div class="clearfix">
			<h3>Help</h3>
			<ul class="nr-icons clearfix">
				<li>
					<a href="<?php echo $docHome; ?>/docs" target="_blank">
						<span class="nr-img icon-info"></span>
						<span class="nr-icon-text"><?php echo JText::_("NR_DOCUMENTATION")?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $docHome; ?>/faqs" target="_blank">
						<span class="nr-img icon-question-sign"></span>
						<span class="nr-icon-text"><?php echo JText::_("NR_FAQ")?></span>
					</a>
				</li>
			</ul>
		</div>
	</span>
	<span class="span4">
		<?php if (!$downloadKey) { ?>
			<div class="alert">
				<h3><?php echo JText::_("NR_DOWNLOAD_KEY_MISSING") ?></h3>
				<p><?php echo JText::sprintf("NR_DOWNLOAD_KEY_HOW", "<b>".JText::_("COM_RSTBOX")."</b>"); ?></p>
				<p><?php echo JText::_("NR_DOWNLOAD_KEY_DESC"); ?></p>
				<a class="btn btn-small btn-info" target="_blank" href="http://www.tassos.gr/downloads">
					<?php echo JText::_("NR_DOWNLOAD_KEY_FIND")?>
				</a>
				<a class="btn btn-small btn-success" href="<?php echo JURI::base() ?>index.php?option=com_plugins&view=plugins&filter_search=novarain">
					<?php echo JText::_("NR_DOWNLOAD_KEY_UPDATE")?>
				</a>
			</div>
		<?php } ?>

		<?php echo JHtml::_('bootstrap.startAccordion', "info", array('active' => 'slide0')); ?>
		
		<!-- Information Slide -->
		<?php echo JHtml::_('bootstrap.addSlide', "info", JText::_("NR_INFORMATION"), 'slide0'); ?>
		<table class="table table-striped">
			<tbody>
				<tr>
					<td><?php echo JText::_("NR_EXTENSION"); ?></td>
					<td><?php echo JText::_("COM_RSTBOX"); ?></td>
				</tr>	
				<tr>
					<td><?php echo JText::_("NR_VERSION"); ?></td>
					<td><?php echo NRFrameworkFunctions::getExtensionVersion("com_rstbox"); ?>
						<a href="http://www.tassos.gr/joomla-extensions/responsive-scroll-triggered-box-for-joomla#changelog" target="_blank"><?php echo JText::_("NR_CHANGELOG"); ?></a>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("NR_DOWNLOAD_KEY"); ?></td>
					<td>
						<?php if ($downloadKey) { ?>
						<span class="label label-success"><?php echo JText::_("NR_OK"); ?></span>
						<?php } else { ?>
						<span class="label label-important"><?php echo JText::_("NR_MISSING"); ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo JText::_("NR_LICENSE"); ?></td>
					<td><a href="http://www.tassos.gr/license" target="_blank">GNU GPLv3 Commercial</td>
				</tr>
				<tr>
					<td><?php echo JText::_("NR_AUTHOR"); ?></td>
					<td>Tassos Marinos - <a href="http://www.tassos.gr" target="_blank">www.tassos.gr</td>
				</tr>
				<tr>
					<td><?php echo JText::_("NR_FOLLOWME"); ?></td>
					<td><a href="#" onclick="window.open('https://twitter.com/intent/follow?screen_name=tassosm','tassos.gr','width=500,height=500');"><span class="label label-info"><?php echo JText::sprintf("NR_FOLLOW", "@mtassos") ?></span></a></td>
				</tr>
			</tbody>
		</table>

		<?php echo JHtml::_('bootstrap.endSlide'); ?>
		
		<!-- Documentation Slide -->
		<?php echo JHtml::_('bootstrap.addSlide', "info", JText::_("NR_DOCUMENTATION"), 'slide1'); ?>
		<?php 
			$docs = array(
				"Getting started with a simple scroll triggered box" => "getting-started-with-a-simple-scroll-box",
				"How to create a AcyMailing Optin Box" => "create-an-email-subscription-popup-for-acymailing",
				"Display a popup before user leaves your website" => "display-a-popup-before-user-leaves-your-website",
				"The onClick trigger point" => "the-onclick-trigger-point",
				"HTML Data Attributes" => "html-data-attributes",
				"Load any existing Joomla! module" => "load-any-existing-joomla-module",
				"Create a Smart Sticky Bar" => "create-a-smart-sticky-bar",
				"Create a YouTube Video Popup" => "create-a-youtube-video-popup",
				"Create a Cookie Based Age Verification Popup" => "create-an-age-verification-popup",
				"Using the Javascript API" => "using-javascript-api",
			);
		?>
		<ul>
			<?php foreach ($docs as $title => $url) { ?>
				<li><a target="_blank" href="<?php echo $docHome; ?>docs/<?php echo $url?>"><?php echo $title ?></a></li>
			<?php } ?>
		</ul>
		<?php echo JHtml::_('bootstrap.endSlide'); ?>

		<!-- Faqs Slide -->
		<?php echo JHtml::_('bootstrap.addSlide', "info", JText::_("NR_FAQ"), 'slide2'); ?>
		<?php 
			$docs = array(
				"I don't see my box" => "i-don-t-see-my-popup",
				"Box suddenly stopped working" => "popup-suddenly-stopped-working",
				"What is the 'Hidden by cookie' indication?" => "what-is-the-hidden-by-cookie-indication",
				"Enable parsing of content plugins (Content Prepare)" => "enable-parsing-of-content-plugins-content-prepare",
				"Install it on a Joomla 2.5 site" => "install-it-on-joomla-2-5-website",
			);
		?>
		<ul>
			<?php foreach ($docs as $title => $url) { ?>
				<li><a target="_blank" href="<?php echo $docHome; ?>faqs/<?php echo $url?>"><?php echo $title ?></a></li>
			<?php } ?>
		</ul>
		<?php echo JHtml::_('bootstrap.endSlide'); ?>


		<!-- Translations Slide -->
		<?php echo JHtml::_('bootstrap.addSlide', "info", JText::_("NR_HELP_WITH_TRANSLATIONS"), 'slide3'); ?>
		<p>
			<?php echo JText::sprintf("NR_TRANSLATE_INTEREST", JText::_("COM_RSTBOX")); ?>
			<a href="https://www.transifex.com/tassosgr/rstbox/" target="_blank"><?php echo JText::_("NR_TRANSIFEX_REQUEST") ?></a>.
		</p>
		<?php echo JHtml::_('bootstrap.endSlide'); ?>

		<?php echo JHtml::_('bootstrap.endAccordion'); ?>
	</span>
</div>
<hr>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR."/layouts/footer.php"); ?>

