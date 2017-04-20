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

$height  = $box->params->get("iframeheight", "500px");
$url     = $box->params->get("iframeurl");
$scroll  = $box->params->get("iframescrolling", "no");
$params  = $box->params->get("iframeparams");
$async   = $box->params->get("async", "afterOpen") == "dom" ? false : $box->params->get("async", "afterOpen");
$header  = $box->params->get("iframeheader", null);
$class   = ($height == "100%") ? "eboxFitFrame" : "";

$content = '<iframe width="100%" height="' . $height . '" src="' . $url . '" scrolling="' . $scroll . '" frameborder="0" allowtransparency="true" ' . $params . ' class="' . $class . '">"></iframe>';

?>

<?php if (!empty($header)) ?>
	<div class="rstbox-content-header">
		<?php echo $box->params->get("iframeheader"); ?>
	</div>
<?php ?>

<div class="rstbox-content-wrap">
	<?php if (!$async) { echo $content; } ?> 
</div>

<?php if ($async || $box->params->get("removeonclose", false)) { ?>
	<script>
		(function($) {

			var box       = $("#rstbox_<?php echo $box->id ?>");
			var container = box.find(".rstbox-content-wrap");
			var content   = '<?php echo $content ?>';

			<?php if (in_array($async, array("afterOpen", "beforeOpen"))) { ?>
				// On Box BeforeOpen or AfterOpen
				box.bind("<?php echo $async ?>", function() {
					if (!container.find("iframe").length) {
						container.html(content);
					}
				});
			<?php } ?>
			
			<?php if ($async == "pageLoad") { ?>
				// On Page Load
				$(window).load(function() {
					container.html(content);
				})
			<?php } ?>

			<?php if ($box->params->get("removeonclose", false)) { ?>
				box.bind("afterClose", function() {
					container.empty();
				})
			<?php } ?>

		})(rstbox.jQuery);
	</script>
<?php } ?>