<?php
    include 'connect.php';
    session_start();
    date_default_timezone_set('Asia/Calcutta');
    if ($_SESSION["skguser"]=="" or $_SESSION["skgpass"]==""){
        echo "SESSION EXPIRED";
        die();
    }
    $db = new connectdb();
    $mysqli = $db->connect();
    $result = $mysqli->query("SELECT * from users where username='".$_SESSION["skguser"]."' and password='".$_SESSION["skgpass"]."'");
    $row = mysqli_fetch_assoc($result);
    $utype = $row["type"];
    $mysqli->close();
    if ($utype != 'admin'){
        echo "<html><head><title>BC Management System</title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    } else {
        $bcid = $_REQUEST["bcid"];
	//	echo "$bcid";
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT * from bc_details where bcid='".$bcid."'");
        $row = mysqli_fetch_assoc($result);
        echo $row["type"].",".$row["startdate"].",".$row["bcmembers"].",".$row["amount"].",".$row["totalbcamount"].",".$row["bc_name"];
        $mysqli->close();
    }
?>
