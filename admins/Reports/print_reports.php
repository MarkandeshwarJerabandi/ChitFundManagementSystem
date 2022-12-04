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
											  
	
 										  
$filename = "PaymentDetails_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
									$mysqli = $db->connect();
									$query = "select bcid,memid,amountpaid,paiddate,cbname,remarks from members_payment_details";
									if(isset($_GET['bcid']))
										$bcid=$_GET['bcid'];
									else
										$bcid='';
								//	echo "bcid = $bcid";
									
									
									if(isset($_GET['memid']))
										$memid=$_GET['memid'];
									else
										$memid='';
									
									
									
									
									
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
									if($bcid!="BC" and $bcid!="")
										$query = $query . " where bcid='".$bcid."' and";
									else
										$query = $query . " where";
									if($memid!="mem" and $memid!="")
										$query = $query . " memid='".$memid."' and";
									else
										$query = $query . "";
									
									if($spdate!="")
										$query = $query . " paiddate='".$spdate."' and";
									if($cbname!="")
										$query = $query . " cbname='".$cbname."' and";
									$query = substr($query,0,-3);
									$query = $query . " order by bcid , memid , paiddate ";
								//	echo "$query";
									$result = $mysqli->query($query);
									if($result)
									{
									$sl =0;
								?>
								<div id="printThisTable">
								<table width="1100" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
									<th>BCID</th>
									<th>Name of Member</th>
									<th>Amount Paid</th>
									<th>Paid Date</th>
									<th>Amount Collected By</th>
									<th>Remarks</th>
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
								<tr>
									<th><?php echo $sl;?></th>
									<th><?php $resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$row['bcid']."'");
											  $bcd = mysqli_fetch_assoc($resultbcd);
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											  echo $bc;
										?></th>
									<th><?php echo $memname;?></th>
									
									<th><?php echo $row['amountpaid'];?></th>
									
									<th><?php echo $row['paiddate'];?></th>
									<th><?php echo $row['cbname'];?></th>
									<th><?php echo $row['remarks'];?></th>
								</tr>
								<?php	
									}
									}
									
									
								
									$mysqli->close();
							  ?>