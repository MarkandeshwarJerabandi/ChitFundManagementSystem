<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>BC Creation Form</title>
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
<form id="bc" name="bc" method="post" action="">
<div align="center" class="style1">
  <h1 class="style2">S K Groups</h1>
</div>
<div align="center" class="style3">
  <p>BC Creation Form</p>
  <p>&nbsp;</p>
</div>
<p><hr align="center" />&nbsp;</p>
<table width="353" border="0" align="center">
  
  <tr>
    <td>Select Type</td>
    <td><label>
      <select name="bctype" id="bctype" accesskey="b" tabindex="1" required>
	  <option value="" >Select</option>
	  <option value="single" >Single</option>
	  <option value="twice">Twice</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td>Select Start Date</td>
    <td><input type="date" name="startdate" id="startdate" accesskey="s" required tabindex="2" /></td>
  </tr>
  <tr>
    <td>Number of BC Members</td>
    <td><input type="text" name="bcmembers" id="bcmembers" accesskey="m" tabindex="3" required /></td>
  </tr>
  <tr>
    <td>Amount per BC Member </td>
    <td><input type="text" name="amount" id="amount" accesskey="a" tabindex="4" required /></td>
  </tr>
</table>

  <label>
  <hr align="center" />
  <p>&nbsp;</p>
  <div align="center">
    <input type="submit" name="Submit" value="Create BC" /> 
    <input name="reset" type="reset" value="Reset" />
  </div>
    <p>&nbsp;</p>
</form>
</body>
</html>