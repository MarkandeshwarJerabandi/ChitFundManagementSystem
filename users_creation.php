<?php
    include 'connect.php';
    session_start();
    date_default_timezone_set('Asia/Calcutta');
    if ($_SESSION["skguser"]=="" or $_SESSION["skgpass"]==""){
        header("Location: ../../");
        die();
    }
    $db = new connectdb();
    $mysqli = $db->connect();
    $result = $mysqli->query("SELECT * from users where userid='".$_SESSION["skguser"]."' and password='".$_SESSION["skgpass"]."'");
    $row = mysqli_fetch_assoc($result);
    $type = $row["type"];
    $mysqli->close();
    if ($admin != 'admin'){
        echo "<html><head><title>SKG BC Management System></title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    }
    $strerror = false;
    $strdescription = "";
    if ($_POST["Add"]){
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from users where username='".$_POST["uname"]."' and pwd='".$_POST["pwd"]."' ");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
        if ($uid > 0){
            $strerror = true;
            $strdescription = "User already exist!!";
        } else {
            $mysqli = $db->connect();
			if($_POST["fname"])
				fname=$_POST["fname"];
			if($_POST["mname"])
				mname=$_POST["mname"];
			if($_POST["lname"])
				lname=$_POST["lname"];
			if($_POST["address"])
				address=$_POST["address"];
			if($_POST["mobile"])
				mobile=$_POST["mobile"];
			if($_POST["utype"])
				utype=$_POST["utype"];
			if($_POST["uname"])
				uname=$_POST["uname"];
			if($_POST["pwd"])
				pwd=$_POST["pwd"];
			if($_POST["cpwd"])
				cpwd=$_POST["cpwd"];
			
            $sql = "INSERT into users (uid,username,password,type,fname,mname,lname,address,mobile) values ('','".$uname."', '".$pwd."', '".$utype."', '".$fname."', '".$mname."', '".$lname."', '".$address."', '".$mobile."')";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "User created!!";
            } else {
                $strerror = true;
                $strdescription = "Database Add Error!!";
            }
            $mysqli->close();
        } 
    } else if ($_POST["update"]){
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from users where userid='".$_POST["email"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
        $password = "laPl/yAxc7PEe/lLnDFTiYfC/y6AArGoMdX28eynq2o=";//encrypted string 'password'
        if ($_POST["status"]){
            $status = "YES";
        } else {
            $status = "NO";
        }
        if ($_POST["control"]){
            $control = "YES";
        } else {
            $control = "NO";
        }
        if ($_POST["manage"]){
            $manage = "YES";
        } else {
            $manage = "NO";
        }
        if ($uid < 1){
            $strerror = true;
            $strdescription = "User not found!!";
        } else {
            $mysqli = $db->connect();
            $sql = "UPDATE users set userid='".$_POST["email"]."', status='".$status."', control='".$control."', manage='".$manage."' where userid='".$_POST["selectuser"]."'";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "User updated!!";
            } else {
                $strerror = true;
                $strdescription = "Database Update Error!!";
            }
            $mysqli->close();
        } 
    } else if ($_POST["reset"]){
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from users where userid='".$_POST["email"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
        $password = "laPl/yAxc7PEe/lLnDFTiYfC/y6AArGoMdX28eynq2o=";//encrypted string 'password'
        if ($uid < 1){
            $strerror = true;
            $strdescription = "User not found!!";
        } else {
            $mysqli = $db->connect();
            $sql = "UPDATE users set password='".$password."'";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "User password reset!!";
            } else {
                $strerror = true;
                $strdescription = "Database Reset Error!!";
            }
            $mysqli->close();
        } 
    } else if ($_POST["remove"]){
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from users where userid='".$_POST["email"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
        $password = "laPl/yAxc7PEe/lLnDFTiYfC/y6AArGoMdX28eynq2o=";//encrypted string 'password'
        if ($uid < 1){
            $strerror = true;
            $strdescription = "User not found!!";
        } else {
            $mysqli = $db->connect();
            $sql = "DELETE from users where userid='".$_POST["email"]."'";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "User removed!!";
            } else {
                $strerror = true;
                $strdescription = "Database Remove Error!!";
            }
            $mysqli->close();
        } 
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Creation Form</title>
<style type="text/css">
<!--
.style1 {color: #0000CC}
.style2 {font-family: "Times New Roman", Times, serif}
.style3 {
	color: #0033CC;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body>
<form id="user" name="user" method="post" action="">
<div align="center" class="style1">
  <h1 class="style2">S K Groups</h1>
</div>
<div align="center" class="style3">
  <p>User Creation Form</p>
  <p>&nbsp;</p>
</div>
<p><hr align="center" />&nbsp;</p>
<table width="353" border="0" align="center">
  <tr>
    <td width="154">First Name </td>
    <td width="189">
      <label>
      <input type="text" name="fname" id="fname" accesskey="f" tabindex="1" required pattern="[a-zA-Z]+" title="Only Alphabets are allowed"/>
      </label></td>
  </tr>
  <tr>
    <td>Middle Name </td>
    <td><label>
      <input type="text" name="mname" id="mname" accesskey="m" tabindex="2" pattern="[a-zA-Z]+" title="Only Alphabets are allowed"/>
    </label></td>
  </tr>
  <tr>
    <td>Last Name </td>
    <td><input type="text" name="lname" id="lname" accesskey="l" tabindex="3" required pattern="[a-zA-Z]+" title="Only Alphabets are allowed"/></td>
  </tr>
  <tr>
    <td>Address</td>
    <td><label>
      <textarea name="address" id="address" accesskey="a" tabindex="4" required></textarea>
    </label></td>
  </tr>
  <tr>
    <td>Mobile Number </td>
    <td><input type="text" name="mobile" id="mobile" accesskey="m" tabindex="5" required pattern="[0-9]{10}" title="Type 10 Digit Number"/></td>
  </tr>
  <tr>
    <td>User Type</td>
    <td><label>
      <select name="utype" id="utype" accesskey="t" tabindex="6" required>
	  <option value="" >Select</option>
	  <option value="admin" >Admin</option>
	  <option value="operator">operator</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>Username</td>
    <td><input type="text" name="uname" id="uname" accesskey="u" tabindex="7" required pattern="[a-zA-Z0-9]+"	title="Only Alphabets and numbers are allowed"/></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" name="pwd" id="pwd" accesskey="p" tabindex="8" required /></td>
  </tr>
  <tr>
    <td>Confirm Password </td>
    <td><input type="password" name="cpwd" id="cpwd" accesskey="c" tabindex="9" required /></td>
  </tr>
</table>

  <label>
  <hr align="center" />
  <p>&nbsp;</p>
  <div align="center">
    <input type="submit" name="Add" value="Add" /> 
    <input name="reset" type="reset" value="Reset" />
  </div>
    <p>&nbsp;</p>
</form>
</body>
</html>