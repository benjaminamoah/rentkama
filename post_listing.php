<?php
	require("includes/display.php");

	session_start();

	$posted = "";

	if(!isset($_SESSION['user_email'])){
		header("location:access_account.php");
	}else{
		if($_SESSION['user_email'] == "utopm7@gmail.com"){
			$manage_db = new manage_db();
			$email = "";
			$log_out = "";
			$disclaimer = "<br /><center><b>Please note that you won't be able to edit this advertisement later since you are not posting with an account! If you would like to edit this advertisement later, please <a href='access_account.php'>login or creat an account</a> first.</b></center><br />";
		}else{
			$email = $_SESSION['user_email'];
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM users WHERE email='$email'");
			$user_id = mysql_result($query, 0, "user_id");
			$name = mysql_result($query, 0, "name");
			$name2 = "<a href='manage_posts.php'>".$_SESSION['user_email']."</a>";
			$log_out = "<div id='nav_options_div4'><a href='includes/logout.php'>Log Out</a></div>";
		}
	}

	if(isset($_POST['post'])){
		$category = $_POST['category'];
		$category = addslashes($category);
		$listing = $_POST['listing'];
		$listing = addslashes($listing);
		$details = $_POST['details'];
		$details = addslashes($details);
		$price = $_POST['price'];
		$rate = $_POST['rate'];
		$region = $_POST['region'];
		$location = $_POST['location'];
		$location = addslashes($location);
		$telephone = $_POST['telephone'];
		$telephone = addslashes($telephone);
		$time = time();

		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM categories WHERE category='$category'");
		$category_id = mysql_result($query, 0, "category_id");

		include('SimpleImage.php');
		$image1 = new SimpleImage();

		if(isset($_FILES['files'])){
			$listing_ref = $time."_".$_SESSION['user_email'];

			$errors= array();
			foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
				$file_name = $listing_ref.$key.$_FILES['files']['name'][$key];
				$file_size =$_FILES['files']['size'][$key];
				$file_tmp =$_FILES['files']['tmp_name'][$key];
				$file_type=$_FILES['files']['type'][$key];
				if($file_size > 2097152){
					$errors[]='File size must be less than 2 MB';
				}
				$query="INSERT INTO images VALUES(null,'$listing_ref','$file_name'); ";
				$desired_dir="user_data";
				if(empty($errors)==true){
					if(is_dir($desired_dir)==false){
						mkdir("$desired_dir", 0700);		// Create directory if it does not exist
					}

					if(isset($_FILES['files']) && !empty($_FILES['files']['name'][$key])){
						$image1->load($_FILES['files']['tmp_name'][$key]);
						$image1->resizeToHeight(306);
						$image1->save($file_tmp);
					}

					if(is_dir("$desired_dir/".$file_name)==false){
						move_uploaded_file($file_tmp,"user_data/".$file_name);
					}else{									//rename the file if another one exist
						$new_dir="user_data/".$file_name.time();
						 rename($file_tmp,$new_dir) ;
					}
					if(isset($_FILES['files']) && !empty($_FILES['files']['name'][$key])){
						mysql_query($query);
					}
				}else{
					print_r($errors);
				}
			}
			if(empty($error)){
				//echo "Success";
			}
		}

		if($manage_db->return_query("INSERT INTO listings VALUES(null, '$category_id', '$user_id', '$listing', '$region', '$location', '$details', '$price', '$rate', '$telephone', '$time', '$image', '$listing_ref', '')")){
			$posted = "<div style='background-color: #ff4; width: 280px; margin: auto; margin-bottom: -20px; text-align: center; padding:5px;'>Listing successfully posted!</div>";

			if(isset($_SESSION['user_email'])){
				if($_SESSION['user_email'] == "utopm7@gmail.com"){
					unset($_SESSION['user_email']);
					//header("location:index.php");
					$posted = "<div style='background-color: #ff4; width: 280px; margin: auto; margin-bottom: -20px; text-align: center; padding:5px;'>Listing successfully posted!</div>";
				}else{
					$posted = "<div style='background-color: #ff4; width: 280px; margin: auto; margin-bottom: -20px; text-align: center; padding:5px;'>Listing successfully posted!</div>";
					//send mail
					$to = $email;
					$subject = "Rentkasa: Ad Posted";
					$header = "From: admin@rentkasa.com";
					$message = "Your ad was successfully posted on Rentkasa. You can edit your ads by logging-in at <a href='http://rentkasa.com'>rentkasa.com</a> and clicking on your email at the top of the page. Best wishes and do remember to like us on facebook at <a href='http://rentkasa.com'>faebook.com/rentkasa</a>. Cheers!!";
					if(@mail($to, $subject, $message, $header)){}
				}
			}else{
				//header("location:index.php");
				$posted = "<div style='background-color: #ff4; width: 280px; margin: auto; margin-bottom: -20px; text-align: center; padding:5px;'>Listing successfully posted!</div>";
			}
			//header("location:index.php");
		}
	}

	if(isset($_POST['cancel'])){
		if(isset($_SESSION['user_email'])){
			if($_SESSION['user_email'] == "utopm7@gmail.com"){
				unset($_SESSION['user_email']);
				header("location:index.php");
			}else{
				header("location:manage_posts.php");
			}
		}else{
			header("location:index.php");
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
		<div id="nav_options">
			<?php echo $log_out; ?>
			<div id="nav_options_div3"><a href="create_account.php">Create Account<a/></div>
			<div id="nav_options_div2"><?php echo $name2; ?></div>
			<div id="nav_options_div1"><a href="index.php">Home</a></div>
		</div>
	</div>

	<div id="wrapper">
		<br /><br />
		<div id="wrapper1">
				<a href="index.php"><img src="images/logo.png" border="0" /></a><br />
		</div><!--wrapper1-->

		<div id="wrapper2">
		<!--<div id="wrapper_post_listing">-->
		<?php
			echo $posted;
		?>
		<div id="post_listing">
		<b>Post a Listing</b><br /><?php echo $disclaimer; ?><br />

			<form action="post_listing.php" method="POST" enctype="multipart/form-data">
			Which category does your listing fall under?<br />
			<select name="category">
			<option>Choose a category</option>
			<option>-- -- --</option>
			<?php
			$query = $manage_db->return_query("SELECT * FROM categories");

			while($row = mysql_fetch_array($query)){
				$category = $row['category'];
				echo "<option>".$category."</option>";
			}
			?>
			</select><br /><br />

			What are you renting or what service are you are you offering?<br />
			<input type="text" name="listing" id="location" /><br /><br />

			Give more details.<br />
			<textarea name="details"></textarea><br /><br />

			Price/Rate:<br />
			<input type="text" name="price" id="price" /> per <select name="rate">
			<option>-- --</option>
			<option>Hour</option>
			<option>Day</option>
			<option>Month</option>
			<option>Year</option>
			<option>Transaction</option>
			</select><br /><br />

			Your Region:<br />
			<select name="region">
				<option>Greater Accra</option>
				<option>Ashanti</option>
				<option>Brong Ahafo</option>
				<option>Volta</option>
				<option>Central</option>
				<option>Eastern</option>
				<option>Western</option>
				<option>Northern</option>
				<option>Upper East</option>
				<option>Upper West</option>
			</select><br /><br />


			Your Location:<br />
			<input type="text" name="location" id="location" /><br />(e.g. Osu, Oxford Street)<br /><br />

			Telephone:<br />
			<input type="text" name="telephone" id="telephone" /><br /><br />

			Want to add some images?<br />
			<input type="file" name="files[]" multiple/><br /><br />


			<div id='post_listing_btn'>
				<input type="submit" name="post" id="post" value="Post" />
			</div>
			</form>


			<form action="post_listing.php" method="POST" />
				<div id='cancel_listing_btn'>
					<input type="submit" name="cancel" id="cancel" value="Cancel" />
				</div>
			</form>
		</div><!--post_listings-->

		<div id="ads1">
			Ads
		</div>

		</div><!--wrapper2-->

	</div><!--wrapper-->

	<div id="copyright">
		<center>Rentkama - Copyright &#169; <?php echo date("Y"); ?><center>
	</div><!--copyright-->
</body>

</html>