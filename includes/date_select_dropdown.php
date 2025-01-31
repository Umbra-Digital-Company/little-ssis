<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_GET['month'])) {

	echo 'No month sent';
	exit;

}

// Number of days in month
$arrMonthDays = array(

	"January" => 31,
	"February" => 28,
	"March" => 31,
	"April" => 30,
	"May" => 31,
	"June" => 30,
	"July" => 31,
	"August" => 31,
	"September" => 30,
	"October" => 31,
	"November" => 30,
	"December" => 31

);

// Select month
$numDays = array_values($arrMonthDays)[$_GET["month"]-1];

// Cycle through days
for ($i=0; $i < $numDays; $i++) { 

	echo '<option value="'.($i + 1).'">'.(sprintf("%02d", ($i + 1))).'</option>';

};

?>