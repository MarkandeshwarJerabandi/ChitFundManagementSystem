<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "skg";
$filename = "excelfilename";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//$SQL = " select bcid,memid,dateofbc,amountpaid,balance,paiddate,remarks 
										//	  from members_payment_details ";
											  
//$result = mysqli_query($conn, $SQL);										  
											  
	
 										  
											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

$query = "select bcid,memid,dateofbc,amountpaid,balance,paiddate,remarks 
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
									
									
									if(isset($_GET['bcdate']))
										$bcdate=$_GET['bcdate'];
									else
										$bcdate='';
									
									
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
									if($bcdate!="")
										$query = $query . " dateofbc = '".$bcdate."' and";
									if($spdate!="")
										$query = $query . " paiddate='".$spdate."' and";
									$query = substr($query,0,-3);
									$query = $query . " order by bcid, memid";
							//		echo "$query";
									$result = mysqli_query($conn,$query);
									if($result)
									{
									$sl =0;
?>
								<table width="900" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
									<th>BCID</th>
									<th>Name of Member</th>
									<th>Date of BC</th>
									<th>Amount Paid</th>
									<th>Balance</th>
									<th>Paid Date</th>
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
										$result1 = mysqli_query($conn,$query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
								?>
								<tr>
									<th><?php echo $sl;?></th>
									<th><?php $resultbcd = mysqli_query($conn,"SELECT * from bc_details where bcid = '".$row['bcid']."'");
											  $bcd = mysqli_fetch_assoc($resultbcd);
                                              $bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											  echo $bc;
										?></th>
									<th><?php echo $memname;?></th>
									<th><?php echo $row["dateofbc"];?></th>
									<th><?php echo $row['amountpaid'];?></th>
									<th><?php echo $row['balance'];?></th>
									<th><?php echo $row['paiddate'];?></th>
									<th><?php echo $row['remarks'];?></th>
								</tr>
							<?php
							  } }
							  ?>