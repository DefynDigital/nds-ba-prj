<?php 
// No Direct Access
defined( '_JEXEC') or die;
?>


 
<?php 

	$org_title = $cck->getValue('art_title');
	$org_mail_address = $cck->getValue('directory_headoffice_address');
	$org_telephone = $cck->getValue('directory_telephone');
	$org_website = $cck->getValue('directory_website');

		//CREATING A LIST OF MAIN PRODUCTS 
	$csvField = $cck->getValue('product_and_services_main');
	$arr = explode(":", $csvField);
 
 	$main_product = '';
	foreach ($arr as $value) {
	   $main_product .= '<li class="mainproduct">' . $value . '</li>';
	}


?>	
						 
<div class="row listing-item">
		<div class="col-md-3 org-logo">
			<img src="<?php echo $cck->get('directory_organisation_logo')->value; ?>" alt="<?php echo $cck->getValue('art_title'); ?>"/>
		</div>
		<div class="col-md-9 org-details">
			
				<h3 class="org-title"><?php echo $cck->renderField('art_title'); ?></h3>
				<p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $org_mail_address; ?> 
				 <i class="fa fa-phone" aria-hidden="true"></i> <?php echo $org_telephone; ?> <br/>
				<i class="fa fa-globe" aria-hidden="true"></i> <a href="<?php echo $org_website ?>" target="_blank"><?php echo $org_website; ?></a></p>
 

		</div>
		


</div>
 
						
								
				 
  
 