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
	if(isset($_REQUEST["selectbc"]))
				$bcid = $_REQUEST["selectbc"];
			else
				$bcid = '';
			if(isset($_REQUEST["bcskip"]))
				$bcskip = $_REQUEST["bcskip"];
			else
				$bcskip = '';
			
	
    if (isset($_POST["payment"]))
	{
			$mysqli = $db->connect();
		//	$bcid=$_POST["selectbc"];
			if(isset($_REQUEST["selectbc"]))
				$bcid = $_REQUEST["selectbc"];
			else
				$bcid = '';
			if(isset($_REQUEST["bcskip"]))
				$bcskip = $_REQUEST["bcskip"];
			else
				$bcskip = '';
			
			$bc_values=preg_split("/_/",$bcid);
			$bcid = $bc_values[0];
			
		//	echo $bcid . " " . $bcskip;
			
			$sql = "Update bc_details SET
							months_skipped = '".$bcskip."',
							flag = '1'
							where bcid = '".$bcid."'
					";
			if ($mysqli->query($sql) === TRUE)
			{
					$strerror = false;
					$strdescription = "$strdescription" . "<br/>". "The number of months skipped is added to BC ID <b>$bcid</b></b>!!";
			} 
			else 
			{
					$strerror = true;
					$strdescription = "$strdescription" . "<br/>". "Database Update Error: self!!" . mysqli_error($mysqli);
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
		
		function clickconfirm()
		{
			var c = confirm("Are you sure you want to Submit the data?");
		//	alert(c);
			if(c==true)
				return true;
			else
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
                                        $result = $mysqli->query("SELECT * from bc_details");
										
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
								<table width="717" border="0" align="center">
								
								
                            	
								<tr>
								<th><label>Enter Number of Months to be Skipped</label></th>
								<td><input type="text" name="bcskip" id="bcskip" required pattern="[0-9]+" tabindex=2></input></td>
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
