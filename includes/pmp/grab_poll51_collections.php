<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB FRAMES

// Set array
$arrCollection = array();

$query = 	"SELECT 
				code,
				LOWER(name)
			FROM 
				poll_51_collections
			WHERE
				brand = 'optical'
			ORDER BY 
				name ASC";

$grabParams = array(
	"code",
    "name"
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

		$arrCollection[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    

}
else {

	showMe(mysqli_error($conn));

};

// Set array
$arrCollectionGeneralColor = array();

$query = 	"SELECT 
				code,
				LOWER(name)
			FROM 
				poll_51_general_colors
			ORDER BY 
				name ASC";

$grabParams = array(
	"code",
    "name"
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

		$arrCollectionGeneralColor[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    

}
else {

	showMe(mysqli_error($conn));

};

// Set array
$arrMaterials = array();

$query = 	"SELECT 
				code,
				LOWER(name)
			FROM 
				poll_51_materials
			ORDER BY 
				name ASC";

$grabParams = array(
	"code",
    "name"
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

		$arrMaterials[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    

}
else {

	showMe(mysqli_error($conn));

};
?>