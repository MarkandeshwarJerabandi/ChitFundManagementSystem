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
        echo "<html><head><title>SKG BC Management System</title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    }
    $strerror = false;
    $strdescription = "";
    if (isset($_POST["create"]))
	{
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from members_details where memid='".$_POST["selectmemid"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
	//	echo $uid;
        $mysqli->close();
        if ($uid > 0){
            $strerror = true;
            $strdescription = "BC Member already exist!!";
        } 
		else {
            $mysqli = $db->connect();
			if($_POST["fname"])
				$fname = $_POST["fname"];		
			
			if(isset($_POST["mname"]))
				$mname=$_POST["mname"];
			else
				$mname='';			
			if($_POST["lname"])
				$lname=$_POST["lname"];
		//	echo "$lname ";
			if($_POST["raddress"])
				$raddress=$_POST["raddress"];
		//	echo "$raddress ";
			if($_POST["off_res"])
				$off_res=$_POST["off_res"];
		//	echo "$off_res ";
			if($off_res=="yes")
				$oaddress=$raddress;
			else
			{	if($_POST["oaddress"])
					$oaddress=$_POST["oaddress"];
				else
					$oaddress='';
			}
		//	echo "$oaddress ";
			if($_POST["mobile"])
				$mobile=$_POST["mobile"];
		//	echo "$mobile ";
			if($_POST["landline"])
				$landline=$_POST["landline"];
			else
				$landline=0;
		//	echo "$landline ";
			if($_POST["altmobile"])
				$altmobile=$_POST["altmobile"];
			else
				$altmobile=0;
		//	echo "$altmobile ";
			if($_POST["emailid"])
				$emailid=$_POST["emailid"];
			else
				$emailid='';
			if($_POST["self_others"])
				$type=$_POST["self_others"];
			else
				$type='';
		//	echo "$emailid ";
			$user = $_SESSION["skguser"];
			$date = date("Y-m-d");
            $sql = "INSERT into members_details (memid,fname,mname,lname,radd,oadd,mobile,landline,emailid,altmobile,type,user,lastmoddate) 
					values ('','".$fname."', '".$mname."', '".$lname."', '".$raddress."', '".$oaddress."', '".$mobile."', '".$landline."', '".$emailid."','".$altmobile."','".$type."', '".$user."', '".$date."')";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "New BC Member created!!";
            } else {
                $strerror = true;
                $strdescription = "Database Add Error!!" . mysqli_error($mysqli);
            }
            $mysqli->close();
        } 
    } 
	else if (isset($_POST["update"])){
		$mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from members_details where memid='".$_POST["selectmemid"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
	//	echo $uid;
        $mysqli->close();
        if($_POST["fname"])
				$fname = $_POST["fname"];		
			
			if(isset($_POST["mname"]))
				$mname=$_POST["mname"];
			else
				$mname='';			
			if($_POST["lname"])
				$lname=$_POST["lname"];
		//	echo "$lname ";
			if($_POST["raddress"])
				$raddress=$_POST["raddress"];
		//	echo "$raddress ";
			if($_POST["off_res"])
				$off_res=$_POST["off_res"];
		//	echo "$off_res ";
			if($off_res=="yes")
				$oaddress=$raddress;
			else
			{	if($_POST["oaddress"])
					$oaddress=$_POST["oaddress"];
				else
					$oaddress='';
			}
		//	echo "$oaddress ";
			if($_POST["mobile"])
				$mobile=$_POST["mobile"];
		//	echo "$mobile ";
			if($_POST["landline"])
				$landline=$_POST["landline"];
			else
				$landline=0;
		//	echo "$landline ";
			if($_POST["altmobile"])
				$altmobile=$_POST["altmobile"];
			else
				$altmobile=0;
		//	echo "$altmobile ";
			if($_POST["emailid"])
				$emailid=$_POST["emailid"];
			else
				$emailid='';
			if($_POST["self_others"])
				$type=$_POST["self_others"];
			else
				$type='';
		//	echo "$emailid ";
			$user = $_SESSION["skguser"];
			$date = date("Y-m-d");
        if ($uid < 1){
            $strerror = true;
            $strdescription = "BC Member not found!!";
        } else {
            $mysqli = $db->connect();
            $sql = "UPDATE members_details set 
											memid='".$_POST["selectmemid"]."', 
											fname='".$fname."',
											mname='".$mname."',
											lname='".$lname."',
											radd='".$raddress."',
											oadd='".$oaddress."',
											mobile='".$mobile."',
											landline='".$landline."',
											emailid='".$emailid."',
											altmobile='".$altmobile."',
											type='".$type."',
											user ='".$user."',
											lastmoddate='".$date."'
											where memid='".$_POST["selectmemid"]."'";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "BC Member updated!!";
            } else {
                $strerror = true;
                $strdescription = "Database Update Error!!";
            }
            $mysqli->close();
        }
    } 
	else if (isset($_POST["remove"])){
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from members_details where memid='".$_POST["selectmemid"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
        if ($uid < 1){
            $strerror = true;
            $strdescription = "BC Member not found!!";
        } else {
            $mysqli = $db->connect();
            $sql = "DELETE from members_details where memid='".$_POST["selectmemid"]."'";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "BC Member removed!!";
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
<style>
	.dropbtn {
		background-color: #4CAF50;
		color: white;
		padding: 16px;
		font-size: 16px;
		border: none;
		cursor: pointer;
	}

	.dropbtn:hover, .dropbtn:focus {
		background-color: #3e8e41;
	}

	#myInput {
		border-box: box-sizing;
		background-image: url('searchicon.png');
		background-position: 14px 12px;
		background-repeat: no-repeat;
		font-size: 16px;
		padding: 14px 20px 12px 45px;
		border: none;
		border-bottom: 1px solid #ddd;
	}

	#myInput:focus {outline: 3px solid #ddd;}

	.dropdown {
		position: relative;
		display: inline-block;
	}

	.dropdown-content {
		display: none;
		position: absolute;
		background-color: #f6f6f6;
		min-width: 230px;
		overflow: auto;
		border: 1px solid #ddd;
		z-index: 1;
	}

	.dropdown-content a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
	}

	.dropdown a:hover {background-color: #ddd}

	.show {display:block;}
</style>

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
	
		function myFunction() {
			document.getElementById("myDropdown").classList.toggle("show");
		}

		function filterFunction() {
			var input, filter, ul, li, a, i;
			input = document.getElementById("myInput");
			filter = input.value.toUpperCase();
			div = document.getElementById("myDropdown");
			a = div.getElementsByTagName("a");
			for (i = 0; i < a.length; i++) {
				if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
					a[i].style.display = "";
				} else {
					a[i].style.display = "none";
				}
			}
		}
        function getbcmember(str){
            if (str == "new"){
                document.getElementById("fname").value = "";
                document.getElementById("mname").value = "";
                document.getElementById("lname").value = "";
				document.getElementById("raddress").value = "";
				document.getElementById("oaddress").value = "";
                document.getElementById("mobile").value = "";
                document.getElementById("landline").value = "";
				document.getElementById("altmobile").value = "";
				document.getElementById("emailid").value = "";
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
                    document.getElementById("fname").value = strvalue[0];
					document.getElementById("mname").value = strvalue[1];
					document.getElementById("lname").value = strvalue[2];
					document.getElementById("raddress").value = strvalue[3];
					document.getElementById("oaddress").value = strvalue[4];
					document.getElementById("mobile").value = strvalue[5];
					document.getElementById("landline").value = strvalue[6];
					document.getElementById("altmobile").value = strvalue[8];
					document.getElementById("emailid").value = strvalue[7];
					document.getElementById("self_others").checked=false;
					
					
                }
            }
            xmlhttp.open("GET","../../getbcmemdetails.php?memid="+str,true);
            xmlhttp.send();
        }
		function callconfirm()
		{
		//	var d = document.getElementById("remove").value;
			var d1 = confirm("Are you sure you want to Create/Update/Delete? Click Yes to Create/Update/Delete!!!!");
			if(d1==true)
			{
				var d2 = confirm("Are you sure you want to Create/Update/Delete? Click Yes to Create/Update/Delete!!!!");
				if(d2==true)
					return true;
				else
					return false;
			}
			else
				return false;
		}
		function search(e)
		{
			var keynum;

			if(window.event) { // IE                    
			  keynum = e.keyCode;
			} else if(e.which){ // Netscape/Firefox/Opera                   
			  keynum = e.which;
			}

		//	alert(String.fromCharCode(keynum));
		}
		function filter() {
			var keyword = document.getElementById("search").value;
			var select = document.getElementById("selectmemid");
			for (var i = 0; i < select.length; i++) {
				var txt = select.options[i].text;
				var key = new RegExp(keyword.toLowerCase());
				if (key.test(txt.toLowerCase())) 
				{
				  $(select.options[i]).removeAttr('disabled').show();
				} else {
				  $(select.options[i]).attr('disabled', 'disabled').hide();
				}
			}
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
                            <a href="../BCMembers/"><i class="fa fa-dashboard fa-fw"></i> Create New BC Member</a>
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
                        <li>
                            <a href="../payslip/"><i class="fa fa-bar-chart-o fa-fw"></i> Payment Slip</a>
                        </li>
						<li>
                            <a href="../Duereport/"><i class="fa fa-bar-chart-o fa-fw"></i> Payment Due Report</a>
                        </li>
                        <li>
                            <a href="../../"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> Manage BC Members</h1>
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
                    <form role="form" method="post" action="index.php" onsubmit="return callconfirm();">
                        <fieldset>
                           <div class="form-group">
							 <input type="text" id="search" placeholder="Type Member Name to search" name="search" style="margin: 10px;width: 265px;" onkeyup="filter(this.value)">
							  <select class="form-control" name="selectmemid" id="selectmemid" required onchange="getbcmember(this.value)" onkeypress="search(this.value)">
                                    <option selected value="new">New BC Member</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from members_details");
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["memid"]."_".$row["fname"]." ".$row["mname"]." ".$row["lname"];
                                            
                                    ?>
                                    <option value="<?=$row['memid']?>"><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
							</div>
					<!--		<div class="dropdown">
								<button onclick="myFunction()" class="dropbtn">Dropdown</button>
								<div id="myDropdown" class="dropdown-content">
								<input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
									<a href="#about">About</a>
									<a href="#base">Base</a>
									<a href="#blog">Blog</a>
									<a href="#contact">Contact</a>
									<a href="#custom">Custom</a>
									<a href="#support">Support</a>
									<a href="#tools">Tools</a>
								 </div>
							</div>
					-->
                            <div class="form-group">
                                <table width="417" border="0" align="center">
								  <tr>
									<td width="226">First Name </td>
									<td width="181">
									  <label>
									  <input type="text" name="fname" id="fname" accesskey="f" pattern="[a-zA-Z]+[0-9]*" tabindex="1" required value="" />
									  </label></td>
								  </tr>
								  <tr>
									<td>Middle Name </td>
									<td><label>
									  <input type="text" name="mname" id="mname" accesskey="m" pattern="[a-zA-Z]+" tabindex="2" />
									</label></td>
								  </tr>
								  <tr>
									<td>Last Name </td>
									<td><input type="text" name="lname" id="lname" accesskey="l" tabindex="3" pattern="[a-zA-Z]+" required/></td>
								  </tr>
								  <tr>
									<td>Residential Address</td>
									<td><label>
									  <textarea name="raddress" id="raddress" accesskey="r" tabindex="4" required ></textarea>
									</label></td>
								  </tr>
								  <tr>
									<td>Is Office Address is same as Residential Address?</td>
									<td><label>
									<input name="off_res" id="off_res" type="radio" value="yes" tabindex="5" checked/>
									Yes 
									<input name="off_res" id="off_res" type="radio" value="no" tabindex="6"/>
									NO</label></td>
								  </tr>
								  <tr>
									<td>If NO, Office Address</td>
									<td><label>
									  <textarea name="oaddress" id="oaddress" accesskey="o" tabindex="7"></textarea>
									</label></td>
								  </tr>
								  <tr>
									<td>Mobile Number </td>
									<td><input type="mobile" name="mobile" id="mobile" accesskey="m" tabindex="8" required pattern="[0-9]{10}" /></td>
								  </tr>
								  <tr>
									<td>Land Line</td>
									<td><input type="text" name="landline" id="landline" accesskey="l" tabindex="9" pattern="[0-9]{11}"/></td>
								  </tr>
								  <tr>
									<td>Alternate Contact Number</td>
									<td><input type="mobile" name="altmobile" id="altmobile" accesskey="a" tabindex="10" pattern="[0-9]{10}"/></td>
								  </tr>
								  <tr>
									<td>Email ID </td>
									<td><input type="email" name="emailid" id="emailid" accesskey="e" tabindex="11" /></td>
								  </tr>
								  <tr>
									<td>Is this Self (Company) Account? or others Account? </td>
									<td><label>
									<input name="self_others" id="self_others" type="radio" value="self" tabindex="12" />
									Self 
									<input name="self_others" id="self_others" type="radio" value="Others" tabindex="13" checked/>
									Others</label></td>
								  </tr>
								</table>

                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                <center>
                                <button type="submit" name="create" value="create" class="btn btn-lg btn-success" style="margin-top: 20px;">Create BC Member</button> 
								<button type="submit" name="update" value="update" class="btn btn-lg btn-primary" style="margin-top: 20px;">Update BC Member</button>
								<button type="submit" name="remove" value="remove"  style="margin-top: 20px;position:absolute;right:-800px;">Remove BC Member</button>
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
