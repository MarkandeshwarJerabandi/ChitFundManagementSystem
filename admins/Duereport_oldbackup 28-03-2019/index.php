<?php
    include '../../connect.php';
    session_start();
    date_default_timezone_set('Asia/Calcutta');
    if ($_SESSION["skguser"]=="" or $_SESSION["skgpass"]==""){
        header("Location: ../../");
        die();
    }
	$flag=0;
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
	$bcid="";
	$gmemid="";
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
			
			url         = "http://localhost/SKG/admins/Duereport/index.php?bcid=" + bcid + "&memid=" + memid ;
			document.location = url;
		}
		function selectspdate(){
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			
			url         = "http://localhost/SKG/admins/Duereport/index.php?bcid=" + bcid + "&memid=" + memid;
			document.location = url;
		}
        function getbc(str){
          //  if (str == "BC"){
				var bcid = document.getElementById("selectbc").value;
				var memid = document.getElementById("selectmem").value;
				url         = "http://localhost/SKG/admins/Duereport/index.php?bcid=" + bcid + "&memid=" + memid;
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
				
				url         = "http://localhost/SKG/admins/Duereport/index.php?bcid=" + bcid + "&memid=" + memid;
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
		function exportdata()
		{
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			
			url         = "http://localhost/SKG/admins/Duereport/export.php?bcid=" + bcid + "&memid=" + memid;
			document.location=url;
		}
		function printdata()
		{
			
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			
			url         = "http://localhost/SKG/admins/Duereport/print.php?bcid=" + bcid + "&memid=" + memid;
			document.location=url;
		}
		function exportdata1()
		{
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			
			url         = "http://localhost/SKG/admins/Duereport/export1.php?bcid=" + bcid + "&memid=" + memid;
			document.location=url;
		}
		function printdata1()
		{
			var bcid = document.getElementById("selectbc").value;
			var memid = document.getElementById("selectmem").value;
			
			url         = "http://localhost/SKG/admins/Duereport/print1.php?bcid=" + bcid + "&memid=" + memid;
			document.location=url;
		}
		function filter() {
			var keyword = document.getElementById("search").value;
			var select = document.getElementById("selectmem");
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> View Payment Paid and Due Details By BC Wise / BC and Member Wise </h1>
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
							   <input type="text" id="search" placeholder="Type Member Name to search" name="search" style="margin: 10px;width: 265px;" onkeyup="filter(this.value)">
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
								<?php
									$mysqli = $db->connect();
									if(isset($_REQUEST['bcid']))
										$bcid=$_REQUEST['bcid'];
									else
										$bcid='';
								//	echo "bcid = $bcid";
									
									
									if(isset($_REQUEST['memid']))
										$memid=$_REQUEST['memid'];
									else
										$memid='';
									$startdate="";	
									$sql = "select startdate,amount,type from bc_details where bcid ='".$bcid."'";
										$result1 = $mysqli->query($sql);
										$row = mysqli_fetch_assoc($result1);
										$startdate = $row['startdate'];
										$bcamount=$row['amount'];
										$bctype=$row['type'];
										$enddate = date('Y-m-d');
										$currentdate=$enddate;
								/*		$d = date("d",strtotime($enddate));
										if($d<=15)
											$currentdate =	date("Y-m-01",strtotime($enddate));
										else
											$currentdate =	date("Y-m-16",strtotime($enddate));	*/
										$newdate=$startdate;
										$s1=0;
										$flag=0;
										$resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$bcid."'");
										$bcd = mysqli_fetch_assoc($resultbcd);
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
										$bc_values=preg_split("/_/",$bc);
										$noofmembers=$bc_values[3];
								?>
								<input type="button" value="Export Detailed BC Date Wise Report to Excel" onclick="exportdata()">
								<input type="button" value="Print Detailed BC Date Wise Report" onclick="printdata()">
								<nbsp><nbsp><nbsp><nbsp><nbsp><nbsp>
								<input type="button" value="Export Consolidated Due Report to Excel" onclick="exportdata1()">
								<input type="button" value="Print Consolidated Due Report" onclick="printdata1()">
								<div id="printThisTable">
								<table width="900" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
								<!--	<th>BCID</th>	-->
									<th>Name of Member</th>
									<th>Date of BC</th>
									<th>Amount Paid</th>
									<th>Balance</th>
								</tr>
								<?php
									if($bcid!="BC" and $bcid!="" and $memid!="mem" and $memid!="")
									{
										$flag=1;
										$query1 = "select fname,mname,lname 
														  from members_details
														  where memid='".$memid."'
														  ";
										$result1 = $mysqli->query($query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
										$totalbalance=0;
									?>
									<tr>
										<th></th>
									</tr>
									<tr align="center">
										
													<th  colspan="5"><?php echo "BC: " . $bc;?></th>
									</tr>
									<tr>
										<th></th>
									</tr>
									<?php
										while($newdate<=$currentdate and $noofmembers>0)
										{
											$s1++;
											$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
											//$records = mysqli_num_rows($result);
											$row=mysqli_fetch_assoc($result);
									//		echo $row['records'];
											
											if($row['records']>0)
											{
												
										?>
												<tr>
													<th><?php echo $s1;?></th>
											<!--		<th><?php echo $bc;?></th>	-->
													<th><?php echo $memname;?></th>
													<th><?php echo $row["dateofbc"];?></th>
													<th><?php echo $row['amountpaid'];?></th>
													<th><?php $balance=($bcamount-$row['amountpaid']);echo $balance;$totalbalance += $balance;?></th>
												</tr>
										<?php
											}
											else											
											{
								//				echo "entered";
										?>
												<tr>
													<th><?php echo $s1;?></th>
											<!--	<th><?php echo $bc;?></th>	-->
													<th><?php echo $memname;?></th>
													<th><?php echo $newdate;?></th>
													<th><?php echo '0';?></th>
													<th><?php echo $bcamount;$totalbalance += $bcamount;?></th>
												</tr>
										<?php
											}
											if($bc_values[1]=="Single")
							$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
						else
						{
									
										$d = date("d",strtotime($startdate));
										//echo $date . " ";
										switch($d)
										{
											case 1:$startdate = date ("Y-m-15", strtotime($startdate));break;
											case 2:$startdate = date ("Y-m-16", strtotime($startdate));break;
											case 3:$startdate = date ("Y-m-17", strtotime($startdate));break;
											case 4:$startdate = date ("Y-m-18", strtotime($startdate));break;
											case 5:$startdate = date ("Y-m-20", strtotime($startdate));break;
											case 6:$startdate = date ("Y-m-21", strtotime($startdate));break;
											case 7:$startdate = date ("Y-m-22", strtotime($startdate));break;
											case 8:$startdate = date ("Y-m-23", strtotime($startdate));break;
											case 9:$startdate = date ("Y-m-24", strtotime($startdate));break;
											case 10:$startdate = date ("Y-m-25", strtotime($startdate));break;
											case 11:$startdate = date ("Y-m-26", strtotime($startdate));break;
											case 12:$startdate = date ("Y-m-27", strtotime($startdate));break;
											case 13:$startdate = date ("Y-m-28", strtotime($startdate));break;
											case 14:$startdate = date ("Y-m-29", strtotime($startdate));break;
											case 15:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-01", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-01", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-01", strtotime($startdate));
													}
														
													break;	
													
											case 16:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-02", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-02", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-02", strtotime($startdate));
													}
													break;
											case 17:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
											//		echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-03", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-03", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-03", strtotime($startdate));
													}
													break;
											case 18:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-04", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-04", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-04", strtotime($startdate));
													}
													break;
											case 20:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-05", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-05", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-05", strtotime($startdate));
													}
													break;
											case 21:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-06", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-06", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-06", strtotime($startdate));
													}
													break;
											case 22:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-07", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-07", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-07", strtotime($startdate));
													}
													break;
											case 23:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-08", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-08", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-08", strtotime($startdate));
													}
													break;
											case 24:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-09", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-09", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-09", strtotime($startdate));
													}
													break;
											case 25:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-10", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-10", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-10", strtotime($startdate));
													}
													break;
											
										}
									
									}
									$newdate = $startdate;
									$noofmembers--;
										}
										?>
										<tr>
															<th></th>
														</tr>
										<tr>
															<b><th colspan="4">Balance Due</th>
															
															<th><?php echo $totalbalance;?></th></b>
										</tr>
										<tr>
										<th></th>
									</tr>
										<?php
										
									}
									else if($bcid!="BC" and $bcid!="" and $memid=="mem" or $memid=="")
									{
										$flag=1;
									//	echo "here";
										$temp=$startdate;
									//	echo $temp;
										$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
									//	$members = mysqli_num_rows($memres);
									//	echo $members;
										?>
										<tr>
											<th></th>
										</tr>
										<tr>
										
													<th colspan="5"><?php echo "BC:" . $bc;?></th>
										</tr>
										<tr>
										<th></th>
										</tr>
										<?php
										while($members = mysqli_fetch_assoc($memres))
										{
												$startdate=$temp;
												$newdate=$startdate;
												$memid=$members["memid"];
												$noofmembers=$bc_values[3];
											//	echo $newdate;
											//	echo $currentdate;
												$query1 = "select fname,mname,lname 
														  from members_details
														  where memid='".$memid."'
														  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);
												$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
												$totalbalance=0;
												while($newdate<=$currentdate and $noofmembers>0)
												{
													$s1++;
											//		echo $memid;
													$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
													//$records = mysqli_num_rows($result);
													$row=mysqli_fetch_assoc($result);
											//		echo $row['records'];
													
													if($row['records']>0)
													{
														
												?>
												
														<tr>
															<th><?php echo $s1;?></th>
												<!--			<th><?php echo $bc;?></th>	-->
															<th><?php echo $memname;?></th>
															<th><?php echo $row["dateofbc"];?></th>
															<th><?php echo $row['amountpaid'];?></th>
															<th><?php $balance=($bcamount-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													else											
													{
										//				echo "entered";
												?>
														<tr>
															<th><?php echo $s1;?></th>
													<!--	<th><?php echo $bc;?></th>	-->
															<th><?php echo $memname;?></th>
															<th><?php echo $newdate;?></th>
															<th><?php echo '0';?></th>
															<th><?php echo $bcamount;$totalbalance += $bcamount;?></th>
														</tr>
												<?php
													}
													if($bc_values[1]=="Single")
														$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
													else
													{
									
														$d = date("d",strtotime($startdate));
														//echo $date . " ";
														switch($d)
														{
															case 1:$startdate = date ("Y-m-15", strtotime($startdate));break;
															case 2:$startdate = date ("Y-m-16", strtotime($startdate));break;
															case 3:$startdate = date ("Y-m-17", strtotime($startdate));break;
															case 4:$startdate = date ("Y-m-18", strtotime($startdate));break;
															case 5:$startdate = date ("Y-m-20", strtotime($startdate));break;
															case 6:$startdate = date ("Y-m-21", strtotime($startdate));break;
															case 7:$startdate = date ("Y-m-22", strtotime($startdate));break;
															case 8:$startdate = date ("Y-m-23", strtotime($startdate));break;
															case 9:$startdate = date ("Y-m-24", strtotime($startdate));break;
															case 10:$startdate = date ("Y-m-25", strtotime($startdate));break;
															case 11:$startdate = date ("Y-m-26", strtotime($startdate));break;
															case 12:$startdate = date ("Y-m-27", strtotime($startdate));break;
															case 13:$startdate = date ("Y-m-28", strtotime($startdate));break;
															case 14:$startdate = date ("Y-m-29", strtotime($startdate));break;
															case 15:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-01", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-01", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-01", strtotime($startdate));
																	}
																		
																	break;	
																	
															case 16:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-02", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-02", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-02", strtotime($startdate));
																	}
																	break;
															case 17:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
															//		echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-03", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-03", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-03", strtotime($startdate));
																	}
																	break;
															case 18:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-04", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-04", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-04", strtotime($startdate));
																	}
																	break;
															case 20:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-05", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-05", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-05", strtotime($startdate));
																	}
																	break;
															case 21:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-06", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-06", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-06", strtotime($startdate));
																	}
																	break;
															case 22:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-07", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-07", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-07", strtotime($startdate));
																	}
																	break;
															case 23:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-08", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-08", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-08", strtotime($startdate));
																	}
																	break;
															case 24:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-09", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-09", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-09", strtotime($startdate));
																	}
																	break;
															case 25:
																	$mm = date("m",strtotime($startdate))+1;
																	$yy = date("Y",strtotime($startdate));
																//	echo $yy;
																	if($mm>12)
																	{
																		$yy++;
																		$mm=1;
																		$startdate = date ("$yy-0$mm-10", strtotime($startdate));
																	}	
																	else
																	{
																		if($mm<=9)
																			$startdate = date ("Y-0$mm-10", strtotime($startdate));
																		else
																			$startdate = date ("Y-$mm-10", strtotime($startdate));
																	}
																	break;
															
														}
									
													}
													$newdate=$startdate;
												//	echo $newdate;
													$noofmembers--;
												//	echo $noofmembers;
												}
												
												?>
												<tr>
															<th></th>
														</tr>
													<tr>
															<th colspan="4"><b>Balance Due</b></th>
															<th><?php echo $totalbalance;?></th>
													</tr>
													<tr>
													<th></th>
													</tr>
												<?php
										}
									}
									if($flag==0)
									{
									//	echo "$bcid" . "$gmemid";
										$bcres=$mysqli->query("select * from bc_details");
										while($bcd = mysqli_fetch_assoc($bcres))
										{
											$bcid=$bcd['bcid'];
									//		echo $bcid;	
											$temp=$bcd['startdate'];
											$bcamount=$bcd['amount'];
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
										?>
										<tr>
															<th></th>
														</tr>
										<tr>
											<th align="center" colspan="5"><?php echo "BC: " . $bc;?></th>
										</tr>
										<tr>
											<th></th>
										</tr>
										<?php
											while($members = mysqli_fetch_assoc($memres))
											{
													$startdate=$temp;
													$newdate=$startdate;
													$memid=$members["memid"];
													$query1 = "select fname,mname,lname 
															  from members_details
															  where memid='".$memid."'
															  ";
													$result1 = $mysqli->query($query1);
													$row1 = mysqli_fetch_assoc($result1);
													$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
													$totalbalance=0;
													while($newdate<=$currentdate)
													{
														$s1++;
														$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
														//$records = mysqli_num_rows($result);
														$row=mysqli_fetch_assoc($result);
												//		echo $row['records'];
														
														if($row['records']>0)
														{
															
													?>
													
															<tr>
																<th><?php echo $s1;?></th>
														<!--		<th><?php echo $bc;?></th>	-->
																<th><?php echo $memname;?></th>
																<th><?php echo $row["dateofbc"];?></th>
																<th><?php echo $row['amountpaid'];?></th>
																<th><?php $balance=($bcamount-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
															</tr>
													<?php
														}
														else											
														{
											//				echo "entered";
													?>
															<tr>
																<th><?php echo $s1;?></th>
															<!--	<th><?php echo $bc;?></th>	-->
																<th><?php echo $memname;?></th>
																<th><?php echo $newdate;?></th>
																<th><?php echo '0';?></th>
																<th><?php echo $bcamount;$totalbalance += $bcamount;?></th>
															</tr>
													<?php
														}
														if($bcd["type"]=="Single")
							$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
						else
						{
									
										$d = date("d",strtotime($startdate));
										//echo $date . " ";
										switch($d)
										{
											case 1:$startdate = date ("Y-m-15", strtotime($startdate));break;
											case 2:$startdate = date ("Y-m-16", strtotime($startdate));break;
											case 3:$startdate = date ("Y-m-17", strtotime($startdate));break;
											case 4:$startdate = date ("Y-m-18", strtotime($startdate));break;
											case 5:$startdate = date ("Y-m-20", strtotime($startdate));break;
											case 6:$startdate = date ("Y-m-21", strtotime($startdate));break;
											case 7:$startdate = date ("Y-m-22", strtotime($startdate));break;
											case 8:$startdate = date ("Y-m-23", strtotime($startdate));break;
											case 9:$startdate = date ("Y-m-24", strtotime($startdate));break;
											case 10:$startdate = date ("Y-m-25", strtotime($startdate));break;
											case 11:$startdate = date ("Y-m-26", strtotime($startdate));break;
											case 12:$startdate = date ("Y-m-27", strtotime($startdate));break;
											case 13:$startdate = date ("Y-m-28", strtotime($startdate));break;
											case 14:$startdate = date ("Y-m-29", strtotime($startdate));break;
											case 15:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-01", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-01", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-01", strtotime($startdate));
													}
														
													break;	
													
											case 16:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-02", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-02", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-02", strtotime($startdate));
													}
													break;
											case 17:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
											//		echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-03", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-03", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-03", strtotime($startdate));
													}
													break;
											case 18:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-04", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-04", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-04", strtotime($startdate));
													}
													break;
											case 20:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-05", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-05", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-05", strtotime($startdate));
													}
													break;
											case 21:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-06", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-06", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-06", strtotime($startdate));
													}
													break;
											case 22:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-07", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-07", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-07", strtotime($startdate));
													}
													break;
											case 23:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-08", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-08", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-08", strtotime($startdate));
													}
													break;
											case 24:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-09", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-09", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-09", strtotime($startdate));
													}
													break;
											case 25:
													$mm = date("m",strtotime($startdate))+1;
													$yy = date("Y",strtotime($startdate));
												//	echo $yy;
													if($mm>12)
													{
														$yy++;
														$mm=1;
														$startdate = date ("$yy-0$mm-10", strtotime($startdate));
													}	
													else
													{
														if($mm<=9)
															$startdate = date ("Y-0$mm-10", strtotime($startdate));
														else
															$startdate = date ("Y-$mm-10", strtotime($startdate));
													}
													break;
											
										}
									
									}
									$newdate=$startdate;
													}
													?>
														<tr>
															<th></th>
														</tr>
														<tr>
																<th colspan="4"><b>Balance Due</b></th>
																<th><?php echo $totalbalance;?></th>
														</tr>
														<tr>
															<th></th>
														</tr>
													<?php
											}
										}
									}
									?>
								</table>
								</div>	
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
