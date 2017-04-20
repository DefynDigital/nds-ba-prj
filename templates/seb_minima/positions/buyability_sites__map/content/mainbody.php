<?php 
// No Direct Access
defined( '_JEXEC') or die;
?>

 				

<?php

	$org_title = $cck->getValue('art_title');
	$org_overview = $cck->getValue('organisation_overview');
	$contact_name = $cck->getValue('contact_person');
	$contact_email = $cck->getValue('email');
	$org_head_office_address = $cck->getValue('head_office_address');
	$org_mail_address = $cck->getValue('mailing_address');
	$org_telephone = $cck->getValue('telephone');
	$contact_position = $cck->getValue('title_of_contact_person');
	$org_website = $cck->getValue('website');
	//$org_title = $cck->getValue('art_title');


 
	//CREATING A LIST OF MAIN PRODUCTS 
	$csvField = $cck->getValue('product_and_services_main');
	$arr = explode(":", $csvField);
 
 	$main_product = '';
	foreach ($arr as $value) {
	   $main_product .= '<li class="mainproduct">' . $value . '</li>';
	}

	//CREATING A LIST OF Vehicles 
 	$csvField_vehicle = $cck->getValue('product_and_services___vehicle_services');
	$prod_vehicles = explode(":", $csvField_vehicle);
 
 	$product_vehicles = '';
	foreach ($prod_vehicles as $value) {
	   $product_vehicles .= '<li class="mainproduct">' . $value . '</li>';
	}
	 

		//CREATING A LIST OF building products 
	$csvField_building = $cck->getValue('product_and_services__building_products');
	$prod_building = explode(":", $csvField_building);
 
 	$product_building = '';
	foreach ($prod_building as $value) {
	   $product_building .= '<li class="mainproduct">' . $value . '</li>';
	}

	//CREATING A LIST OF Design and Advertising 
	$csvField_design = $cck->getValue('product_and_services__design_and_advertising');
	$prod_design = explode(":", $csvField_design);
 
 	$product_design = '';
	foreach ($prod_design as $value) {
	   $product_design .= '<li class="mainproduct">' . $value . '</li>';
	}

		//CREATING A LIST OF Garden and Maintenance 
	$csvField_garden = $cck->getValue('product_and_services__garden_and_maintenance');
	$prod_garden = explode(":", $csvField_garden);
 
 	$product_garden = '';
	foreach ($prod_garden as $value) {
	   $product_garden .= '<li class="mainproduct">' . $value . '</li>';
	}

		//CREATING A LIST OF Mail and Packaging
	$csvField_mail = $cck->getValue('product_and_services__mail_and_packaging');
	$prod_mail = explode(":", $csvField_mail);
 
 	$product_mail = '';
	foreach ($prod_mail as $value) {
	   $product_mail .= '<li class="mainproduct">' . $value . '</li>';
	}

		//CREATING A LIST OF Services and Manufacturing
	$csvField_manufacturing = $cck->getValue('product_and_services__manufacturing');
	$prod_manufacturing = explode(":", $csvField_manufacturing);
 
 	$product_manufacturing = '';
	foreach ($prod_manufacturing as $value) {
	   $product_manufacturing .= '<li class="mainproduct">' . $value . '</li>';
	}

			//CREATING A LIST OF Print and Copy
	$csvField_print = $cck->getValue('product_and_services__print_and_copy');
	$prod_print = explode(":", $csvField_print);
 
 	$product_print = '';
	foreach ($prod_print as $value) {
	   $product_print .= '<li class="mainproduct">' . $value . '</li>';
	}
	

			//CREATING A LIST OF Retail and Sales
	$csvField_retail = $cck->getValue('product_and_services__retail_and_sales');
	$prod_retail = explode(":", $csvField_retail);
 
 	$product_retail = '';
	foreach ($prod_retail as $value) {
	   $product_retail .= '<li class="mainproduct">' . $value . '</li>';
	}

			//CREATING A LIST OF Waste and Recycling
	$csvField_waste = $cck->getValue('product_and_services__waste_and_recycling');
	$prod_waste = explode(":", $csvField_waste);
 
 	$product_waste = '';
	foreach ($prod_waste as $value) {
	   $product_waste .= '<li class="mainproduct">' . $value . '</li>';
	}

				//CREATING A LIST OF Administration
	$csvField_administration = $cck->getValue('products_and_services__administration');
	$prod_administer = explode(":", $csvField_administration);
 
 	$product_administer = '';
	foreach ($prod_administer as $value) {
	   $product_administer .= '<li class="mainproduct">' . $value . '</li>';
	}
	


	?>


	<div class="buyability-network-detail-page">
		<div class="row">

				<div class="organisation-title">

						<h2><?php echo $org_title; ?></h2> 
						</div>
		</div>
  		<div class="clearfix visible-xs-block"></div>

			
		<div class="row">

				<div class="products_and_logo">
				<div class="col-md-9 main-product-section">

				<div class="col-md-6 list-area">
				<h3>Main Products and Services </h3>

				<ul>
					<?php echo $main_product; ?>
				</ul>
				</div>

				<div class="col-md-6 list-area">

				<?php if (!empty($cck->get('product_and_services___vehicle_services')->value)):
					?>
					<h4>Vehicle services</h4>

					 	<ul>
							<?php echo $product_vehicles; ?>
						</ul>
						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__building_products')->value)):
					?>		
						<h4>Building products and services</h4>
		
						<ul>
							<?php echo $product_building; ?>
						</ul>
					<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__design_and_advertising')->value)):
					?>
					<h4>Design and advertising</h4>

					  	<ul>
							<?php echo $product_design; ?>
					  	</ul>
					  						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__garden_and_maintenance')->value)):
					?>
					<h4>Garden and maintenance</h4>

					  	<ul>
							<?php echo $product_garden; ?>
					  	</ul>
					  						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__mail_and_packaging')->value)):
					?>
					<h4>Mail and packaging</h4>

					  	<ul>
							<?php echo $product_mail; ?>
					  	</ul>
					  						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__manufacturing')->value)):
					?>
						<h4>Manufacturing</h4>

					  		<ul>
							<?php echo $product_manufacturing; ?>
					  	</ul>
					  						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__print_and_copy')->value)):
					?>
					 	<h4>Print and copy</h4>

					  		<ul>
							<?php echo $product_print; ?>
					  	</ul>
					  						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__retail_and_sales')->value)):
					?>
						<h4>Retail and sales</h4>

					  		<ul>
							<?php echo $product_retail; ?>
					  	</ul>
					  						<?php endif; ?>
				<?php if (!empty($cck->get('product_and_services__waste_and_recycling')->value)):
					?>
					<h4>Waste and recycling</h4>

					  		<ul>
							<?php echo $product_waste; ?>
					  	</ul>

			  						<?php endif; ?>
				<?php if (!empty($cck->get('products_and_services__administration')->value)):
					?>
					<h4>Administration</h4>

					  		<ul>
							<?php echo $product_administer; ?>
					  	</ul>
					  	<?php endif; ?>

					  	</div>

				</div>
				<div class="col-md-3">
					<div id="org_logo">
								<img src="<?php echo $cck->get('organisation_logo')->value; ?>" alt="<?php echo $org_title; ?>"/>
						</div>
				</div>

						 
						</div>
		</div>		 
					
					<div class="row">
						<div id="org_overview">
								<p>
								<?php echo $org_overview;?>
								</p>
						</div>
						 
						 <div class="org_information">
						 	<h2>Organisation Information</h2>
						 	<p>Head Office Address: <strong><?echo $org_head_office_address; ?></strong></p>
						 	<p>Mail Address: <strong><?echo $org_mail_address; ?></strong></p>
						 
						 	<p>Telephone: <strong><?echo $org_telephone; ?></strong></p>
						 	<p>Website: <strong><a href="<?echo $org_website; ?>" target="_blank"><?echo $org_website; ?></a></strong></p>
						 	 

						 	 
						 </div>
					</div>

						 
		 	<div class="row">
		 			<div class="contact_information">
		 				<div class="contact_title">Contact Information</div>
		 				<div class="contact_details">
		 						<p><strong> For any enquiries, please contact <?php echo $contact_name . ', ' . $contact_position . ', ' . $contact_email; ?></strong></p>

		 				</div>

		 			</div>





		 	</div>


</div>
  

  

  

   