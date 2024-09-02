<?php 
$arrPowerBi =array();


$queryPower="SELECT link,
link2,
link3,
link4,
link5,
link6,
link7,
link8,
link_title_1,
link_title_2,
link_title_3,
link_title_4,
link_title_5,
link_title_6,
link_title_7,
link_title_8
 FROM finance_report";

$grabParamsPower = array( 'power_link','power_link2','power_link3','power_link4','power_link5','power_link6','power_link7','power_link8',
                            'power_link_title1', 'power_link_title2','power_link_title3',
                            'power_link_title4', 'power_link_title5', 'power_link_title6', 'power_link_title7', 'power_link_title8'
    );


$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryPower)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8
    , $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParamsPower); $i++) { 

            $tempArray[$grabParamsPower[$i]] = ${'result' . ($i+1)};

        };

        $arrPowerBi[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};
?>