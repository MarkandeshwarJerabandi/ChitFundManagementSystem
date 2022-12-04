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
											  
	
 										  
$filename = "Payment_Due_Details_" . date('Y-m-d');											  
$file_ending = "xls";			

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character

echo "<p align=\"center\"><b>SKG BC Payment Due Details</b></p>";
echo "<p align=\"right\"><b>Date:</b> " . date('d-m-Y') . "</p>";
$mysqli = $db->connect();
									if(isset($_GET['bcid']))
										$bcid=$_GET['bcid'];
									else
										$bcid='';
								//	echo "bcid = $bcid";
									
									
									if(isset($_GET['memid']))
										$memid=$_GET['memid'];
									else
										$memid='';
									$startdate="";	
									$sql = "select startdate,amount,type from bc_details where bcid ='".$bcid."'";
										$result1 = $mysqli->query($sql);
										$row = mysqli_fetch_assoc($result1);
										$startdate = $row['startdate'];
										$bcamount=$row['amount'];
										$bctype=$row['type'];
										$enddate = date('Y-m-d');
									/*	$d = date("d",strtotime($enddate));
										if($d<=15)
											$currentdate =	date("Y-m-01",strtotime($enddate));
										else
											$currentdate =	date("Y-m-16",strtotime($enddate));
										*/
										$currentdate=$enddate;
										$newdate=$startdate;
										$s1=0;
										
										$resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$bcid."'");
										$bcd = mysqli_fetch_assoc($resultbcd);
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
										$bc_values=preg_split("/_/",$bc);
										$noofmembers=$bc_values[3];
								?>
								<table width="900" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
								<!--	<th>BCID</th>	-->
									<th>Name of Member</th>
									<th>Date of BC</th>
									<th>Amount Paid</th>
									<th>Balance</th>
								</tr>
								<?php
									if($bcid!="BC" and $bcid!="" and $memid!="mem" and $memid!="")
									{
										$query1 = "select fname,mname,lname 
														  from members_details
														  where memid='".$memid."'
														  ";
										
										$result1 = $mysqli->query($query1);
										$row1 = mysqli_fetch_assoc($result1);
										$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
										$totalbalance=0;
									?>
									<tr>
															<th></th>
														</tr>
										<tr>
											<th align="center" colspan="5"><?php echo "BC: " . $bc;?></th>
										</tr>
										<tr>
											<th></th>
										</tr>
									<?php
										while($newdate<=$currentdate and $noofmembers>0)
										{
											$s1++;
											$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
											//$records = mysqli_num_rows($result);
											$row=mysqli_fetch_assoc($result);
									//		echo $row['records'];
											
											if($row['records']>0)
											{
												
										?>
										
												<tr>
													<th><?php echo $s1;?></th>
											<!--		<th><?php echo $bc;?></th>	-->
													<th><?php echo $memname;?></th>
													<th><?php echo $row["dateofbc"];?></th>
													<th><?php echo $row['amountpaid'];?></th>
													<th><?php $balance=($bcamount-$row['amountpaid']);echo $balance;$totalbalance += $balance;?></th>
												</tr>
										<?php
											}
											else											
											{
								//				echo "entered";
										?>
												<tr>
													<th><?php echo $s1;?></th>
											<!--		<th><?php echo $bc;?></th>	-->
													<th><?php echo $memname;?></th>
													<th><?php echo $newdate;?></th>
													<th><?php echo '0';?></th>
													<th><?php echo $bcamount;$totalbalance += $bcamount;?></th>
												</tr>
										<?php
											}
											if($bc_values[1]=="Single")
							$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
						else
						{
									
										$d = date("d",strtotime($startdate));
										//echo $date . " ";
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
										$noofmembers--;
										}
										?>
										<tr>
															<th colspan="4"><b>Balance Due</b></th>
															
															<th><?php echo $totalbalance;?></th>
										</tr>
										<?php
									}
									else if($bcid!="BC" and $bcid!="" and $memid=="mem" or $memid=="")
									{
										$bcres=$mysqli->query("select * from bc_details where bcid='".$bcid."' ");
										$bcd = mysqli_fetch_assoc($bcres);
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
										$temp=$startdate;
										$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
									?>
									<tr>
															<th></th>
														</tr>
										<tr>
											<th align="center" colspan="5"><?php echo "BC: " . $bc;?></th>
										</tr>
										<tr>
											<th></th>
										</tr>
									<?php
										while($members = mysqli_fetch_assoc($memres))
										{
												$startdate=$temp;
												$newdate=$startdate;
												$memid=$members["memid"];
												$noofmembers=$bc_values[3];
												$query1 = "select fname,mname,lname 
														  from members_details
														  where memid='".$memid."'
														  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);
												$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
												$totalbalance=0;
												while($newdate<=$currentdate and $noofmembers>0)
												{
													$s1++;
													$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
													//$records = mysqli_num_rows($result);
													$row=mysqli_fetch_assoc($result);
											//		echo $row['records'];
													
													if($row['records']>0)
													{
														
												?>
												
														<tr>
															<th><?php echo $s1;?></th>
												<!--			<th><?php echo $bc;?></th>	-->
															<th><?php echo $memname;?></th>
															<th><?php echo $row["dateofbc"];?></th>
															<th><?php echo $row['amountpaid'];?></th>
															<th><?php $balance=($bcamount-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													else											
													{
										//				echo "entered";
												?>
														<tr>
															<th><?php echo $s1;?></th>
													<!--		<th><?php echo $bc;?></th>		-->
															<th><?php echo $memname;?></th>
															<th><?php echo $newdate;?></th>
															<th><?php echo '0';?></th>
															<th><?php echo $bcamount;$totalbalance += $bcamount;?></th>
														</tr>
												<?php
													}
													if($bc_values[1]=="Single")
							$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
						else
						{
									
										$d = date("d",strtotime($startdate));
										//echo $date . " ";
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
									$noofmembers--;
												}
												?>
													<tr>
															<th colspan="4"><b>Balance Due</b></th>
															<th><?php echo $totalbalance;?></th>
													</tr>
												<?php
										}
									}
									else if($bcid=="BC" or $bcid=="" and $gmemid=="mem" or $gmemid=="")
									{
									//	echo "$bcid" . "$gmemid";
										$bcres=$mysqli->query("select * from bc_details");
										while($bcd = mysqli_fetch_assoc($bcres))
										{
											$bcid=$bcd['bcid'];
											$temp=$bcd['startdate'];
											$bcamount=$bcd['amount'];
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"];
											$bc_values=preg_split("/_/",$bc);
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
										?>
										<tr>
															<th></th>
														</tr>
										<tr>
											<th align="center" colspan="5" style="color:blue;"><?php echo "BC: " . $bc;?></th>
										</tr>
										<tr>
											<th></th>
										</tr>
										<?php
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
														$s1++;
														$result = $mysqli->query("select bcid,memid,dateofbc,sum(amountpaid) as amountpaid,count(*) as records from members_payment_details where bcid='".$bcid."' and memid='".$memid."' and dateofbc='".$newdate."'");
														//$records = mysqli_num_rows($result);
														$row=mysqli_fetch_assoc($result);
												//		echo $row['records'];
														
														if($row['records']>0)
														{
															
													?>
													
															<tr>
																<th><?php echo $s1;?></th>
														<!--		<th><?php echo $bc;?></th>	-->
																<th><?php echo $memname;?></th>
																<th><?php echo $row["dateofbc"];?></th>
																<th><?php echo $row['amountpaid'];?></th>
																<th><?php $balance=($bcamount-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
															</tr>
													<?php
														}
														else											
														{
											//				echo "entered";
													?>
															<tr>
																<th><?php echo $s1;?></th>
															<!--	<th><?php echo $bc;?></th>	-->
																<th><?php echo $memname;?></th>
																<th><?php echo $newdate;?></th>
																<th><?php echo '0';?></th>
																<th><?php echo $bcamount;$totalbalance += $bcamount;?></th>
															</tr>
													<?php
														}
														if($bc_values[1]=="Single")
							$startdate = date ("Y-m-d", strtotime("+1 month", strtotime($startdate)));
						else
						{
									
										$d = date("d",strtotime($startdate));
										//echo $date . " ";
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
													?>
														<tr>
															<th></th>
														</tr>
														<tr>
																<th colspan="4"><b>Balance Due</b></th>
																<th><?php echo $totalbalance;?></th>
														</tr>
														<tr>
															<th></th>
														</tr>
													<?php
											}
										}
									}
									?>
									
								</table>