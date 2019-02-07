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

	if(isset($_POST['delete_listing'])){
		$listing_id = $_POST['listing_id'];
	}

	if(isset($_POST['delete'])){
		$listing_id = $_POST['listing_id'];
		$manage_db = new manage_db();
		$query_listing_ref = $manage_db->return_query("SELECT * FROM listings WHERE listing_id='$listing_id'");
		$listing_ref = mysql_result($query_listing_ref, 0, "listing_ref");

		$manage_db->query("DELETE FROM listings WHERE listing_id='$listing_id'");

		$query_images = $manage_db->return_query("SELECT * FROM images WHERE listing_ref='$listing_ref'");
		$num = mysql_num_rows($query_images);

		if($num > 0){
			while($row = mysql_fetch_array($query_images)){
				$image = "user_data/".$row['image'];
				if(file_exists($image)){
					unlink($image);
				}
			}
			$manage_db->query("DELETE FROM images WHERE listing_ref='$listing_ref'");
		}
		header("location:manage_posts.php");
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

///////////////////////////////////////////////////////

    <link href="themes/1/js-image-slider.css" rel="stylesheet" type="text/css" />
    <script src="themes/1/js-image-slider.js" type="text/javascript"></script>
    <link href="generic.css" rel="stylesheet" type="text/css" />

///////////////////////////////////////////////////////

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
						listings = listings+listing[i];
					}
					document.getElementById('listings').innerHTML = listings;
				}
			}

			http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			http.send("category_id="+category_id);
		}

		function show_details(listing_id){
			var details_input = "details_input"+listing_id;
			document.getElementById(listing_id).style.height = "100%";
			document.getElementById(details_input).innerHTML = "<input type='button' onClick='hide_details("+listing_id+")' value='hide details' />";
		}

		function hide_details(listing_id){
			var details_input = "details_input"+listing_id;
			document.getElementById(listing_id).style.height = "0px";
			document.getElementById(details_input).innerHTML = "<input type='button' onClick='show_details("+listing_id+")' value='view details' />";
		}

/********
		function go_to_categories(region){
			document.getElementById('home_regions').innerHTML = '<a onClick="go_to_regions()"><< Back to regions</a><h3>Choose a Category in '+region+' Region</h3><div id="home_regions_a"><a href="accommodation.php">Accommodation</a></div><div id="home_regions_a"><a href="">Events Locations</a></div><div id="home_regions_a"><a href="">Vehicles and Transport Services</a></div><div id="home_regions_a"><a href="">Events Seating</a></div><div id="home_regions_a"><a href="">Electronic Equipment</a></div><div id="home_regions_a"><a href="">Live Music Services</a></div><div id="home_regions_a"><a href="">Catering Services</a></div><div id="home_regions_a"><a href="">Decorations and Interior Design Services</a></div><div id="home_regions_a"><a href="">Others</a></div>';
		}
************/

	</script>
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

	<div id="home_wrapper_2">
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

		<div id="home_wrapper2_2_top">

			<div id="home_listings_2_top2_details">

				<div id="home_regions">
					<h3>Edit Options</h3>
					<div id="home_regions_a"><form action="edit_listing.php" method="POST"><input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>" /><input type="submit" name="edit_listing" value="Edit Listing" /></form></div>
					<div id="home_regions_a"><form action="edit_listing_gallery.php" method="POST"><input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>" /><input type="submit" name="edit_gallery" value="Edit Gallery" /></form></div>
					<div id="home_regions_a"><form action="delete_listing.php" method="POST"><input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>" /><input type="submit" name="delete_listing" value="Delete Listing" style="font-weight: bold" /></form></div>
				</div>

				<div id="home_image">
					<!--<img src="images/home_image.png" />-->

				<div id="home_regions" style="width: 450px">
					<h3>Are you sure you want to delete this advertisement?</h3>
					<div id='category_listing2'>
					<?php
						echo $removed;
						$manage_db = new manage_db();
						$query = $manage_db->return_query("SELECT * FROM listings WHERE listing_id='$listing_id'");
						$num = mysql_num_rows($query);

						while($row = mysql_fetch_array($query)){
							 $listing_id = $row['listing_id'];
							 $user_id = $row['user_id'];

							 if($user_id != 0){
							 $query_user = $manage_db->return_query("SELECT * FROM users WHERE user_id='$user_id'");
							$name = mysql_result($query_user, 0, "name");
							}else{
								$name = "";
							}

							$date = $row['date_posted'];
							$date = date("l dS  F, Y", $date);
							 echo "<h4>".$row['listing']."</h4>".$row['details']."<br /><br />";
						}
					?>

					<form action="delete_listing.php" method="POST" />
						<div id='post_listing_btn'>
							<input type='hidden' name='listing_id' value='<?php echo $listing_id; ?>' />
							<input type="submit" name="delete" id="post" value="Delete"  style="background:#a22" />
						</div>
					</form>


					<form action="manage_posts.php" method="POST" />
						<div id='cancel_listing_btn'>
							<input type="submit" name="cancel" id="cancel" value="Cancel" />
						</div>
					</form>

					</div><!--category listing2-->

 			    </div>

				</div>

				<div id="" style="margin-left: 20px; float: left;">
					<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Frentpapa&amp;width=200&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:80px;" allowTransparency="true">
					</iframe>
				</div>

				<div id="ads1">
					Ads
				</div>

			</div><!--home_listings_2_top2-->
		</div>

		<div id="home_wrapper2_2">

		<!--<div id="home_listings_2">
		<h4>CATEGORIES</h4>-->

		</div><!--home_listings_2-->

		</div><!--home_wrapper2_2-->
	</div><!--home_wrapper-->

	<div id='space'>
		<!--space between wrapper and copyright-->
	</div>

	<div id="copyright">
		<center>Rentkama - Copyright &#169; <?php echo date("Y"); ?><center>
	</div><!--copyright-->
</body>

</html>