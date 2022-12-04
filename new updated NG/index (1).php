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
        echo "<html><head><title>IoT Home Automation Server></title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    }
    $strerror = false;
    $strdescription = "";
	if(isset($_REQUEST["bcid"]))
		$bcid = $_REQUEST["bcid"];
	else
		$bcid = '';
	if(isset($_REQUEST["memid"]))
		$gmemid = $_REQUEST["memid"];
	else
		$gmemid = '';
	if(isset($_REQUEST["bcdate"]))
		$gbcdate = $_REQUEST["bcdate"];
	else
		$gbcdate = '';
	if(isset($_REQUEST["spdate"]))
		$gspdate = $_REQUEST["spdate"];
	else
		$gspdate = '';
    if (isset($_POST["payment"]))
	{
			$mysqli = $db->connect();
			if($_POST["selectbc"]!="BC" and $_POST["selectmem"]!="member")
			{
				$bcid=$_POST["selectbc"];
				if(isset($_POST["selectmem"]))
					$memid=$_POST["selectmem"];
				if(isset($_POST["bcdate"]))
					$bcdate=$_POST["bcdate"];
				if(isset($_POST["memamount"]))
					$memamount=$_POST["memamount"];
				if(isset($_POST["balamount"]))
					$balamount=$_POST["balamount"];
				if(isset($_POST["amountdate"]))
					$amountdate=$_POST["amountdate"];
				$user = $_SESSION["skguser"];
				$date = date("Y-m-d");
				$sql = "INSERT into members_payment_details (pid,bcid,memid,dateofbc,amountpaid,balance,paiddate,user,lastmoddate) 
									values ('','".$bcid."', '".$memid."','".$bcdate."','".$memamount."','".$balamount."','".$amountdate."','".$user."', '".$date."')";
				if ($mysqli->query($sql) === TRUE)
				{
					$strerror = false;
					$strdescription = "$strdescription" . "<br/>". "Payment Details added to Member ID <b>$memid</b> for BC ID <b>$bcid</b>!!";
				} 
				else 
				{
					$strerror = true;
					$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error!!" . mysqli_error($mysqli);
				}
			}
			$mysqli->close();
    }
	else if (isset($_POST["remove"]))
	{
        $mysqli = $db->connect();
			if($_POST["selectbc"]!="map")
			{
				$bcid=$_POST["selectbc"];
				$user = $_SESSION["skguser"];
				$date = date("Y-m-d");
				$activity="Delete a member from BC";
				$memsql = "select memid from members_details";
				$memresult = $mysqli->query($memsql);
				while($row = mysqli_fetch_assoc($memresult))
				{
					$memid = $row["memid"];
					if(isset($_POST[$memid]))
					{
						$mapsql = "select count(*) as c from bc_member_mapping where bcid='".$bcid."' and memid='".$memid."' ";
						$mapresult = $mysqli->query($mapsql);
						$count = mysqli_fetch_assoc($mapresult);
						//echo $count["c"];
						if($count["c"]==0)
						{
							$strerror = false;
							$strdescription = "$strdescription" . "<br/>"." Member ID <b>$memid</b> Doesn't exist with BC ID <b>$bcid</b>!!";
						} 
						else 
						{
							$sql = "delete from bc_member_mapping where bcid='".$bcid."' and memid='".$memid."' ";
							if ($mysqli->query($sql) === TRUE)
							{
								$strerror = false;
								$strdescription = "$strdescription" . "<br/>". "Member ID <b>$memid</b> is DELETED From BC ID <b>$bcid</b>!!";
							} 
							else 
							{
								$strerror = true;
								$strdescription = "$strdescription" . "<br/>". "Database Remove/Delete Error!!" . mysqli_error($mysqli);
							}
						}
						
					}
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
		function balance(){
			var amount_to_paid = document.getElementById("amount_to_paid").value;
			var memamount = document.getElementById("memamount").value;
			var balamount = amount_to_paid - memamount;
			document.getElementById("balamount").value = balamount;
			document.getElementById("balamount").readonly = true;
		}
		function selectdate(){
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			var bcdate = document.getElementById("bcdate").value;
			var spdate = document.getElementById("spdate").value;
			url         = "http://localhost/SKG/admins/Reports/index.php?bcid=" + bcid + "&memid=" + memid + "&bcdate=" + bcdate + "&spdate=" + spdate;
			document.location = url;
		}
		function selectspdate(){
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			var bcdate = document.getElementById("bcdate").value;
			var spdate = document.getElementById("spdate").value;
			url         = "http://localhost/SKG/admins/Reports/index.php?bcid=" + bcid + "&memid=" + memid + "&bcdate=" + bcdate + "&spdate=" + spdate;
			document.location = url;
		}
        function getbc(str){
          //  if (str == "BC"){
				var bcid = document.getElementById("selectbc").value;
				var memid = document.getElementById("selectmem").value;
				var bcdate = document.getElementById("bcdate").value;
				var spdate = document.getElementById("spdate").value;
				url         = "http://localhost/SKG/admins/Reports/index.php?bcid=" + bcid + "&memid=" + memid + "&bcdate=" + bcdate + "&spdate=" + spdate;
				document.location = url;
                
            //}
		/*	else
			{
				url         = "http://localhost/SKG/admins/Reports/index.php?bcid=" + str;
				document.location = url;
			} */	
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
                    document.getElementById("bcid").value = strvalue[0];
					
                }
            }
            xmlhttp.open("GET","../../getbcid.php?bcid="+str,true);
            xmlhttp.send();		
        }
		function getmem(str){
          //  if (str == "mem"){
				var bcid = document.getElementById("selectbc").value;
				var memid = document.getElementById("selectmem").value;
				var bcdate = document.getElementById("bcdate").value;
				var spdate = document.getElementById("spdate").value;
				url         = "http://localhost/SKG/admins/Reports/index.php?bcid=" + bcid + "&memid=" + memid + "&bcdate=" + bcdate + "&spdate=" + spdate;
                document.location=url;
          //  }
		//	else
		/*	{
				var bcid = document.getElementById("selectbc").value;
				var memid = document.getElementById("selectmem").value;
				var bcdate = document.getElementById("bcdate").value;
				var spdate = document.getElementById("spdate").value;
			//	alert(bcid);
				url         = "http://localhost/SKG/admins/Reports/index.php?bcid=" + bcid + "&bcdate=" + bcdate + "&memid=" + str;
				document.location = url;
			} */	
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
                            <a href="../Reports/"><i class="fa fa-bar-chart-o fa-fw"></i> Reports</a>
                        </li>
                        <li>
                            <a href="../users/"><i class="fa fa-users fa-fw"></i> Users</a>
                        </li>
                        <li>
                            <a href="../settings"><i class="fa fa-wrench fa-fw"></i> Settings</a>
                        </li>
                        <li>
                            <a href="../logs/"><i class="fa fa-book fa-fw"></i> System Logs</a>
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> View Payment Details By BC Wise / Member Wise / BC and Member Wise / Date of BC Wise / On Specific Date </h1>
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
								
                                <select class="form-control" name="selectbc" id="selectbc" autofocus required tabindex=1 onchange="getbc(this.value)">
								<option value="BC">Select BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details");
										
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"];
                                            
                                    ?>
                                    <option value="<?=$row['bcid']?>" <?php if($bcid==$row['bcid']) echo "selected"; else echo '';?>><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
							<div class="form-group">
                               <?php //echo $current_bc;?>
								<select class="form-control" name="selectmem" id="selectmem" required tabindex=2 onchange="getmem(this.value)">
								<option value="mem">Select Member</option>
                                    <?php
                                        $mysqli = $db->connect();
										if($bcid!="BC" and $bcid!="")
										{	
                                        $mapresult = $mysqli->query("SELECT memid
																  from bc_member_mapping
																  where bcid='".$bcid."'
																  ");
                                        while($row = mysqli_fetch_assoc($mapresult))
										{
                                            $memid = $row["memid"];
										//	echo "$memid";
											$memresult = $mysqli->query("SELECT fname,mname,lname
																  from members_details
																  where memid='".$memid."'
																  ");
																
											$memrow = mysqli_fetch_assoc($memresult);
											$mem = "$memid" . "->" .$memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
                                            
                                    ?>
                                    <option value="<?=$memid?>" <?php if($gmemid==$row['memid']) { echo "selected"; $current_mem = $memid;} else echo '';?>><?=$mem?></option>
                                    <?php
                                        }
										}
										else if($bcid=="BC" or $bcid=="")
										{
											$memresult = $mysqli->query("SELECT memid,fname,mname,lname
																  from members_details");
																
											while($memrow = mysqli_fetch_assoc($memresult))
											{
												$memid = $memrow["memid"];
												$mem = $memrow["memid"] . "->" .$memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
                                            
                                    ?>
                                    <option value="<?=$memid?>" <?php if($gmemid==$memid) { echo "selected"; $current_mem = $memid;} else echo '';?>><?=$mem?></option>
                                    <?php
                                        }
										}
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
							<div class="form-group">
								<table width="417" border="0" align="center">
								<tr>
								<th><label>Select Date of BC</label></th>
								<td><input type="date" name="bcdate" id="bcdate" value="<?php if(isset($_GET['bcdate'])) echo $_GET['bcdate']; else echo '';?>" required tabindex=3 onchange="selectdate(this.value)"></input></td>
								</tr>
								<tr />
								</table>
							</div>
							<div class="form-group">
								<table width="417" border="0" align="center">
								<tr>
								<th><label>Select Specific Date for which you want to see payment details</label></th>
								<td><input type="date" name="spdate" id="spdate" value="<?php if(isset($_GET['spdate'])) echo $_GET['spdate']; else echo '';?>" required tabindex=4 onchange="selectspdate(this.value)"></input></td>
								</tr>
								<tr />
								</table>
							</div>
                            <div class="form-group">
								<?php
									$mysqli = $db->connect();
									$query = "select bcid,memid,dateofbc,amountpaid,balance,paiddate,remarks 
											  from members_payment_details ";
									if(isset($_GET['bcid']))
										$bcid=$_GET['bcid'];
									else
										$bcid='';
								//	echo "bcid = $bcid";
									
									
									if(isset($_GET['memid']))
										$memid=$_GET['memid'];
									else
										$memid='';
									
									
									if(isset($_GET['bcdate']))
										$bcdate=$_GET['bcdate'];
									else
										$bcdate='';
									
									
									if(isset($_GET['spdate']))
										$spdate=$_GET['spdate'];
									else
										$spdate='';
									
											  
									if($bcid!="BC" and $bcid!="")
										$query = $query . " where bcid='".$bcid."' and";
									else
										$query = $query . " where";
									if($memid!="mem" and $memid!="")
										$query = $query . " memid='".$memid."' and";
									else
										$query = $query . "";
									if($bcdate!="")
										$query = $query . " dateofbc = '".$bcdate."' and";
									if($spdate!="")
										$query = $query . " paiddate='".$spdate."' and";
									$query = substr($query,0,-3);
									$query = $query . " order by bcid, memid";
							//		echo "$query";
									$result = $mysqli->query($query);
									if($result)
									{
									$sl =0;
								?>
								<table width="900" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
									<th>BCID</th>
									<th>Name of Member</th>
									<th>Date of BC</th>
									<th>Amount Paid</th>
									<th>Balance</th>
									<th>Paid Date</th>
									<th>Remarks</th>
								</tr>
								<?php
									while($row = mysqli_fetch_assoc($result))
									{
										$sl++;
										$memid = $row['memid'];
										$query1 = "select fname,mname,lname 
											  from members_details
											  where memid='".$memid."'
											  ";
										$result1 = $mysqli->query($query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
								?>
								<tr>
									<th><?php echo $sl;?></th>
									<th><?php $resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$row['bcid']."'");
											  $bcd = mysqli_fetch_assoc($resultbcd);
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											  echo $bc;
										?></th>
									<th><?php echo $memname;?></th>
									<th><?php echo $row["dateofbc"];?></th>
									<th><?php echo $row['amountpaid'];?></th>
									<th><?php echo $row['balance'];?></th>
									<th><?php echo $row['paiddate'];?></th>
									<th><?php echo $row['remarks'];?></th>
								</tr>
								<?php	
									}
									}
									
								?>
								
                            </div>
							
                        </fieldset>
						
						
						
                    </form>
					<form action="export.php" method="get">
					  <input type="submit" value="Export to Excel">
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
