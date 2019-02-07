<?php
	require($_SERVER['DOCUMENT_ROOT']."/listings/includes/manage.php");

	session_start();
	if(isset($_SESSION['user_email'])){
		$name = $_SESSION['user_email'];
		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$name'");
		$user_id = mysql_result($query, 0, "user_id");
		$users_name = mysql_result($query, 0, "name");
		$log_out = "<div id='nav_options_div4'><a href='includes/logout.php'>Log Out</a></div>";
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
		Welcome to Listings
	</title>

	<link type="text/css" rel="stylesheet" href="main.css" />

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

		<div id="category_listings">
			<?php
				echo $removed;
				$manage_db = new manage_db();
				$query_category = $manage_db->return_query("SELECT * FROM categories WHERE category='events locations'");
				$category_id = mysql_result($query_category, 0, "category_id");
				$query = $manage_db->return_query("SELECT * FROM listings WHERE user_id='$user_id'");
				$num = mysql_num_rows($query);

				while($row = mysql_fetch_array($query)){
					 $listing_id = $row['listing_id'];
					echo "<div id='category_listing_container'>
								<div id='category_listing'>
								<div id='listing_title'><h3>".$row['listing']."</h4></div>
								<div id='listing_detail'>
								<form action='manage_posts.php' method='POST'>
									<input type='hidden' name='listing_id' value='".$listing_id."' />
									<input type='submit' name='delete_listing' id='delete_listing' title='delete this listing' value='x' />
								</form>";
					$i++;
					echo $row['details']."</div></div><!--category listing-->
					<div id='listing_img'>
						<img src='images/paperbag.png' style='width: 120px; height: 120px' />
					</div><!--listing img-->
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