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
        echo "<html><head><title></title></head><body><h2>You do not have administrative priviliges!!</h2><br/>Please click <a href='../'>here</a> to return to homepage</body></html>";
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
									$bcid="";
									$memid="";
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
									$sql = "select startdate,amount,type,bcmembers from bc_details where bcid ='".$bcid."'";
										$result1 = $mysqli->query($sql);
										$row = mysqli_fetch_assoc($result1);
										$startdate = $row['startdate'];
									//	$startdate = date($startdate);
									//	echo $startdate;
										$bcamount=$row['amount'];
										$bctype=$row['type'];
										$total_months = $row['bcmembers'];
										/* retrieve number of extra bidding */
										$esql = "SELECT count(*) as extra FROM `bc_bidding_details` where `bcid` = '".$bcid."' and bidtype='EXTRA'";
										$eresult1 = $mysqli->query($esql);
										$erow = mysqli_fetch_assoc($eresult1);
										$no_of_extra = $erow['extra'];
										
										$no_of_months = $total_months - $no_of_extra;
							
										$enddate = date('Y-m-d');
										$diff = abs(strtotime($enddate) - strtotime($startdate));
										$years = floor($diff / (365*60*60*24));
										$months = ($years*12)+floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
										$diff_of_months = $no_of_months - $months;
									
										if($diff_of_months<=0)
										{
											$diff_of_months--;
											$enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " $diff_of_months month");
											$enddate = date("Y-m-d",$enddate);
										}	
										else
											$enddate = date('Y-m-d');
										$currentdate=$enddate;
										$newdate=$startdate;
										$s1=0;
										$flag=0;
										$resultbcd = $mysqli->query("SELECT * from bc_details where bcid = '".$bcid."'");
										$bcd = mysqli_fetch_assoc($resultbcd);
										$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
										$bc_values=preg_split("/_/",$bc);
								?>
								<table width="700" border="1" align="center">
								<tr>
									<th>Sl. NO</th>
							<!--		<th>BCID</th>		-->
									<th>Name of Member</th>
									<th>Amount Due</th>
								</tr>
								<?php
									$grandtotal=0;
									if($bcid!="BC" and $bcid!="" and $memid!="mem" and $memid!="")
									{
										$flag=1;
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
									<tr align="center">
										
										<th  colspan="3" style="color:red;"><?php echo "BC: " . $bc;?></th>
									</tr>
									<tr>
										<th></th>
									</tr>
									<?php
										$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$bcid."'");
										$noofbids = mysqli_num_rows($result2);
										$result = $mysqli->query("select bcid, memid, sum(amountpaid) as amountpaid,count(*) as records 
													from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
											//$records = mysqli_num_rows($result);
										$row=mysqli_fetch_assoc($result);
										$totalamounttobepaid = $noofbids * $bc_values[4];
										$s1++;
									?>
										<tr>
													<th><?php echo $s1;?></th>
													<th><?php echo $memname;?></th>
										<!--			<th><?php echo $noofbids;?></th>	-->
													
									<?php
									/*	while($bcdates = mysqli_fetch_array($result2))
										{
											$newdate = $bcdates["bcdate"];
											
										*/	
									//		echo $row['records'];
										?>
											<!--			<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	-->
											<!--		<th><?php //echo $newdate;?></th>	-->
										<?php
									//	}
										if($row['records']>0)
										{
												
										?>	
											<!--		<th><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	
													
													<th><?php echo $totalamounttobepaid;?></th>
													<th><?php echo $row['amountpaid'];?></th>		-->
													<th><?php $balance=($totalamounttobepaid-$row['amountpaid']);echo $balance;$totalbalance += $balance;?></th>
												</tr>
										<?php
										}
										else
											{
								//				echo "entered";
										?>
											<!--		<th><?php // if($noofbids==0) echo "Still Bidding not done";?></th>	
													<th><?php echo $totalamounttobepaid;?></th>
													<th><?php echo '0';?></th>		-->
													<th><?php $balance=($totalamounttobepaid-$row['amountpaid']);echo $balance;$totalbalance += $balance;?></th>
												</tr>
										<?php
											}	
											?>
										<tr>
														<th></th>
										</tr>
										<tr>
															<b><th colspan="2">Balance Due</th>
															<th style="color:red;"><?php echo $totalbalance;?></th></b>
										</tr>
										<tr>
										<th></th>
										</tr>
										<?php
										$grandtotal += $totalbalance;
									}
									else if($bcid!="BC" and $bcid!="" and $memid=="mem" or $memid=="")
									{
									//	$bc_values=preg_split("/_/",$bcid);
										$flag=1;
									//	echo "here";
										$temp=$startdate;
									//	echo $temp;
										$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
									//	$members = mysqli_num_rows($memres);
									//	echo $members;
										?>
										<tr>
											<th></th>
										</tr>
										<tr>
										
													<th colspan="3" style="color:red;"><?php echo "BC:" . $bc;?></th>
										</tr>
										<tr>
										<th></th>
										</tr>
										<?php
										$grandtotal=0;
										$totalbalance=0;
										$s1=0;
										while($members = mysqli_fetch_assoc($memres))
										{
												$startdate=$temp;
												$newdate=$startdate;
												$memid=$members["memid"];
												$noofmembers=$bc_values[3];
											//	echo $newdate;
											//	echo $currentdate;
												$query1 = "select fname,mname,lname 
														  from members_details
														  where memid='".$memid."'
														  ";
												$result1 = $mysqli->query($query1);
												$row1 = mysqli_fetch_assoc($result1);
												$memname = $row1['fname']." ".$row1['mname']." ".$row1['lname'];
												
												
												$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$bcid."' order by bcdate ASC");
												$noofbids = mysqli_num_rows($result2);
												$totalamounttobepaid = $noofbids * $bc_values[4];
												
												$result = $mysqli->query("select bcid,memid,sum(amountpaid) as amountpaid,count(*) as records 
																	from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
													//$records = mysqli_num_rows($result);
												$row=mysqli_fetch_assoc($result);
												
												$s1++;
												?>
													<tr >
																<th rowspan="<?php //if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $s1;?></th>
																<th rowspan="<?php //if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $memname;?></th>
														<!--		<th><?php echo $noofbids;?></th>	-->
																
												<?php
												
												
										/*		while($bcdates = mysqli_fetch_assoc($result2))
												{
													$newdate = $bcdates["bcdate"];
												*/	
												?>
														
													<!--		<th><?php //echo $newdate;?></th></tr>	-->
															
														
												<?php
											//	}	
											//		echo $memid;
													
											//		echo $row['records'];													
													if($row['records']>0)
													{
												?>
														
												<!--			<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $totalamounttobepaid;?></th>
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo $row['amountpaid'];?></th> -->
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													else											
													{
										//				echo "entered";
												?>
														
															<!--			<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php //if($noofbids==0) echo "Still Bidding not done";?></th>	
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>" ><?php echo $totalamounttobepaid;?></th>
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php echo '0';?></th> -->
															<th rowspan="<?php // if($noofbids>0) echo $noofbids; else echo "1";?>"><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													
												?>
														
												<?php
												}
												
												?>
														<tr>
															<th></th>
														</tr>
													<tr>
															<th colspan="2"><b>Balance Due</b></th>
															<th style="color:red;"><?php echo $totalbalance;?></th>
													</tr>
													<tr>
													<th></th>
													</tr>
													
													
												<?php
												$grandtotal += $totalbalance;
										}
								//	}
									if($flag==0)
									{
									//	echo "$bcid" . "$gmemid";
										$bcres=$mysqli->query("select * from bc_details");
										
										while($bcd = mysqli_fetch_assoc($bcres))
										{
											$bcid=$bcd['bcid'];
									//		echo $bcid;	
											$temp=$bcd['startdate'];
											$bcamount=$bcd['amount'];
											$bc = $bcd["bcid"]."_".$bcd["type"]."_".$bcd["startdate"]."_".$bcd["bcmembers"]."_".$bcd["amount"]."_".$bcd["totalbcamount"]."_".$bcd["bc_name"];
											$memres=$mysqli->query("select memid from bc_member_mapping where bcid='".$bcid."'");
											$bgrandtotal=0;
										?>
										<tr>
															<th></th>
														</tr>
										<tr>
											<th align="center" colspan="3" style="color:red;"><?php echo "BC: " . $bc;?></th>
										</tr>
										<tr>
											<th></th>
										</tr>
										<?php
											$totalbalance=0;
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
													
													
													$result2 = $mysqli->query("SELECT distinct bcdate from bc_bidding_details where bcid='".$bcid."'");
													$noofbids = mysqli_num_rows($result2);
													$totalamounttobepaid = $noofbids * $bcd["amount"];
													
													$result = $mysqli->query("select bcid,memid,sum(amountpaid) as amountpaid,count(*) as records 
																		from members_payment_details where bcid='".$bcid."' and memid='".$memid."'");
														//$records = mysqli_num_rows($result);
													$row=mysqli_fetch_assoc($result);
													
													$s1++;
													?>
														<tr>
																	<th rowspan="<?php// echo $noofbids;?>"><?php echo $s1;?></th>
																	<th rowspan="<?php //echo $noofbids;?>"><?php echo $memname;?></th>
															<!--		<th><?php echo $noofbids;?></th>	-->
																	
													<?php
													
										/*			while($bcdates = mysqli_fetch_assoc($result2))
												{
													$newdate = $bcdates["bcdate"];
										*/
												?>
														
													<!--		<th><?php // echo $newdate;?></th>		-->
															
														
												<?php
											//	}
											//		echo $memid;
													
											//		echo $row['records'];													
													if($row['records']>0)
													{
												?>
															<!--		<th><?php // if($noofbids==0) echo "Still Bidding not done";?></th>	
															<th ><?php echo $totalamounttobepaid;?></th>
															<th ><?php echo $row['amountpaid'];?></th>  -->
															<th ><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													else											
													{
										//				echo "entered";
												?>
															<!--		<th><?php // if($noofbids==0) echo "Still Bidding not done";?></th>	
															<th ><?php echo $totalamounttobepaid;?></th>
															<th><?php echo '0';?></th>		-->
															<th ><?php $balance=($totalamounttobepaid-$row['amountpaid']); echo $balance; $totalbalance += $balance;?></th>
														</tr>
												<?php
													}
													?>
													
												<?php
												}
												
												?>
												<tr>
															<th></th>
														</tr>
													<tr>
															<th colspan="2"><b>Balance Due</b></th>
															<th style="color:red;"><?php echo $totalbalance;?></th>
													</tr>
													<tr>
													<th></th>
													</tr>
													
												<?php
												$grandtotal += $totalbalance;
										}
									}
									?>
									<tr>
										<th colspan="2"><b>Grand Total Due of All BC</b></th>
										<th style="color:red;"><?php echo $grandtotal;?></th>
									</tr>
								</table>