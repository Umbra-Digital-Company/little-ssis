<?php
    
    if(!isset($conn)){
        $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
      // Required includes
      require $sDocRoot."/includes/connect.php";
    }

    function getPackaging($data_cgc){
        global $conn;
        $arrPaperBag = [];
        $query = 'SELECT  
                            item_code,
                            item_name
                        FROM 
                           poll_51_face_new
                WHERE
                    data_cgc IN ("'.implode('","',$data_cgc).'");';

        $grabParamsQF = array(
            'product_code',
            'item_name'
        );
        
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParamsQF); $i++) { 

                    $tempArray[$grabParamsQF[$i]] = ${'result' . ($i+1)};

                };
                
                $arrPaperBag[]= $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        return $arrPaperBag;
    }
	function paperBagList(){

		// $arrPaperBag = [
        //         [
        //             'product_code' => 'P1008-34',
        //             'item_name' => 'SBC SPACE AGE PAPER BAG'
        //         ],
        //         [
        //             'product_code' => 'P1008-36',
        //             'item_name' => 'SBC PAPER BAG CLOUD GRADIENT'
        //         ],
        //         [
        //             'product_code' => 'P1011-4',
        //             'item_name' => 'HOLIDAY BOX 2022 - PINK'
        //         ],
        //         [
        //             'product_code' => 'P1011-5',
        //             'item_name' => 'HOLIDAY BOX 2022 - GREEN'
        //         ],
        //         [
        //             'product_code' => 'SCO0036',
        //             'item_name' => 'AR KIDS BOX 2022'
        //         ],

        //         [
        //             'product_code' => 'SSP003-11',
        //             'item_name' => 'FACE CREAM PB BIG'
        //         ],
        //         [
        //             'product_code' => 'P1007-16',
        //             'item_name' => 'SBC PAPER BAG SPIRULINA'
        //         ],
        //         [
        //             'product_code' => 'P1008-35',
        //             'item_name' => 'SBC 2019 HOLIDAY PAPER BAG - XL'
        //         ],
        //         [
        //             'product_code' => 'P1008-4',
        //             'item_name' => 'SBC PAPER BAG REINDEER'
        //         ],
        //         [
        //             'product_code' => 'P1011-1',
        //             'item_name' => 'SUNNIES 4Q 2017 BOX'
        //         ],
        //         [
        //             'product_code' => 'P1011-2',
        //             'item_name' => 'SUNNIES XMAS HANG'
        //         ],
        //         [
        //             'product_code' => 'P1008-37',
        //             'item_name' => 'SBS PAPERBAG UNBRANDED - L'
        //         ],
        //         [
        //             'product_code' => 'SML001',
        //             'item_name' => 'LAZADA LARGE POUCH WITH SLEEVE'
        //         ],
        //         [
        //             'product_code' => 'SML002',
        //             'item_name' => 'LAZADA MEDIUM POUCH WITH SLEEVE'
        //         ],
        //         [
        //             'product_code' => 'SMS001',
        //             'item_name' => 'SHOPEE LARGE POUCH - BLACK'
        //         ],
        //         [
        //             'product_code' => 'HT001-01',
        //             'item_name' => 'HOLIDAY TAG 2021 - CLOUD GRADIENT'
        //         ],
        //         [
        //             'product_code' => 'HT001-02',
        //             'item_name' => 'HOLIDAY TAG 2021 - ORANGE'
        //         ]
        // ];

        return getPackaging(['DCGC0010','DCGC0008']);
	}

	function sacList(){
		// $arrPaperBag = [
        //         [
        //             'product_code' => 'SC101-03',
        //             'item_name' => 'SBC SUNNIES SAC SPACE AGE w/ CLOTH'
        //         ],
        //         [
        //             'product_code' => 'SC101-04',
        //             'item_name' => 'SBC SUNNIES SAC W/ CLOTH CLOUD GRADIENT'
        //         ],
        //         [
        //             'product_code' => 'SC101-05',
        //             'item_name' => 'SUN SAC W/ CLOTH COLOR STORY - CREAM'
        //         ],
        //         [
        //             'product_code' => 'SC101-06',
        //             'item_name' => 'SUN SAC W/ CLOTH COLOR STORY  - SOFT BLACK'
        //         ],
        //         [
        //             'product_code' => 'SC101-06',
        //             'item_name' => 'SUN SAC W/ CLOTH COLOR STORY  - SOFT BLACK'
        //         ],
        //         [
        //             'product_code' => 'P1004-24',
        //             'item_name' => 'SBC SUNNIES SAC LACMA'
        //         ],
        //         [
        //             'product_code' => 'P1009-18',
        //             'item_name' => 'SBC SUNNIES SAC SPIRULINA'
        //         ],
        //         [
        //             'product_code' => 'P1009-23',
        //             'item_name' => 'SBC SUNNIES SAC CHRISTMAS PUPPIES'
        //         ],
        //         [
        //             'product_code' => 'P1009-25',
        //             'item_name' => 'SBC SUNNIES SAC CHOCOLATE'
        //         ],
        //         [
        //             'product_code' => 'P1009-26',
        //             'item_name' => 'SBC SUNNIES SAC ABSTRACT LEMON'
        //         ],
        //         [
        //             'product_code' => 'P1009-27',
        //             'item_name' => 'SBC SUNNIES SAC ABSTRACT CANYON'
        //         ],
        //         [
        //             'product_code' => 'P1009-28',
        //             'item_name' => 'SBC SUNNIES SAC PEOPLE'
        //         ],
        //         [
        //             'product_code' => 'P1009-29',
        //             'item_name' => 'SBC SUNNIES SAC PLAID'
        //         ],
        //         [
        //             'product_code' => 'P1009-30',
        //             'item_name' => 'SBC SUNNIES SAC NAVY BLUE'
        //         ],
        //         [
        //             'product_code' => 'P1009-33',
        //             'item_name' => 'SBC SUNNIES SAC LOGOMANIA 3Q 2018'
        //         ],
        //         [
        //             'product_code' => 'P1009-34',
        //             'item_name' => 'SBC SUNNIES SAC SNOW PLAY'
        //         ],
        //         [
        //             'product_code' => 'P1009-35',
        //             'item_name' => 'SBC SUNNIES SAC LIGHT ROOM'
        //         ],
        //         [
        //             'product_code' => 'SC101-13',
        //             'item_name' => 'EYEWEAR CORE SAC + CLOTH - BLUE'
        //         ],
        //         [
        //             'product_code' => 'SC101-14',
        //             'item_name' => 'EYEWEAR CORE SAC + CLOTH - CREAM'
        //         ],
        //         [
        //             'product_code' => 'SC101-15',
        //             'item_name' => 'EYEWEAR HOLIDAY SAC 2023 - CREAM'
        //         ]
        // ];

        return getPackaging(['DCGC0008']);
	}

	function othersList(){
		$arrPaperBag = [
                [
                    'product_code' => 'GRH0001',
                    'item_name' => 'Receipt holder 2022'
                ]
        ];

        return $arrPaperBag;
	}
?>