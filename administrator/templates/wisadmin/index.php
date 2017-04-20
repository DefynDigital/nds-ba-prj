<?php
/**
 * @package     Joomla.Administrator.Template
 * @subpackage  Templates.wisadmin
 * @copyright   Copyright (C) 2016 JoomlaTema.Net. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */

defined('_JEXEC') or die;

$app             = JFactory::getApplication();
$doc             = JFactory::getDocument();
$lang            = JFactory::getLanguage();
$this->language  = $doc->language;
$this->direction = $doc->direction;
$input           = $app->input;
$user            = JFactory::getUser();
$colorVariation  = $this->params->get('colorVariation','default');
$googlefonts  = $this->params->get('googlefonts','Rajdhani');
$basefontsize = $this->params->get('basefontsize','14px');
// author image file
	if ($this->params->get('authorimage'))
	{
		$authorimage = JUri::root() . $this->params->get('authorimage');
	}
	else
	{
		$authorimage = $this->baseurl . '/templates/' . $this->template . '/images/authorimage.jpg';
	}
	
// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScriptVersion($this->baseurl . '/templates/' . $this->template . '/js/template.js');

// Add Stylesheets
$doc->addStyleSheetVersion($this->baseurl . '/templates/' . $this->template . '/css/template' . ($this->direction == 'rtl' ? '-rtl' : '') . '.css');
$doc->addStyleSheetVersion('templates/' . $this->template . '/css/' . ($this->params->get('colorVariation')) . '.css');


// Load custom.css
$file = 'templates/' . $this->template . '/css/custom.css';

if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}

// Load specific language related CSS
$file = 'language/' . $lang->getTag() . '/' . $lang->getTag() . '.css';

if (is_file($file))
{
	$doc->addStyleSheetVersion($file);
}

//////////Google Fonts//////////////

if ($googlefonts=='Source+Sans+Pro:400,600')
{
	$fontfamily='Source Sans Pro';
}
if ($googlefonts=='Open+Sans:400,300')
{
	$fontfamily='Open Sans';
}
if ($googlefonts=='PT+Sans')
{
	$fontfamily='PT Sans';
}
if ($googlefonts=='Rajdhani')
{
	$fontfamily='Rajdhani';
}
if ($googlefonts=='Oswald:400,300,700')
{
	$fontfamily='Oswald';
}

// Detecting Active Variables
$option   = $input->get('option', '');
$view     = $input->get('view', '');
$layout   = $input->get('layout', '');
$task     = $input->get('task', '');
$itemid   = $input->get('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename', ''), ENT_QUOTES, 'UTF-8');
$cpanel   = ($option === 'com_cpanel');

$hidden = JFactory::getApplication()->input->get('hidemainmenu');

$showSubmenu          = false;
$this->submenumodules = JModuleHelper::getModules('submenu');

foreach ($this->submenumodules as $submenumodule)
{
	$output = JModuleHelper::renderModule($submenumodule);

	if (strlen($output))
	{
		$showSubmenu = true;
		break;
	}
}


// Template Parameters
$displayHeader = $this->params->get('displayHeader', '1');
$statusFixed   = $this->params->get('statusFixed', '1');
$stickyToolbar = $this->params->get('username', '1');
$usernametype = $this->params->get('usernametype');

if ($usernametype=='username')
{
	$showuser=$user->username;
}
else
	{
		$showuser=$user->name;
	}
	
if ($showuser==$user->username)
{
	$showuserdropdown=$user->name;
}
else
	{
		$showuserdropdown=$user->username;
	}

// Header classes
$template_is_light = ($this->params->get('templateColor') && colorIsLight($this->params->get('templateColor')));
$header_is_light = ($displayHeader && $this->params->get('headerColor') && colorIsLight($this->params->get('headerColor')));

if ($displayHeader)
{
	// Logo file
	if ($this->params->get('logoFile'))
	{
		$logo = JUri::root() . $this->params->get('logoFile');
	}
	else
	{
		$logo = $this->baseurl . '/templates/' . $this->template . '/images/logo.png';
	}
}


if ($googlefonts=='Oswald:400,300,700')
{
	$fontweight='300';
}
else
	{
		$fontweight = 'bold';
	}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<jdoc:include type="head" />
	<!--[if lt IE 9]>
	<script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script>
	<![endif]-->
