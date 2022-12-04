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
	if(isset($_REQUEST["bcbiddate"]))
		$gbcbiddate = $_REQUEST["bcbiddate"];
	else
		$gbcbiddate = '';
	if(isset($_POST['bidtype']))
		$bidtype = $_POST['bidtype'];
	else
		$bidtype = '';
    if (isset($_POST["payment"]))
	{
			$mysqli = $db->connect();
		//	$bcid=$_POST["selectbc"];
			
			
			if($_POST["selectbc"]!="BC" and $_POST["selectmem"]!="member")
			{
				$bcid=$_POST["selectbc"];
				$tbcamount = $mysqli->query("SELECT totalbcamount
										from bc_details
										where bcid = '".$bcid."'
										");
				$row1 = mysqli_fetch_assoc($tbcamount);
				$totalbcamount = $row1['totalbcamount'];
				if(isset($_POST["bcdate"]))
					$bcdate=$_POST["bcdate"];
				if(isset($_POST["bcbiddate"]))
					$bcbiddate=$_POST["bcbiddate"];
				if(isset($_POST["selectmem"]))
					$memid=$_POST["selectmem"];
				if(isset($_POST["bidtype"]))
					$bidtype=$_POST["bidtype"];
			//	echo "$bidtype";
				if(isset($_POST['bidamount']))
						$bidamount = $_POST['bidamount'];
				if(isset($_POST['compamount']))
						$compamount = $_POST['compamount'];
				if(isset($_POST['balamount']))
						$balamount = $_POST['balamount'];
				if(isset($_POST['memamount']))
						$memamount = $_POST['memamount'];
				
			//	$row = mysqli_fetch_assoc($result);
			//	$count = $row['num'];
				
			//	$tbcamount = $totalbcamount;
			//	$sql = "select balance as cb, companybal,bidtype from bc_bidding_details where bcid='".$bcid."'";
			//	$result =$mysqli->query($sql);
			
			$bc_values=preg_split("/_/",$bcid);
			$startdate=$bc_values[2];
		//	echo $startdate;
			$adjustment=0;
			$balancepayment=0;
			$user = $_SESSION["skguser"];
			$modifieddate = date("Y-m-d");
			$newdate=$startdate;
			
			$result = $mysqli->query("SELECT bidid, balance as cb, companybal, bidtype
										from bc_bidding_details
										where bcid = '".$bcid."' order by bidid
										");
				$tcb=0;
				$count=0;
				while($row = mysqli_fetch_assoc($result))
				{
					$count=$count + 1;
					if($row["cb"]>0 and $row["bidtype"]=="REGULAR")
						$tcb = $tcb + $row["cb"];
					else if($row["bidtype"]=="EXTRA")
						$tcb=$row["companybal"];
		//			echo $tcb . "<br />";
				}
				
		//		echo $count;
				$balance = $tcb;
		//		echo " $balance ";
		//		echo " $totalbcamount ";
			//	echo "comp = $compbalamount";
			//	echo "total = $totalbcamount";
				if($bidtype=="REGULAR")
				{
						if($count == 0)
							$compbalamount=$balamount;
						else
							$compbalamount = $balance + $balamount;
						if(isset($_POST['memamount']))
							$memamount = $_POST['memamount'];
						$user = $_SESSION["skguser"];
						$date = date("Y-m-d H:i:s");
						
						$adjustment = 0;  //set to 0 since not recover balance amount from bid amount
						
						$netpayment = $memamount-$adjustment;
						$sql = "INSERT into bc_bidding_details (bcid,bcdate,biddate,bidbymember,bidamount,company,balance,companybal,paymentmade,
																amtadjustedtopayment,netpayment,bidtype,user,lastmoddate) 
											values ('".$bc_values[0]."','".$bcdate."','".$bcbiddate."','".$memid."','".$bidamount."','".$compamount."','".$balamount."',
											'".$compbalamount."','".$memamount."','".$adjustment."','".$netpayment."','".$bidtype."','".$user."','".$date."')";
						
						if ($mysqli->query($sql) === TRUE)
						{
							$strerror = false;
							$strdescription = "$strdescription" . "<br/>". "Bidding Details added to Member ID <b>$memid</b> for BC ID <b>$bcid</b>!!";
						} 
						else 
						{
							$strerror = true;
							$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error 11111!!" . mysqli_error($mysqli);
						}
				}	
				else
				{
					if($count!=0)
					{	
						if($balance>=$totalbcamount or $balance>=($totalbcamount-($totalbcamount*0.20)))
						{	
							$compbalamount = ($balance-$totalbcamount)+$balamount;
						//	echo " new==== $compbalamount";
							if(isset($_POST['memamount']))
								$memamount = $_POST['memamount'];
							$user = $_SESSION["skguser"];
							$date = date("Y-m-d H:i:s");
							
							$adjustment = 0;  //set to 0 since not recover balance amount from bid amount
							
							$netpayment=$memamount-$adjustment;
							$sql = "INSERT into bc_bidding_details (bcid,bcdate,biddate,bidbymember,bidamount,company,balance,companybal,paymentmade,amtadjustedtopayment,netpayment,bidtype,user,lastmoddate) 
												values ('".$bc_values[0]."','".$bcdate."','".$bcbiddate."','".$memid."','".$bidamount."','".$compamount."','".$balamount."',
												'".$compbalamount."','".$memamount."','".$adjustment."','".$netpayment."','".$bidtype."','".$user."','".$date."')";
							if ($mysqli->query($sql) === TRUE)
							{
								$strerror = false;
								$strdescription = "$strdescription" . "<br/>". "Bidding Details added to Member ID <b>$memid</b> for BC ID <b>$bcid</b>!!";
							} 
							else 
							{
								$strerror = true;
								$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error 222222!!" . mysqli_error($mysqli);
							}
						}
						else
						{
							$strerror = true;
							$strdescription = "$strdescription" . "<br/>". "Error: Bid Type EXTRA cannot be allowed!!";
						}
					}
					else
					{
							$strerror = true;
							$strdescription = "$strdescription" . "<br/>". "Error: Bid Type EXTRA cannot be allowed!!";
					}	
						
				}				
			}
			
		
			// adjusting company amount to self account'
			$companyamt = $compamount;
	//		echo $companyamt;
			$sqlbid1 = "select mem.memid as memid from members_details as mem, bc_member_mapping as bc where bc.bcid='".$bc_values[0]."' and mem.memid=bc.memid and mem.type='self' order by bc.memid ASC";
			$resultbid1 = $mysqli->query($sqlbid1);
			$self_count = mysqli_num_rows($resultbid1);
			$total_self=$self_count;
			$bcamount = $bc_values[4];
			$commision = ($bc_values[5]*0.05);
			$temp = $commision;
			$bcdate=$_POST["bcdate"];
			
			$count = 0;
			while($temp > 0)
			{
				$count = $count + 1;
				$temp = $temp - $bcamount;
			}
			$temp = $commision;
			
			while($memres=mysqli_fetch_assoc($resultbid1) and $temp>0)
			{
					$self_count--;
					$memid=$memres['memid'];
			//		echo "self count:" . $self_count . " memid:" . $memres['memid'] . " and Commision:" . $temp;
			//		echo "<br/>";
			
					$recselfamountres = $mysqli->query("select amountpaid from members_payment_details 
														where bcid='".$bcid."' and memid='".$memid."' and remarks='Adjusted to Self Account by Bid Amount'
														order by pid desc");
														//$records = mysqli_num_rows($result);
					$row=mysqli_fetch_assoc($recselfamountres);
					$recentselfamt = $row["amountpaid"];
					
					echo $recentselfamt;
					
					if($recentselfamt==$bcamount)
					{
					//	continue;
						$sqlbid1 = "select mem.memid as memid from members_details as mem, bc_member_mapping as bc 
									where bc.bcid='".$bc_values[0]."' and mem.memid=bc.memid and mem.type='self' 
									order by bc.memid DESC";
						$resultbid1 = $mysqli->query($sqlbid1);
						while($memres=mysqli_fetch_assoc($resultbid1) and $temp>0)
						{
							$memid=$memres['memid'];
							if($temp>=$bcamount)		
							{
								$selfamount = $bcamount;
								$temp = $temp-$bcamount;
							}	
							else
							{
								$selfamount = $temp;
								$temp =0;
							}	
							$sql = "INSERT into members_payment_details (bcid,memid,amountpaid,paiddate,user,lastmoddate,remarks) 
									values ('".$bc_values[0]."', '".$memid."','".$selfamount."','".$bcbiddate."','".$user."', '".$bcbiddate."','Adjusted to Self Account by Bid Amount')";
							if ($mysqli->query($sql) === TRUE)
							{
												$strerror = false;
												$strdescription = "$strdescription" . "<br/>". "Payment Details added to Self Member ID <b>$memid</b> for BC ID <b>$bc_values[0]</b>!!";
							} 
							else 
							{
												$strerror = true;
												$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error 1111!!" . mysqli_error($mysqli);
							}
						}
						break;
					}
					else
					{
						if($temp>=$bcamount)		
						{
							$selfamount = $bcamount;
							$temp = $temp-$bcamount;
						}	
						else
						{
							$selfamount = $temp;
							$temp =0;
						}	
						$sql = "INSERT into members_payment_details (bcid,memid,amountpaid,paiddate,user,lastmoddate,remarks) 
								values ('".$bc_values[0]."', '".$memid."','".$selfamount."','".$bcbiddate."','".$user."', '".$bcbiddate."','Adjusted to Self Account by Bid Amount')";
						if ($mysqli->query($sql) === TRUE)
						{
											$strerror = false;
											$strdescription = "$strdescription" . "<br/>". "Payment Details added to Self Member ID <b>$memid</b> for BC ID <b>$bc_values[0]</b>!!";
						} 
						else 
						{
											$strerror = true;
											$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error 1111!!" . mysqli_error($mysqli);
						}
					}
			}
			
			//	Adjusting company amount to all members when last remaining bid is left
			$bc_values=preg_split("/_/",$bcid);
			$nom1 = $bc_values[3];
			$id=$bc_values[0];
			$sqlbid = "select * from bc_bidding_details where bcid='".$id."' order by bidid desc";
			$resultbid = $mysqli->query($sqlbid);
			
			$rowbid = mysqli_fetch_assoc($resultbid);
			$nom2 = mysqli_num_rows($resultbid);
			$companybal = $rowbid["companybal"];
			
			$strdescription = "$strdescription" . "<br/>". $nom1 ."<br/>" . $nom2;
			if($nom2 == ($nom1-1))
			{
				$strdescription = "$strdescription" . "<br/>". "<p style=\"color:red;\">last bidding is left:: Company Balance of Rs $companybal is adjusted to all members of BC $bc_values[0]</p><br/>";
				$tamtadj = ($companybal/$nom1);
				$ramttobepaid = $bc_values[4]-$tamtadj;
				$strdescription = "$strdescription" . "<br/>". "Amount Adjusted to member is : Rs. $tamtadj <br/>";
				$strdescription = "$strdescription" . "<br/>". "Amount to be paid for last month is Rs. $ramttobepaid";
				
				$sqlbid1 = "select memid from bc_member_mapping where bcid='".$id."'";
				$resultbid1 = $mysqli->query($sqlbid1);
				while($rowbid1 = mysqli_fetch_assoc($resultbid1))
				{
					$amtadj=$tamtadj;
					$memid=$rowbid1["memid"];	
					$sql = "INSERT into members_payment_details (bcid,memid,amountpaid,paiddate,user,lastmoddate,remarks) 
							values ('".$bc_values[0]."', '".$memid."','".$amtadj."','".$bcbiddate."','".$user."', '".$bcbiddate."','Adjusted By Company Balance Amount')";
					if ($mysqli->query($sql) === TRUE)
					{
									$strerror = false;
									$strdescription = "$strdescription" . "<br/>". "Payment Details added to Member ID <b>$memid</b> for BC ID <b>$bc_values[0]</b>!!";
					} 
					else 
					{
									$strerror = true;
									$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error 33333!!" . mysqli_error($mysqli);
					}
					
				}
					
			}
			$mysqli->close();
			
    }
	else if (isset($_POST["remove"]))
	{
        $mysqli = $db->connect();
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
		function companyamt(){
			var bidamount = parseInt(document.getElementById("bidamount").value);
			var bidtype = document.getElementById("bidtype").value;
			var bcid = document.getElementById("selectbc").value;
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
				var bcid = document.getElementById("selectbc").value;
				var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcbiddate = document.getElementById("bcbiddate").value;
				var memid = document.getElementById("selectmem").value;
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
						var lastbcdate = xmlhttp.responseText.split("=");
						alert("Last BC Date = " +lastbcdate[1]);
						alert("Current BC Date="+bcdate)
						if(lastbcdate[1]!="")
						{
							
							if(lastbcdate[1]<bcdate && bidtype=="REGULAR")
							{
								url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
								document.location = url;
							}
							else if(lastbcdate[1] == bcdate && bidtype=="EXTRA")
							{
								url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
								document.location = url;
							}
							else
							{
								alert("BC Date is not valid!!! Select Valid Date1!!");
								document.getElementById("bcdate").value="";
								return false;
							}
						}
						else
						{
								url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
								document.location = url;
						}
					}
				}
				xmlhttp.open("GET","../../getbcdate.php?bcid="+bcid + "&bidtype=" + bidtype,true);
				xmlhttp.send();		
		}
		function selectbiddate(){
				var bcid = document.getElementById("selectbc").value;
				var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcbiddate = document.getElementById("bcbiddate").value;
				if(bcbiddate<bcdate)
				{
					alert("BC Bid Date must be equal or later than BC Date");
					document.getElementById("bcbiddate").value="";
					
					
					return false;
				}	
				var memid = document.getElementById("selectmem").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
		}
        function getbc(str){
            	var bcid = document.getElementById("selectbc").value;
				var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcbiddate = document.getElementById("bcbiddate").value;
				var memid = document.getElementById("selectmem").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
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
            
				var bcid = document.getElementById("selectbc").value;
				var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcbiddate = document.getElementById("bcbiddate").value;
				var memid = document.getElementById("selectmem").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
				
		}
		function exportdata()
		{
			var bcid = document.getElementById("selectbc").value;
			var bidtype = document.getElementById("bidtype").value;
				var bcdate = document.getElementById("bcdate").value;
				var bcbiddate = document.getElementById("bcbiddate").value;
				var memid = document.getElementById("selectmem").value;
			//	alert(bcid);
				var strvalue=bcid.split("_");
				url         = "http://localhost/SKG/admins/BCBidding/export.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid+ "&bidtype=" + bidtype;
				document.location = url;
		}
		function sbidtype()
		{
			var bcid = document.getElementById("selectbc").value;
			var bidtype = document.getElementById("bidtype").value;
			var bcdate = document.getElementById("bcdate").value;
			var bcbiddate = document.getElementById("bcbiddate").value;
			var memid = document.getElementById("selectmem").value;
			//	alert(bcid);
			var strvalue=bcid.split("_");
			url         = "http://localhost/SKG/admins/BCBidding/index.php?bcid=" + strvalue[0] + "&bcdate=" + bcdate + "&bcbiddate=" + bcbiddate + "&memid=" + memid + "&bidtype=" + bidtype;
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
			url         = "http://localhost/SKG/admins/BCBidding/index.php";
			document.location=url;
			return false;
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
								
                                <select class="form-control" name="selectbc" id="selectbc" required tabindex=1 onchange="getbc(this.value)">
								<option value="BC">Select BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details  where flag = 'open'");
										
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"]."_".$row["bc_name"];
                                            
                                    ?>
                                    <option value="<?=$bc?>" <?php if($bcid==$row['bcid']) echo "selected"; else echo '';?>><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
							<tr>
							<table width="700" border="0" align="center">
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
								<th><label>BID Type</label></th>
								<td>
								<select name="bidtype" id="bidtype" required onchange="sbidtype()">
								<option value="">Select Type</option>
								<?php 
									if(isset($_GET['bidtype']))
										$bidtype = $_GET['bidtype'];
									else
										$bidtype = '';
								?>
								<option value="REGULAR" <?php if($bidtype=="REGULAR") echo "selected"; else echo '';?>>REGULAR</option>
								<?php 
									if($flag==1)
									{
								?>
										<option value="EXTRA" <?php if($bidtype=="EXTRA") echo "selected"; else echo '';?>>EXTRA</option>
								<?php
									}
								?>
								</select>
								</td>
							</tr>
							<div class="form-group">
								
								<tr>
								<th><label>Select BC Date</label></th>
								<td><input type="date" name="bcdate" id="bcdate" value="<?php if(isset($_GET['bcdate'])) echo $_GET['bcdate']; else echo '';?>" required tabindex=3 onchange="selectbcdate(this.value)"></input></td>
								</tr>
								<tr />
								
							</div>
							<div class="form-group">
								
								<tr>
								<th><label>Select BC Bidding Date</label></th>
								<td><input type="date" name="bcbiddate" id="bcbiddate" value="<?php if(isset($_GET['bcbiddate'])) echo $_GET['bcbiddate']; else echo '';?>" required tabindex=3 onchange="selectbiddate(this.value)"></input></td>
								</tr>
								<tr />
								
							</div>
							</table>
                            <div class="form-group">
                                <input type="text" id="search" placeholder="Type member name to search" name="search" style="margin: 10px;width: 265px;" onkeyup="filter(this.value)">
								<select class="form-control" name="selectmem" id="selectmem" required tabindex=2 onchange="getmem(this.value)">
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
											$bidresult = $mysqli->query("SELECT count(*) as num from bc_bidding_details where bcid='".$bcid."' and bidbymember='".$memid."'
																  ");
											$count = mysqli_fetch_assoc($bidresult);
											$c = $count["num"];
										//	$mem = "$c " . "$memid" . "->" .$memrow['fname']." ".$memrow['mname']." ".$memrow['lname'];
											if($c==0)
                                            {
												
                                    ?>
                                    <option value="<?=$memid?>" <?php if($gmemid==$row['memid']) echo "selected"; else echo '';?>><?=$mem?></option>
                                    <?php
											}
										}
                                        
                                    ?>
                                </select>
                            </div>
							<div class="form-group">
								<table width="717" border="0" align="center">
								
								
                            	<tr />
								<tr>
								<th><label>BID Amount</label></th>
								<td><input type="text" name="bidamount" id="bidamount" required pattern="[0-9]+" onblur="companyamt()" tabindex=5></input></td>
								</tr>
								<tr />
                            	<tr>
								<th><label>Company Amount</label></th>
								<td><input type="text" name="compamount" id="compamount" readonly pattern="[0-9]+" tabindex=6></input></td>
								</tr>
								<tr />
                            	<tr>
								<th /><label>Balance</label>
								<td /><input type="text" name="balamount" id="balamount" required pattern="[0-9]+" readonly tabindex=7></input>
								</tr>
								<tr>
								<tr>
								<th /><label>Amount Paid To the BID Member</label>
								<td /><input type="text" name="memamount" id="memamount" required pattern="[0-9]+" readonly tabindex=7></input>
								</tr>
							</table>
							</div>
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                <center>
                                <button type="submit" name="payment" value=Add" class="btn btn-lg btn-success" style="margin-top: 20px;">Submit</button>
						<!--		<button type="submit" name="update" value=Add" class="btn btn-lg btn-success" style="margin-top: 20px;">Update Payment</button> 								
								<button type="submit" name="remove" value="remove" class="btn btn-lg btn-danger" style="margin-top: 20px;">Remove Payment</button>	-->
                                </center>
                            </div>
							
							<div class="form-group">
								<?php
									$mysqli = $db->connect();
									$query = "select * from bc_bidding_details ";
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
									
									
									if(isset($_GET['bcbiddate']))
										$bcbiddate=$_GET['bcbiddate'];
									else
										$bcbiddate='';
									
											  
									if($bcid!="BC" and $bcid!="")
										$query = $query . " where bcid='".$bcid."' and";
									else
										$query = $query . " where";
									if($memid!="member" and $memid!="")
										$query = $query . " memid='".$memid."' and";
									else
										$query = $query . "";
									if($bcdate!="")
										$query = $query . " bcdate = '".$bcdate."' and";
									if($bcbiddate!="")
										$query = $query . " biddate='".$bcbiddate."' and";
									$query = substr($query,0,-3);
									$query = $query . " order by bcid,bcdate,biddate,lastmoddate";
							//		echo "$query";
									$result = $mysqli->query($query);
									if($result)
									{
									$sl =0;
								?>
								<div id="printThisTable">
								<table width="1500" border="1" align="center">
								<caption>BC Bidding Details</caption>
								<tr>
									<th>Sl. NO</th>
									<th>BCID</th>
									<th>Bid Member Name</th>
									<th>BC Date</th>
									<th>BID Date</th>
									<th>BID Amount</th>
									<th>Company Amount</th>
									<th>Balance</th>
									<th>Company Balance</th>
									<th>Payment to be Made to Member</th>
									<th>Balance Payment Adjusted to BC</th>
									<th>Net Payment Made to Member</th>
									<th>Bid Type</th>
								</tr>
								<?php
									while($row = mysqli_fetch_assoc($result))
									{
										$sl++;
										$memid = $row['bidbymember'];
										$query1 = "select fname,mname,lname 
											  from members_details
											  where memid='".$memid."'
											  ";
										$result1 = $mysqli->query($query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
										if($row['bidtype']=="EXTRA")
										{
								?>
								<tr style="color:red;">
								
									<th><?php echo $sl;?></th>
									<th><?php $resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$row['bcid']."'");
											  $bcd = mysqli_fetch_assoc($resultbcd);
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$row["bc_name"];
											  echo $bc;
										?></th>
									<th><?php echo $memname;?></th>
									<th><?php echo $row["bcdate"];?></th>
									<th><?php echo $row["biddate"];?></th>
									<th><?php echo $row["bidamount"];?></th>
									<th><?php echo $row['company'];?></th>
									<th><?php echo $row['balance'];?></th>
									<th><?php echo $row['companybal'];?></th>
									<th><?php echo $row['paymentmade'];?></th>
									<th><?php echo $row['amtadjustedtopayment'];?></th>
									<th><?php echo $row['netpayment'];?></th>
									<th><?php echo $row['bidtype'];?></th>
								</tr>
										
								
								<?php	
									}
									else
									{
								?>
									<tr style="color:black;">
								
									<th><?php echo $sl;?></th>
									<th><?php $resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$row['bcid']."'");
											  $bcd = mysqli_fetch_assoc($resultbcd);
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$row["bc_name"];
											  echo $bc;
										?></th>
									<th><?php echo $memname;?></th>
									<th><?php echo $row["bcdate"];?></th>
									<th><?php echo $row["biddate"];?></th>
									<th><?php echo $row["bidamount"];?></th>
									<th><?php echo $row['company'];?></th>
									<th><?php echo $row['balance'];?></th>
									<th><?php echo $row['companybal'];?></th>
									<th><?php echo $row['paymentmade'];?></th>
									<th><?php echo $row['amtadjustedtopayment'];?></th>
									<th><?php echo $row['netpayment'];?></th>
									<th><?php echo $row['bidtype'];?></th>
								</tr>
								<?php		
									}
									}
									}
									?>
							
                            </div>
							 <input type="button" value="Export to Excel" onclick="exportdata()" style="position:absolute;right:-200px;">
							 <input type="button" value="Print" onclick="printdata()" style="position:absolute;right:-80px;">
							 </table>
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
