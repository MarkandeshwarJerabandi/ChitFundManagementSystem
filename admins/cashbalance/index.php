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
	if(isset($_REQUEST["collection"]))
		$collection = $_REQUEST["collection"];
	else
		$collection = '';
	if($collection=="Other")
	{
		if(isset($_REQUEST["cbname"]))
			$cbname = $_REQUEST["cbname"];
		else
			$cbname = '';
	}
	else
		$cbname=$collection;
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
    <script >
		
		function exportdata()
		{
			url         = "http://localhost/SKG/admins/cashbalance/export.php";
			document.location=url;
		}
		
		function printdata()
		{
			var printContents = $("#printThisTable").html();
			$("body").html(printContents);
			var css = '@page { size: landscape; }',
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');

			style.type = 'text/css';
			style.media = 'print';

			if (style.styleSheet){
			  style.styleSheet.cssText = css;
			} else {
			  style.appendChild(document.createTextNode(css));
			}

			head.appendChild(style); 
			window.print();
			url         = "http://localhost/SKG/admins/cashbalance/index.php";
			document.location=url;
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> View Cash Balance of Company </h1>
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
                            
							          <?php
                                        $mysqli = $db->connect();
                                        
                                    ?>
                              
							<div class="form-group">
								<input type="button" value="Export to Excel" onclick="exportdata()">
								<input type="button" value="Print" onclick="printdata()">
								<div id="printThisTable">
									
									
									
								<table width="1100" border="1" align="center">
									<tr>
										
										<th>BCID</th>
										<th>Total Collection From Members</th>
										<th></th>
										<th>Commission Amount</th>
										<th></th>
										<th>Payment to Members Till Date</th>
										<th>Cash in Hand</th>
										<th></th>
										<th>Due Balance From Members</th>
										<th></th>
										<th>Net Cash Balance of Company</th>
										<th></th>
										<th>Advance Cash Payment by Members</th>
										<th>Total Company Balance</th>
									</tr>
									<tr>
										
										<?php 
											$resultbcd = $mysqli->query("SELECT * from bc_details");
											$tcollection = 0;
											$tcompany = 0;
											$tpaymentmade = 0;
											$tcash_in_hand = 0;
											$tduebalance = 0;
											$tnet_cash_balance = 0;
											$tadvanceamount = 0;
											$ttotal = 0;
											while($bcd = mysqli_fetch_assoc($resultbcd))
											{
												$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
										?>
										<th>
										<?php
												echo $bc;
												$bcid = $bcd["bcid"];
											?></th>
										<th>
										<?php
												$query1 = "select sum(amountpaid) as amountpaid 
												  from members_payment_details 
												  where bcid='".$bcid."' AND remarks != 'advance cash payment'
												  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);	
												$amountpaid = $row1['amountpaid'];
												echo $row1['amountpaid'];
												$collection = $row1['amountpaid'];
										?>
										</th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th>
										<?php
												$query1 = "select sum(company) as company, sum(paymentmade) as paymentmade 
												  from bc_bidding_details
												  where bcid='".$bcid."'
												  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);
												$company = $row1['company'];
												$paymentmade = $row1['paymentmade'];
												echo $row1['company'];
										?>
										</th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th>
										<?php
											echo $row1['paymentmade'];
										?>
										</th>
										<th style="text-align:center;color:red; font-size:20px;">
										<?php
											$cash_in_hand = $amountpaid - $company - $paymentmade;
											echo $cash_in_hand;
										?>
										</th>
										
										<th style="color:green;font-size:40px;text-align:center;">+</th>
										<th>
										<?php
											// if(isset($_GET['bcid']))
												// $bcid=$_GET['bcid'];
											// else
												// $bcid='';
											// $bcres=$mysqli->query("select * from bc_details where bcid='".$bcid."'");
											// $bcd = mysqli_fetch_assoc($bcres);
											$temp=$bcd['startdate'];
											$sql = "select startdate,amount,type,bcmembers from bc_details where bcid ='".$bcid."'";
										$result1 = $mysqli->query($sql);
										$row = mysqli_fetch_assoc($result1);
										$startdate = $row['startdate'];
									//	$startdate = date($startdate);
									//	echo $startdate;
										$bcamount=$row['amount'];
										$bctype=$row['type'];
										$total_months = $row['bcmembers'];
										/* retrieve number of extra bidding */
										$esql = "SELECT count(*) as extra FROM `bc_bidding_details` where `bcid` = '".$bcid."' and bidtype='EXTRA'";
										$eresult1 = $mysqli->query($esql);
										$erow = mysqli_fetch_assoc($eresult1);
										$no_of_extra = $erow['extra'];
										
										$no_of_months = $total_months - $no_of_extra;
							
										$enddate = date('Y-m-d');
										$diff = abs(strtotime($enddate) - strtotime($startdate));
										$years = floor($diff / (365*60*60*24));
										$months = ($years*12)+floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
										$diff_of_months = $no_of_months - $months;
									
										if($diff_of_months<=0)
										{
											$diff_of_months--;
											$enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " $diff_of_months month");
											$enddate = date("Y-m-d",$enddate);
										}	
										else
											$enddate = date('Y-m-d');
									//	$currentdate=$enddate;
										$currentdate=$enddate;
											$bcamount=$bcd['amount'];
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
											$bgrandtotal=0;
											$grandtotal=0;
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
													//		$s1++;
															$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
															//$records = mysqli_num_rows($result);
															$row=mysqli_fetch_assoc($result);
													//		echo $row['records'];
															
															if($row['records']>0)
															{
																$balance=($bcamount-$row['amountpaid']); 
																$totalbalance += $balance;
															}
															else											
															{
																$totalbalance += $bcamount;
															}
															if($bcd["type"]=="Single")
																$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
															else
															{
																$d = date("d",strtotime($startdate));
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
														$bgrandtotal += $totalbalance;
											}
											$grandtotal += $bgrandtotal;
											echo $grandtotal;
											$duebalance = $grandtotal;
										?>
										</th>										
										<th style="color:green;text-align:center;font-size:40px;">=</th>
										<th> 
										<?php $net_cash_balance = $cash_in_hand + $grandtotal; echo $net_cash_balance;?>
										</th>
										<th style="color:green;text-align:center;font-size:40px;">+</th>
										<th>
										<?php
												$query1 = "select sum(amountpaid) as amountpaid 
												  from members_payment_details 
												  where bcid='".$bcid."' AND remarks = 'advance cash payment'
												  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);	
												$advanceamount = $row1['amountpaid'];
												echo $advanceamount;
										?>
										</th>
										<th>
											<?php
												$total = $net_cash_balance + $advanceamount;
												echo $total;
											?>
										</th>
									</tr>
									<?php
											$tcollection += $collection;
											$tcompany += $company;
											$tpaymentmade += $paymentmade;
											$tcash_in_hand += $cash_in_hand;
											$tduebalance += $duebalance;
											$tnet_cash_balance += $net_cash_balance;
											$tadvanceamount += $advanceamount;
											$ttotal += $total;
											}
									?>
									<tr>
										<th style="font-size:40px;text-align:center;color:red;">Grand Total</th>
										<th> <?php echo $tcollection;?></th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th> <?php echo $tcompany;?></th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th> <?php echo $tpaymentmade;?></th>
										<th style="font-size:20px;text-align:center;color:red;"> <?php echo $tcash_in_hand;?></th>
										<th style="font-size:40px;text-align:center;color:green;">+</th>
										<th> <?php echo $tduebalance;?></th>
										<th style="font-size:40px;text-align:center;color:green;">=</th>
										<th> <?php echo $tnet_cash_balance;?></th>
										<th style="font-size:40px;text-align:center;color:green;">+</th>
										<th> <?php echo $tadvanceamount;?></th>
										
										<th> <?php echo $ttotal;?></th>
									</tr>
								</table>
								
								</div>
							</div>
							<?php
							$mysqli->close();
							?>
							
                        </fieldset>
						
                    </form>
					</div>
					
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->


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
