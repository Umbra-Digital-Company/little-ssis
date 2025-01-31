<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set occupations array
$arrOccupations = array();
$arrOccupationsCons = array();

// Set industries array
$arrIndustries = array(

	"Accounting" => array(),
	"Airlines/Aviation" => array(),
	"Alternative Dispute Resolution" => array(),
	"Alternative Medicine" => array(),
	"Animation" => array(),
	"Apparel and Fashion" => array(),
	"Architecture and Planning" => array(),
	"Arts and Crafts" => array(),
	"Automotive" => array(),
	"Aviation and Aerospace" => array(),
	"Banking" => array(),
	"Biotechnology" => array(),
	"Broadcast Media" => array(),
	"Building Materials" => array(),
	"Business Supplies and Equipment" => array(),
	"Capital Markets" => array(),
	"Chemicals" => array(),
	"Civic and Social Organization" => array(),
	"Civil Engineering" => array(),
	"Commercial Real Estate" => array(),
	"Computer and Network Security" => array(),
	"Computer Games" => array(),
	"Computer Hardware" => array(),
	"Computer Networking" => array(),
	"Computer Software" => array(),
	"Construction" => array(),
	"Consumer Electronics" => array(),
	"Consumer Goods" => array(),
	"Consumer Services" => array(),
	"Cosmetics" => array(),
	"Dairy" => array(),
	"Defense and Space" => array(),
	"Design" => array(),
	"Education Management" => array(),
	"E-Learning" => array(),
	"Electrical/Electronic Manufacturing" => array(),
	"Entertainment" => array(),
	"Environmental Services" => array(),
	"Events Services" => array(),
	"Executive Office" => array(),
	"Facilities Services" => array(),
	"Farming" => array(),
	"Financial Services" => array(),
	"Fine Art" => array(),
	"Fishery" => array(),
	"Food and Beverages" => array(),
	"Food Production" => array(),
	"Fund-Raising" => array(),
	"Furniture" => array(),
	"Gambling and Casinos" => array(),
	"Glass, Ceramics and Concrete" => array(),
	"Government Administration" => array(),
	"Government Relations" => array(),
	"Graphic Design" => array(),
	"Health, Wellness and Fitness" => array(),
	"Higher Education" => array(),
	"Hospital and Health Care" => array(),
	"Hospitality" => array(),
	"Human Resources" => array(),
	"Import and Export" => array(),
	"Individual and Family Services" => array(),
	"Industrial Automation" => array(),
	"Information Services" => array(),
	"Information Technology and Services" => array(),
	"Insurance" => array(),
	"International Affairs" => array(),
	"International Trade and Development" => array(),
	"Internet" => array(),
	"Investment Banking" => array(),
	"Investment Management" => array(),
	"Judiciary" => array(),
	"Law Enforcement" => array(),
	"Law Practice" => array(),
	"Legal Services" => array(),
	"Legislative Office" => array(),
	"Leisure, Travel and Tourism" => array(),
	"Libraries" => array(),
	"Logistics and Supply Chain" => array(),
	"Luxury Goods and Jewelry" => array(),
	"Machinery" => array(),
	"Management Consulting" => array(),
	"Maritime" => array(),
	"Marketing and Advertising" => array(),
	"Market Research" => array(),
	"Mechanical or Industrial Engineering" => array(),
	"Media Production" => array(),
	"Medical Devices" => array(),
	"Medical Practice" => array(),
	"Mental Health Care" => array(),
	"Military" => array(),
	"Mining and Metals" => array(),
	"Motion Pictures and Film" => array(),
	"Museums and Institutions" => array(),
	"Music" => array(),
	"Nanotechnology" => array(),
	"Newspapers" => array(),
	"Nonprofit Organization Management" => array(),
	"Oil and Energy" => array(),
	"Online Media" => array(),
	"Outsourcing/Offshoring" => array(),
	"Package/Freight Delivery" => array(),
	"Packaging and Containers" => array(),
	"Paper and Forest Products" => array(),
	"Performing Arts" => array(),
	"Pharmaceuticals" => array(),
	"Philanthropy" => array(),
	"Photography" => array(),
	"Plastics" => array(),
	"Political Organization" => array(),
	"Primary/Secondary Education" => array(),
	"Printing" => array(),
	"Professional Training and Coaching" => array(),
	"Program Development" => array(),
	"Public Policy" => array(),
	"Public Relations and Communications" => array(),
	"Public Safety" => array(),
	"Publishing" => array(),
	"Railroad Manufacture" => array(),
	"Ranching" => array(),
	"Real Estate" => array(),
	"Recreational Facilities and Services" => array(),
	"Religious Institutions" => array(),
	"Renewables and Environment" => array(),
	"Research" => array(),
	"Restaurants" => array(),
	"Retail" => array(),
	"Security and Investigations" => array(),
	"Semiconductors" => array(),
	"Shipbuilding" => array(),
	"Sporting Goods" => array(),
	"Sports" => array(),
	"Staffing and Recruiting" => array(),
	"Supermarkets" => array(),
	"Telecommunications" => array(),
	"Textiles" => array(),
	"Think Tanks" => array(),
	"Tobacco" => array(),
	"Translation and Localization" => array(),
	"Transportation/Trucking/Railroad" => array(),
	"Utilities" => array(),
	"Venture Capital and Private Equity" => array(),
	"Veterinary" => array(),
	"Warehousing" => array(),
	"Wholesale" => array(),
	"Wine and Spirits" => array(),
	"Wireless" => array(),
	"Writing and Editing" => array()

);

