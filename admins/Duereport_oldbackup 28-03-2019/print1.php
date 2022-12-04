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
function printdata1()
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
							<input type="button" value="Print" onclick="printdata1()"></input>
<?php
echo "<p align=\"center\"><b>SKG BC Payment Due Details</b></p>";
echo "<p align=\"right\"><b>Date:</b> " . date('d-m-Y') . "</p>";
$mysqli = $db->connect();
$bcid="";
$memid="";
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
									$sql = "select startdate,amount,type from bc_details where bcid ='".$bcid."'";
										$result1 = $mysqli->query($sql);
										$row = mysqli_fetch_assoc($result1);
										$startdate = $row['startdate'];
										$bcamount=$row['amount'];
										$bctype=$row['type'];
										$enddate = date('Y-m-d');
										$currentdate=$enddate;
									/*	$d = date("d",strtotime($enddate));
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
								?>
								<div id="printThisTable">
								<table width="700" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
							<!--		<th>BCID</th>		-->
									<th>Name of Member</th>
									<th>Amount Due</th>
								</tr>
								<?php
									if($bcid!="BC" and $bcid!="" and $memid!="mem" and $memid!="")
									{
										$query1 = "select fname,mname,lname 
														  from members_details
														  where memid='".$memid."'
														  ";
										$result1 = $mysqli->query($query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
										$totalbalance=0;
										$s1++;
										$flag=1;
										?>
										
										<tr >
										
													<th rowspan="2" colspan="3"><?php echo "BC:" . $bc;?></th>
										</tr>
										<tr>
										</tr>
										
										<?php
										while($newdate<=$currentdate)
										{
											
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
										}
										?>
										<tr>
													<th><?php echo $s1;?></th>
											<!--		<th><?php echo $bc;?></th>	-->
													<th><?php echo $memname;?></th>
													<th><?php echo $totalbalance;?></th>
										</tr>
										
										<?php
									}
									else if($bcid!="BC" and $bcid!="" and $memid=="mem" or $memid=="")
									{
										$temp=$startdate;
										$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
										$flag=1;
									?>
										
										<tr>
										
													<th rowspan="2" colspan="3"><?php echo "BC:" . $bc;?></th>
										</tr>
										<tr>
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
												$s1++;
												while($newdate<=$currentdate)
												{
													
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
												}
												?>
											<tr>
													<th><?php echo $s1;?></th>
										<!--			<th><?php echo $bc;?></th>		-->
													<th><?php echo $memname;?></th>
													<th><?php echo $totalbalance;?></th>
											</tr>
												<?php
										}
									}
									else if($flag==0)
									{
										$bcres=$mysqli->query("select * from bc_details");
										while($bcd = mysqli_fetch_assoc($bcres))
										{
											$bcid=$bcd['bcid'];
											$temp=$bcd['startdate'];
											$bcamount=$bcd['amount'];
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
											$s1=0;
										?>
										
										<tr >
										
													<th rowspan="2" colspan="3"><?php echo "BC:" . $bc;?></th>
										</tr>
										<tr>
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
													$s1++;
													while($newdate<=$currentdate)
													{
														
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
														if($bcd['type']=="Single")
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
														<th><?php echo $s1;?></th>
											<!--			<th><?php echo $bc;?></th>	-->
														<th><?php echo $memname;?></th>
														<th><?php echo $totalbalance;?></th>
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