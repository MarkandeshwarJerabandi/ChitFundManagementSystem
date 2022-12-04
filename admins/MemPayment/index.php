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
	if(isset($_REQUEST["bcstartdate"]))
		$gbcdate = $_REQUEST["bcstartdate"];
	else
		$gbcdate = '';
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
//	echo $cbname;
    if (isset($_POST["payment"]))
	{
			$mysqli = $db->connect();
			if($_POST["selectbc"]!="BC" and $_POST["selectmem"]!="member")
			{
				$bcid = $_POST["selectbc"];
				$memid = $_POST["selectmem"];
				$bcdate = $_POST["bcstartdate"];
				$user = $_SESSION["skguser"];
				$modifieddate = date("Y-m-d");
				$sql1 = "select * from bc_details where bcid='".$bcid."'";
				$result = $mysqli->query($sql1);
				$bc_values = mysqli_fetch_assoc($result);
				$num = mysqli_num_rows($result);
				$noofbc=$bc_values["bcmembers"];
				$regbcamount = $bc_values["amount"];
				if($num>0){
				if(isset($_POST["paymentdate"]))
					$paymentdate=$_POST["paymentdate"];
				if(isset($_POST["currentpayment"]))
					$currentpayment=$_POST["currentpayment"];
			
				
				if($currentpayment>0)
				{
							$sql = "INSERT into members_payment_details (bcid,memid,amountpaid,paiddate,user,lastmoddate,cbname,remarks) 
									values ('".$bcid."', '".$memid."','".$currentpayment."','".$paymentdate."','".$user."', '".$modifieddate."','".$cbname."','Regular Cash Payment')";
							if ($mysqli->query($sql) === TRUE)
							{
								$strerror = false;
								$strdescription = "$strdescription" . "<br/>". "Payment Details added to the Member ID <b>$memid</b> for BC ID <b>$bcid</b>!!";
							} 
							else 
							{
								$strerror = true;
								$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error 111!!" . mysqli_error($mysqli);
							}
				}
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
		//	var memid = document.getElementById("selectmem").value;
			var bcdate = document.getElementById("bcdate").value;
			url         = "http://localhost/SKG/admins/MemPayment/index.php?bcid=" + bcid + "&bcdate=" + bcdate;
			document.location = url;
		}
        function getbc(str){
            if (str == "BC"){
                return;
            }
			else
			{
				url         = "http://localhost/SKG/admins/MemPayment/index.php?bcid=" + str;
				document.location = url;
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
                    document.getElementById("bcid").value = strvalue[0];
					
                }
            }
            xmlhttp.open("GET","../../getbcid.php?bcid="+str,true);
            xmlhttp.send();		
        }
		function getmem(str){
            if (str == "member"){
                return;
            }
			else
			{
				var bcid = document.getElementById("selectbc").value;
			//	var bcdate = document.getElementById("bcdate").value;
			//	alert(bcid);
				url         = "http://localhost/SKG/admins/MemPayment/index.php?bcid=" + bcid +  "&memid=" + str; //"&bcdate=" + bcdate
				document.location = url;
			}	
		}
		function adjust(currentpayment){
			var id=0;
			var noofbc=0;
			var totalbcamount;
			var str = document.getElementById("selectbc").value;
			currentpayment = parseInt(currentpayment);
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
                    noofbc  = strvalue[2];
					totalbcamount = strvalue[4];
			//		alert(totalbcamount);
			//		alert(currentpayment);
				//	alert(noofbc);
					if(currentpayment>0)
					{
						if(currentpayment<=totalbcamount)
						{
							var bcstartdate = document.getElementById("bcstartdate").value;
							var d= new Date;
							var currentdate = 1 + "-" + (d.getMonth()+1) + "-" + d.getFullYear() ;
						//	alert(bcstartdate + " " + currentdate);
							var temp = bcstartdate;
							while(id<noofbc)
							{
								id=id+1;
								var element = document.getElementById(id+"currentbcamount");
								if(element!=null)
								{
									document.getElementById(id+"currentbcamount").value=0;
									document.getElementById(id+"currentbalanceamount").value=0;
									var bcamtbal = parseInt(document.getElementById(id+"bcamountbal").value);
								//	var bcamtbal = (document.getElementById(id+"bcamountbal").value);	
									if(bcamtbal>0)
									{
									//	alert(currentpayment+" " + bcamtbal);
										if(currentpayment<bcamtbal)
										{
								//			alert(currentpayment);
											document.getElementById(id+"currentbcamount").value=currentpayment;
											rbalance=(bcamtbal-currentpayment);
											document.getElementById(id+"currentbalanceamount").value=rbalance;
											currentpayment = 0;
									//		break;
										}
										else if(currentpayment>=bcamtbal)
										{
									//		alert(currentpayment);
											document.getElementById(id+"currentbcamount").value=bcamtbal;
											document.getElementById(id+"currentbalanceamount").value=0;
											currentpayment = (currentpayment-bcamtbal);
										}
									}
								}
								else
								{
								//	document.getElementById("currentpayment").innerHTML = "Excess Amount of Rs. " . currentpayment . " is adjusted to Next Months";
									if(currentpayment>0)
										alert("Excess Amount of Rs. " + currentpayment + " is adjusted to Next Months")
									break;	
								}
								//temp = 1 + "-" + (temp.getMonth()+1) + "-" + temp.getFullYear() ;
							}
						}
						else
						{
							alert("Amount is more than total BC amount!! So cannot process");
							return false;
						}
					}
					
						
					
                }
            }
            xmlhttp.open("GET","../../getbcdetails.php?bcid="+str,true);
            xmlhttp.send();
			
			
		}
		function clickconfirm()
		{
			var c = confirm("Are you sure you want to add payment details?!!Please confirm before you submit");
		//	alert(c);
			if(c==true)
				return true;
			else
				return false;
		}
		function dispothers()
		{
			var c = document.getElementById("collection").value;
			var cp= document.getElementById("cbname");
		//	alert(c);
			if(c!="Other")
			{
				cp.style.visibility='hidden';
				cp.required=false;
				return true;
			}
			else
			{
				cp.style.visibility='visible';
				cp.required=true;
				return false;
			}
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
		$(function() {
			$("#paymentdate").datepicker();
			
			$("#paymentdate").val();
			
			$("#paymentdate").on("change",function(){
				var selected = $(this).val();
				alert(selected);
			});
		});
	/*	function checkDate(){
			var paymentdate = document.getElementById("paymentdate");
			var d= new Date;
			var currentdate = d.getFullYear()+ "-";
			if(d.getMonth()+1<10)
				currentdate += "0" + (d.getMonth()+1) + "-" + d.getDate() ;
			else
				currentdate += (d.getMonth()+1) + "-" + d.getDate() ;
			//alert(currentdate);
			//alert(paymentdate.value);
			if (paymentdate.value < currentdate)
			{
				alert("Do you want to set different date than today's date");
				password = prompt("Are you sure, you want to select the previous date!! Then Enter Password");
				if(password == "Yes")
					return true;
				else
					paymentdate.value='';
			}
			else
				return true;
		}	*/
		$("#paymentdate").datepicker({
        "dateFormat": "dd-mm-yyyy",
        "minDate": -3,
        "maxDate": new Date()
		})
		.attr("readonly", true);
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> BC Amount Payment By Members</h1>
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
                    <form role="form" id="mempayment" name="mempayment" method="post" action="index.php" onsubmit="return clickconfirm();">
                        <fieldset>
                            <div class="form-group">
								
                                <select class="form-control" name="selectbc" id="selectbc"  required tabindex=1 onchange="getbc(this.value)">
								<option value="BC">Select BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details  where flag = 'open'");
										
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"]."_".$row["bc_name"];
                                            
                                    ?>
                                    <option value="<?=$row['bcid']?>" <?php if($bcid==$row['bcid']) { echo "selected"; $current_bc=$bc;} else echo '';?>><?=$bc?></option>
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
								<option value="member">Select Member</option>
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
											$mem = "$memid" . "->" .$memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
                                            
                                    ?>
                                    <option value="<?=$memid?>" <?php if($gmemid==$row['memid']) { echo "selected"; $current_mem = $memid;} else echo '';?>><?=$mem?></option>
                                    <?php
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
							<div class="form-group">
								<?php //echo $current_mem;
									if(isset($current_bc) and isset($current_mem))
									{
										$bc_values=preg_split("/_/",$current_bc);
									//	echo $bc_values[2];
										
									
								?>
								<table width="817" border="0" align="center">
								<tr>
								<th colspan="2"><label align="center">BC Start Date</label></th>
								<td><input type="date" name="bcstartdate" id="bcstartdate" value="<?php echo $bc_values[2];?>" required tabindex=3 readonly></input></td>		
								</tr>
						<!--		<tr align="center" ><td colspan="3">Payment Details Till <b><?php echo date("01-m-Y");?></b></td></tr>
								<tr>
								<th>Amount To be Paid: <b><label><?php echo "Amount";?></label></th>
								<th>Amount Paid: <?php echo "Amount";?></label></th>
								<th>Balance Amount: <?php echo "Amount";?></label></th>
								</tr>	-->
								<tr></tr>
								<tr></tr>
								<tr></tr>
								<tr >
								<td colspan="2"><b>Current Payment Details As on:</b></td>
								<td>
								<!--<input type="date" id="paymentdate" name="paymentdate" value="<?php// echo date("d-m-Y");?>" required tabindex=4></input>-->
								<?php $today = date("d-m-Y");?>
							<!--	<input type="text" name="paymentdate" id="paymentdate" required readonly onclick="setDifferentDate()" tabindex=4 value="<?php echo $today;?>"></input>	-->
								<input type="date" name="paymentdate" id="paymentdate" required tabindex=4  onchange="checkDate()"></input>
								</td>
								</tr>
								<tr>
								<th colspan="2" ><b>Amount Paid: </b></th>
								<th><input type="text" tabindex=5 pattern="[0-9]+" name="currentpayment" 
								id="currentpayment" required size="8"></input></th>
								</tr>
								<tr>
								<th colspan="2" align="center">Amount Collected By : <b></th>
								<th><select name="collection" id="collection" height="50px" onchange="dispothers()" required>
									<option value="">Select</option>
									<option value="Direct By Party">Direct by Party</option>
									<option value="KSA">KSA</option>
									<option value="PSA">PSA</option>
									<option value="SSH">SSH</option>
									<option value="Collection Boy 1">Collection Boy 1</option>
									<option value="Collection Boy 2">Collection Boy 2</option>
									<option value="Other">Other</option>
									<input type="text" name="cbname" id="cbname" style="visibility:hidden;"></input>
								</th>
								</tr>
								</table>
								
							
								
							</div>
							
                            <?php
									}
									?>
							
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                <center>
                                <button type="submit" name="payment" value="Add" class="btn btn-lg btn-success" style="margin-top: 20px;">Submit</button>
								
								
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
