<?php 
// No Direct Access
defined( '_JEXEC') or die;
?>


<div style="clear:both;"></div>
								
						
	
							<div class="each-employee">
								<h1><?php echo $cck->renderField('art_title'); ?></h1>
								<div class="full-image pull-right">
								<img src="<?php echo $cck->get('art_image_intro')->value; ?>" alt="<?php echo $cck->getValue('art_title'); ?>"/>
								</div>
								
								<div class="intro-text"><?php echo $cck->renderField('art_fulltext'); ?></div>

							</div>

						  <div class="clearfix visible-xs-block"></div>
						 
					 
