<?php
	require("includes/manage_db.php");
	$login_error = "";

	if(isset($_POST['login'])){
		$email = $_POST['email'];
		$password = $_POST['password'];

		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$email' AND password='$password'");
		if(mysql_num_rows($query) == 1){
			session_start();
			$_SESSION['user_email'] = $email;
			header("location:post_listing.php");
		}else{
			$login_error = "Please check your email or password for any mistakes and try again.";
		}
	}


	if(isset($_POST['just_post'])){
		session_start();
		$_SESSION['user_email'] = "utopm7@gmail.com";
		header("location:post_listing.php");
	}


	if(isset($_POST['create_account'])){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$time = time();

		$create_account_error = 0;

		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$email'");
		if(mysql_num_rows($query) == 0){
			$create_account_error = $create_account_error + 1;
			if($manage_db->return_query("INSERT INTO users VALUES(null, '$name', '$email', '$password', '', '$time')")){
				$create_account_error = $create_account_error + 1;
			}
		}else{
			$login_error = "Sorry! the email belongs to someone else.";
		}

		if($create_account_error == 2){
			session_start();
			$_SESSION['user_email'] = $email;
			header("location:post_listing.php");
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>
		Welcome to Rentkama
	</title>

	<link type="text/css" rel="stylesheet" href="main.css" />
	<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >

	<script type="text/javascript">
		function create_account(){
			document.getElementById("access_account").innerHTML = '	<form action="access_account.php" method="POST">Choose a Name: (e.g. business name, nickname etc)<br /><input type="text" name="name" id="name" /><br />Your Email:<br /><input type="text" name="email" id="email" /><br />Your Password:<br /><input type="text" name="password" id="password" /><input type="submit" name="create_account" id="create_account" value="CREATE ACCOUNT" /></form>';
			document.getElementById("access_account_options").style.height = "0px";
			document.getElementById("access_account_options").style.overflow = "hidden";
			document.getElementById("access_account_options").style.padding = "0px";
		}
	</script>
</head>

<body>
	<div id="panel">
		<div id="nav_options">
			<?php echo $log_out; ?>
			<div id="nav_options_div3"><a href="create_account.php">Create Account<a/></div>
			<div id="nav_options_div1"><a href="index.php">Home</a></div>
		</div>
	</div>

		<div id="wrapper_access_account">
		<div id="wrapper_access_account1">
			<center>
				<a href="index.php"><img src="images/logo.png" border="0" /></a><br />
			</center><br />
		</div>

		<div id="access_account">
		<b>Log In</b><br />
			<form action="access_account.php" method="POST">
				Your Email:<br />
				<input type="text" name="email" id="email" /><br />
				Your Password:<br />
				<input type="text" name="password" id="password" />
				<input type="submit" name="login" id="login" value="Log In!" />
				<?php echo "<br />".$login_error; ?>
			</form>
		</div>

		<div id="access_account_options">
			<br />
			<center><b>Don't have an account?</b></center>
			<br />
			<!--<center><input type="submit" name="create" id="create" value="CREATE ONE!" onClick="create_account()"/></center>-->
			<form action="create_account.php" method="POST">
				<center><input type="submit" name="create_account_btn" id="create_account_btn" value="CREATE ONE!" /></center>
			</form>

			<br />
			<center><b>- OR -</b></center>
			<br />
			<form action="access_account.php" method="POST">
				<center><input type="submit" name="just_post" id="just_post" value="POST WITHOUT AN ACCOUNT" /></center>
			</form>
		</div>

		</div>
		</div><!--wrapper_access_account-->

	<div id="copyright">
		<center>Rentkama - Copyright &#169; <?php echo date("Y"); ?><center>
	</div><!--copyright-->
</body>

</html>