<?php 

if(!isset($_SESSION)) {

  session_start();

};

$arrCustomerP = array();

$grabParams = array(
		"prescription_id", 		
		"prescription_updated",
		"prescription_date", 
		"prescription_type", 
		"prescription_name",
		"sph_od",
		"cyl_od",
		"axis_od",
		"add_od",
		"ipd_od",
		"ph_od",
		"va_od",
		"sph_os",
		"cyl_os",
		"axis_os",
		"add_os",
		"ipd_os",
		"ph_os",
		"va_os",
		"pres_id",
		"doctors_remarks",
		"prescription_name",
		"sph_od_old",
		"cyl_od_old",
		"axis_od_old",
		"add_od_old",
		"sph_os_old",
		"cyl_os_old",
		"axis_os_old",
		"add_os_old",
		"sph_od_full",
		"cyl_od_full",
		"axis_od_full",
		"add_od_full",
		"sph_os_full",
		"cyl_os_full",
		"axis_os_full",
		"add_os_full"
);

 $query = 	"SELECT
				pp.id,				
				DATE_FORMAT(pp.date_updated, '%m/%d/%Y'),
				DATE_FORMAT(pp.date_created, '%m/%d/%Y'),
				pp.prescription_type,
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
				pp.prescription_id,
				pp.doctors_remarks,
				pp.prescription_name,
				pfp.sph_od,
				pfp.cyl_od,
				pfp.axis_od,
				pfp.add_od,
				pfp.sph_os,
				pfp.cyl_os,
				pfp.axis_os,
				pfp.add_os,
				pop.sph_od,
				pop.cyl_od,
				pop.axis_od,
				pop.add_od,
				pop.sph_os,
				pop.cyl_os,
				pop.axis_os,
				pop.add_os
			FROM  
				profiles_prescription pp
					LEFT JOIN profile_full_prescription pfp
						ON pfp.prescription_id = pp.prescription_id
					LEFT JOIN profile_old_prescription pop
						ON pop.prescription_id = pp.prescription_id
			WHERE 
				pp.profile_id = '".$_GET['profile_id']."'							
			ORDER BY
				pp.date_updated DESC;";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34, $result35, $result36, $result37, $result38);

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParams); $i++) { 

      $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

    };

    $arrCustomerP[] = $tempArray;

  };

  mysqli_stmt_close($stmt);    
                            
}
else {

  echo mysqli_error($conn);
  exit;

}; 

?>