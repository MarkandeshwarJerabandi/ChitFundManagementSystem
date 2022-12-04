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
											  
	
 										  
$filename = "CollectionBoyReceipt_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

$mysqli = $db->connect();
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
									$resultbcd = $mysqli->query("SELECT * from bc_details where flag='open'");
									echo "<h2 align=\"center\"><b>SKG BC Management System</b></h2>";
									echo "<h3 align=\"center\"><b>Collection Boy Receipt</b></h3>";
									echo "<p align=\"left\"><b>Name of Collection Boy:  " . $cbname . "</b></p>";
									echo "<p align=\"left\"><b>Date of Collection:  " . $spdate . "</b></p>";
									$sl=0;
									$total=0;
									?>
									<table width="600" border="1" align="center">
										<tr>
											<th>Sl. NO</th>
											<th>BC Name</th>
											<th>Name of Member</th>
											<th>Amount Paid</th>
											
										</tr>
									<?php
									while($bcd = mysqli_fetch_assoc($resultbcd))
									{
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
										$bcid = $bcd['bcid'];
										$resultmem = $mysqli->query("SELECT memid from bc_member_mapping where bcid = '".$bcid."'");
										$bc_name = $bcd['bc_name'];
										$total_of_bc=0;
										while($mem = mysqli_fetch_assoc($resultmem))
										{
											$memid=$mem['memid'];
											$query1 = "select fname,mname,lname 
											  from members_details
											  where memid='".$memid."'
											  ";
											$result1 = $mysqli->query($query1);
											$row1 = mysqli_fetch_assoc($result1);
											$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
											
											$query = "select sum(amountpaid) as amountpaid
											  from members_payment_details where bcid='$bcid' and memid='$memid'";
											if($spdate!="")
												$query = $query . " and paiddate='".$spdate."' and";
											else
												$query = $query . " and";
											if($cbname!="")
												$query = $query . " cbname='".$cbname."' and";
											$query = substr($query,0,-3);
											$query = $query . " order by bcid, memid";
											$result = $mysqli->query($query);
											$num = mysqli_num_rows($result);
											if($num>0)
											{
												$res = mysqli_fetch_assoc($result);
												$amt = $res['amountpaid'];
												if($amt>0)
												{
													$total = $total + $amt;
													$total_of_bc+=$amt;
													$sl++;
													if($sl==1){
										?>
									<!--	<tr  align="center">
											<th colspan="3"><?php // echo $bc; ?></th>
										</tr>	-->
										<?php
												}
										?>
										
											<tr align="center">
												<td><?php echo $sl; ?></td>
												<td><?php echo $bc_name;?></td>
												<td><?php echo $memname;?></td>
												<td><?php echo $amt;?></td>
												
											</tr>
										<?php
												}	
											}
												
										}
										?>
										<tr align="center">
												<th colspan="3" ><?php echo "Total of BC";?></th>
												<th><?php echo $total_of_bc;?></th>
										</tr>
										<?php
									}
									
                                    ?>
									
									<tr>
										<th colspan="2">Grand Total Amount Collected</th>
										<th><?php echo $total;?></th>
									</tr>
									<tr></tr>
									<tr></tr>
									<tr>
										<th>Signature of Collection Boy</th>
										<th/>
										<th>Signature of Authority</th>
									</tr>
								</table>