<?php
    include '../../connect.php';
    session_start();
    date_default_timezone_set('Asia/Calcutta');
    if ($_SESSION["skguser"]=="" or $_SESSION["skgpass"]==""){
        header("Location: ../../");
        die();
    }
    $db = new connectdb();
    $mysqli = $db->connect();
    $result = $mysqli->query("SELECT * from users where username='".$_SESSION["skguser"]."' and password='".$_SESSION["skgpass"]."'");
    $row = mysqli_fetch_assoc($result);
    $utype = $row["type"];
    $mysqli->close();
    if ($utype != 'admin'){
        echo "<html><head><title>SKG BC Management System</title></head>
		<body><h2>You do not have administrative priviliges!!</h2>
		<br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    }
    $strerror = false;
    $strdescription = "";
    if (isset($_POST["create"]))
	{
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from users where username='".$_POST["uname"]."' and password='".$_POST["pwd"]."'");
      //  if($result)
	//	{
			$count = mysqli_fetch_assoc($result);
			$uid = $count["uid"];
			$mysqli->close();
			if ($uid > 0)
			{
				$strerror = true;
				$strdescription = "User already exist!!";
			} 
			else
			{
				$mysqli = $db->connect();
				if($_POST["fname"])
					$fname = $_POST["fname"];
				if($_POST["mname"])
					$mname=$_POST["mname"];
				if($_POST["lname"])
					$lname=$_POST["lname"];
				if($_POST["address"])
					$address=$_POST["address"];
				if($_POST["mobile"])
					$mobile=$_POST["mobile"];
				if($_POST["utype"])
					$utype=$_POST["utype"];
				if($_POST["uname"])
					$uname=$_POST["uname"];
				if($_POST["pwd"])
					$pwd=$_POST["pwd"];
				if($_POST["cpwd"])
					$cpwd=$_POST["cpwd"];
				if($pwd==$cpwd)
				{
					
					$sql = "INSERT into users (uid,username,password,type,fname,mname,lname,address,mobile) 
						values ('','".$uname."', '".$pwd."', '".$utype."', '".$fname."', '".$mname."', '".$lname."', '".$address."', '".$mobile."')";
					if ($mysqli->query($sql) === true)
					{
						$strerror = false;
						$strdescription =  "User created!!";
					} 
					else 
					{
						$strerror = true;
						$strdescription = "Database Add Error!!";
					}
					$mysqli->close();
				}
				else
				{
					$strerror = true;
					$strdescription = "Password and confirm password are not same!!!";
				}
			} 
	//	}	
    } 
	else if (isset($_POST["remove"]))
	{
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from users where username='".$_POST["uname"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
       // $password = "laPl/yAxc7PEe/lLnDFTiYfC/y6AArGoMdX28eynq2o=";//encrypted string 'password'
        if ($uid < 1)
		{
            $strerror = true;
            $strdescription = "User not found!!";
        } 
		else 
		{
            $mysqli = $db->connect();
            $sql = "DELETE from users where username='".$_POST["uname"]."'";
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
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SKG BC Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        function getUser(str){
            if (str == "new"){
					document.getElementById("fname").value = "";
                    document.getElementById("mname").value = "";
                    document.getElementById("lname").value = "";
					document.getElementById("address").value = "";
					document.getElementById("mobile").value = "";
					document.getElementById("utype").value = "";
					document.getElementById("uname").value = "";
					document.getElementById("pwd").value = "";
					document.getElementById("cpwd").value = "";
                return;
            }
            if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
            // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    var strvalue = xmlhttp.responseText.split(",");
                    document.getElementById("fname").value = strvalue[3];
                    document.getElementById("mname").value = strvalue[4];
                    document.getElementById("lname").value = strvalue[5];
					document.getElementById("address").value = strvalue[6];
					document.getElementById("mobile").value = strvalue[7];
					document.getElementById("utype").value = strvalue[2];
					document.getElementById("uname").value = strvalue[0];
					document.getElementById("pwd").value = strvalue[1];
					document.getElementById("cpwd").value = strvalue[1];
                }
            }
            xmlhttp.open("GET","../../getuser.php?username="+str,true);
            xmlhttp.send();
        }
    </script>

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../">SKG BC Management System</a>
            </div>
            <!-- /.navbar-header -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="../"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
						<li>
                            <a href="../BC/"><i class="fa fa-dashboard fa-fw"></i> Create New BC</a>
                        </li>
						<li>
                            <a href="../BCMembers/"><i class="fa fa-dashboard fa-fw"></i> Create New BC Members</a>
                        </li>
                        <li>
                            <a href="../MapBCMembers/"><i class="fa fa-wrench fa-fw"></i> Add Members to BC</a>
                        </li>
						<li>
                            <a href="../MemPayment/"><i class="fa fa-wrench fa-fw"></i> BC Amount Payment</a>
                        </li>
						<li>
                            <a href="../BCBidding/"><i class="fa fa-wrench fa-fw"></i> BC Bidding</a>
                        </li>
						<li>
                            <a href="../Reports/"><i class="fa fa-bar-chart-o fa-fw"></i>Payment Reports</a>
                        </li>
						<li>
                            <a href="../cbreceipts/"><i class="fa fa-bar-chart-o fa-fw"></i>Collection Boy Receipts</a>
                        </li>
					<!--	<li>
                            <a href="../admins/Duereport/"><i class="fa fa-bar-chart-o fa-fw"></i> Payment Due Report</a>
                        </li>	-->
						<li>
                            <a href="../payslip/"><i class="fa fa-bar-chart-o fa-fw"></i> Payment Slip</a>
                        </li>
						<li>
                            <a href="../Duereport/"><i class="fa fa-bar-chart-o fa-fw"></i> Payment Due Report</a>
                        </li>
                        <li>
                            <a href="../users/"><i class="fa fa-users fa-fw"></i> Manage Users</a>
                        </li>
                                               
                       
                        <li>
                            <a href="../"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> Manage Users</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <?php
                    if ($strerror == true && $strdescription!=""){
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 style="color:#C9302C;"><?=$strdescription?></h4>
                </div>        
                <?php
                    } else if ($strerror == false && $strdescription!=""){
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 style="color:#449D44;"><?=$strdescription?></h4>
                </div>        
                <?php
                    }
                ?>
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <form role="form" method="post" action="index.php">
                        <fieldset>
                            <div class="form-group">
                                <select class="form-control" name="selectuser" autofocus required onchange="getUser(this.value)">
                                    <option selected value="new">New User</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from users");
                                        while($row = mysqli_fetch_assoc($result)){
                                            $utype = $row["type"];
                                            if ($utype != 'admin'){
                                    ?>
                                    <option value="<?=$row['username']?>"><?=$row["username"]?></option>
                                    <?php
                                            }
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
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

                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                <center>
                                <button type="submit" name="create" value="create" class="btn btn-lg btn-success" style="margin-top: 20px;">Create User</button> 
								
								<button type="submit" name="remove" value="remove"  style="margin-top: 20px;position:absolute;right:-400px;">Remove User</button>
                                </center>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../../jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>

</body>

</html>
