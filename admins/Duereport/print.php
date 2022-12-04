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
	$strdescription="";										  
	
/*			  
$filename = "Payment_Due_Details_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
//$sep = "\t"; //tabbed character

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
function printdata()
		{
			var printContents = $("#printThisTable").html();
			$("body").html(printContents);
		/*	var css = '@page { size: portrait; }',
			head = document.head || document.getElementsByTagName('head')[0],
			style = document.createElement('style');

			style.type = 'text/css';
			style.media = 'print';

			if (style.styleSheet){
			  style.styleSheet.cssText = css;
			} else {
			  style.appendChild(document.createTextNode(css));
			}

			head.appendChild(style); */
			window.print();
			url         = "http://localhost/SKG/admins/Duereport/index.php";
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
                    <form role="form" method="post" action="">
                        <fieldset>
                            <div class="form-group">
							<input type="button" value="Print" onclick="printdata()"></input>


<?php
echo "<p align=\"center\"><b>SKG BC Payment Due Details</b></p>";
echo "<p align=\"right\"><b>Date:</b> " . date('d-m-Y') . "</p>";
									$mysqli = $db->connect();
									if(isset($_GET['bcid']))
										$bcid=$_GET['bcid'];
									else
										$bcid='';
								//	echo "bcid = $bcid";
									
									
									if(isset($_GET['memid']))
										$memid=$_GET['memid'];
									else
										$memid='';
									$startdate="";	
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
										$currentdate=$enddate;
										$newdate=$startdate;
										$s1=0;
										$flag=0;
										$resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$bcid."'");
										$bcd = mysqli_fetch_assoc($resultbcd);
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
										$bc_values=preg_split("/_/",$bc);
										$noofmembers=$bc_values[3];
								?>
								<table width="900" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
								<!--	<th>BCID</th>	-->
									<th>Name of Member</th>
									<th>Number of BC till Today</th>
									<th>Total Amount to Be Paid</th>
									<th>Amount Paid Till Today</th>
									<th>Balance</th>
								</tr>
								<?php
									$grandtotal=0;
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
										
										<th  colspan="6" style="color:red;"><?php echo "BC: " . $bc;?></th>
									</tr>
									<tr>
										<th></th>
									</tr>
									<?php
										$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$bcid."'");
										$noofbids = mysqli_num_rows($result2);
										$result = $mysqli->query("select bcid, memid, sum(amountpaid) as amountpaid,count(*) as records 
													from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
											//$records = mysqli_num_rows($result);
										$row=mysqli_fetch_assoc($result);
										$totalamounttobepaid = $noofbids * $bc_values[4];
										$s1++;
									?>
										<tr>
													<th><?php echo $s1;?></th>
													<th><?php echo $memname;?></th>
													<th><?php echo $noofbids;?></th>
													
									<?php
									/*	while($bcdates = mysqli_fetch_array($result2))
										{
											$newdate = $bcdates["bcdate"];
											
										*/	
									//		echo $row['records'];
										?>
											<!--			<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	-->
											<!--		<th><?php //echo $newdate;?></th>	-->
										<?php
									//	}
										if($row['records']>0)
										{
												
										?>	
											<!--		<th><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	-->
													
													<th><?php echo $totalamounttobepaid;?></th>
													<th><?php echo $row['amountpaid'];?></th>
													<th><?php $balance=($totalamounttobepaid-$row['amountpaid']);echo $balance;$totalbalance += $balance;?></th>
												</tr>
										<?php
										}
										else
											{
								//				echo "entered";
										?>
											<!--		<th><?php // if($noofbids==0) echo "Still Bidding not done";?></th>	-->
													<th><?php echo $totalamounttobepaid;?></th>
													<th><?php echo '0';?></th>
													<th><?php $balance=($totalamounttobepaid-$row['amountpaid']);echo $balance;$totalbalance += $balance;?></th>
												</tr>
										<?php
											}	
											?>
										<tr>
														<th></th>
										</tr>
										<tr>
															<b><th colspan="5">Balance Due</th>
															<th style="color:red;"><?php echo $totalbalance;?></th></b>
										</tr>
										<tr>
										<th></th>
										</tr>
										<?php
										$grandtotal += $totalbalance;
									}
									else if($bcid!="BC" and $bcid!="" and $memid=="mem" or $memid=="")
									{
									//	$bc_values=preg_split("/_/",$bcid);
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
										
													<th colspan="6" style="color:red;"><?php echo "BC:" . $bc;?></th>
										</tr>
										<tr>
										<th></th>
										</tr>
										<?php
										$grandtotal=0;
										$totalbalance=0;
										$s1=0;
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
												
												
												$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$bcid."' order by bcdate ASC");
												$noofbids = mysqli_num_rows($result2);
												$totalamounttobepaid = $noofbids * $bc_values[4];
												
												$result = $mysqli->query("select bcid,memid,sum(amountpaid) as amountpaid,count(*) as records 
																	from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
													//$records = mysqli_num_rows($result);
												$row=mysqli_fetch_assoc($result);
												
												$s1++;
												?>
													<tr >
																<th rowspan="<?php //if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $s1;?></th>
																<th rowspan="<?php //if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $memname;?></th>
																<th><?php echo $noofbids;?></th>
																
												<?php
												
												
										/*		while($bcdates = mysqli_fetch_assoc($result2))
												{
													$newdate = $bcdates["bcdate"];
												*/	
												?>
														
													<!--		<th><?php //echo $newdate;?></th></tr>	-->
															
														
												<?php
											//	}	
											//		echo $memid;
													
											//		echo $row['records'];													
													if($row['records']>0)
													{
												?>
														
												<!--			<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	-->
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $totalamounttobepaid;?></th>
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $row['amountpaid'];?></th>
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													else											
													{
										//				echo "entered";
												?>
														
															<!--			<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	-->
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>" ><?php echo $totalamounttobepaid;?></th>
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo '0';?></th>
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													
												?>
														
												<?php
												}
												
												?>
														<tr>
															<th></th>
														</tr>
													<tr>
															<th colspan="5"><b>Balance Due</b></th>
															<th style="color:red;"><?php echo $totalbalance;?></th>
													</tr>
													<tr>
													<th></th>
													</tr>
													
													
												<?php
												$grandtotal += $totalbalance;
										}
								//	}
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
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
											$bgrandtotal=0;
										?>
										<tr>
															<th></th>
														</tr>
										<tr>
											<th align="center" colspan="6" style="color:red;"><?php echo "BC: " . $bc;?></th>
										</tr>
										<tr>
											<th></th>
										</tr>
										<?php
											$totalbalance=0;
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
													
													
													$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$bcid."'");
													$noofbids = mysqli_num_rows($result2);
													$totalamounttobepaid = $noofbids * $bcd["amount"];
													
													$result = $mysqli->query("select bcid,memid,sum(amountpaid) as amountpaid,count(*) as records 
																		from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
														//$records = mysqli_num_rows($result);
													$row=mysqli_fetch_assoc($result);
													
													$s1++;
													?>
														<tr>
																	<th rowspan="<?php// echo $noofbids;?>"><?php echo $s1;?></th>
																	<th rowspan="<?php //echo $noofbids;?>"><?php echo $memname;?></th>
																	<th><?php echo $noofbids;?></th>
																	
													<?php
													
										/*			while($bcdates = mysqli_fetch_assoc($result2))
												{
													$newdate = $bcdates["bcdate"];
										*/
												?>
														
													<!--		<th><?php // echo $newdate;?></th>		-->
															
														
												<?php
											//	}
											//		echo $memid;
													
											//		echo $row['records'];													
													if($row['records']>0)
													{
												?>
															<!--		<th><?php // if($noofbids==0) echo "Still Bidding not done";?></th>	-->
															<th ><?php echo $totalamounttobepaid;?></th>
															<th ><?php echo $row['amountpaid'];?></th>
															<th ><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													else											
													{
										//				echo "entered";
												?>
															<!--		<th><?php // if($noofbids==0) echo "Still Bidding not done";?></th>	-->
															<th ><?php echo $totalamounttobepaid;?></th>
															<th><?php echo '0';?></th>
															<th ><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													?>
													
												<?php
												}
												
												?>
												<tr>
															<th></th>
														</tr>
													<tr>
															<th colspan="5"><b>Balance Due</b></th>
															<th style="color:red;"><?php echo $totalbalance;?></th>
													</tr>
													<tr>
													<th></th>
													</tr>
													
												<?php
												$grandtotal += $totalbalance;
										}
									}
									?>
									<tr>
										<th colspan="5"><b>Grand Total Due of All BC</b></th>
										<th style="color:red;"><?php echo $grandtotal;?></th>
									</tr>
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