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
											  
	
 										  
$filename = "BiddingDetails_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

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
									echo "<p align=\"center\"><b>SKG BC Bidding Details</b></p>";
									echo "<p align=\"right\"><b>Date:</b> " . date('d-m-Y') . "</p>";
									if($result)
									{
									$sl =0;
								?>
								<table width="1500" border="1" align="center">
								
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
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
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
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
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