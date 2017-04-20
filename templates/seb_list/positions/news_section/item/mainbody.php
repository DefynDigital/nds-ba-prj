<?php 
// No Direct Access
defined( '_JEXEC') or die;
?>


<div style="clear:both;"></div>
								
						
						 
							<h2><?php echo $cck->getValue('art_title'); ?></h2>
						<div class="row">
						
							<div class="col-md-4">
								
								<div id="intro-image">
								<img src="<?php echo $cck->get('art_image_intro')->value; ?>" alt="<?php echo $cck->getValue('art_image_intro_alt'); ?>"/>
								</div>


							</div>
							<div class="col-md-8">
								<div> 	<i class="fa fa-quote-left" aria-hidden="true"></i>
										<?php echo $cck->getValue('art_fulltext'); ?>
										<span class="quote-right"><i class="fa fa-quote-right" aria-hidden="true"></i></span>

								</div>
							
							</div>
						  <div class="clearfix visible-xs-block"></div>
						</div>
					 


						
								
				 
  