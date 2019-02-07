<?php
	require("includes/manage.php");

	session_start();
	if(isset($_SESSION['user_email'])){
		$name = $_SESSION['user_email'];
		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$name'");
		$user_id = mysql_result($query, 0, "user_id");
		$users_name = mysql_result($query, 0, "name");
		$log_out = "<div id='nav_options_div4'><a href='includes/logout.php'>Log Out</a></div>";
	}

	if(!isset($_SESSION['user_email'])){
		$name = "<form action='index.php' method='POST'><input type='text' name='email' /><input type='text' name='password' /><input type='submit' name='login' value='Log In' /></form>".$login_error;
	}else{
		if($_SESSION['user_email'] == "utopm7@gmail.com"){
			unset($_SESSION['user_email']);
			$name = "<form action='index.php' method='POST'><input type='text' name='email' /><input type='text' name='password' /><input type='submit' name='login' value='Log In' /></form>";
		}else{
			$name = "<a href='manage_posts.php'>".$_SESSION['user_email']."</a>";
			$log_out = "<div id='nav_options_div4'><a href='includes/logout.php'>Log Out</a></div>";
		}
	}

	if(isset($_POST['region'])){
		$region = $_POST['region_val'];
		if($region == "All Regions"){
			$in_region = " in ".$region;
			$sql_region = "";
		}else{
			$in_region = " in ".$region." Region";
			$sql_region = " AND region='".$region."'";
		}
	}else{
		$region = "";
		$sql_region = "";
	}

	if(isset($_POST['login'])){
		$email = $_POST['email'];
		$password = $_POST['password'];

		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$email' AND password='$password'");
		if(mysql_num_rows($query) == 1){
			$_SESSION['user_email'] = $email;
		}else{
			$login_error = "Wrong email or password. try again!";
		}
	}

	$removed = "";
	if(isset($_POST['delete_listing'])){
		$listing_id = $_POST['listing_id'];
		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM listings WHERE listing_id='$listing_id'");
		$image = mysql_result($query, 0, "images");

		if(strlen(image) > 0){
			unlink($image);
		}

		if($manage_db->return_query("DELETE FROM listings WHERE listing_id='$listing_id'")){
			$removed = "lising removed successfully!";
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
		function getXMLHttp(){
			if(window.XMLHttpRequest){
				return new XMLHttpRequest();
			}else if(window.ActiveXObject){
				return new ActiveXObject("Microsoft.XMLHTTP");
			}else{
				alert("Sorry, Your browser does not support Ajax!");
			}
		}

		function filter_listing(category_id){
			var http = getXMLHttp();

			http.open("POST", "includes/display_listings.php", true);

			http.onreadystatechange = function(){
				if(http.readyState == 4 && http.status == 200){
					var listing = http.responseText.split("[BRK]");
					var listings = "";

					var num = listing.length;
					for(var i=1; i<num; i++){
						listings = listings+listing[i]+"<br />";
					}
					document.getElementById('listings').innerHTML = listings;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("category_id="+category_id);
		}
	</script>
</head>

<body>
	<div id="panel">
		<div id="nav_options">
			<?php echo $log_out; ?>
			<div id="nav_options_div3"><a href="create_account.php">Create Account<a/></div>
			<div id="nav_options_div2"><?php echo $name; ?></div>
			<div id="nav_options_div1"><a href="index.php">Home</a></div>
			</div>
	</div>

	<div id="wrapper">
	<br /><br /><br />
			<form action="post_listing.php" method="POST">
				<input type="image" src="images/button.png" name="go_post" value="POST A LISTING" style="float:right" />
			</form>

		<div id="wrapper1">
				<a href="index.php"><img src="images/logo.png" border="0" /></a>
				<!--
				<div id="ads3">
					Ads
				</div>-->
		</div><!--wrapper1-->

		<div id="wrapper2">
			<h3>Decorations and Interior Design <?php echo $in_region; ?></h3>

		<div id="category_listings">
			<?php
				echo $removed;
				$manage_db = new manage_db();
				$query_category = $manage_db->return_query("SELECT * FROM categories WHERE category='decorations and interior design services'");
				$category_id = mysql_result($query_category, 0, "category_id");
				$query = $manage_db->return_query("SELECT * FROM listings WHERE category_id='$category_id' $sql_region ORDER BY listing_id DESC");
				$num = mysql_num_rows($query);

				if($num == 0){
					echo "<div style='text-align: center; padding: 10px; font-weight: bold'>No enteries yet!</div>";
				}

				while($row = mysql_fetch_array($query)){
					 $listing_id = $row['listing_id'];
					 $user_id = $row['user_id'];
					 $listing_ref = $row['listing_ref'];

					 $query_image = $manage_db->return_query("SELECT * FROM images WHERE listing_ref='$listing_ref' ORDER BY image_id");

					 if(mysql_num_rows($query_image) > 0){
					 	$image = mysql_result($query_image, 0, "image");
					 	$image = "user_data/".$image;
					 }else{
					 	$image = "";
					 }

					 if($user_id != 0){
					 $query_user = $manage_db->return_query("SELECT * FROM users WHERE user_id='$user_id'");
					$name = mysql_result($query_user, 0, "name");
					}else{
						$name = "";
					}

					 echo "<div id='category_listing_container'>
								<div id='category_listing'>
								<div id='listing_title'><form action='details.php' method='POST'><input type='hidden' value='".$row['listing_id']."' name='listing_id' /><input type='submit' value='".$row['listing']."' name='listing' /></form></div>
								<div id='listing_detail'>";

								$details = $row['details'];
								if(strlen($details) > 120){
									$details = substr($details, 0, 120);
									$details = $details."...";
								}
								$price = $row['price'];
								$rate = $row['rate'];
								if($rate != "-- --"){
									$rate = " per ".$rate;
								}else{
									$rate = "";
								}
					$i++;
					echo $details."<br /><br /><b>".$price.$rate."</b></div></div><!--category listing-->
					<div id='listing_img'>";

					if(strlen($image) > 0){
						echo "<img src='".$image."' style='width: 120px; vertical-align: middle; display: table-cell; text-align: center' />";
					}else{
						echo "<img src='images/paperbag.png' style='width: 120px; height: 120px' />";
					}

					echo "</div><!--listing img-->
					</div><!--category listing container-->";
				}
			?>
		</div>
		</div><!--wrapper2-->
	</div><!--wrapper-->

	<div id="copyright">
		<center>Rentkama - Copyright &#169; <?php echo date("Y"); ?><center>
	</div><!--copyright-->
</body>

</html>