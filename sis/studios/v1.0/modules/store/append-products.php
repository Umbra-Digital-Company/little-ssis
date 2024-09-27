<?php
function trimColor($color_name)
{
	// Remove abbreviations and classes
	$color_name =
		str_replace(
			"/",
			" ",
			str_replace(
				"blk",
				"black",
				str_replace(
					"brown lns",
					"",
					str_replace(
						"brn",
						"brown",
						str_replace(
							"mt",
							"m",
							str_replace(
								"matte",
								"m",
								str_replace(
									"flt",
									"f",
									str_replace(
										"lens",
										"",
										str_replace(
											"flat",
											"f",
											str_replace(
												"grn",
												"",
												str_replace(
													"gdt",
													"",
													str_replace(
														"/crml",
														"",
														trim($color_name)
													)
												)
											)
										)
									)
								)
							)
						)
					)
				)
			)
		);

	return $color_name;

}
;

$arrProductsSorted = $_POST['arrProd'];
$showProduct = '';
for ($i = 0; $i < count($arrProductsSorted); $i++) {
	$showProduct .= '<div class="frame-style col-6 mb-3 hide-lazy" data-style="' . trim($arrProductsSorted[$i]['item_description']) . '">
	                                
	        <div class="frame-style__slider" id="slider-product-' . str_replace(' ', '_', trim($arrProductsSorted[$i]['item_description'])) . '">';

	// Set current colors array
	$curColors = $arrProductsSorted[$i]["colors"];
	for ($a = 0; $a < sizeOf($curColors); $a++) {


		$showProduct .= '<div class="product-option" data-color-name="' . $curColors[$a]['color'] . '" data-color-code="' . $curColors[$a]['product_code'] . '" product-code="' . $curColors[$a]['product_code'] . '">
	                <input type="radio" name="frame_style" class="sr-only" >
	                <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center" style="background-color: #fff;">';

		$curImageURL = $curColors[$a]["image"];

		$showProduct .= '<div class="image-wrapper" style="width: 100%; padding-bottom: 75%; background-color: #f1f1f1; background-image: url(' . $curImageURL . '); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>

	                    

	                </label>
	            </div>';

	}

	$showProduct .= '</div>
	        <div style="background: #fff; padding: 15px; border-radius: 0 0 10px 10px;">
	            

				<div class="d-flex justify-content-between">
                                        <section
                                            class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                            <div>
                                                <h4> ' . trim($arrProductsSorted[$i]['item_description']) . '
                                                </h4>
                                                <h4><span class="blk">' . trimColor($curColors[0]['color']) . '</span> </h4>
                                            </div>

                                        </section>

                                        <section
                                            class="product-details flex-nowrap no-gutters align-items-start justify-content-between">
                                            <h5>
                                                <span >P '  . trim($curColors[0]['price']) . '</span>
                                            </h5>
                                        </section>
                                    </div>

	            <ul class="row switch-color col-12">';


	$totalColors = sizeof($curColors);
	$maxVisibleColors = 4; // Limit the number of visible colors to 4
	for ($a = 0; $a < sizeOf($curColors); $a++) {

		if ($a < $maxVisibleColors) {
			$showProduct .= '<li class="' . (($a === 0) ? 'active' : '') . '" data-index="' . $a . '" data-style-name="' . trim($arrProductsSorted[$i]['item_description']) . '" data-color-name="' . trimColor($curColors[$a]['color']) . '" data-color-code="' . $curColors[$a]['product_code'] . '" data-color-price="P ' . trim($curColors[$a]['price']) . '" style="' . (($curColors[$a]['color_swatch'] != '') ? 'background-color: ' . $curColors[$a]['color_swatch'] : 'background-color: #000;') . '"></li>';
		}
	}

	if ($totalColors > $maxVisibleColors) {
		$remainingColors = $totalColors - $maxVisibleColors;

		// Show the remaining colors with a "more" button
		$showProduct .= '<li class="more-item" data-index="' . $a . '"></li>';

		// Loop to display hidden colors
		for ($a = $maxVisibleColors; $a < $totalColors; $a++) {
			$showProduct .= '
				<li class="hidden hidden-colors" 
					id="' . trim($arrProductsSorted[$i]['item_description']) . '" 
					data-index="' . $a . '" 
					data-style-name="' . trim($arrProductsSorted[$i]['item_description']) . '" 
					data-color-name="' . trimColor($curColors[$a]['color']) . '" 
					data-color-code="' . trim($curColors[$a]['product_code']) . '" 
					data-color-price="P' . $curColors[$a]['price'] . '" 
					style="' . (($curColors[$a]['color_swatch'] != '') ? 'background-color: ' . $curColors[$a]['color_swatch'] : 'background-color: #000;') . ';">
				</li>';
		}
	}


	$showProduct .= '</ul>    
	            <div class="row d-flex justify-content-center mt-3">
	                <form class="col-12 form-quick-add-to-bag" id="form-quick-add-to-bag' . $i . '" method="POST">
	                    <input type="hidden" name="studios_product_code" id="input-sku-' . trim($arrProductsSorted[$i]['item_description']) . '" value="' . $curColors[0]['product_code'] . '">
	                    <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
	                    <button type="submit" class="btn btn-not-cancel">Add to bag</button>
	                </form>
	            </div>                                
	        </div>
	    </div>	
		';
}

echo json_encode(['show_product' => $showProduct, 'item_description' => str_replace(' ', '_', trim($arrProductsSorted[0]['item_description']))]);