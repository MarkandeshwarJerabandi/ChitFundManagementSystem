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
											  
	
 										  
$filename = "PaymentSlip_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
		
										$mysqli = $db->connect();
									$query = "select bcid,memid,amountpaid,paiddate,remarks 
											  from members_payment_details ";
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
									$query = substr($query,0,-3);
									$query = $query . " order by bcid, memid, paiddate";
							//		echo "$query";
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
									<th>Remarks</th>
								</tr>
								<?php
									$totalamountpaid=0;
									$id=0;
									$amount=0;
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
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
											  $id = $bcd["bcid"];
											  $amount = $bcd["amount"];
											  echo $bc;
										?></th>
									<th><?php echo $memname;?></th>
									<th><?php echo $row['amountpaid'];$totalamountpaid=$totalamountpaid+$row['amountpaid'];?></th>
									
									<th><?php echo $row['paiddate'];?></th>
									<th><?php echo $row['remarks'];?></th>
								</tr>
								<?php	
									}
									?>
									<tr style="color:red;font-weight:bold italic;">
										<th colspan="2">
										<?php
										$id = $_GET["bcid"];
										$memid = $_GET["memid"];
										if(isset($_GET["spdate"]))
											$spdate=$_GET["spdate"];
										else	
											$spdate="";
									//	echo "spdate=" . $spdate;
										$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$id."' order by bcdate ASC");
										$noofbids = mysqli_num_rows($result2);
										
										$result3 = $mysqli->query("SELECT bcmembers from bc_details where bcid='".$id."'");
										$r = mysqli_fetch_array($result3);
									//	echo "$memid";
									if($spdate=="")
									{
										if($memid=="mem")
											$noofmems = $r["bcmembers"];
										else if($memid>0)
											$noofmems = 1;
									//	echo "Total members: " . $noofmems;
										if($id>0)
											echo "Total Amount to be Paid: " . $noofmems * $noofbids * $amount;
										?>
										</th>
										<th colspan="1"><?php echo "Total Paid: ";?>
										<th><?php echo $totalamountpaid;?></th>
										<?php
										if($id>0)
										{
										?>
										<th colspan="1">Balance:</th>
										<th colspan="1"><?php echo ($noofmems * $noofbids * $amount)-$totalamountpaid;?> </th>
									</tr>
									<?php
										}
									}
									else
									{
									?>
										</th>
										<th colspan="1"><?php echo "Total Paid: ";?>
										<th><?php echo $totalamountpaid;?></th>
									<?php										
									}
									}
									
									
								?>
							
                            
							 </table>