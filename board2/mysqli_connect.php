<?php # Script 18.4 - mysqli_connect.php
// This file contains the database access information.
// This file also establishes a connection to MySQL
// and selects the database.

// Set the database access information as constants:
DEFINE ('DB_USER', 'navyism');
DEFINE ('DB_PASSWORD', 'a1234');
DEFINE ('DB_HOST', '172.30.150.173');
DEFINE ('DB_NAME', 'uptrans');

// Make the connection:
//$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// If no connection could be made, trigger an error:
if (!$dbc) {
	trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
} else { // Otherwise, set the encoding:
	mysqli_set_charset($dbc, 'utf8');
}
