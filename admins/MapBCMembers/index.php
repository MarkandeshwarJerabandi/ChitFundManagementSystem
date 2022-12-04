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
        echo "<html><head><title>SKG</title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
        die();
    }
    $strerror = false;
    $strdescription = "";
	if(isset($_REQUEST["bcid"]))
		$gbcid = $_REQUEST["bcid"];
	else
		$gbcid = '';
//	echo "$gbcid";
    if (isset($_POST["Add"]))
	{
			$mysqli = $db->connect();
			if($_POST["selectbc"]!="map")
			{
				$bcid=$_POST["selectbc"];
				$user = $_SESSION["skguser"];
				$date = date("Y-m-d");
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
						if($count["c"]>0)
						{
							$strerror = false;
							$strdescription = "$strdescription" . "<br/>"." Member ID <b>$memid</b> is already Added to BC ID <b>$bcid</b>!!";
						} 
						else 
						{
							$sql = "INSERT into bc_member_mapping (bcid,memid,user,lastmoddate) 
									values ('".$bcid."', '".$memid."','".$user."', '".$date."')";
							if ($mysqli->query($sql) === TRUE)
							{
								$strerror = false;
								$strdescription = "$strdescription" . "<br/>". "Member ID <b>$memid</b> is ADDED to BC ID <b>$bcid</b>!!";
							} 
							else 
							{
								$strerror = true;
								$strdescription = "$strdescription" . "<br/>". "Database Add/Insert Error!!" . mysqli_error($mysqli);
							}
						}
						
					}
				}
			
           
				$mysqli->close();
			}
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
							if ($mysqli->query($sql) === true)
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
	$mysqli = $db->connect();
	$res = $mysqli->query("select bcid, memid from bc_member_mapping");
	while($row = mysqli_fetch_assoc($res))
	{
		$bcid = $row['bcid'];
		$memid = $row['memid'];
		$id = $bcid . "_" . $memid;
	//	echo $id;
		if(isset($_POST[$id]))
		{
		//	echo "you selected $id";
			$bidres = $mysqli->query("select count(*) as num1 from bc_bidding_details where bcid='".$bcid."' and bidbymember='".$memid."'");
			$r1 = mysqli_fetch_assoc($bidres);
			$num1 = $r1['num1'];
		//	echo "bid  count = " . $num1;
			$payres = $mysqli->query("select count(*) as num2 from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
			$r2 = mysqli_fetch_assoc($payres);
			$num2 = $r2['num2'];
		//	echo "pay  count = " . $num2;
			if($num1==0 and $num2==0)
			{
				$res1 = $mysqli->query("delete from bc_member_mapping where bcid='".$bcid."' and memid='".$memid."'");
				if($res1)
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
			else
			{
				$strerror = true;
				$strdescription = "$strdescription" . "<br/>". "Bidding Details and Payment details are not empty for member id $memid !!So Cannot delete" . mysqli_error($mysqli);
			}
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
        function getbc(str){
            if (str == "map"){
                url         = "http://localhost/SKG/admins/MapBCMembers/index.php?bcid=" + str;
				document.location = url;
				return;
            }
			else
			{
				url         = "http://localhost/SKG/admins/MapBCMembers/index.php?bcid=" + str;
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
		function countmem()
		{
			var bcid = document.getElementById("selectbc").value;
			var flag = true;
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
                    var bcmembers = strvalue[2];
					var already_mapped_count = strvalue[4];
				//	alert(bcmembers);
				//	alert(already_mapped_count);
					var inputs = CheckboxContainer.getElementsByTagName("input");
				//	alert(inputs.length);
					var checked = 0;   
					for (var i = 0; i < inputs.length; i++) 
					{   
						//var input = inputs[i];
						if( inputs[i].type === "checkbox" && inputs[i].checked === true)
						{
							checked++;
						//	alert(parseInt(checked)+parseInt(already_mapped_count));
							if(parseInt(checked)+parseInt(already_mapped_count) > parseInt(bcmembers)) 
							{ 
								alert("You have exceeded maximum count!!! Unselect Some!!!"); 
								inputs[i].checked=false;
								return false;
							}	
						}	
					}
					
					
						
                }
				
            }
            xmlhttp.open("GET","../../getbcmemcount.php?bcid="+bcid,true);
            xmlhttp.send();
			
		}
		function del_confirm()
		{
			var c = confirm("Are you sure you want to delete?");
			if(c==true)
				return true;
			else
				return false;
		}
		function clickconfirm()
		{
			var c = confirm("Are you sure you want to Map?");
		//	alert(c);
			if(c==true)
				return true;
			else
				return false;
		}
		function filter() {
			var keyword = document.getElementById("search").value;
			var select = document.getElementById("selectbc");
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
                    <h1 class="page-header"><i class="fa fa-users fa-fw"></i> Add Members to BC</h1>
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
                    <form role="form" method="post" action="index.php" onsubmit="return clickconfirm();">
                        <fieldset>
						
                            <div class="form-group">
								<input type="text" id="search" placeholder="Type BC details to search" name="search" style="margin: 10px;width: 265px;" onkeyup="filter(this.value)">
                                <select class="form-control" name="selectbc" id="selectbc" required onchange="getbc(this.value)">
								<option value="map">Select BC</option>
                                    <?php
                                        $mysqli = $db->connect();
                                        $result = $mysqli->query("SELECT * from bc_details  where flag = 'open'");
                                        while($row = mysqli_fetch_assoc($result)){
                                            $bc = $row["bcid"]."_".$row["type"]."_".$row["startdate"]."_".$row["bcmembers"]."_".$row["amount"]."_".$row["totalbcamount"]."_".$row["bc_name"];
                                            
                                    ?>
                                    <option value="<?=$row['bcid']?>" <?php if($gbcid==$row['bcid']) echo "selected"; else echo '';?>><?=$bc?></option>
                                    <?php
                                            
                                        }
                                        $mysqli->close();
                                    ?>
                                </select>
                            </div>
                            <div id="CheckboxContainer" class="CheckboxContainer">
							<?php
								if($gbcid!="map" and $gbcid!='')
								{
									$mysqli = $db->connect();
									$res1 = $mysqli->query("select count(*) as totalmap from bc_member_mapping where bcid = '".$gbcid."' ");
									$result1 = mysqli_fetch_assoc($res1);
									$totalmap = $result1['totalmap'];
							//		echo $totalmap;
									$res2 = $mysqli->query("SELECT * from bc_details where bcid = '".$gbcid."'");
									$result2 = mysqli_fetch_assoc($res2);
									$bcmembers=$result2['bcmembers'];
							//		echo $bcmembers;
									if($totalmap<$bcmembers)
									{
							?>
							<label>Select Members to Add</label>	 
								<table width="417" border="0" align="center">
								<tr >
									<th align="center">Sl. NO</th>
									<th align="center">Mem ID</th>
									<th align="center">Member Name</th>
									<th align="center">Select</th>
								</tr>
								  <?php
                                        $mysqli = $db->connect();
									/*	if(isset($_POST["selectbc"]))
											$bcid=$_POST["selectbc"];
										else
											$bcid='';
										echo $bcid;	*/
                                        //$result = $mysqli->query("SELECT * from members_details");
										$result = $mysqli->query("SELECT * from members_details");
										$s=0;
										$flag=0;
                                        while($row = mysqli_fetch_assoc($result))
										{
											$s++;
											$mapres = $mysqli->query("SELECT count(*) as num from bc_member_mapping
																	where bcid='".$gbcid."' and memid='".$row['memid']."'
																	");
											$count = mysqli_fetch_assoc($mapres);
											if($count['num']==0)
											{
												$flag=1;
									?>
										<tr align="left">
											<td><?php echo $s;?></td>
											<td><?php echo $row["memid"];?></td>
											<td><?php echo $row["fname"]." ".$row["mname"]." ".$row["lname"];?></td>
											<td><input type="checkbox" name="<?php echo $row["memid"];?>" id="<?php echo $row["memid"];?>" value="<?php echo $row["memid"];?>" onchange="return countmem()"></input></td>
										</tr>	
									<?php	
											}
										}
										
								  ?>
								  
								</table>
							<?php
								if($flag==0)
											echo "<b style=\"color:red;\">NO MEMBERS TO ADD TO BC</b>";
								}
								else
										echo "<p style=\"color:red;\">Members mapping reached to Maximum!! Cannot add any more members!!!!</p>";
								
							?>	
                            </div>
                            <!-- Change this to a button or input when using this as a form -->
                            <div class="btn-group inline">
                                <center>
                                <button type="submit" name="Add" value=Add" class="btn btn-lg btn-success" style="margin-top: 20px;">Add BC Member</button> 
								
                                </center>
                            </div>
							<?php
									}
									
							?>
                                
							<div class="form-group">
								<?php
									$mysqli = $db->connect();
									$query = "select * from bc_member_mapping";
									if(isset($_GET['bcid']))
										$bcid=$_GET['bcid'];
									else
										$bcid='';
							//		echo "bcid = $bcid";
									
									if($bcid!="BC" and $bcid!="")
										$query = $query . " where bcid='".$bcid."'";
									
									$query = $query . " order by bcid,memid";
							//		echo "$query";
									$result = $mysqli->query($query);
									$records=mysqli_num_rows($result);
									if($records>0)
									{
									$sl =0;
								?>
								<table width="700" border="1" align="center">
								<caption>BC and Member Mapping Details</caption>
								<tr>
									<th>Sl. NO</th>
									<th>BCID</th>
									<th>Member Name</th>
									<th>Self/Others</th>
									
									<th>Click To Delete</th>
								</tr>
								<?php
									while($row = mysqli_fetch_assoc($result))
									{
										$sl++;
										$memid = $row['memid'];
										$query1 = "select fname,mname,lname,type 
											  from members_details
											  where memid='".$memid."'
											  ";
										$result1 = $mysqli->query($query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
										
								?>
								<tr>
								
									<th><?php echo $sl;?></th>
									<?php $resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$row['bcid']."'");
											  $bcd = mysqli_fetch_assoc($resultbcd);
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
									?>
									<th><?php echo $bc;?></th>
									<th><?php echo $memname;?></th>
									<th><?php echo $row1["type"];?></th>
									<th><input type="submit" name="<?php echo $bcd['bcid'] . "_" . $memid;?>" id="<?php echo $bcd['bcid'] . "_" . $memid;?>" value="Delete" onclick="return del_confirm();"></input></th>
									
								</tr>								

								<?php	
									}
								}
								?>
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
