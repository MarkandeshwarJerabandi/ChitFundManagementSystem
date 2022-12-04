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
											  
	
 										  
$filename = "CashBalanceDetails_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

echo "<p align=\"center\"><b>SKG BC Cash Balance Details</b></p>";
echo "<p align=\"right\"><b>Date:</b> " . date('d-m-Y') . "</p>";
$mysqli = $db->connect();                        
?>
	<table width="1100" border="1" align="center">
									<tr>
										<th>BCID</th>
										<th>Total Collection From Members</th>
										<th></th>
										<th>Commission Amount</th>
										<th></th>
										<th>Total Collection</th>
										<th></th>
										<th>Till Date Payment to Members </th>
										<th></th>
										<th>Cash in Hand</th>
									</tr>
									<tr>
										
										<?php 
											$resultbcd = $mysqli->query("SELECT * from bc_details");
											$tcollection = 0;
											$tcompany = 0;
											$tpaymentmade = 0;
											$tcash_in_hand = 0;
											$tactual_collection = 0;
											$ttotal_collection= 0;
											$tadvanceamount = 0;
											$ttotal = 0;
											while($bcd = mysqli_fetch_assoc($resultbcd))
											{
												$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
										?>
										<th>
										<?php
												echo $bc;
												$bcid = $bcd["bcid"];
											?></th>
										<th>
										<?php
												$query1 = "select sum(amountpaid) as amountpaid 
												  from members_payment_details 
												  where bcid='".$bcid."'
												  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);	
												$amountpaid = $row1['amountpaid'];
												echo $row1['amountpaid'];
												$collection = $row1['amountpaid'];
										?>
										</th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th>
										<?php
												$query1 = "select sum(company) as company, sum(paymentmade) as paymentmade 
													  from bc_bidding_details
													  where bcid='".$bcid."'
													  ";
													$result1 = $mysqli->query($query1);
													$row1 = mysqli_fetch_assoc($result1);
													$company = $row1['company'];
													
													$query2 = "select sum(actual_payment) as paymentmade 
													  from bc_bidding_payment_details
													  where bcid='".$bcid."'
													  ";
													$result2 = $mysqli->query($query2);
													$row2 = mysqli_fetch_assoc($result2);
													$paymentmade = $row2['paymentmade'];
												
										
												
												echo $row1['company'];
										?>
										</th>
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										<th>
										<?php
											$actual_collection = $collection - $company;
											echo $actual_collection;
										?>
										</th>
								<!--		<th style="color:green;font-size:40px;text-align:center;">+</th>
										<th>
										<?php
									/*			$query1 = "select sum(amountpaid) as amountpaid 
												  from members_payment_details 
												  where bcid='".$bcid."' AND remarks = 'advance cash payment'
												  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);	
												$advanceamount = $row1['amountpaid'];
												echo $advanceamount;	*/
										?>
										</th>
										<th style="color:green;font-size:40px;text-align:center;">=</th>
										<th>
										<?php
										//	$total_collection = $actual_collection + $advanceamount;
										//	echo $total_collection;
										?>
										</th>	-->
										<th style="color:green;font-size:40px;text-align:center;">-</th>
										<th>
										<?php
											$till_date_payment_to_members = $paymentmade;
											echo $till_date_payment_to_members;
										?>
										</th>
										<th style="color:green;font-size:40px;text-align:center;">=</th>
										<th style="text-align:center;color:red; font-size:20px;">
										<?php
											$cash_in_hand = $actual_collection - $till_date_payment_to_members;
											echo $cash_in_hand;
										?>
										</th>
									</tr>
									<?php
											$tcollection += $collection;
											$tcompany += $company;
											$tactual_collection += $actual_collection;
										//	$tadvanceamount += $advanceamount;
										//	$ttotal_collection += $actual_collection;
											$tpaymentmade += $paymentmade;
											$tcash_in_hand += $cash_in_hand;
											
											}
									?>
									<tr>
										<th>Grand Total</th>
										<th> <?php echo $tcollection;?></th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th> <?php echo $tcompany;?></th>
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										<th> <?php echo $tactual_collection;?></th>
									<!--	<th style="font-size:40px;text-align:center;color:green;">+</th>
										<th> <?php echo $tadvanceamount;?></th>
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										<th> <?php echo $ttotal_collection;?></th>	-->
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th> <?php echo $tpaymentmade;?></th>
										<th style="font-size:40px;text-align:center;color:red;">=</th>
										<th style="font-size:20px;text-align:center;color:red;"> <?php echo $tcash_in_hand;?></th>										
									</tr>
								</table>
							</div>
							<?php
							$mysqli->close();
							?>
                </div>
					
                        </fieldset>