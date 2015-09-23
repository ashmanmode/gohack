<?php


function db_connect(){
	$con = mysqli_connect("localhost","root","25011994","hackerearth");
	
    // Check connection
	if (mysqli_connect_errno())
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	
	
	return $con;
}


$conn = db_connect();

?>