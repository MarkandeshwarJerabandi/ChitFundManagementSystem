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
											  
	
 										  
$filename = "BiddingPaymentDetails_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

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
								<table width="1000" border="1" align="center">
								<tr>
									<th colspan="10" align="center">SKG BC Bidding Payment Details</th>
								</tr>
								<tr>
									<th colspan="10" align="right">Date: <?php echo date('d-m-Y');?></th>
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