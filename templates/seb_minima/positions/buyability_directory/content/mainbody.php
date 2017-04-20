<?php 
// No Direct Access
defined( '_JEXEC') or die;
?>

 				

<?php

	$org_title = $cck->getValue('art_title');
	$org_overview = $cck->getValue('directory_org_overview');
	$contact_name = $cck->getValue('directory_contact_name');
	$contact_email = $cck->getValue('directory_contact_email');
	$org_head_office_address = $cck->getValue('directory_headoffice_address');
 	$org_email =$cck->getValue('directory_email');
	$org_telephone = $cck->getValue('directory_telephone');
 	$org_website = $cck->getValue('directory_website');
 

  
?>


	<div class="buyability-network-detail-page">
		<div class="row">

				<div class="organisation-title">

						<h1><?php echo $org_title; ?></h1> 
						</div>
		</div>
  		<div class="clearfix visible-xs-block"></div>

 		 
					
					<div class="row">
						<div id="org_overview">
								<p>
								<?php echo $org_overview;?>
								</p>
						</div>
						 
						 <div class="org_information">
						 	<h2>Organisation Information</h2>
						 	<p>Head Office Address: <strong><?echo $org_head_office_address; ?></strong></p>
 						 
						 	<p>Telephone: <strong><?echo $org_telephone; ?></strong></p>
						 	<p>Email: <strong><?echo $org_email; ?></strong></p>

						 	<p>Website: <strong><a href="<?echo $org_website; ?>" target="_blank"><?echo $org_website; ?></a></strong></p>
						 	 

						 	 
						 </div>
					</div>

						 
		 	<div class="row">
		 			<div class="contact_information">
		 				<div class="contact_title">Contact Information</div>
		 				<div class="contact_details">
		 						<p><strong> For any enquiries, please contact <?php echo $contact_name . ', ' . $contact_email; ?></strong></p>

		 				</div>

		 			</div>





		 	</div>


</div>
  

  

  

   