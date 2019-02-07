<?php
	require("includes/display.php");

	session_start();
	if(!isset($_SESSION['user_email'])){
		$name = "<form action='index.php' method='POST'><input type='text' name='email' /> <input type='password' name='password' /><input type='submit' name='login' value='Log In' /></form>".$login_error;
	}else{
		if($_SESSION['user_email'] == "utopm7@gmail.com"){
			unset($_SESSION['user_email']);
			$name = "<form action='index.php' method='POST'><input type='text' name='email' /> <input type='password' name='password' /><input type='submit' name='login' value='Log In' /></form>";
		}else{
			$name = "<a href='manage_posts.php'>".$_SESSION['user_email']."</a>";
			$log_out = "<div id='nav_options_div4'><a href='includes/logout.php'>Log Out</a></div>";
		}
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
			$login_error = "<div style='background-color: #fac; color: red; padding: 10px; border: 2px solid #f84'>Wrong email or password. try again!</div>";
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
			<div id="nav_options_div2"><?php echo $name." ".$login_error; ?></div>
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
			<div id="home_listings_2_top">
				<h3>Welcome to Rentkama!</h3>
				Rentkama is all about giving access to really great stuff available for rent. It has all you need when it
				comes to organizing weddings, birthdays and other events. Find accommodation in your area. Explore what is on offer from
				vendors all across the country, and tell a friend. Rent any and everthing.
			</div>

			<div id="home_listings_2_top2">
				<div id="home_image">
					<img src="images/home_image.png" />
				</div>

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

				<!--<div id="" style="margin-left: 20px; float: left;">
					<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Frentpapa&amp;width=200&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:80px;" allowTransparency="true">
					</iframe>
				</div>-->

				<div id="ads1" style="height: 200px; line-height: 250px;">
					Ads
				</div><br /><br />
				<div style="margin-left: 20px; margin-top: 20px; float:right" class="fb-like-box" data-href="https://www.facebook.com/RentKama" data-width="240" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="false"></div>

			</div><!--home_listings_2_top2-->
		</div>

		<div id="home_wrapper2_2">

		<div id="home_listings_2">
		<h4>CATEGORIES</h4>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="accommodation.php" /><h3>Accommodation</h3></a>
				<a href="accommodation.php" />Apartments / Houses / <br />Guest Houses</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/accommodation.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="events_locations.php" /><h3>Events Locations</h3></a>
				<a href="events_locations.php" />Hotels / Halls / Wedding <br />Spaces / Resaurants</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/events_locations.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="vehicles_and_transport_services.php" /><h3>Vehicles and Transport <br />Services</h3></a>
				<a href="vehicles_and_transport_services.php" />Cars for Weddings / Moving <br />Trucks</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/vehicles2.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="events_seating.php" /><h3>Events Seating</h3></a>
				<a href="events_seating.php" />Canopies / Chairs and Tables</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/event_seating2.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="electronic_equipment.php" /><h3>Electronic Equipment</h3></a>
				<a href="electronic_equipment.php" />Music and Sound Equipment / <br />Camera Equipment or filming <br />Services</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/electronics5.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="live_music_services.php" /><h3>Live Music Services</h3></a>
				<a href="live_music_services.php" />Instrumentalists / Singers / <br />Live Bands</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/live band.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="catering_services.php" /><h3>Catering Services</h3></a>
				<a href="catering_services.php" />Cakes / Pasteries / <br />Catering Services / Drinks</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/catering.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="decorations_and_interior_design_services.php" /><h3>Decorations and Interior <br />Design Services</h3></a>
				<a href="decorations_and_interior_design_services.php" />Flowers / Decorations</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/decorations.jpg" />
			</div>
		</div>
		<div id="home_listings_component_2">
			<div id="home_listings_component_2_left">
				<a href="other.php" /><h3>Other</h3></a>
				<a href="other.php" />Stage props / Dresses / Building equipment / Costumes</a><br /><br />
			</div>
			<div id="home_listings_component_2_right">
				<img src="images/other.jpg" />
			</div>
		</div>
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