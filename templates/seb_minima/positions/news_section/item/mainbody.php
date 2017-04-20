<?php 
// No Direct Access
defined( '_JEXEC') or die;
?>


<div style="clear:both;"></div>
								
						
						 
				 
					
					<div class="col-md-6">
						<div id="intro-new-image">
								<img src="<?php echo $cck->get('art_image_intro')->value; ?>" alt="<?php echo $cck->getValue('art_image_intro_alt'); ?>"/>
						</div>
						<h3><?php echo $cck->renderField('art_title'); ?></h3>
						<div class="news-item-description">
						<?php echo $cck->renderField('art_introtext'); ?>
						</div>
					</div>

						
								
				 
  