<?php
	require("includes/display.php");

	session_start();
	if(!isset($_SESSION['user_email'])){
		$name = "<form action='index.php' method='POST'><input type='text' name='email' /><input type='text' name='password' /><input type='submit' name='login' value='Log In' /></form>".$login_error;
	}else{
		if($_SESSION['user_email'] == "utopm7@gmail.com"){
			unset($_SESSION['user_email']);
			$name = "<form action='index.php' method='POST'><input type='text' name='email' /><input type='text' name='password' /><input type='submit' name='login' value='Log In' /></form>".$login_error;
		}else{
			$name = "<a href='manage_posts.php'>".$_SESSION['user_email']."</a>";
			$log_out = "<div id='nav_options_div4'><a href='includes/logout.php'>Log Out</a></div>";
		}
	}

	if(isset($_POST['listing'])){
		$listing_id = $_POST['listing_id'];
	}

	if(isset($_POST['login'])){
		$email = $_POST['email'];
		$password = $_POST['password'];

		$manage_db = new manage_db();
		$query = $manage_db->return_query("SELECT * FROM users WHERE email='$email' AND password='$password'");
		if(mysql_num_rows($query) == 1){
			$_SESSION['user_email'] = $email;
			$name = "<a href='manage_posts.php'>".$_SESSION['user_email']."</a>";
		}else{
			$login_error = "Wrong email or password. try again!";
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

    <link href="themes/1/js-image-slider.css" rel="stylesheet" type="text/css" />
    <script src="themes/1/js-image-slider.js" type="text/javascript"></script>
    <link href="generic.css" rel="stylesheet" type="text/css" />

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

		function go_to_categories(region){
			if(region == "All Regions"){
				region1 = "All Regions";
			}else{
				region1 = region+" Region";
			}
			document.getElementById('home_regions').innerHTML = '<a onClick="go_to_regions()"><< Back to regions</a><h3>Choose a Category in '+region1+' </h3><div id="home_regions_a"><form action="accommodation.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Accommodation" /></form></div><div id="home_regions_a"><form action="events_locations.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Events Locations" /></form></div><div id="home_regions_a"><form action="vehicles_and_transport_services.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Vehicles and Transport &#13;&#10;Services" /></form></div><div id="home_regions_a"><form action="events_seating.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Events Seating" /></form></div><div id="home_regions_a"><form action="electronic_equipment.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Electronic Equipment" /></form></div><div id="home_regions_a"><form action="live_music_services.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Live Music Services" /></form></div><div id="home_regions_a"><form action="catering_services.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Catering Services" /></form></div><div id="home_regions_a"><form action="decorations_and_interior_design_services.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Decorations and Interior &#13;&#10;Design Services" /></form></div><div id="home_regions_a"><form action="other.php" method="POST"><input type="hidden" name="region_val" value="'+region+'" /><input type="submit" name="region" value="Others" /></form></div>';
		}

		function go_to_regions(){
			var all = "'All Regions'";
			var ga = "'Greater Accra'";
			var a = "'Ashanti'";
			var ba = "'Brong Ahafo'";
			var v = "'Volta'";
			var c = "'Central'";
			var e = "'Eastern'";
			var w = "'Western'";
			var n = "'Northern'";
			var ue = "'Upper East'";
			var uw = "'Upper West'";

			document.getElementById('home_regions').innerHTML = '<h3>Go to Your Region</h3><div id="home_regions_a"><a onClick="go_to_categories('+all+')">All Regions</a></div><div id="home_regions_a"><a onClick="go_to_categories('+ga+')">Greater Accra</a></div><div id="home_regions_a"><a onClick="go_to_categories('+a+')">Ashanti</a></div><div id="home_regions_a"><a onClick="go_to_categories('+ba+')">Brong Ahafo</a></div><div id="home_regions_a"><a onClick="go_to_categories('+v+')">Volta</a></div><div id="home_regions_a"><a onClick="go_to_categories('+c+')">Central</a></div><div id="home_regions_a"><a onClick="go_to_categories('+e+')">Eastern</a></div><div id="home_regions_a"><a onClick="go_to_categories('+w+')">Western</a></div><div id="home_regions_a"><a onClick="go_to_categories('+n+')">Northern</a></div><div id="home_regions_a"><a onClick="go_to_categories('+ue+')">Upper East</a></div><div id="home_regions_a"><a onClick="go_to_categories('+uw+')">Upper West</a></div><h3>Or select a category below...</h3>';
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

		<div id="home_wrapper2_2_top" style="height: 100%">

			<div id="home_listings_2_top2_details" style="height: 100%">

				<div id="home_regions">
					<h3>Go to Your Region</h3>
					<div id="home_regions_a"><a onClick="go_to_categories('All Regions')">All Regions</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Greater Accra')">Greater Accra</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Ashanti')">Ashanti</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Brong Ahafo')">Brong Ahafo</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Volta')">Volta</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Central')">Central</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Eastern')">Eastern</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Western')">Western</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Northern')">Northern</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Upper East')">Upper East</a></div>
					<div id="home_regions_a"><a onClick="go_to_categories('Upper West')">Upper West</a></div>
					<h3>Or select a category below...</h3>
				</div>

				<div id="home_image">
					<!--<img src="images/home_image.png" />-->

					<div id="sliderFrame">
						<div id="slider">
						`   <?php
							$manage_db = new manage_db();
							$query_listing_ref = $manage_db->return_query("SELECT * FROM listings WHERE listing_id='$listing_id'");
						    $listing_ref = mysql_result($query_listing_ref, 0, "listing_ref");
							$title = mysql_result($query_listing_ref, 0, "listing");
							$telephone = mysql_result($query_listing_ref, 0, "telephone");
							$listing_price = mysql_result($query_listing_ref, 0, "price");
							if($listing_price != "0"){
								if(is_numeric($listing_price)){
									$price = "GHc ".$listing_price;
								}else{
									$price = $listing_price;
								}
							}
							$query_image = $manage_db->return_query("SELECT * FROM images WHERE listing_ref='$listing_ref'");
							$num_image = mysql_num_rows($query_image);
							if($num_image > 0){
							$i = 0;
							while($row = mysql_fetch_array($query_image)){
								 $image = "user_data/".$row['image'];
								 $i++;
								 if($i % 2 == 0){
								 	echo "<img src='".$image."' alt='".$price."' />";
								 }else if($i % 3 == 0){
								 	echo "<img src='".$image."' alt='Call ".$telephone."' />";
								 }else{
								 	echo "<img src='".$image."' alt='".$title."' />";
								 }

							}
							}
							?>
							<img src="images/fb_cover1.png" alt="" />
						</div>
						<div id="htmlcaption" style="display: none;">
							<em>HTML</em> caption. Link to <a href="http://www.google.com/">Google</a>.
						</div>

				</div>

				<div id="home_regions" style="height: 100%">
					<!--<h3>The Details</h3>-->
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
							 echo "<div id='category_listing1' style='height: 100%'><br /><br />
										<div id='listing_title'><h3>".$row['listing']."</h3></div>
										<div id='listing_detail'>";
							$i++;
							echo "<b>Details:</b> ".$row['details']."
							<br /><br /><b>Region:</b> ".$row['region']."
							<br /><br /><b>Location:</b> ".$row['location']."
							<br /><br /><b>Price:</b> ".$row['price']." per ".$row['rate']."
							<br /><br /><b>Telephone:</b> ".$row['telephone']."
							<br /><br /><b>Date Posted:</b> ".$date."
							<br /><br />posted by ".$name."
							</div></div><!--category listing-->
							</div>";
						}
					?>
 			    </div>

				</div>

				<!--<div id="" style="margin-left: 20px; float: left;">
					<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Frentpapa&amp;width=200&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:80px;" allowTransparency="true">
					</iframe>
				</div>-->

				<div id="ads1">
					Ads
				</div><br /><br />
				<div style="margin-left: 20px; margin-top: 20px; float:left" class="fb-like-box" data-href="https://www.facebook.com/RentKama" data-width="240" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>

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