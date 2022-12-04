<?php
	include 'crypto.php';
	$strdescription="";
	ob_start();
	system('ipconfig /all');
	$mycom=ob_get_contents(); 
	ob_clean(); 
	$findme = "Physical";
	$pm = strpos($mycom, $findme); 
	$textm=substr($mycom,($pm+36),17);
	$crypt = new crypto();
	$text2 = $crypt->cypher($textm);
	$text1="lVnUjWc8lBmUHkZkOJxLrNJA0acqUTM0RHruwc4vBz4=";
	if($text2==$text1)
		echo "you are authorized user";
	else
		echo "you are not authorized user";
?>
	