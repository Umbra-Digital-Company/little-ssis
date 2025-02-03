<?php

include("./modules/includes/grab_order_details.php");
include("./modules/includes/grab_dispatch_details.php");
include("./modules/includes/grab_history.php");
include("./modules/includes/grab_profile.php");

?>

<div class="overview-details">

	<div id="personal-content" class="details-content active">
		<p class="text-uppercase font-bold text-primary">personal information</p>
		<div class="personal-card mt-3">
			<div class="d-flex align-items-center justify-content-between no-gutters">
				<?php $gender_icon = ( $arrCustomerProfile[0]["gender"] == 'male' ) ? 'male' : 'female' ?>
				<div class="col">
					<div class="d-flex align-items-center">
						<canvas class="gender-icon" style="background-image: url(<?= get_url('images/icons') ?>/icon-gender-<?= $gender_icon ?>.png" alt="<?= $gender_icon ?>)"></canvas>
						<section>
							<p class="font-bold"><?= ucwords($arrCustomerProfile[0]["first_name"] . ' ' . $arrCustomerProfile[0]["middle_name"] . ' ' . $arrCustomerProfile[0]["last_name"]) ?></p>
							<p><?= ( $arrCustomerProfile[0]["occupation"] != NULL ) ?ucwords($arrCustomerProfile[0]["occupation"]) : 'No Occupation' ?></p>
						</section>
					</div>
				</div>
				<!-- <a href="#edit-personal" id="edit-personal"><img src="<?= get_url('images/icons') ?>/icon-edit-theme-doctor.png" alt="edit" class="img-fluid"></a> -->
			</div>
			<hr>
			<div class="d-flex align-items-center details-list">
				<span><img src="<?= get_url('images/icons') ?>/icon-birthdate.png" alt="birthdate" class="img-fluid"></span>
				<p><?= cvdate(2, $arrCustomerProfile[0]["birthday"]) ?> - <?= $arrCustomerProfile[0]['age'] ?> Years Old</p>
			</div>
			<br/>
			<div class="d-flex align-items-center details-list">
				<span><img src="<?= get_url('images/icons') ?>/icon-address.png" alt="address" class="img-fluid"></span>
				<p><?= ucwords($arrCustomerProfile[0]["address"] .', '. str_replace('-', ' ', $arrCustomerProfile[0]["barangay"]) .', '. str_replace('-', ' ', $arrCustomerProfile[0]["city"]) .', '. str_replace('-', ' ', $arrCustomerProfile[0]["province"])) ?></p>
			</div>
			<br/>
			<div class="d-flex align-items-center details-list">
				<span><img src="<?= get_url('images/icons') ?>/icon-phone.png" alt="phone number" class="img-fluid"></span>
				<p><?= str_replace('63', '+63 ', str_replace('-', ' ', $arrCustomerProfile[0]["phone_number"])) ?></p>
			</div>
			<br/>
			<div class="d-flex align-items-center details-list">
				<span><img src="<?= get_url('images/icons') ?>/icon-email.png" alt="email" class="img-fluid"></span>
				<p><?= $arrCustomerProfile[0]["email_address"] ?></p>
			</div>
		</div>
		<p class="text-uppercase font-bold text-primary mt-4">joining date</p>
		<div class="personal-card mt-3">
			<div class="d-flex align-items-center justify-content-between no-gutters">
				<div class="col-6">
					<div class="d-flex align-items-center details-list">
						<span><img src="<?= get_url('images/icons') ?>/icon-date.png" alt="date" class="img-fluid"></span>
						<p><?= cvdate(2,$arrCustomerProfile[0]["date_created"]) ?></p>
					</div>
				</div>
				<div class="col-6">
					<div class="d-flex align-items-center details-list">
						<span><img src="<?= get_url('images/icons') ?>/icon-store.png" alt="store" class="img-fluid"></span>
						<p><?= str_replace("Up Town Center", "UP Town Center", str_replace("Sm ", "SM ", str_replace("Mw ", "MW ", str_replace("Ali ", "ALI ", ucwords( strtolower($arrCustomerProfile[0]['branch']) ))))) ?></p>
					</div>
				</div>
				
			</div>
		</div>
		<?php if( $arrCustomerDetail[0]["status"]!='return' && $arrCustomerDetail[0]["status"]!='cancelled' ){ ?>
		<!-- <div class="text-center mt-5">
			<button id="re_sendPOS" class="btn btn-primary editO"  data-id="<?= $_GET['orderspecsid'] ?>">Send To Cashier</button>
			<br/><br/>
		
		</div> -->
		<?php } ?>
	</div>

	<div id="edit-personal-content" class="details-content">
		<p class="text-uppercase font-bold text-primary">edit personal information</p>
		<form method="POST" action="modules/process/edit-overview-details.php" class="mt-3">
			<input type="hidden" name="pagefrom" value="<?=$_GET['page']?>">
			<div class="no-gutters form-row">
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="p_fname" data-old="<?= ucwords( $arrCustomerProfile[0]["first_name"] ) ?>" class="form-control" id="p_fname" value="<?= ucwords( $arrCustomerProfile[0]["first_name"] ) ?>" required>
						<label class="placeholder" for="p_fname">First name</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="p_mname" data-old="<?= ucwords( $arrCustomerProfile[0]["middle_name"] ); ?>" class="form-control" id="p_mname" value="<?= ucwords( $arrCustomerProfile[0]["middle_name"] ); ?>" required>
						<label class="placeholder" for="p_mname">Middle name</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="p_lname" data-old="<?= ucwords( $arrCustomerProfile[0]["last_name"] ) ?>" class="form-control" id="p_lname" value="<?= ucwords( $arrCustomerProfile[0]["last_name"] ); ?>" required>
						<label class="placeholder" for="p_lname">Last name</label>
					</div>
				</div>
				<div class="col-12">
					<div class="form-group">
						<input type="text" name="P_home_address" data-old="<?= ucwords( $arrCustomerProfile[0]["address"] ) ?>" class="form-control" id="P_home_address" value="<?= ucwords( $arrCustomerProfile[0]["address"] ); ?>" required>
						<label class="placeholder" for="P_home_address">Home address</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="p_email" data-old="<?= $arrCustomerProfile[0]["email_address"] ?>" class="form-control" id="p_email" value="<?= $arrCustomerProfile[0]["email_address"] ?>" required>
						<label class="placeholder" for="p_email">Email address</label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="mnum" data-old="<?= str_replace('63', '', $arrCustomerProfile[0]["phone_number"] ) ?>" class="form-control mobile-number <?= ($arrCustomerProfile[0]['phone_number'] != '') ? 'active' : '' ?>" id="mnum" value="<?= str_replace('63', '', $arrCustomerProfile[0]["phone_number"] ) ?>" required>
						<label class="placeholder" for="mnum">Mobile number</label>
						<span class="mobile-format  <?= ($arrCustomerProfile[0]['phone_number'] == '') ? 'hide' : '' ?>">+63</span>
					</div>
				</div>
			</div>
			<div class="text-center mt-5">
				<input type="hidden" name="edit_profile_id" value="<?= $_GET['profile_id'] ?>">
				<input type="hidden" name="edit_order_specs_id" value="<?= $_GET['orderspecsid'] ?>">
				<input type="hidden" name="edit_orderno" value="<?= $_GET['orderNo'] ?>" >
				<button type="submit" class="btn btn-primary">update</button>
				<br/><br/>
				<a href="#personal" id="cancel-edit" class="text-secondary">Cancel</a>
			</div>
		</form>
	</div>

	<div id="prescription-content" class="details-content">
		<p class="text-uppercase font-bold text-primary">prescription details</p>
		
			<?php if ( empty($arrCustomerP) ) : ?>
				
				<div class="prescription-card mt-3">
					<div class="text-center">
						<p class="text-danger font-bold h2">No Prescription Found</p>
					</div>
				</div>
			
			<?php else : 

				// Current date
				$curDate = date("m/d/Y");
				if ( $curDate - $arrCustomerPrescription[0]['prescription_date'] < 7 ) {
					$expirationClass = 'text-success-dark';
				} else {
					$expirationClass = 'text-danger-dark';
				}

			?>

