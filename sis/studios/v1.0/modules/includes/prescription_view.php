<?php 
include("../connect.php");
$arrCustomerV = array();

$grabParams = array("prescription_id", "prescription_date", "prescription_name", "sph_od","cyl_od","axis_od","add_od","ipd_od","ph_od","va_od","sph_os","cyl_os","axis_os","add_os","ipd_os","ph_os","va_os","last_name","first_name","middle_name","email_address","province","city","barangay","birthday","age","gender","phone_number","date_created","store_name","profile_id", "address", "lab_name","pickup" );

$query = 	"SELECT
				pp.id,
				DATE_FORMAT(pp.date_created, '%m/%d/%Y'),
				pp.prescription_name,
				pp.sph_od,
				pp.cyl_od,
				pp.axis_od,
				pp.add_od,
				pp.ipd_od,
				pp.ph_od,
				pp.va_od,
				pp.sph_os,
				pp.cyl_os,
				pp.axis_os,
				pp.add_os,
				pp.ipd_os,
				pp.ph_os,
				pp.va_os,
				pi.last_name,
				pi.first_name,
				pi.middle_name,
				pi.email_address,
				pi.province,
				pi.city,
				pi.barangay,
				pi.birthday,
				pi.age,
				pi.gender,
				pi.phone_number,
				pi.date_created,
				sc.store_name,
				pi.profile_id,
				pi.address,
        		lab_name,
		(SELECT store_name from stores_locations sl where sl.store_id=o.store_id) as pickup
       
			FROM  profiles_info pi

					LEFT JOIN   profiles_prescription pp
						ON pp.profile_id = pi.profile_id
					LEFT JOIN  stores_locations sc on sc.store_id=pi.branch_applied
lEFT JOIN labs_locations l on l.lab_id=sc.lab_id
LEFT JOIN orders o on o.profile_id=pi.profile_id

				
					
			WHERE 
				pp.id='".$_GET['presid']."'
				
			ORDER BY
				pp.date_created DESC;";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerV[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>


<div class="prescription-table">
				<table cellpadding="0" cellspacing="0" class="list-table">
					
					<tbody class="list-table-body">
						
			
							<tr class="prescription-holder2" data-row="<?= $_GET['presid'] ?>" >
								<td colspan="4" style="padding: 0; border: 0">
									<table cellpadding="0" cellspacing="0" class="list-table">
										<thead>
											<tr>
												<td align="center"><span class="small font-weight-bold">FINAL RX</span></td>
												<td align="center"><span class="small font-weight-bold">SPH</span></td>
												<td align="center"><span class="small font-weight-bold">CYL</span></td>
												<td align="center"><span class="small font-weight-bold">AXIS</span></td>
												<td align="center"><span class="small font-weight-bold">ADD</span></td>
												<td align="center"><span class="small font-weight-bold">IPD</span></td>
												<td align="center"><span class="small font-weight-bold">PH</span></td>
												<td align="center"><span class="small font-weight-bold">VA W/ RX</span></td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td align="center">OD</td>
												<td align="center">
													
													<?php
													if($arrCustomerV[0]['sph_od']=='0'){ echo "plano"; }
													elseif($arrCustomerV[0]['sph_od']==''){
									
														}else{
														echo	number_format($arrCustomerV[0]['sph_od'], 2, '.', ',');
												} ?>
												</td>
												<td align="center"><?php
									if($arrCustomerV[0]['cyl_od']=='0'){
										echo "plano";
									}elseif($arrCustomerV[0]['cyl_od']==''){ 
									}
									else{
										echo	number_format($arrCustomerV[0]['cyl_od'], 2, '.', ','); 
											}		
													?></td>
												<td align="center"><?= $arrCustomerV[0]['axis_od']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['add_od']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['ipd_od']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['ph_od']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['va_od']; ?></td>
											</tr>
											<tr>
												<td align="center">OS</td>
												<td align="center"><?php
													if($arrCustomerV[0]['sph_os']=='0'){ echo "plano"; }
													elseif($arrCustomerV[0]['sph_os']==''){}
													else{
													echo number_format($arrCustomerV[0]['sph_os'], 2, '.', ',');
													}?></td>
												<td align="center"><?php
													if($arrCustomerV[0]['cyl_os']=='0'){
														echo "plano";
													}elseif($arrCustomerV[0]['cyl_os']==''){}
													else{
													echo number_format($arrCustomerV[0]['cyl_os'], 2, '.', ','); 
													}?></td>
												<td align="center"><?= $arrCustomerV[0]['axis_os']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['add_os']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['ipd_os']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['ph_os']; ?></td>
												<td align="center"><?= $arrCustomerV[0]['va_os']; ?></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

						
					</tbody>
				</table>
			
</div>