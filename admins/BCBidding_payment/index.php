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
	if(isset($_REQUEST["bcpaymentdate"]))
		$gbcbiddate = $_REQUEST["bcpaymentdate"];
	else
		$gbcbiddate = '';
	if(isset($_POST['bidtype']))
		$bidtype = $_POST['bidtype'];
	else
		$bidtype = '';
	if(isset($_POST['payment_to_be_made']))
		$payment_to_be_made = $_POST['payment_to_be_made'];
	else
		$payment_to_be_made = 0;
	if(isset($_POST['actual_amount']))
		$actual_amount = $_POST['actual_amount'];
	else
		$actual_amount = 0;
	if(isset($_POST['amount_paying']))
		$amount_paying = $_POST['amount_paying'];
	else
		$amount_paying = 0;
	if(isset($_POST['balance_amount']))
		$balance_amount = $_POST['balance_amount'];
	else
		$balance_amount = 0;
	$bc_values=preg_split("/_/",$bcid);
	$gbcid=$bc_values[0];
    if (isset($_POST["payment"]))
	{
			$mysqli = $db->connect();
		//	$bcid=$_POST["bcid"];
			$modifieddate = date("Y-m-d H:i:s");
			echo $actual_amount;
			$sql = "INSERT into bc_bidding_payment_details(bcid,memid,bcdate,bcpaymentdate,actual_payment,balance_amount,lastmodifiedon) 
					values ('".$gbcid."', '".$gmemid."','".$gbcdate."','".$gbcbiddate."','".$amount_paying."','".$balance_amount."', '".$modifieddate."')";
			if ($mysqli->query($sql) === TRUE)
			{
				$strerror = false;
				$strdescription = "$strdescription" . "<br/>". "Bidding Payment Details added to Member ID <b>$gmemid</b> for BC ID <b>$bcid</b>!!";
			} 
			else 
			{
				$strerror = true;
				$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error!!" . mysqli_error($mysqli);
			}
			$mysqli->close();
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
		function companyamt(){
			var bidamount = parseInt(document.getElementById("bidamount").value);
			var bidtype = document.getElementById("bidtype").value;
			var bcid = document.getElementById("bcid").value;
			var strvalue=bcid.split("_");
			var tbcamount=strvalue[5];
			if(bidamount<=tbcamount)
			{
				var compamount = strvalue[5]*0.05;
				var balamount = bidamount - compamount;
				var memamount = strvalue[5]-bidamount;
				document.getElementById("compamount").value = compamount;
				document.getElementById("balamount").value = balamount;
				document.getElementById("memamount").value = memamount;
			}
			else
			{
				document.getElementById("bidamount").value='';
				alert("Bid Amount is more than Total BC amount!!! Please Retry");
			}
				
			
		}
		function selectbcdate(){
				var bcid = document.getElementById("bcid").value;
				var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcpaymentdate = document.getElementById("bcpaymentdate").value;
				var memid = document.getElementById("memid").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp = new XMLHttpRequest();
				} 
				else 
				{
					// code for IE6, IE5
						xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() 
				{
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
					{
						var strvalue1 = xmlhttp.responseText.split("=");
				//		alert("from database = " +strvalue1[1]);
				//		alert("bcdate="+bcdate)
						if(strvalue1[1]!="")
						{
							
							if(strvalue1[1]==bcdate)
							{
								url         = "http://localhost/SKG/admins/BCBidding_payment/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&memid=" + memid+ "&bidtype=" + bidtype; //+ "&bcpaymentdate=" + bcpaymentdate
								document.location = url;
							}
							else
							{
								alert("BC Date is not valid!!! Select Valid Date!!");
								document.getElementById("bcdate").value="";
								return false;
							}
						}
						else if(bcdate==strvalue[2])
						{
							url         = "http://localhost/SKG/admins/BCBidding_payment/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&memid=" + memid+ "&bidtype=" + bidtype; //+ "&bcpaymentdate=" + bcpaymentdate
							document.location = url;
						}
						else
						{
								alert("BC Date is not valid!!! Select Valid Date!!");
								document.getElementById("bcdate").value="";
								return false;
							}
					}
				}
				xmlhttp.open("GET","../../getbcdate.php?bcid="+bcid + "&bidtype=" + bidtype,true);
				xmlhttp.send();		
		}
		function selectpaymentdate(){
				var bcid = document.getElementById("bcid").value;
				
				var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcpaymentdate = document.getElementById("bcpaymentdate").value;
				if(bcpaymentdate<bcdate)
				{
					alert("BC Bid Payment Date must equal or later than BC Date");
					document.getElementById("bcpaymentdate").value="";
					
					
					return false;
				}	
				var memid = document.getElementById("memid").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding_payment/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
		}
        function getbc(str){
            	var bcid = document.getElementById("bcid").value;
			//	var bidtype = document.getElementById("bidtype").value;
			//	var bcdate = document.getElementById("bcdate").value;
			//	var bcpaymentdate = document.getElementById("bcpaymentdate").value;
			//	var memid = document.getElementById("memid").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding_payment/index.php?bcid=" + strvalue[0]; // + "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;	
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
            
				var bcid = document.getElementById("bcid").value;
		//		var bidtype = document.getElementById("bidtype").value;
		//		var bcdate = document.getElementById("bcdate").value;
		//		var bcpaymentdate = document.getElementById("bcpaymentdate").value;
				var memid = document.getElementById("memid").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding_payment/index.php?bcid=" + strvalue[0] + "&memid=" + memid ; //+ "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate + "&bidtype=" + bidtype;
				document.location = url;
				
		}
		function exportdata()
		{
			var bcid = document.getElementById("bcid").value;
			var bcdate = document.getElementById("bcdate").value;
			var memid = document.getElementById("memid").value;
			var bidtype = document.getElementById("bidtype").value;
			var bcpaymentdate = document.getElementById("bcpaymentdate").value;
				
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding_payment/export.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
		}
		function sbidtype()
		{
			var bcid = document.getElementById("bcid").value;
			var memid = document.getElementById("memid").value;
			var bidtype = document.getElementById("bidtype").value;
		//	var bcdate = document.getElementById("bcdate").value;
		//	var bcpaymentdate = document.getElementById("bcpaymentdate").value;
			
			//	alert(bcid);
			var strvalue=bcid.split("_");
			url         = "http://localhost/SKG/admins/BCBidding_payment/index.php?bcid=" + strvalue[0] + "&memid=" + memid + "&bidtype=" + bidtype;  //+ "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate
			document.location = url;
		}
		function clickconfirm()
		{
			var c = confirm("Are you sure you want to Submit the data?");
		//	alert(c);
			if(c==true)
				return true;
			else
				return false;
		}
		function printdata()
		{
			
			var bcid = document.getElementById("bcid").value;
			var bcdate = document.getElementById("bcdate").value;
			var memid = document.getElementById("memid").value;
			var bidtype = document.getElementById("bidtype").value;
			var bcpaymentdate = document.getElementById("bcpaymentdate").value;
				
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding_payment/print.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
		}
		function printslip()
		{
			
			var bcid = document.getElementById("bcid").value;
			var bcdate = document.getElementById("bcdate").value;
			var memid = document.getElementById("memid").value;
			var bidtype = document.getElementById("bidtype").value;
			var bcpaymentdate = document.getElementById("bcpaymentdate").value;
				
			//	alert(bcid);
				var strvalue=bcid.split("_");
			if(memid>0)
			{
				url         = "http://localhost/SKG/admins/BCBidding_payment/print_slip.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcpaymentdate=" + bcpaymentdate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
			}
			else
			{
				alert("select Member to Generate BIDPayment Slip");
				return false;
			}
				
		}
		function filter() {
			var keyword = document.getElementById("search").value;
			var select = document.getElementById("memid");
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
		function calbalance(){
			var payment_to_be_made = document.getElementById("payment_to_be_made").value;
			var actual_amount = document.getElementById("actual_amount").value;
			var amount_paying = document.getElementById("amount_paying").value;
			var balance_amount = document.getElementById("balance_amount").value;
			if(balance_amount!=0)
				balance_amount = payment_to_be_made - actual_amount - amount_paying;
			else
			{
				document.getElementById("amount_paying").value = 0;
				document.getElementById("payment").disabled = true;
				return false;
			}	
			document.getElementById("balance_amount").value=balance_amount;
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> BC Bidding and Payment Details</h1>
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
                    <form role="form" method="post" action="index.php" onsubmit="return clickconfirm()">
                        <fieldset>
                            <div class="form-group">
								
                                <select class="form-control" name="bcid" id="bcid" required tabindex=1 onchange="getbc(this.value)">
								<option value="BC">Select BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details  where flag = 'open'");
										
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"];
                                            
                                    ?>
                                    <option value="<?=$bc?>" <?php if($bcid==$row['bcid']) echo "selected"; else echo '';?>><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
							<div class="form-group">
                                <input type="text" id="search" placeholder="Type member name to search" name="search" style="margin: 10px;width: 265px;" onkeyup="filter(this.value)">
								<select class="form-control" name="memid" id="memid" required tabindex=2 onchange="getmem(this.value)">
								<option value="member">Select BID Member</option>
                                    <?php
                                        $mysqli = $db->connect();
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
										//	$mem = "$memid" . "->" .$memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
											$mem = $memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
										//	$bidresult = $mysqli->query("SELECT count(*) as num from bc_bidding_details where bcid='".$bcid."' and bidbymember='".$memid."'
										//						  ");
										//	$count = mysqli_fetch_assoc($bidresult);
										//	$c = $count["num"];
										//	$mem = "$c " . "$memid" . "->" .$memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
										//	if($c==0)
                                         //   {
												
                                    ?>
                                    <option value="<?=$memid?>" <?php if($gmemid==$row['memid']) echo "selected"; else echo '';?>><?=$mem?></option>
                                    <?php
										//	}
										}
                                        
                                    ?>
                                </select>
                            </div>
							
							
							<table width="700" border="0" align="center">
							<tr>
							<?php 
										$mysqli = $db->connect();
									//	echo $bcid;
										$sql1 = "select totalbcamount as tbcamount  from bc_details where bcid='".$bcid."' ";
										$result1 =$mysqli->query($sql1);
										$row1 = mysqli_fetch_assoc($result1);
										$tbcamount = $row1["tbcamount"];
										$sql = "select bidid, balance as cb, companybal,bidtype from bc_bidding_details where bcid='".$bcid."' order by bidid";
										$result =$mysqli->query($sql);
										$tcb=0;
										$flag=0;
										while($row = mysqli_fetch_assoc($result))
										{
			//								echo "<br />" . $row["bidid"] . "<br />" . $row["bidtype"];
										//	echo "<br />" . $row['cb'] . " + " . $tcb; 
											if($row["cb"]>0 and $row["bidtype"]=="REGULAR")
												$tcb = $tcb + $row["cb"];
											else if($row["bidtype"]=="EXTRA")
												$tcb=$row["companybal"];
											
										}
										if($tcb>0 and $tcb>=($tbcamount-($tbcamount*0.10)))
										{	
											echo "<p style=\"color:red;\"><b>Company Balance Amount is Rs. $tcb...EXTRA BID Can be DONE</b></p>";
											$flag=1;
										}
										
										$mysqli->close();
									?>
									<?php 
									$payment_to_be_made=0;
										$mysqli = $db->connect();
									//	echo $bcid;
										if(isset($_GET['bcid']))
											$bcid = $_GET['bcid'];
										else
											$bcid = '';
										if(isset($_GET['memid']))
											$memid = $_GET['memid'];
										else
											$memid = '';
										$sql1 = "select bidtype, bcdate, paymentmade, netpayment  from bc_bidding_details where bcid='".$bcid."' and bidbymember = '".$memid."' ";
										$result1 =$mysqli->query($sql1);
										$row1 = mysqli_fetch_assoc($result1);
										$payment_to_be_made = $row1["paymentmade"];
									//	$payment_to_be_made = $row1["netpayment"];	
										$bcdate = $row1['bcdate'];
										$bidtype = $row1['bidtype'];
								//		echo $payment_to_be_made;
										
										$sql2 = "select sum(actual_payment) as actual_amount from bc_bidding_payment_details where bcid='".$bcid."' and memid = '".$memid."' ";
										$result2 =$mysqli->query($sql2);
										$row2 = mysqli_fetch_assoc($result2);
										$n = mysqli_num_rows($result2);
										if($n>0)
											$actual_amount = $row2['actual_amount'];
										else
											$actual_amount = 0;
										
										$balance_amount = $payment_to_be_made - $actual_amount;										
										
										$mysqli->close();
								?>
								<th><label>BID Type</label></th>
								<td>
								<select name="bidtype" disabled id="bidtype" required tabindex=3>
								<option value="">Select Type</option>
								<option value="REGULAR"  <?php if($bidtype=="REGULAR") echo "selected"; else echo '';?>>REGULAR</option>
								<option value="EXTRA"  <?php if($bidtype=="EXTRA") echo "selected"; else echo '';?>>EXTRA</option>
								</select>
								</td>
							</tr>
							
							<div class="form-group">
								
								<tr>
								<th><label>Select BC Date</label></th>
								<td><input type="date" name="bcdate" id="bcdate" value="<?php echo $bcdate;?>" required tabindex=4 readonly></input></td>
								</tr>
								<tr />
								
							</div>
							<div class="form-group">
								
								<tr>
								<th><label>Select BC Payment Date</label></th>
								<td><input type="date" name="bcpaymentdate" id="bcpaymentdate" value="<?php if(isset($_GET['bcpaymentdate'])) echo $_GET['bcpaymentdate']; else echo '';?>" required tabindex=5 onchange="selectpaymentdate(this.value)"></input></td>
								</tr>
								<tr />
								
							</div>
							</table>
							<br/>
							
							<div class="form-group">
								<table width="717" border="0" align="center">
                            	<tr />
								<tr>
								<th><label>Payment to be Made to Member</label></th>
								
								<td><input type="text" name="payment_to_be_made" id="payment_to_be_made" readonly required pattern="[0-9]+" tabindex=6
								value = "<?php echo $payment_to_be_made;?>"></input></td>
								</tr>
								<tr />
                            	<tr>
								<th><label>Amount Already Paid</label></th>
								<td><input type="text" name="actual_amount" id="actual_amount" value="<?php echo $actual_amount;?>" pattern="[0-9]+" tabindex=7 readonly></input></td>
								</tr>
								<tr>
								<th><label>Amount Paying</label></th>
								<td><input type="text" name="amount_paying" id="amount_paying" pattern="[0-9]+" tabindex=8 onkeyup="calbalance();"></input></td>
								</tr>
								<tr />
                            	<tr>
								<th /><label>Balance</label>
								<td /><input type="text" name="balance_amount" id="balance_amount" required value="<?php echo $balance_amount;?>" pattern="[0-9]+" readonly tabindex=9></input>
								</tr>
							</table>
							</div>
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                <center>
                                <button type="submit" id="payment" name="payment" value=Add" class="btn btn-lg btn-success" style="margin-top: 20px;">Submit</button>
						<!--		<button type="submit" name="update" value=Add" class="btn btn-lg btn-success" style="margin-top: 20px;">Update Payment</button> 								
								<button type="submit" name="remove" value="remove" class="btn btn-lg btn-danger" style="margin-top: 20px;">Remove Payment</button>	-->
                                </center>
                            </div>
							
							 <input type="button" value="Export to Excel" onclick="exportdata()" style="position:absolute;right:-200px;">
							 <input type="button" value="Print Payment History" onclick="printdata()" style="position:absolute;right:-80px;">
							 <input type="button" value="Print Payment Slip" onclick="return printslip()" style="position:absolute;right:80px;">
                            </div>
							 
							 </table>
							 <?php
									$mysqli = $db->connect();
									$query = "select * from bc_bidding_payment_details ";
									if(isset($_GET['bcid']))
										$bcid=$_GET['bcid'];
									else
										$bcid='';
							//		echo "bcid = $bcid";
									
									
									if(isset($_GET['memid']))
										$memid=$_GET['memid'];
									else
										$memid='';
									
							//		echo "memid=" . $memid;
									
							/*		if(isset($_GET['bcbiddate']))
										$bcbiddate=$_GET['bcbiddate'];
									else
										$bcbiddate='';	*/
									
											  
									if($bcid!="BC" and $bcid!="")
										$query = $query . " where bcid='".$bcid."' and";
									else
										$query = $query . " where";
									
									if($memid!="member" and $memid!="")
										$query = $query . " memid='".$memid."' and";
									else
										$query = $query . "";
									
							/*		if($bcbiddate!="")
										$query = $query . " bcdate='".$bcbiddate."' and";		*/
									
									$query = substr($query,0,-3);
									$query = $query . " order by bcid,memid,bcpaymentdate,lastmodifiedon";
							//		echo "$query";
									$result = $mysqli->query($query);
									
									$resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$bcid."'");
									$bcd = mysqli_fetch_assoc($resultbcd);
                                    $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
									if($result)
									{
									$sl =0;
								?>
								
								<table width="1200" border="1" align="center">
								<caption align="center">SKG BC Bidding Payment Details</caption>
								<tr>
									
									<th colspan="2"align="right">Date: <?php echo date('d-m-Y');?></th>
								</tr>
								<tr>
									<th colspan="10"><?php 
											  echo $bc;
										?>
									</th>
								</tr>
								<tr>
									<th>Sl. NO</th>
									<th>Bid Member Name</th>
									<th>BC Date</th>
									<th>Bid Type</th>
									<th>BID Date</th>
									<th>BID Amount</th>
									<th>Payment to be Made to Member</th>
									<th>Amount Paid</th>
									<th>Balance Amount To be Paid</th>
									<th>Payment Date</th>
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
									<th><?php echo $sl;?></th>
									
									<?php 
										$bidresult = $mysqli->query("SELECT * from bc_bidding_details where bcid = '".$bcid."' and bidbymember='".$memid."'");
										$bid = mysqli_fetch_assoc($bidresult);
										$bcdate = $bid["bcdate"];
										$biddate = $bid["biddate"];
										$bidamount = $bid["bidamount"];
										$amounttobepaid = $bid["paymentmade"];
										$bidtype = $bid["bidtype"];
									?>
									<th><?php echo $memname;?></th>
									<th><?php echo $bcdate;?></th>
									<th><?php echo $bidtype;?></th>
									<th><?php echo $biddate;?></th>
									<th><?php echo $bidamount;?></th>
									<th><?php echo $amounttobepaid;?></th>
									<th><?php echo $row['actual_payment'];?></th>
									<th><?php echo $row['balance_amount'];?></th>
									<th><?php echo $row['bcpaymentdate'];?></th>
									
								</tr>
								<?php		
									
									}
								}
									?>

							 
							 
							 
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

