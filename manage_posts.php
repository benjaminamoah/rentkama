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

	if(isset($_POST['num_nav'])){

	}else{
		$num_nav = "LIMIT 6";
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
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


	<div id="panel">
		<div id="nav_options">
			<?php echo $log_out; ?>
			<div id="nav_options_div3"><a href="create_account.php">Create Account<a/></div>
			<div id="nav_options_div2"><?php echo $name; ?></div>
			<div id="nav_options_div1"><a href="index.php">Home</a></div>
			</div>
	</div>

	<div id="wrapper">
		<div id="users_name">
			Hi, <?php echo $users_name; ?>
		</div><!--wrapper1-->

	<br /><br /><br />
			<form action="post_listing.php" method="POST">
				<input type="image" src="images/button.png" name="go_post" value="POST A LISTING" style="float:right" />
			</form>

		<div id="wrapper1"><br />
				<a href="index.php"><img src="images/logo.png" border="0" /></a>
				<!--
				<div id="ads3">
					Ads
				</div>-->
		</div><!--wrapper1-->
		<?php
			if(isset($_SESSION['edit_msg'])){
				echo $_SESSION['edit_msg'];
				unset($_SESSION['edit_msg']);
			}
		?>
		<div id="wrapper2" style="width:890px">

		<div id="category_listings" style="width:880px">

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

			<?php
				echo $removed;
				$manage_db = new manage_db();
				$query_category = $manage_db->return_query("SELECT * FROM categories WHERE category='events locations'");
				$category_id = mysql_result($query_category, 0, "category_id");
				$query = $manage_db->return_query("SELECT * FROM listings WHERE user_id='$user_id' ORDER BY listing_id DESC $num_nav");
				$num = mysql_num_rows($query);

				if($num == 0){
					echo "<br /><br /><center><h3 style='padding-right:8px'>You haven't posted an advertisement yet! To do so, click on the blue button at the top-right side of this page.</h3></center>";
				}

				echo "<div>";
				while($row = mysql_fetch_array($query)){
					 $listing_id = $row['listing_id'];
					 $listing_ref = $row['listing_ref'];
					echo "<div id='category_listing_container1' style='float:right'>
								<div id='category_listing1'>
								<div id='listing_title' style='padding-top: 5px'><form action='details.php' method='POST'><input type='hidden' value='".$row['listing_id']."' name='listing_id' /><input type='submit' value='".$row['listing']."' name='listing' /></form></div>
								<div id='listing_detail'>";
								$details = $row['details'];
								if(strlen($details) > 160){
									$details = substr($details, 0, 160);
									$details = $details."...";
								}
								$price = $row['price'];
								$rate = $row['rate'];
								if($rate != "-- --"){
									$rate = " per ".$rate;
								}else{
									$rate = "";
								}
					echo $details."<br /><br /><b>".$price.$rate."</b></div></div><!--category listing-->";

				    $query_image = $manage_db->return_query("SELECT * FROM images WHERE listing_ref='$listing_ref' ORDER BY image_id");

					 if(mysql_num_rows($query_image) > 0){
					 	$image = mysql_result($query_image, 0, "image");
					 	$image = "user_data/".$image;
					 }else{
					 	$image = "";
					 }

					echo "<div id='listing_img'>";

					if(strlen($image) > 0){
						echo "<div style='width: 120px; height: 120px; vertical-align: middle; float: right'><img src='".$image."' style='width: 120px;' /></div>";
					}else{
						echo "<img src='images/paperbag.png' style='width: 120px; height: 120px' />";
					}

					echo "</div><!--listing img-->

					<div id='edit' style='width: 18px; float: left'>
					<form action='edit_listing.php' method='POST'>
						<input type='hidden' name='listing_id' value='".$listing_id."' />
						<input type='submit' name='edit_listing' id='edit_listing' title='edit this listing' value='Edit' />
					</form>
					</div>

					<div id='edit' style='width: 38px; margin-left: 20px; float: left'>
					<form action='delete_listing.php' method='POST'>
						<input type='hidden' name='listing_id' value='".$listing_id."' />
						<input type='submit' name='delete_listing' id='delete_listing' title='delete this listing' value='Delete' />
					</form>
					</div>

					</div><!--category listing container-->56756";
				}

				if($num > 6){
					$lim = 4; //$num/6;
					for($i = 1; $i<=$lim; $i++){
						echo "<br /><div style='float:left; height:50px'>".$i."</div>5";
					}
				}
				echo "444444</div>";
				if($num > 6){
					$lim = 4; //$num/6;
					for($i = 1; $i<=$lim; $i++){
						echo "<br /><div style='float:left; height:50px'>".$i."</div>4";
					}
				}
			?>

		</div>

		</div><!--wrapper2-->

				<!--<div id="" style="background: #fff; width: 280px; float: left; margin-top: 4px; padding-top: 40px">
				<div id="" style="margin-left: 20px; float: left;">
					<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Frentpapa&amp;width=200&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:80px;" allowTransparency="true">
					</iframe>
				</div>-->

				<div id="ads1" style="margin-left: 20px; float: left;">
					Ads
				</div><br /><br />
				<div style="margin-left: 20px; margin-top: 20px; float:left" class="fb-like-box" data-href="https://www.facebook.com/RentKama" data-width="240" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>
				</div>


	</div><!--wrapper-->

	<div id="copyright">
		<center>Rentkama - Copyright &#169; <?php echo date("Y"); ?><center>
	</div><!--copyright-->
</body>

</html>