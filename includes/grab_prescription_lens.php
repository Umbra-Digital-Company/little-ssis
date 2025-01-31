<?php 



 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	
	//////////////////////////				

$grabParams2 = array("vision_name","stock_rx","lens_code","sph_min","sph_max","cyl_min","cyl_max","add_min","add_max","special_condition","sc_sph_min","sc_sph_max","sc_cyl_min","sc_cyl_max");
$arrPrescription = array();


 $query = 	"SELECT
 				vision_name,
 				stock_rx,
 				lens_code,
 				sph_min,
 				sph_max,
 				cyl_min,
 				cyl_max,
 				add_min,
 				add_max,
 				special_condition,
 				sc_sph_min,
 				sc_sph_max,
 				sc_cyl_min,
 				sc_cyl_max
 				FROM 
 					prescription_lens
 					ORDER BY
 						vision_name, stock_rx, lens_code ASC;
 				";
				
			

$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $query)) {

    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14);

    while (mysqli_stmt_fetch($stmt2)) {

        $tempArray2 = array();

        for ($i=0; $i < 24; $i++) { 

            $tempArray2[$grabParams2[$i]] = ${'result' . ($i+1)};

        };

        $arrPrescription[] = $tempArray2;

    };

    mysqli_stmt_close($stmt2);    
                            
}
else {

    echo mysqli_error($conn);

}; 


?>