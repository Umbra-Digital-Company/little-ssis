<?php
	
	// Set array
	$arrDailyStaff = array();

	$query = 	"SELECT
					dl.emp_id,
					CONCAT(CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),'|',et.designation,'|',IF(et.bank_name !='',et.bank_name,'N/A'),'|',IF(et.bank_number !='',et.bank_number,'N/A'))
					FROM daily_login dl LEFT JOIN emp_table et ON et.emp_id = dl.emp_id
					WHERE dl.store_code = '".$line['store_id']."'
					AND dl.daily_date = '".date('Y-m-d', strtotime($line['payment_date']))."';";

	$grabParams = array(
		'emp_id',
		'name'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrDailyStaff[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

?>