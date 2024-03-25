<?php
include_once 'config.php';

$responseBody = file_get_contents('php://input');

if (!$conn_sql)
{
	$input_contents = date("F j, Y, g:i a") . " - ".mysqli_error($conn_sql)." \r\n";	
}
else
{
	$sql = "INSERT INTO walks (action) VALUES ('".time()."')";
}

if ($conn_sql->query($sql) === TRUE)
{
	$input_contents = date("F j, Y, g:i a") . " - SUCCESS \r\n";
}
else
{
	$input_contents = date("F j, Y, g:i a") . " - ".mysqli_error($conn_sql)." \r\n";	
}

$input_log_file_name 		=__DIR__ . "/" . date("m-Y") . '_settingslog.txt';

file_put_contents($input_log_file_name, $input_contents, FILE_APPEND);
echo 'SUCCESS';
header('HTTP/1.1 201 Created');