<link href='https://fonts.googleapis.com/css?family=<?php echo $googlefonts;?>' rel='stylesheet' type='text/css'>
<style type="text/css">
		body,body.admin{font-family:<?php echo $fontfamily;?>,Helvetica Neue,Helvetica,Arial, sans-serif;font-size:<?php echo $basefontsize;?>;}
		body.component,accordion-toggle a{font-family:<?php echo $fontfamily;?>,Helvetica Neue,Helvetica,Arial, sans-serif;}
		.quick-icons h2.nav-header,.well .module-title.nav-header{font-size:<?php echo $this->params->get('headerfontsize','14px');?>}
		strong,.nav-header,h1, h2, h3, h4, h5, h6 {font-weight: <?php  echo $fontweight;?>;}
</style>

</head>
<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?>">
<!-- Header -->
<?php if ($displayHeader) : ?>
	<header class="header<?php echo $header_is_light ? ' header-inverse' : ''; ?>">
	<div class="row-fluid">
	<div class="span2">
		<div class="container-logo">
			<a class="container-logo" <?php echo 'href="' . $this->baseurl . '"'; ?>><img src="<?php echo $logo; ?>" class="logo" alt="<?php echo $sitename;?>" /></a>
		</div>
		</div>
		<div class="span10"><!-- Top Navigation -->
<nav class="navbar<?php echo $template_is_light ? '' : ' navbar-inverse'; ?> navbar-fixed-top">
	<div class="navbar-inner">
	
	<ul class="brand hidden-desktop nav nav-user<?php echo ($this->direction == 'rtl') ? ' pull-left' : ' pull-right'; ?>">
					<li class="dropdown">
						<a class="<?php echo ($hidden ? ' disabled' : 'dropdown-toggle'); ?>" data-toggle="<?php echo ($hidden ? '' : 'dropdown'); ?>" <?php echo ($hidden ? '' : 'href="#"'); ?>>
						<span class="username"><?php echo $showuser;?></span><img src="<?php echo $authorimage; ?>" width="29" height="29" class="authorimage" alt="<?php echo $showuser;?>" />
						<span class="caret"></span></a>
						<ul class="user dropdown-menu">
							<?php if (!$hidden) : ?>
								<li>
									<span>
										<span class="icon-user"></span>
										<strong><?php echo $showuserdropdown; ?></strong>
									</span>
								</li>
																<li>
								<span>
								<span class="icon-envelope-opened"></span>
									<a href="index.php?option=com_messages"><?php echo JText::_('TPL_WISADMIN_MESSAGES'); ?></a>
									</span>
								</li>
								<li>
								<span>
								<span class="icon-edit"></span>
									<a href="index.php?option=com_admin&amp;task=profile.edit&amp;id=<?php echo $user->id; ?>"><?php echo JText::_('TPL_WISADMIN_EDIT_ACCOUNT'); ?></a>
									</span>
								</li>
								<li><span>
								<span class="icon-exit"></span>
									<a href="<?php echo JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1'); ?>"><?php echo JText::_('TPL_WISADMIN_LOGOUT'); ?></a>
									</span>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				</ul>
				
		<div class="container-fluid">
		<div class="row-fluid">
			<?php if ($this->params->get('admin_menus') != '0') : ?>
				<a href="#" class="btn btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
			<?php endif; ?>
			<div class="sitename"><a class="brand hidden-desktop hidden-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_WISADMIN_PREVIEW', $sitename); ?>" target="_blank"><?php echo JHtml::_('string.truncate', $sitename, 14, false, false); ?>
				<span class="icon-out-2 small"></span></a></div>
			<div<?php echo ($this->params->get('admin_menus') != '0') ? ' class="nav-collapse collapse"' : ''; ?>>
				<jdoc:include type="modules" name="menu" style="none" />	
				<div class="sitename"><a class="brand visible-desktop visible-tablet" href="<?php echo JUri::root(); ?>" title="<?php echo JText::sprintf('TPL_WISADMIN_PREVIEW', $sitename); ?>" target="_blank"><?php echo JHtml::_('string.truncate', $sitename, 14, false, false); ?>
					</span><span class="icon-out-2 small"></span></a>
					<ul class="nav nav-user<?php echo ($this->direction == 'rtl') ? ' pull-left' : ' pull-right'; ?>">
					<li class="dropdown">
						<a class="<?php echo ($hidden ? ' disabled' : 'dropdown-toggle'); ?>" data-toggle="<?php echo ($hidden ? '' : 'dropdown'); ?>" <?php echo ($hidden ? '' : 'href="#"'); ?>>
						<span class="username"><?php echo $showuser;?></span><img src="<?php echo $authorimage; ?>" width="29" height="29" class="authorimage" alt="<?php echo $showuser;?>" />
						<span class="caret"></span></a>
						<ul class="user dropdown-menu">
							<?php if (!$hidden) : ?>
								<li>
									<span>
										<span class="icon-user"></span>
										<strong><?php echo $showuserdropdown; ?></strong>
									</span>
								</li>
																<li>
								<span>
								<span class="icon-envelope-opened"></span>
									<a href="index.php?option=com_messages"><?php echo JText::_('TPL_WISADMIN_MESSAGES'); ?></a>
									</span>
								</li>
								<li>
								<span>
								<span class="icon-edit"></span>
									<a href="index.php?option=com_admin&amp;task=profile.edit&amp;id=<?php echo $user->id; ?>"><?php echo JText::_('TPL_WISADMIN_EDIT_ACCOUNT'); ?></a>
									</span>
								</li>
								<li><span>
								<span class="icon-exit"></span>
									<a href="<?php echo JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1'); ?>"><?php echo JText::_('TPL_WISADMIN_LOGOUT'); ?></a>
									</span>
								</li>
							<?php endif; ?>
						</ul>
					</li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</nav></div>
		</div>
		</div>
		<div>
		</div>
	</header>
