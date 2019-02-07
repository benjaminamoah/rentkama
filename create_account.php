<?php
	require("includes/manage.php");
	$login_error = "";

	if(isset($_POST['create_account'])){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];
		$time = time();

		$create_account_error = 0;

		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$email'");
		if(mysql_num_rows($query) == 0){
			$create_account_error = $create_account_error + 1;
			if(strlen($name) == 0 || strlen($email) == 0 || strlen($password) == 0){
				$reg_error = "<div style='background-color: #fac; color: red; padding: 10px; border: 2px solid #f84; text-align: center'>All fields must be filled!</div>";
			}else if($password != $confirm_password){
				$reg_error = "<div style='background-color: #fac; color: red; padding: 10px; border: 2px solid #f84; text-align: center'>Please make sure you retyped the same password!</div>";
			}else{
				if($manage_db->return_query("INSERT INTO users VALUES(null, '$name', '$email', '$password', '', '$time')")){
					$create_account_error = $create_account_error + 1;
				}
			}
		}else{
			$reg_error = "<div style='background-color: #fac; color: red; padding: 10px; border: 2px solid #f84; text-align: center'>Sorry! the email belongs to someone else.</div>";
		}

		if($create_account_error == 2){
			session_start();
			$_SESSION['user_email'] = $email;
			header("location:manage_posts.php");
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
</head>

<body>
	<div id="panel">
		<div id="nav_options_div1"><a href="index.php">Home</a></div>
	</div>

		<div id="wrapper_access_account">
		<div id="wrapper_access_account1">
			<center>
				<a href="index.php"><img src="images/logo.png" border="0" /></a><br />
			</center><br />
		</div>

		<div id="access_account">
			<form action="create_account.php" method="POST">
				Choose a Name: (e.g. business name, nickname etc)<br />
				<input type="text" name="name" id="name" /><br />
				Your Email:<br />
				<input type="text" name="email" id="email" /><br />
				Choose Your Password:<br />
				<input type="password" name="password" id="password" /><br />
				Retype Password:<br />
				<input type="password" name="confirm_password" id="confirm_password" />
				<input type="submit" name="create_account" id="create_account" value="DONE" />
				<a href="index.php" style="font-weight: bold; margin-left: 10px; color: #44f">Cancel</a>
				<?php echo "<br />".$reg_error; ?>
			</form>
		</div>

		<div id="ads3">
			Ads
		</div>

		</div><!--wrapper_access_account-->

	<div id="copyright">
		<center>Rentkama - Copyright &#169; <?php echo date("Y"); ?><center>
	</div><!--copyright-->
</body>

</html>