$query 	= 	"SELECT
				LOWER(o.occupation)
			FROM
				orders o
			GROUP BY
				o.occupation
			ORDER BY
				o.occupation ASC";

$grabParams = array('occupation');

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrOccupations[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

// Consolidate
for ($i=0; $i < sizeOf($arrOccupations); $i++) { 

	// Set current occupation
	$curOccupation = $arrOccupations[$i]['occupation'];
	$addOccupation = '';

	switch ($curOccupation) {

		case ' ':
		case '-':
		case '.':
		case NULL:
		case 'n a':
		case 'na':
		case 'none':
		case '.':
		case '..':
			$addOccupation = 'n/a';
			break;

		default:
			$addOccupation = $curOccupation;
			break;
		
		
	};

	array_push($arrOccupationsCons, $addOccupation);
	
};

$arrOccupationsCons = array_unique($arrOccupationsCons);



?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<select name="industryChooser" id="industryChooser-editLocationForm" type="singleselect" aria-describedby="industryChooser-editLocationForm-error">
		<option value="">Choose industry...</option>
		<option value="47">Accounting</option>
		<option value="94">Airlines/Aviation</option>
		<option value="120">Alternative Dispute Resolution</option>
		<option value="125">Alternative Medicine</option>
		<option value="127">Animation</option>
		<option value="19">Apparel &amp; Fashion</option>
		<option value="50">Architecture &amp; Planning</option>
		<option value="111">Arts and Crafts</option>
		<option value="53">Automotive</option>
		<option value="52">Aviation &amp; Aerospace</option>
		<option value="41">Banking</option>
		<option value="12">Biotechnology</option>
		<option value="36">Broadcast Media</option>
		<option value="49">Building Materials</option>
		<option value="138">Business Supplies and Equipment</option>
		<option value="129">Capital Markets</option>
		<option value="54">Chemicals</option>
		<option value="90">Civic &amp; Social Organization</option>
		<option value="51">Civil Engineering</option>
		<option value="128">Commercial Real Estate</option>
		<option value="118">Computer &amp; Network Security</option>
		<option value="109">Computer Games</option>
		<option value="3">Computer Hardware</option>
		<option value="5">Computer Networking</option>
		<option value="4">Computer Software</option>
		<option value="48">Construction</option>
		<option value="24">Consumer Electronics</option>
		<option value="25">Consumer Goods</option>
		<option value="91">Consumer Services</option>
		<option value="18">Cosmetics</option>
		<option value="65">Dairy</option>
		<option value="1">Defense &amp; Space</option>
		<option value="99">Design</option>
		<option value="69">Education Management</option>
		<option value="132">E-Learning</option>
		<option value="112">Electrical/Electronic Manufacturing</option>
		<option value="28">Entertainment</option>
		<option value="86">Environmental Services</option>
		<option value="110">Events Services</option>
		<option value="76">Executive Office</option>
		<option value="122">Facilities Services</option>
		<option value="63">Farming</option>
		<option value="43">Financial Services</option>
		<option value="38">Fine Art</option>
		<option value="66">Fishery</option>
		<option value="34">Food &amp; Beverages</option>
		<option value="23">Food Production</option>
		<option value="101">Fund-Raising</option>
		<option value="26">Furniture</option>
		<option value="29">Gambling &amp; Casinos</option>
		<option value="145">Glass, Ceramics &amp; Concrete</option>
		<option value="75">Government Administration</option>
		<option value="148">Government Relations</option>
		<option value="140">Graphic Design</option>
		<option value="124">Health, Wellness and Fitness</option>
		<option value="68">Higher Education</option>
		<option value="14">Hospital &amp; Health Care</option>
		<option value="31">Hospitality</option>
		<option value="137">Human Resources</option>
		<option value="134">Import and Export</option>
		<option value="88">Individual &amp; Family Services</option>
		<option value="147">Industrial Automation</option>
		<option value="84">Information Services</option>
		<option value="96">Information Technology and Services</option>
		<option value="42">Insurance</option>
		<option value="74">International Affairs</option>
		<option value="141">International Trade and Development</option>
		<option value="6">Internet</option>
		<option value="45">Investment Banking</option>
		<option value="46">Investment Management</option>
		<option value="73">Judiciary</option>
		<option value="77">Law Enforcement</option>
		<option value="9">Law Practice</option>
		<option value="10">Legal Services</option>
		<option value="72">Legislative Office</option>
		<option value="30">Leisure, Travel &amp; Tourism</option>
		<option value="85">Libraries</option>
		<option value="116">Logistics and Supply Chain</option>
		<option value="143">Luxury Goods &amp; Jewelry</option>
		<option value="55">Machinery</option>
		<option value="11">Management Consulting</option>
		<option value="95">Maritime</option>
		<option value="80">Marketing and Advertising</option>
		<option value="97">Market Research</option>
		<option value="135">Mechanical or Industrial Engineering</option>
		<option value="126">Media Production</option>
		<option value="17">Medical Devices</option>
		<option value="13">Medical Practice</option>
		<option value="139">Mental Health Care</option>
		<option value="71">Military</option>
		<option value="56">Mining &amp; Metals</option>
		<option value="35">Motion Pictures and Film</option>
		<option value="37">Museums and Institutions</option>
		<option value="115" selected="selected">Music</option>
		<option value="114">Nanotechnology</option>
		<option value="81">Newspapers</option>
		<option value="100">Nonprofit Organization Management</option>
		<option value="57">Oil &amp; Energy</option>
		<option value="113">Online Media</option>
		<option value="123">Outsourcing/Offshoring</option>
		<option value="87">Package/Freight Delivery</option>
		<option value="146">Packaging and Containers</option>
		<option value="61">Paper &amp; Forest Products</option>
		<option value="39">Performing Arts</option>
		<option value="15">Pharmaceuticals</option>
		<option value="131">Philanthropy</option>
		<option value="136">Photography</option>
		<option value="117">Plastics</option>
		<option value="107">Political Organization</option>
		<option value="67">Primary/Secondary Education</option>
		<option value="83">Printing</option>
		<option value="105">Professional Training &amp; Coaching</option>
		<option value="102">Program Development</option>
		<option value="79">Public Policy</option>
		<option value="98">Public Relations and Communications</option>
		<option value="78">Public Safety</option>
		<option value="82">Publishing</option>
		<option value="62">Railroad Manufacture</option>
		<option value="64">Ranching</option>
		<option value="44">Real Estate</option>
		<option value="40">Recreational Facilities and Services</option>
		<option value="89">Religious Institutions</option>
		<option value="144">Renewables &amp; Environment</option>
		<option value="70">Research</option>
		<option value="32">Restaurants</option>
		<option value="27">Retail</option>
		<option value="121">Security and Investigations</option>
		<option value="7">Semiconductors</option>
		<option value="58">Shipbuilding</option>
		<option value="20">Sporting Goods</option>
		<option value="33">Sports</option>
		<option value="104">Staffing and Recruiting</option>
		<option value="22">Supermarkets</option>
		<option value="8">Telecommunications</option>
		<option value="60">Textiles</option>
		<option value="130">Think Tanks</option>
		<option value="21">Tobacco</option>
		<option value="108">Translation and Localization</option>
		<option value="92">Transportation/Trucking/Railroad</option>
		<option value="59">Utilities</option>
		<option value="106">Venture Capital &amp; Private Equity</option>
		<option value="16">Veterinary</option>
		<option value="93">Warehousing</option>
		<option value="133">Wholesale</option>
		<option value="142">Wine and Spirits</option>
		<option value="119">Wireless</option>
		<option value="103">Writing and Editing</option>
	</select>	
</body>
</html>