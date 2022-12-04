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
	
		function selectdate(){
			
			var spdate = document.getElementById("spdate").value;
			var c = document.getElementById("collection").value;
			url         = "http://localhost/SKG/admins/cbreceipts/index.php?spdate=" + spdate + "&collection=" + c;
			document.location = url;
		}
		function selectspdate(){
			var spdate = document.getElementById("spdate").value;
			var c = document.getElementById("collection").value;
			url         = "http://localhost/SKG/admins/cbreceipts/index.php?spdate=" + spdate + "&collection=" + c;
			document.location = url;
		}
        function getbc(str){
          //  if (str == "BC"){
				var spdate = document.getElementById("spdate").value;
				var c = document.getElementById("collection").value;
				url         = "http://localhost/SKG/admins/cbreceipts/index.php?spdate=" + spdate + "&collection=" + c;
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
				
				var spdate = document.getElementById("spdate").value;
				var c = document.getElementById("collection").value;
				url         = "http://localhost/SKG/admins/Reports/cbreceipts/index.php?bcid=" + bcid + "&memid=" + memid + "&spdate=" + spdate + "&collection=" + c;
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
			var spdate = document.getElementById("spdate").value;
			var c = document.getElementById("collection").value;
			url         = "http://localhost/SKG/admins/cbreceipts/export.php?spdate=" + spdate + "&collection=" + c;
			document.location=url;
		}
		function dispothers()
		{
			var c = document.getElementById("collection").value;
			var spdate = document.getElementById("spdate").value;
			if(c!="")
			{
				url         = "http://localhost/SKG/admins/cbreceipts/index.php?spdate=" + spdate + "&collection=" + c;
				document.location=url;
			}
			else
			{
				url         = "http://localhost/SKG/admins/cbreceipts/index.php?spdate=" + spdate + "&collection=" + '';
				document.location=url;
			}
		}
		function printdata()
		{
			
			var printContents = $("#printThisTable").html();
			$("body").html(printContents);
			window.print();
			url         = "http://localhost/SKG/admins/cbreceipts/index.php";
			document.location=url;
			return false;
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
                            
                                    <?php
                                        $mysqli = $db->connect();
						
                                        $mapresult = $mysqli->query("SELECT memid
																  from bc_member_mapping
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
                                            
                          
                                        }
                                    ?>
								<div>
							 <input type="button" value="Export to Excel" onclick="exportdata()">
							 <input type="button" value="print" onclick="javascript:return printdata()">
							</div>
								<table width="800" border="0" align="center">
								
							
								<tr>
								<th colspan="2"><label>Select Date</label></th>
								<td><input type="date" name="spdate" id="spdate" value="<?php if(isset($_GET['spdate'])) echo $_GET['spdate']; else echo '';?>" required tabindex=4 onchange="selectspdate(this.value)"></input></td>
								</tr>
								<tr>
								<th colspan="2" align="center">Select Collection Boy Name : <b></th>
								<th><select name="collection" id="collection" height="50px" onchange="dispothers()">
									<option value="">Select</option>
									<?php 
											$mysqli = $db->connect();
											$cbresult = $mysqli->query("SELECT distinct cbname from members_payment_details");
																
											while($cbrow = mysqli_fetch_assoc($cbresult))
											{
												$lcbname = $cbrow["cbname"];
													
									?>
									<option value="<?=$lcbname?>" <?php if($cbname==$lcbname) { echo "selected"; } else echo '';?>><?=$lcbname?></option>
                                    <?php
                                        }
										$mysqli->close();
									?>
								</th>
								</tr>	
								</table>
							</div>
                            <div class="form-group">
								<?php
									$mysqli = $db->connect();
									if(isset($_GET['spdate']))
										$spdate=$_GET['spdate'];
									else
										$spdate='';
									
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
									$resultbcd = $mysqli->query("SELECT * from bc_details where flag='open'");
								?>
								<div id="printThisTable">
								<?php
									echo "<h2 align=\"center\"><b>SKG BC Management System</b></h2>";
									echo "<h3 align=\"center\"><b>Collection Boy Receipt</b></h3>";
									echo "<p align=\"left\"><b>Name of Collection Boy:  " . $cbname . "</b></p>";
									echo "<p align=\"left\"><b>Date of Collection:  " . $spdate . "</b></p>";
									$sl=0;
									$total =0;
									?>
									<table width="600" border="1" align="center">
										<tr>
											<th>Sl. NO</th>
											<th>BC Name</th>
											<th>Name of Member</th>
											<th>Amount Paid</th>
											
										</tr>
									<?php
									while($bcd = mysqli_fetch_assoc($resultbcd))
									{
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
										$bcid = $bcd['bcid'];
										$resultmem = $mysqli->query("SELECT memid from bc_member_mapping where bcid = '".$bcid."'");
										$flag=0;
										$bc_name = $bcd['bc_name'];
									?>
									
										
									<?php
										$total_of_bc=0;
										while($mem = mysqli_fetch_assoc($resultmem))
										{
											$memid=$mem['memid'];
											$query1 = "select fname,mname,lname 
											  from members_details
											  where memid='".$memid."'
											  ";
											$result1 = $mysqli->query($query1);
											$row1 = mysqli_fetch_assoc($result1);
											$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
											
											$query = "select sum(amountpaid) as amountpaid
											  from members_payment_details where bcid='$bcid' and memid='$memid'";
											if($spdate!="")
												$query = $query . " and paiddate='".$spdate."' and";
											else
												$query = $query . " and";
											if($cbname!="")
												$query = $query . " cbname='".$cbname."' and";
											$query = substr($query,0,-3);
											$query = $query . " order by bcid, memid";
											$result = $mysqli->query($query);
											$num = mysqli_num_rows($result);
											if($num>0)
											{
										?>
										<?php
												$res = mysqli_fetch_assoc($result);
												$amt = $res['amountpaid'];
												if($amt>0)
												{
													$total = $total + $amt;
													$total_of_bc+=$amt;
													$sl++;
													if($sl==1){
										?>
									<!--	<tr  align="center">
											<th colspan="3"><?php // echo $bc; ?></th>
										</tr>	-->
										<?php
												}
										?>
											<tr>
												<td><?php echo $sl; ?></td>
												<td><?php echo $bc_name;?></td>
												<td><?php echo $memname;?></td>
												<td><?php echo $amt;?></td>
												
											</tr>
										<?php
												}
											}
											
											else
												{
										?>
											<tr>
												<td><?php echo "NILL";?></td>
											</tr>
										<?php
												}
												
										}
										?>
										<tr align="center">
												<th colspan="3" ><?php echo "Total of BC";?></th>
												<th><?php echo $total_of_bc;?></th>
										</tr>
										<?php
									}
									
                                    ?>
									<tr>
										<th colspan="3">Grand Total Amount Collected</th>
										<th><?php echo $total;?></th>
									</tr>
									<tr>
									</tr>
									<tr>
									
										<th>Signature of Collection Boy</th>
										<th/><th/>
										<th>Signature of Authority</th>
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
