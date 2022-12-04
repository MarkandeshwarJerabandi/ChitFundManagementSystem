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
										<th>Payment to Members Till Date</th>
										<th>Cash in Hand</th>
										<th></th>
										<th>Due Balance From Members</th>
										<th></th>
										<th>Net Cash Balance of Company</th>
										<th></th>
										<th>Advance Cash Payment by Members</th>
										<th>Total Company Balance</th>
									</tr>
									<tr>
										
										<?php 
											$resultbcd = $mysqli->query("SELECT * from bc_details");
											$tcollection = 0;
											$tcompany = 0;
											$tpaymentmade = 0;
											$tcash_in_hand = 0;
											$tduebalance = 0;
											$tnet_cash_balance = 0;
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
												  where bcid='".$bcid."' AND remarks != 'advance cash payment'
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
												$paymentmade = $row1['paymentmade'];
												echo $row1['company'];
										?>
										</th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th>
										<?php
											echo $row1['paymentmade'];
										?>
										</th>
										<th style="text-align:center;color:red; font-size:20px;">
										<?php
											$cash_in_hand = $amountpaid - $company - $paymentmade;
											echo $cash_in_hand;
										?>
										</th>
										
										<th style="color:green;font-size:40px;text-align:center;">+</th>
										<th>
										<?php
											// if(isset($_GET['bcid']))
												// $bcid=$_GET['bcid'];
											// else
												// $bcid='';
											// $bcres=$mysqli->query("select * from bc_details where bcid='".$bcid."'");
											// $bcd = mysqli_fetch_assoc($bcres);
											$temp=$bcd['startdate'];
											$enddate = date('Y-m-d');
											$currentdate=$enddate;
											$bcamount=$bcd['amount'];
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
											$bgrandtotal=0;
											$grandtotal=0;
											while($members = mysqli_fetch_assoc($memres))
											{
													
														$startdate=$temp;
														$newdate=$startdate;
														$memid=$members["memid"];
														$query1 = "select fname,mname,lname 
																  from members_details
																  where memid='".$memid."'
																  ";
														$result1 = $mysqli->query($query1);
														$row1 = mysqli_fetch_assoc($result1);
														$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
														$totalbalance=0;
														while($newdate<=$currentdate)
														{
													//		$s1++;
															$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
															//$records = mysqli_num_rows($result);
															$row=mysqli_fetch_assoc($result);
													//		echo $row['records'];
															
															if($row['records']>0)
															{
																$balance=($bcamount-$row['amountpaid']); 
																$totalbalance += $balance;
															}
															else											
															{
																$totalbalance += $bcamount;
															}
															if($bcd["type"]=="Single")
																$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
															else
															{
																$d = date("d",strtotime($startdate));
																switch($d)
																{
																	case 1:$startdate = date ("Y-m-15", strtotime($startdate));break;
																	case 2:$startdate = date ("Y-m-16", strtotime($startdate));break;
																	case 3:$startdate = date ("Y-m-17", strtotime($startdate));break;
																	case 4:$startdate = date ("Y-m-18", strtotime($startdate));break;
																	case 5:$startdate = date ("Y-m-20", strtotime($startdate));break;
																	case 6:$startdate = date ("Y-m-21", strtotime($startdate));break;
																	case 7:$startdate = date ("Y-m-22", strtotime($startdate));break;
																	case 8:$startdate = date ("Y-m-23", strtotime($startdate));break;
																	case 9:$startdate = date ("Y-m-24", strtotime($startdate));break;
																	case 10:$startdate = date ("Y-m-25", strtotime($startdate));break;
																	case 11:$startdate = date ("Y-m-26", strtotime($startdate));break;
																	case 12:$startdate = date ("Y-m-27", strtotime($startdate));break;
																	case 13:$startdate = date ("Y-m-28", strtotime($startdate));break;
																	case 14:$startdate = date ("Y-m-29", strtotime($startdate));break;
																	case 15:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-01", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-01", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-01", strtotime($startdate));
																			}
																				
																			break;	
																			
																	case 16:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-02", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-02", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-02", strtotime($startdate));
																			}
																			break;
																	case 17:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																	//		echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-03", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-03", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-03", strtotime($startdate));
																			}
																			break;
																	case 18:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-04", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-04", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-04", strtotime($startdate));
																			}
																			break;
																	case 20:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-05", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-05", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-05", strtotime($startdate));
																			}
																			break;
																	case 21:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-06", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-06", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-06", strtotime($startdate));
																			}
																			break;
																	case 22:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-07", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-07", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-07", strtotime($startdate));
																			}
																			break;
																	case 23:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-08", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-08", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-08", strtotime($startdate));
																			}
																			break;
																	case 24:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-09", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-09", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-09", strtotime($startdate));
																			}
																			break;
																	case 25:
																			$mm = date("m",strtotime($startdate))+1;
																			$yy = date("Y",strtotime($startdate));
																		//	echo $yy;
																			if($mm>12)
																			{
																				$yy++;
																				$mm=1;
																				$startdate = date ("$yy-0$mm-10", strtotime($startdate));
																			}	
																			else
																			{
																				if($mm<=9)
																					$startdate = date ("Y-0$mm-10", strtotime($startdate));
																				else
																					$startdate = date ("Y-$mm-10", strtotime($startdate));
																			}
																			break;
																	
																}
										
															}
															$newdate=$startdate;
														}														
														$bgrandtotal += $totalbalance;
											}
											$grandtotal += $bgrandtotal;
											echo $grandtotal;
											$duebalance = $grandtotal;
										?>
										</th>										
										<th style="color:green;text-align:center;font-size:40px;">=</th>
										<th> 
										<?php $net_cash_balance = $cash_in_hand + $grandtotal; echo $net_cash_balance;?>
										</th>
										<th style="color:green;text-align:center;font-size:40px;">+</th>
										<th>
										<?php
												$query1 = "select sum(amountpaid) as amountpaid 
												  from members_payment_details 
												  where bcid='".$bcid."' AND remarks = 'advance cash payment'
												  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);	
												$advanceamount = $row1['amountpaid'];
												echo $advanceamount;
										?>
										</th>
										<th>
											<?php
												$total = $net_cash_balance + $advanceamount;
												echo $total;
											?>
										</th>
									</tr>
									<?php
											$tcollection += $collection;
											$tcompany += $company;
											$tpaymentmade += $paymentmade;
											$tcash_in_hand += $cash_in_hand;
											$tduebalance += $duebalance;
											$tnet_cash_balance += $net_cash_balance;
											$tadvanceamount += $advanceamount;
											$ttotal += $total;
											}
									?>
									<tr>
										<th>Grand Total</th>
										<th> <?php echo $tcollection;?></th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th> <?php echo $tcompany;?></th>
										<th style="font-size:40px;text-align:center;color:red;">-</th>
										<th> <?php echo $tpaymentmade;?></th>
										<th style="font-size:20px;text-align:center;color:red;"> <?php echo $tcash_in_hand;?></th>
										<th style="font-size:40px;text-align:center;color:green;">+</th>
										<th> <?php echo $tduebalance;?></th>
										<th style="font-size:40px;text-align:center;color:green;">=</th>
										<th> <?php echo $tnet_cash_balance;?></th>
										<th style="font-size:40px;text-align:center;color:green;">+</th>
										<th> <?php echo $tadvanceamount;?></th>
										
										<th> <?php echo $ttotal;?></th>
									</tr>
								</table>
							</div>
							<?php
							$mysqli->close();
							?>
                </div>
					
                        </fieldset>