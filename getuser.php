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
        $username = $_REQUEST["username"];
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT * from users where username='".$username."'");
        $row = mysqli_fetch_assoc($result);
        echo $row["username"].",".$row["password"].",".$row["type"].",".$row["fname"].",".$row["mname"].",".$row["lname"].",".$row["address"].",".$row["mobile"];
        $mysqli->close();
    }
?>
