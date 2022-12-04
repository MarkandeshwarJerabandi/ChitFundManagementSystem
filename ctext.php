<?php
include 'crypto.php';
$crypt = new crypto();
$text='22-49-0F-62-E9-1B';  // replace this string value by your system mac address and run the code to get encrypted value
$text1 = $crypt->cypher($text);
echo $text1;
?>