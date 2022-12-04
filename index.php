<?php
	include 'crypto.php';
	$strdescription="";
	ob_start();
	system('ipconfig /all');
	$mycom=ob_get_contents(); 
	ob_clean(); 
/*	$findme = "Physical";
	$pm = strpos($mycom, $findme); 
	$textm=substr($mycom,($pm+36),17);
	$crypt = new crypto();
	$text2 = $crypt->cypher($textm);
	$text1="GlpZdCqi1a0q51WsPLQkwYHTUbUsxZeOWlrqntg+JRA=";
	if($text2==$text1)
	{	*/
		include 'connect.php';
		session_start();
		$userid = $password = "";
		$_SESSION["skguser"] = null;
		$_SESSION["skgpass"] = "";
		$_SESSION = array();
		session_unset();
		session_destroy();
		$strdescription="";
		
		date_default_timezone_set('Asia/Calcutta');

		if (isset($_POST["login"])=="login")
		{
			$crypt = new crypto();
			$username = $_POST["uname"];
		   // $password = $crypt->cypher($_POST["pwd"]);
			$password = $_POST["pwd"];
			$db = new connectdb();
			$mysqli = $db->connect();
			$result = $mysqli->query("SELECT count(*) as uid from users where username='".$username."' and password='".$password."'");
			$count = mysqli_fetch_assoc($result);
			$uid = $count["uid"];
			//laPl/yAxc7PEe/lLnDFTiYfC/y6AArGoMdX28eynq2o=;
			if ($uid > 0){
				$result = $mysqli->query("SELECT * from users where username='".$username."' and password='".$password."'");
				$row = mysqli_fetch_assoc($result);
				session_start();
				$_SESSION["skguser"] = $row["username"];
				$_SESSION["skgpass"] = $row["password"];
				$utype = $row["type"];
				$mysqli->close();
				if ($utype == 'admin'){
					header("Location: admins/");
				} else {
					header("Location: operators/");
				}
				die();
			} else {
				$strdescription="Invalid Username/Password";
				$mysqli->close();
			}
		}	
	//} 
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SKG BC Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" style="text-align: center;margin-top:20px;">
            <h1><strong>SKG</strong><br/>BC Management System</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default" style="margin-top:20px;">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-sign-in fa-fw"></i> Please Sign In</h3>
                        <?php
                            if ($strdescription){
                        ?>
                        <br/>
                        <h5 style="color:#ff0000;"><?=$strdescription?></h4>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="index.php">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="uname" type="text" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="pwd" type="password" value="" required>
                                </div>
                                
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" name="login" value="login" class="btn btn-lg btn-success btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