<script>
$(document).ready(function(){

	$(document).on('select', '.memid', function(){
        var memid = $(this).attr("memid");
		alert(memid);
 /*       var btn_action = 'fetch_single';
        $.ajax({
            url:"customer_action.php",
            method:"POST",
            data:{customer_id:customer_id, btn_action:btn_action},
            dataType:"json",
            success:function(data){
                $('#customerModal').modal('show');
                $('#customer_name').val(data.customer_name);
				$('#firm_name').val(data.firm_name);
                $('#address').val(data.address);
				$('#place').val(data.place);
				$('#zipcode').val(data.zipcode);
				$('#customer_type').val(data.customer_type);
				if(data.customer_type!='' || data.customer_type!='Unregistered')
				{
					$('#GSTIN').val(data.GSTIN);
					$('#GSTIN').attr('readonly',true);
				}
				else
				{
					$('#GSTIN').val(data.GSTIN);
					$('#GSTIN').attr('readonly',false);
				}
                $('#contact_no').val(data.contact_no);
                $('#email_id').val(data.email_id);
				$('#current_outstanding').val(data.current_outstanding)
				$('#current_outstanding').attr('readonly', true);
				$('#outstanding_date').val(outstanding_date);
				$('#outstanding_date').attr('readonly', true);
                $('.modal-title').html("<i class='fa fa-pencil-square-o'></i> Edit Customer");
                $('#customer_id').val(customer_id);
                $('#action').val("Edit");
                $('#btn_action').val("Edit");
            }
        })
	*/
    });


});


</script>

</body>

</html>
