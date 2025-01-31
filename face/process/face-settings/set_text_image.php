<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";

	if(!isset($_SESSION['user_login']['id'])){
		echo '<script type="text/javascript"> window.location = "/"; </script>';
		exit;
	}

	function compressImage($source, $destination, $quality) {

      $info = getimagesize($source);
    
      if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);
    
      elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);
    
      elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);
    
      imagejpeg($image, $destination, $quality);
    
    }

    function insertData($image_1, $image_2, $video, $text){
    	global $conn;

    	 $arrTextImages = array();

	    $queryAll = "SELECT
	                    text,
	                    image_1,
	                    image_1_status,
	                    image_2,
	                    image_2_status,
	                    video,
	                    video_status
	                    FROM face_text_images_settings
	                    WHERE active = 1;
	                    ";

	    $grabParams = array(
	        'text',
	        'image_1',
	        'image_1_status',
	        'image_2',
	        'image_2_status',
	        'video',
	        'video_status'
	    );

	    $query = $queryAll;

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {
	        
	        mysqli_stmt_execute($stmt);
	        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

	        while (mysqli_stmt_fetch($stmt)) {

	            $tempArray = array();

	            for ($i=0; $i < sizeOf($grabParams); $i++) { 

	                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	            };

	            $arrTextImages[] = $tempArray;

	        };

	        mysqli_stmt_close($stmt);    
	                                
	    }
	    else {

	        echo mysqli_error($conn);

	    };
    	// print_r($arrTextImages); exit;
    	$query = 	'UPDATE face_text_images_settings SET active = 0 WHERE active = 1';

    	$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);

		}
		else {

			echo mysqli_error($conn);
			exit;

		};

    	$query = 	'INSERT IGNORE INTO
						face_text_images_settings (
							text,
							image_1,
							image_1_status,
							image_2,
							image_2_status,
							video,
							video_status,
							active,
							created_by
						)
					VALUES (
						"'.mysqli_real_escape_string($conn,$text).'",
						"'.$image_1.'",
						"'.$arrTextImages[0]['image_1_status'].'",
						"'.$image_2.'",
						"'.$arrTextImages[0]['image_2_status'].'",
						"'.$video.'",
						"'.$arrTextImages[0]['video_status'].'",
						"1",
						"'.$_SESSION['user_login']['id'].'"
					);';
					// echo $query; exit;
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);

		}
		else {

			echo mysqli_error($conn);
			exit;

		};

		echo '<script type="text/javascript"> alert("Text/Images/Video successfully set"); window.location = "/face/face-settings"; </script>';
    }
    $filename_video = $_FILES['filename_video']['name'];
	$ext_video = pathinfo($filename_video, PATHINFO_EXTENSION);

	$video_file = '';
	$allowed = array('mp4','mkv', 'MP4','MKV');
	if( $filename_video != '' && !in_array( $ext_video, $allowed )) {
		echo '<script type="text/javascript"> alert("Invalid extension to be upload"); window.location = "/face/face-settings"; </script>';
	}else{
	
		$directory = $_SERVER['DOCUMENT_ROOT']."/face/face-settings/videos/";

		if($filename_video != ''){	
			$fileName = pathinfo($_FILES['filename_video']['name']);
			$fileName = 'video_1_'.date("YmdHis").".".$fileName['extension'];
			$video_file = $fileName;
			$path = $directory."/".$fileName;
			// $file_convert = 0;
			// ($_FILES['filename_1']['size'] >= 7120000 && $_FILES['filename_1']['size'] <= 10120000) ? $file_convert = 50 : '';
			// ($_FILES['filename_1']['size'] >= 4120000 && $_FILES['filename_1']['size'] < 7120000) ? $file_convert = 70 : '';
			// ($_FILES['filename_1']['size'] >= 2120000 && $_FILES['filename_1']['size'] < 4120000) ? $file_convert = 80 : '';
			// ($_FILES['filename_1']['size'] >= 1120000 && $_FILES['filename_1']['size'] < 2120000) ? $file_convert = 90 : '';

			// if($file_convert != 0){
			// 	compressImage($_FILES['filename_1']['tmp_name'],$path,$file_convert);
			// }
			// elseif(move_uploaded_file($_FILES['filename_1']['tmp_name'],$path)){}
			move_uploaded_file($_FILES['filename_video']['tmp_name'],$path);
		}else{
			$video_file = $_POST['retain_video'];
		}
	}



	$filename_1 = $_FILES['filename_1']['name'];
	$ext_1 = pathinfo($filename_1, PATHINFO_EXTENSION);
	$filename_2 = $_FILES['filename_2']['name'];
	$ext_2 = pathinfo($filename_2, PATHINFO_EXTENSION);

	$img1 = '';
	$img2 = '';
	$allowed = array('jpg','png','gif','JPG','PNG','GIF');
	if( ($filename_1 != '' && !in_array( $ext_1, $allowed )) || ($filename_2 != '' && !in_array( $ext_2, $allowed )) ) {
		echo '<script type="text/javascript"> alert("Invalid extension to be upload"); window.location = "/face/face-settings"; </script>';
	}elseif($_FILES['filename_1']['size'] > 10120000 || $_FILES['filename_2']['size'] > 10120000){
		echo '<script type="text/javascript"> alert("Invalid file size to be upload"); window.location = "/face/face-settings"; </script>';
	}else{
	
		$directory = $_SERVER['DOCUMENT_ROOT']."/face/face-settings/images/";

		if($filename_1 != ''){	
			$fileName = pathinfo($_FILES['filename_1']['name']);
			$fileName = 'image_1_'.date("YmdHis").".".$fileName['extension'];
			$img1 = $fileName;
			$path = $directory."/".$fileName;
			$file_convert = 0;
			($_FILES['filename_1']['size'] >= 7120000 && $_FILES['filename_1']['size'] <= 10120000) ? $file_convert = 50 : '';
			($_FILES['filename_1']['size'] >= 4120000 && $_FILES['filename_1']['size'] < 7120000) ? $file_convert = 70 : '';
			($_FILES['filename_1']['size'] >= 2120000 && $_FILES['filename_1']['size'] < 4120000) ? $file_convert = 80 : '';
			($_FILES['filename_1']['size'] >= 1120000 && $_FILES['filename_1']['size'] < 2120000) ? $file_convert = 90 : '';

			if($file_convert != 0){
				compressImage($_FILES['filename_1']['tmp_name'],$path,$file_convert);
			}
			elseif(move_uploaded_file($_FILES['filename_1']['tmp_name'],$path)){}
		}else{
			$img1 = $_POST['retain_image_1'];
		}

		if($filename_2 != ''){	
			$fileName = pathinfo($_FILES['filename_2']['name']);
			$fileName = 'image_2_'.date("YmdHis").".".$fileName['extension'];
			$img2 = $fileName;
			$path = $directory."/".$fileName;
			$file_convert = 0;
			($_FILES['filename_2']['size'] >= 7120000 && $_FILES['filename_2']['size'] <= 10120000) ? $file_convert = 50 : '';
			($_FILES['filename_2']['size'] >= 4120000 && $_FILES['filename_2']['size'] < 7120000) ? $file_convert = 70 : '';
			($_FILES['filename_2']['size'] >= 2120000 && $_FILES['filename_2']['size'] < 4120000) ? $file_convert = 80 : '';
			($_FILES['filename_2']['size'] >= 1120000 && $_FILES['filename_2']['size'] < 2120000) ? $file_convert = 90 : '';

			if($file_convert != 0){
				compressImage($_FILES['filename_2']['tmp_name'],$path,$file_convert);
			}
			elseif(move_uploaded_file($_FILES['filename_2']['tmp_name'],$path)){}
		}
	}
	if($filename_1 == ''){
		$img1 = $_POST['retain_image_1'];
	}
	if($filename_2 == ''){
		$img2 = $_POST['retain_image_2'];
	}

	insertData($img1, $img2, $video_file, $_POST['text_data']);
?>