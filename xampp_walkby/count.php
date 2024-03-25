<?php
include_once 'config.php';

//$timestamp = time();

//echo date('l dS \o\f F Y h:i:s A', $timestamp);




//$date1 = "2019-05-16";
//$timestamp1 = strtotime($date1);
//echo $timestamp1; // Outputs: 1557964800


$query = "SELECT * FROM walks WHERE action > '".strtotime("today")."' ORDER BY id ASC";
$result = $conn_sql->query($query);
$num_rows = mysqli_num_rows($result);

echo "$num_rows Rows\n";
echo '<hr>';
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
	echo $row["action"] . '<br>';	
}

