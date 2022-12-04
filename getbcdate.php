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
        echo "<html><head><title>SKG BC Management System</title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    } else {
        $bcid = $_REQUEST["bcid"];
		$bidtype = $_REQUEST["bidtype"];
	//	echo "$bcid";
		$bc_values= preg_split("/_/",$bcid);
        $mysqli = $db->connect();
        
		$result1 = $mysqli->query("SELECT bcdate from bc_bidding_details where bcid='".$bc_values[0]."' order by bcdate desc");
		$rows = mysqli_num_rows($result1);
	//	echo "rows=" . $rows;
		if($rows>0)
		{
			$bcd = mysqli_fetch_assoc($result1);
			$date = $bcd['bcdate'];
			echo "newdate=" . $date;
		}
		else
			echo "=";
     
        $mysqli->close();
    }
?>