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
        $result = $mysqli->query("SELECT count(*) as uid from bc_details where bcid='".$_POST["selectbc"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
	//	echo $uid;
        $mysqli->close();
        if ($uid > 0){
            $strerror = true;
            $strdescription = "BC already exist!!";
        } 
		else {
            $mysqli = $db->connect();
			if($_POST["bctype"])
				$bctype = $_POST["bctype"];		
			if($_POST["startdate"])
				$startdate=$_POST["startdate"];
			if($_POST["bcmembers"])
				$bcmembers=$_POST["bcmembers"];
			if($_POST["amount"])
				$amount=$_POST["amount"];
			if($_POST["bc_name"])
				$bc_name=$_POST["bc_name"];
			$totalbcamount = $amount * $bcmembers;
			$user = $_SESSION["skguser"];
			$date = date("Y-m-d");
            $sql = "INSERT into bc_details (type,startdate,bcmembers,amount,totalbcamount,user,lastmoddate,bc_name) 
					values ('".$bctype."', '".$startdate."', '".$bcmembers."', '".$amount."', '".$totalbcamount."', '".$user."', '".$date."','".$bc_name."')";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "New BC created!!";
            } else {
                $strerror = true;
                $strdescription = "Database Add Error!!";
            }
            $mysqli->close();
        } 
    } 
	else if (isset($_POST["update"])){
		$mysqli = $db->connect();
		
		$bidres = $mysqli->query("select count(*) as num1 from bc_bidding_details where bcid='".$_POST["selectbc"]."'");
		$r1 = mysqli_fetch_assoc($bidres);
		$num1 = $r1['num1'];
		$payres = $mysqli->query("select count(*) as num2 from members_payment_details where bcid='".$_POST["selectbc"]."'");
		$r2 = mysqli_fetch_assoc($payres);
		$num2 = $r2['num2'];
		$mapres = $mysqli->query("select count(*) as num3 from bc_member_mapping where bcid='".$_POST["selectbc"]."'");
		$r3 = mysqli_fetch_assoc($payres);
		$num3 = $r3['num3'];
		//	echo "pay  count = " . $num2;
		if($num1==0 and $num2==0 and $num3==0)
		{
			$result = $mysqli->query("SELECT count(*) as uid from bc_details where bcid='".$_POST["selectbc"]."'");
			$count = mysqli_fetch_assoc($result);
			$uid = $count["uid"];
		//	echo $uid;
			$mysqli->close();
			if ($_POST["bctype"]){
				$bctype = $_POST["bctype"];
			} 
			if ($_POST["startdate"]){
				$startdate = $_POST["startdate"];
			} 
			if ($_POST["bcmembers"]){
				$bcmembers = $_POST["bcmembers"];
			}
			if ($_POST["amount"]){
				$amount = $_POST["amount"];
			}
			if($_POST["bc_name"])
				$bc_name=$_POST["bc_name"];
			$totalbcamount = $amount * $bcmembers;
			$user = $_SESSION["skguser"];
			$date = date("Y-m-d");
			if ($uid < 1){
				$strerror = true;
				$strdescription = "BC not found!!";
			} else {
				$mysqli = $db->connect();
				$sql = "UPDATE bc_details set 
												bcid='".$_POST["selectbc"]."', 
												type='".$bctype."', 
												startdate='".$startdate."', 
												bcmembers='".$bcmembers."', 
												amount='".$amount."',
												totalbcamount='".$totalbcamount."',
												user ='".$user."',
												lastmoddate='".$date."',
												bc_name = '".$bc_name."'
												where bcid='".$_POST["selectbc"]."'";
				if ($mysqli->query($sql) === TRUE){
					$strerror = false;
					$strdescription =  "BC updated!!";
				} else {
					$strerror = true;
					$strdescription = "Database Update Error!!";
				}
				$mysqli->close();
			}
		}
		else
		{
				$strerror = true;
				$strdescription = "$strdescription" . "<br/>". "Bidding Details, BC-Member Mapping and Payment details are not empty for BC id " . $_POST["selectbc"] . "!!So Cannot update" . mysqli_error($mysqli);
		}
    } 
	else if (isset($_POST["remove"])){
        $mysqli = $db->connect();
        $result = $mysqli->query("SELECT count(*) as uid from bc_details where bcid='".$_POST["selectbc"]."'");
        $count = mysqli_fetch_assoc($result);
        $uid = $count["uid"];
        $mysqli->close();
        if ($uid < 1){
            $strerror = true;
            $strdescription = "BC not found!!";
        } else {
            $mysqli = $db->connect();
            $sql = "DELETE from bc_details where bcid='".$_POST["selectbc"]."'";
            if ($mysqli->query($sql) === TRUE){
                $strerror = false;
                $strdescription =  "BC removed!!";
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
    <script type="text/javascript">
        function getbc(str){
            if (str == "new"){
                document.getElementById("bctype").value = "";
                document.getElementById("startdate").value = "";
                document.getElementById("bcmembers").value = "";
				document.getElementById("amount").value = "";
				document.getElementById("bc_name").value = "";
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
                    document.getElementById("bctype").value = strvalue[0];
                    document.getElementById("startdate").value = strvalue[1];
                    document.getElementById("bcmembers").value = strvalue[2];
					document.getElementById("amount").value = strvalue[3];
					document.getElementById("bc_name").value = strvalue[5];
					
                }
            }
            xmlhttp.open("GET","../../getbcdetails.php?bcid="+str,true);
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
		function filter() {
			var keyword = document.getElementById("search").value;
			var select = document.getElementById("selectbc");
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> Manage BC</h1>
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
                    <form role="form" method="post" action="index.php"  onsubmit="return callconfirm();">
                        <fieldset>
                            <div class="form-group">
                                <input type="text" id="search" placeholder="Type BC details to search" name="search" style="margin: 10px;width: 265px;" onkeyup="filter(this.value)">
                                <select class="form-control" name="selectbc" id="selectbc" required onchange="getbc(this.value)">
                                    <option selected value="new">New BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details  where flag = 'open'");
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"]."_".$row["bc_name"];
                                            
                                    ?>
                                    <option value="<?=$row['bcid']?>"><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <table width="353" border="0" align="center">
  
								  <tr>
									<td>Select Type</td>
									<td><label>
									  <select name="bctype" id="bctype" accesskey="b" tabindex="1" required>
									  <option value="" >Select</option>
									  <option value="Single" >Single</option>
									  <option value="Twice">Twice</option>
									  </select>
									</label></td>
								  </tr>
								  <tr>
									<td>Select Start Date</td>
									<td><input type="date" name="startdate" id="startdate" accesskey="s" required tabindex="2" /></td>
								  </tr>
								  <tr>
									<td>Number of BC Members</td>
									<td><input type="text" name="bcmembers" id="bcmembers" accesskey="m" tabindex="3" pattern="[0-9]+" required /></td>
								  </tr>
								  <tr>
									<td>Amount per BC Member </td>
									<td><input type="text" name="amount" id="amount" accesskey="a" tabindex="4" pattern="[0-9]+" required /></td>
								  </tr>
								  <tr>
									<td>BC Name </td>
									<td><input type="text" name="bc_name" id="bc_name" accesskey="a" tabindex="5" pattern="[0-9A-Za-z ]+" required /></td>
								  </tr>
								</table>

                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                
                                <button type="submit" name="create" value="create" class="btn btn-lg btn-success" style="margin-top: 20px;">Create BC</button> 
								<button type="submit" name="update" id="update" value="update" class="btn btn-lg btn-primary" style="margin-top: 20px;">Update BC</button>
								<button type="submit" name="remove" id="remove" value="remove" style="margin-top: 20px;position:absolute; right:-800px;" >Remove BC</button>
                                
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
