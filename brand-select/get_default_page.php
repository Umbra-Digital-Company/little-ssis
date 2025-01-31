<?php
	function grabDefaultPages($username) {

	    global $conn;
	    $arrDefaultPage = array();
	    $query =    "SELECT ua.default_page,
	                    ua.default_page_studios,
	                    ua.default_page_face,
	                    ua.default_page_flask,
	                    ua.default_page_general,
	                    (SELECT page_link FROM sunnies_pages WHERE page = ua.default_page LIMIT 1) as specs_link,
	                    (SELECT page_link FROM sunnies_pages WHERE page = ua.default_page_studios LIMIT 1) as studios_link,
	                    (SELECT page_link FROM sunnies_pages WHERE page = ua.default_page_face LIMIT 1) as face_link,
	                    (SELECT page_link FROM sunnies_pages WHERE page = ua.default_page_flask LIMIT 1) as flask_link,
	                    (SELECT page_link FROM sunnies_pages WHERE page = ua.default_page_general LIMIT 1) as general_link
	                FROM user_access_v2 ua WHERE ua.username = '".$username."';";

	    $grabParams = array(
	        'specs',
	        'studios',
	        'face',
	        'flask',
	        'general',
	        'specs_link',
	        'studios_link',
	        'face_link',
	        'flask_link',
	        'general_link'
	    );
	                    
	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);
	        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

	        while (mysqli_stmt_fetch($stmt)) {

	            $tempArray = array();

	            for ($i=0; $i < sizeOf($grabParams); $i++) { 

	                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	            };
	            $arrDefaultPage[] = $tempArray;

	        };

	        mysqli_stmt_close($stmt);    

	    }
	    else {

	        showMe(mysqli_error($conn));

	    };

	    return $arrDefaultPage[0];

	};
?>