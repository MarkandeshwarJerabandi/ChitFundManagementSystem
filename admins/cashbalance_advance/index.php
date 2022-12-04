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
		function getbc(str){
          //  if (str == "BC"){
				var bcid = document.getElementById("selectbc").value;
				url         = "http://localhost/SKG/admins/cashbalance_advance/index.php?bcid=" + bcid;
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
		
		function exportdata()
		{
			url         = "http://localhost/SKG/admins/cashbalance_advance/export.php";
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
			url         = "http://localhost/SKG/admins/cashbalance_advance/index.php";
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
									
								<div class="form-group">
								
                                <select class="form-control" name="selectbc" id="selectbc" autofocus required tabindex=1 onchange="getbc(this.value)">
								<option value="BC">Select BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details");
										$r = mysqli_num_rows($result);
										
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc =  $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"];
										//	$r . " " .
                                            
                                    ?>
                                    <option value="<?=$row['bcid']?>" <?php if($bcid==$row['bcid']) echo "selected"; else echo '';?>><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        
                                    ?>
                                </select>
                            </div>	
									
									<table  border="1" align="center">
									
										<tr>
										
											<?php 
												$resultbcd = $mysqli->query("SELECT * from bc_details where bcid='".$bcid."'");
												$bcd = mysqli_fetch_assoc($resultbcd);
												$tcollection = 0;
												$tcompany = 0;
												$tpaymentmade = 0;
												$tcash_in_hand = 0;
												$tduebalance = 0;
												$tnet_cash_balance = 0;
												$tadvanceamount = 0;
												$ttotal = 0;
												if(isset($_GET['bcid']))
													$bcid = $_GET['bcid'];
												else
													$bcid='';
											?>
											<th>Total Collection From Members</th>
											<th>
											<?php
											/*		$query1 = "select sum(amountpaid) as amountpaid 
													  from members_payment_details 
													  where bcid='".$bcid."' AND remarks != 'advance cash payment'
													  ";	*/
													
													$query1 = "select sum(amountpaid) as amountpaid 
													  from members_payment_details 
													  where bcid='".$bcid."'
													  ";
													$result1 = $mysqli->query($query1);
													$row1 = mysqli_fetch_assoc($result1);	
													$amountpaid = $row1['amountpaid'];
													echo $row1['amountpaid'];
													$collection = $row1['amountpaid'];
											?>
											
											</th>
										</tr>
										<tr>
											
											<th style="font-size:40px;text-align:center;color:red;">-</th>
										</tr>
										<tr>
											<th>Commission Amount</th>
											<th>
											<?php
												//	$paymentmade = 0;
													$query1 = "select sum(company) as company, sum(paymentmade) as paymentmade 
													  from bc_bidding_details
													  where bcid='".$bcid."'
													  ";
													$result1 = $mysqli->query($query1);
													$row1 = mysqli_fetch_assoc($result1);
													$company = $row1['company'];
													
													$query2 = "select sum(actual_payment) as paymentmade 
													  from bc_bidding_payment_details
													  where bcid='".$bcid."'
													  ";
													$result2 = $mysqli->query($query2);
													$row2 = mysqli_fetch_assoc($result2);
													$paymentmade = $row2['paymentmade'];
													echo $row1['company'];
											?>
											</th>
										</tr>
										<tr>
										
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										</tr>
										<tr>
										<th>Total Collection</th>
										<th style="font-size:20px;text-align:center;color:red;"><?php $actual_collection = $collection - $company; echo $collection - $company;?></th>
										</tr>
								<!--		<tr>
										
											<th style="color:green;font-size:40px;text-align:center;">+</th>
										</tr>
										<tr>											
											<th>Advance Cash Payment by Members</th>
											<th>
											<?php
											/*		$query1 = "select sum(amountpaid) as amountpaid 
													  from members_payment_details 
													  where bcid='".$bcid."' AND remarks = 'advance cash payment'
													  ";
													$result1 = $mysqli->query($query1);
													$row1 = mysqli_fetch_assoc($result1);	
													$advanceamount = $row1['amountpaid'];
													echo $advanceamount;	*/
											?>
											</th>
										</tr>	
										<tr>
										
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										</tr>
										<tr>
										<th>Total Collection</th>
										<th style="font-size:20px;text-align:center;color:red;"><?php //$total_collection = $actual_collection;//$total_collection = $actual_collection + $advanceamount; echo $total_collection;?></th>
										</tr>	-->
										
										<tr>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										</tr>
										<tr>
											<th>Till Date Payment to Members </th>
											<th>
											<?php
												echo $paymentmade;
											?>
											</th>
										</tr>
										<tr>
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										</tr>
										<tr>
											<th>Cash in Hand</th>
											
											<th style="text-align:center;color:red; font-size:20px;">
											<?php
												$cash_in_hand = $actual_collection - $paymentmade;
												echo $cash_in_hand;
											?>
											</th>
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