<div class="d-flex align-items-center justify-content-between mt-3">
					<p class="font-bold text-primary text-uppercase small">old rx</p>
				</div>

				<div class="prescription-card mt-2">
					<div class="d-flex no-gutters justify-content-between">
						<div class="text-center">
							<p class="font-bold text-uppercase">&nbsp;</p>
							<p class="font-bold text-uppercase">od</p>
							<p class="font-bold text-uppercase">os</p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">sph</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_sph_od"] == "" || $arrCustomerPrescription[0]["op_sph_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_sph_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_sph_os"] == "" || $arrCustomerPrescription[0]["op_sph_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_sph_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">cyl</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_cyl_od"] == "" || $arrCustomerPrescription[0]["op_cyl_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_cyl_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_cyl_os"] == "" || $arrCustomerPrescription[0]["op_cyl_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_cyl_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">axis</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_axis_od"] == "" || $arrCustomerPrescription[0]["op_axis_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_axis_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_axis_os"] == "" || $arrCustomerPrescription[0]["op_axis_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_axis_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">add</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_add_od"] == "" || $arrCustomerPrescription[0]["op_add_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_add_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["op_add_os"] == "" || $arrCustomerPrescription[0]["op_add_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["op_add_os"]; }; ?></p>
						</div>
					</div>
				</div>

				<div class="d-flex align-items-center justify-content-between mt-3">
					<p class="font-bold text-primary text-uppercase small">full rx</p>
				</div>

				<div class="prescription-card mt-2">
					<div class="d-flex no-gutters justify-content-between">
						<div class="text-center">
							<p class="font-bold text-uppercase">&nbsp;</p>
							<p class="font-bold text-uppercase">od</p>
							<p class="font-bold text-uppercase">os</p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">sph</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_sph_od"] == "" || $arrCustomerPrescription[0]["fp_sph_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_sph_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_sph_os"] == "" || $arrCustomerPrescription[0]["fp_sph_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_sph_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">cyl</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_cyl_od"] == "" || $arrCustomerPrescription[0]["fp_cyl_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_cyl_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_cyl_os"] == "" || $arrCustomerPrescription[0]["fp_cyl_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_cyl_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">axis</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_axis_od"] == "" || $arrCustomerPrescription[0]["fp_axis_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_axis_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_axis_os"] == "" || $arrCustomerPrescription[0]["fp_axis_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_axis_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">add</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_add_od"] == "" || $arrCustomerPrescription[0]["fp_add_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_add_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["fp_add_os"] == "" || $arrCustomerPrescription[0]["fp_add_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["fp_add_os"]; }; ?></p>
						</div>
					</div>
				</div>

				<div class="d-flex align-items-center justify-content-between mt-3">
					<p class="font-bold text-primary text-uppercase small">final rx</p>
					<p>
						<span class="small">Refraction Date:</span>
						<span class="small <?= $expirationClass ?>"><?= cvdate(2, $arrCustomerPrescription[0]['prescription_date']) ?></span>
					</p>
				</div>

				<div class="prescription-card mt-2">
					<div class="d-flex no-gutters justify-content-between">
						<div class="text-center">
							<p class="font-bold text-uppercase">&nbsp;</p>
							<p class="font-bold text-uppercase">od</p>
							<p class="font-bold text-uppercase">os</p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">sph</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["sph_od"] == "" || $arrCustomerPrescription[0]["sph_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["sph_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["sph_os"] == "" || $arrCustomerPrescription[0]["sph_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["sph_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">cyl</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["cyl_od"] == "" || $arrCustomerPrescription[0]["cyl_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["cyl_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["cyl_os"] == "" || $arrCustomerPrescription[0]["cyl_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["cyl_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">axis</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["axis_od"] == "" || $arrCustomerPrescription[0]["axis_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["axis_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["axis_os"] == "" || $arrCustomerPrescription[0]["axis_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["axis_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">add</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["add_od"] == "" || $arrCustomerPrescription[0]["add_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["add_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["add_os"] == "" || $arrCustomerPrescription[0]["add_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["add_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">ipd</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["ipd_od"] == "" || $arrCustomerPrescription[0]["ipd_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["ipd_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["ipd_os"] == "" || $arrCustomerPrescription[0]["ipd_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["ipd_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">ph</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["ph_od"] == "" || $arrCustomerPrescription[0]["ph_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["ph_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["ph_os"] == "" || $arrCustomerPrescription[0]["ph_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["ph_os"]; }; ?></p>
						</div>
						<div class="text-center">
							<p class="font-bold text-uppercase">va</p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["va_od"] == "" || $arrCustomerPrescription[0]["va_od"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["va_od"]; }; ?></p>
							<p class="text-uppercase"><?php if($arrCustomerPrescription[0]["va_os"] == "" || $arrCustomerPrescription[0]["va_os"] == NULL) { echo "&nbsp;"; } else { echo $arrCustomerPrescription[0]["va_os"]; }; ?></p>
						</div>
					</div>
				</div>

				<?php if ( !empty($arrCustomerPrescription[0]["doctors_remarks"]) ) : ?>

					<p class="text-uppercase text-primary font-bold mt-4">doctor's remark</p>
					<div class="prescription-card mt-3">
						<span><?= $arrCustomerPrescription[0]["doctors_remarks"] ?></span>
					</div>

					<?php endif ?>

			<?php endif; ?>
		
	</div>

	<div id="order-content" class="details-content">
		<p class="text-uppercase font-bold text-primary">order details</p>
		<div class="order-list mt-3">

		
		<?php for ( $p = 0; $p < sizeof($arrCustomerPrescription); $p++ ) :
			
			if ( file_exists('./images/specs/'.$arrCustomerPrescription[$p]['item_description'].'/'.str_replace(" ", "-", trim($arrCustomerPrescription[$p]['color'])).'/front.png') ) {
				$img_url = './images/specs/'.$arrCustomerPrescription[$p]['item_description'].'/'.str_replace(" ", "-", trim($arrCustomerPrescription[$p]['color'])).'/front.png';
				//$img_url = './images/specs/'.$arrCustomerPrescription[$p]['product_code'].'.png';
			} else {
				$img_url = './images/specs/no-image/no_specs_frame_available_b.png';
			}

			if ( $arrCustomerPrescription[$p]['lens_option'] == 'lens only' ) {
				$img_url = './assets/images/icons/icon-lens-only.png';
				$titleName = "Lens Only";
			} else {

				if ( file_exists('./images/specs/'.$arrCustomerPrescription[$p]['item_description'].'/'.str_replace(" ", "-", trim($arrCustomerPrescription[$p]['color'])).'/front.png') ) {
					$img_url = './images/specs/'.$arrCustomerPrescription[$p]['item_description'].'/'.str_replace(" ", "-", trim($arrCustomerPrescription[$p]['color'])).'/front.png';
				} else {
					$img_url = './images/specs/no-image/no_specs_frame_available_b.png';
				}
			}

		?>

			<div class="list-item d-flex no-gutters align-items-center justify-content-between">
				<div class="col">
					<div class="d-flex align-items-center">
						
						<?php if ( $arrCustomerPrescription[$p]['lens_option'] != 'service' ) { ?>
							
							<img src="<?= $img_url ?>" alt="<?= $arrCustomerPrescription[$p]['item_description'] ?>" class="img-fluid">

						<?php } else {

							if ( sizeof($arrCustomerPrescription) > 1 ) { ?>
						
								<span style="display: block; width: 100px;"></span>

							<?php }
						
						} ?>

						<section>

							<?php if ( $arrCustomerPrescription[$p]["lens_option"] != 'service' ) { ?>
								<?php 		if($arrCustomerPrescription[$p]['item_description']!=''){ ?>
								<p class="font-bold"><?= ucwords( $arrCustomerPrescription[$p]['item_description'] )." ". ucwords( $arrCustomerPrescription[$p]['color'] ) ?></p>
							<?php }else{ ?>

								<p class="font-bold"><?= ucwords(	$arrCustomerPrescription[$p]['product_code'])?>- SKU no longer accepted by POS</p>
						<?php 	} ?>
							<?php } else { 

								$service_name = "";

								switch ( $arrCustomerPrescription[$p]['product_upgrade']) {
									case 'SR001' : $service_name = 'Plastic Lamination'; break;
									case 'SR002' : $service_name = 'Drilling Screw'; break;
									case 'SR003' : $service_name = 'Soldering'; break;
									case 'SR004' : $service_name = 'Polish Edge'; break;
								};

							?>
							
								<p class="font-bold"><?= ucwords($service_name) ?></p>
							
							<?php } ?>

							<?php $tagline = ( !empty($arrCustomerPrescription[$p]['product_upgrade'] ) ) ? ucwords(str_replace( '_', ' ', $arrCustomerPrescription[$p]['product_upgrade'] )) : $tagline = 'Frame only'; ?>
							
							<span class="text-secondary"><?= ( $arrCustomerPrescription[$p]['lens_option'] != 'service' ) ? ucwords($tagline) : ucwords($arrCustomerPrescription[$p]['lens_option']) ?></span>
							
						</section>
					</div>
				</div>

				<?php if($arrCustomerPrescription[$p]["lab_print"]=='n') : ?>

					<section class="text-right">
						<span class="text-primary font-bold format-price d-block"><?= $arrCustomerPrescription[$p]['price'] ?></span>
					</section>

				<?php else : ?>

					<span class="text-primary font-bold format-price d-block"><?= $arrCustomerPrescription[$p]['price'] ?></span>

				<?php endif ?>
			</div>

			<?php endfor ?>
			
			<p class="text-uppercase font-bold text-primary mt-4">pickup location</p>

			<div class="form-group mb-0 store-location mt-3">
				<div class="d-flex align-items-center justify-content-between list-item button">
					<section>
						<p class="font-bold store-name"><?= ucwords(strtolower($arrCustomerProfile[0]["pickup"])) ?></p>
						<p class="store-address small">Specs Branch</p>
					</section>
					<img src="<?= get_url('images/icons') ?>/icon-store-theme-doctor.png" alt="store" class="img-fluid m-0">
				</div>
			</div>

			<?php if ( $arrCustomerDetail[0]["signature"] != "" ) { ?>

				<p class="text-uppercase font-bold text-primary mt-4">signature</p>

				<div class="custom-card text-center mt-3">
					<img class="img-fluid signature" src="<?= $arrCustomerDetail[0]["signature"];?>">
				</div>

			<?php } ?>

			<?php if($arrCustomerDetail[0]["status"]!='cancelled' && $arrCustomerDetail[0]["status"]!="return"){ 
				?>
				<!-- <div class="text-center mt-5">
					<button id="re_order" class="btn btn-primary re_order"  data-id="<?= $_GET['orderspecsid'] ?>">Re-order</button>
				</div> -->
			<?php } ?>

		</div>
	</div>

	<div class="details-navigation">
		<div class="d-flex align-items-center">
			<a href="./?page=doctor-complete" class="col text-center logout"><canvas class="d-block nav-1"></canvas></a>
			<a href="#personal" class="col text-center"><canvas class="d-block nav-2 doctor active"></canvas></a>
			<a href="#prescription" class="col text-center"><canvas class="d-block doctor nav-3"></canvas></a>
			<a href="#order" class="col text-center"><canvas class="d-block doctor nav-4"></canvas></a>
		</div>
	</div>

</div>