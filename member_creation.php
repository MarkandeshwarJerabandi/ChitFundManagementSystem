<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Members Creation Form</title>
<style type="text/css">
<!--
.style1 {color: #0000CC}
.style2 {font-family: "Times New Roman", Times, serif}
.style3 {
	color: #0033CC;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body>
<form id="member" name="member" method="post" action="">
<div align="center" class="style1">
  <h1 class="style2">S K Groups</h1>
</div>
<div align="center" class="style3">
  <p>Members Registration Form</p>
  <p>&nbsp;</p>
</div>
<p><hr align="center" />&nbsp;</p>
<table width="417" border="0" align="center">
  <tr>
    <td width="226">First Name </td>
    <td width="181">
      <label>
      <input type="text" name="fname" id="fname" accesskey="f" tabindex="1" required value="sas" />
      </label></td>
  </tr>
  <tr>
    <td>Middle Name </td>
    <td><label>
      <input type="text" name="mname" id="mname" accesskey="m" tabindex="2" />
    </label></td>
  </tr>
  <tr>
    <td>Last Name </td>
    <td><input type="text" name="lname" id="lname" accesskey="l" tabindex="3"  required/></td>
  </tr>
  <tr>
    <td>Residential Address</td>
    <td><label>
      <textarea name="raddress" id="raddress" accesskey="r" tabindex="4" required ></textarea>
    </label></td>
  </tr>
  <tr>
    <td>Is Office Address is same as Residential Address?</td>
    <td><label>
    <input name="off_res" id="off_res" type="radio" value="yes" tabindex="5"/>
    Yes 
    <input name="off_res" id="off_res" type="radio" value="no" tabindex="6"/>
    NO</label></td>
  </tr>
  <tr>
    <td>If NO, Office Address</td>
    <td><label>
      <textarea name="oaddress" id="oaddress" accesskey="o" tabindex="7"></textarea>
    </label></td>
  </tr>
  <tr>
    <td>Mobile Number </td>
    <td><input type="text" name="mobile" id="mobile" accesskey="m" tabindex="8" required /></td>
  </tr>
  <tr>
    <td>Land Line</td>
    <td><input type="text" name="landline" id="landline" accesskey="l" tabindex="9" /></td>
  </tr>
  <tr>
    <td>Alternate Contact Number</td>
    <td><input type="text" name="altmobile" id="altmobile" accesskey="a" tabindex="10" /></td>
  </tr>
  <tr>
    <td>Email ID </td>
    <td><input type="text" name="emailid" id="emailid" accesskey="e" tabindex="11" /></td>
  </tr>
</table>

  <label>
  <hr align="center" />
  <p>&nbsp;</p>
  <div align="center">
    <input type="submit" name="Submit" value="Add Member" /> 
    <input name="reset" type="reset" value="Reset" />
  </div>
    <p>&nbsp;</p>
</form>
</body>
</html>