<?php endif; ?>

<?php if (!$cpanel) : ?>
	<!-- Subheader -->
	<div class="toolbar-subhead-wrapper">
	<div class="row-fluid">
	<div class="span12">
	<div class="span2">
	<jdoc:include type="modules" name="title" />
	</div>
	<div class="span10">
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
		
		</div>
		</div>
		</div>
		</div>
	
<?php else : ?>
	<div></div>
<?php endif; ?>
<!-- container-fluid -->
<div class="container-fluid container-main">
	<section id="content">
		<!-- Begin Content -->
		<jdoc:include type="modules" name="top" style="xhtml" />
		<div class="row-fluid">
			<?php if ($showSubmenu) : ?>
			<div class="span2">
				<jdoc:include type="modules" name="submenu" style="none" />
			</div>
			<div class="span10">
				<?php else : ?>
				<div class="span12">
					<?php endif; ?>
				
					<?php
					// Show the page title here if the header is hidden
					if (!$displayHeader) : ?>
						<h1 class="content-title" style="margin-bottom:25px;"><?php echo JHtml::_('string.truncate', $app->JComponentTitle, 0, false, false); ?></h1>
					<?php endif; ?>	
					<jdoc:include type="message" />
					<jdoc:include type="component" />
				</div>
			</div>
			<?php if ($this->countModules('bottom')) : ?>
				<jdoc:include type="modules" name="bottom" style="xhtml" />
			<?php endif; ?>
			<!-- End Content -->
	</section>
</div>
<?php if (($statusFixed) && ($this->countModules('status'))) : ?>
	<!-- Begin Status Module -->
	<div id="status" class="navbar navbar-fixed-bottom hidden-phone">
		<div class="btn-toolbar">
			<div class="btn-group pull-right">
				<p>
					<jdoc:include type="modules" name="footer" style="no" />
					&copy; <?php echo date('Y'); ?> <?php echo $sitename; ?>
				</p>

			</div>
			<jdoc:include type="modules" name="status" style="no" />
		</div>
	</div>
	<?php else : ?>
	<div id="status" class="navbar navbar-fixed-bottom hidden-phone" style="display:none;"></div>
	<!-- End Status Module -->
<?php endif; ?>
<jdoc:include type="modules" name="debug" style="none" />
<?php if ($stickyToolbar) : ?>
	<script>
		jQuery(function($)
		{

			var navTop;
			var isFixed = false;

			processScrollInit();
			processScroll();

			$(window).on('resize', processScrollInit);
			$(window).on('scroll', processScroll);

			function processScrollInit()
			{
				if ($('.subhead').length) {
					navTop = $('.subhead').length && $('.subhead').offset().top - <?php echo ($displayHeader || !$statusFixed) ? 30 : 20;?>;

					// Fix the container top
					$(".container-main").css("top", $('.subhead').height() + $('nav.navbar').height());

					// Only apply the scrollspy when the toolbar is not collapsed
					if (document.body.clientWidth > 480)
					{
						$('.subhead-collapse').height($('.subhead').height());
						$('.subhead').scrollspy({offset: {top: $('.subhead').offset().top - $('nav.navbar').height()}});
					}
				}
			}

			function processScroll()
			{
				if ($('.subhead').length) {
					var scrollTop = $(window).scrollTop();
					if (scrollTop >= navTop && !isFixed) {
						isFixed = true;
						$('.subhead').addClass('subhead-fixed');

						// Fix the container top
						$(".container-main").css("top", $('.subhead').height() + $('nav.navbar').height());
					} else if (scrollTop <= navTop && isFixed) {
						isFixed = false;
						$('.subhead').removeClass('subhead-fixed');
					}
				}
			}
		});
	</script>
<?php endif; ?>
</body>
</html>
