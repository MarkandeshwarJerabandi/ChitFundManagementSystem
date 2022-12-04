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
        echo "<html><head><title>IoT Home Automation Server></title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    } else {
        $memid = $_REQUEST["memid"];
	//	echo "$bcid";
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT * from members_details where memid='".$memid."'");
        $row = mysqli_fetch_assoc($result);
        echo $row["fname"].",".$row["mname"].",".$row["lname"].",".$row["radd"].",".$row["oadd"].",".$row["mobile"].",".$row["landline"].",".$row["emailid"].",".$row["altmobile"].",".$row["type"];
        $mysqli->close();
    }
?>
