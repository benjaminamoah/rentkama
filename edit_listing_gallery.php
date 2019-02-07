<?php
	require("includes/display.php");
	require('SimpleImage.php');

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

	if(isset($_POST['edit_gallery'])){
		$listing_id = $_POST['listing_id'];
	}

	if(isset($_POST['delete_image'])){
		$listing_id = $_POST['listing_id'];
		$image_id = $_POST['image_id'];
		$manage_db = new manage_db();
		$query_image = $manage_db->return_query("SELECT * FROM images WHERE image_id='$image_id'");
		$image = mysql_result($query_image, 0, "image");
		$image1 = "user_data/".$image;

		if(file_exists($image1)){
			unlink($image1);
	    	$delete_msg = "<div style='background-color: #ff4; width: 280px; margin: auto; text-align: center; padding:5px;'>".$image." has been deleted!</div>";
		}

		$manage_db->query("DELETE FROM images WHERE image_id='$image_id'");
	}

if(isset($_POST['upload'])){
	if(isset($_FILES['files'])){
		$listing_id = $_POST['listing_id'];
		$manage_db = new manage_db();
		$query_listing_ref = $manage_db->return_query("SELECT * FROM listings WHERE listing_id='$listing_id'");

		while($row = mysql_fetch_array($query_listing_ref)){
			$listing_ref = $row['listing_ref'];
		}

		$image1 = new SimpleImage();

		$errors= array();
		foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
			if(mysql_num_rows($query_listing_ref) == 0){
				$time = time();
				$listing_ref = $time."_".$_SESSION['user_email'];
			}

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

				$image1->load($_FILES['files']['tmp_name'][$key]);
				$image1->resizeToHeight(306);
				$image1->save($file_tmp);

				if(is_dir("$desired_dir/".$file_name)==false){
					move_uploaded_file($file_tmp,"user_data/".$file_name);
				}else{									//rename the file if another one exist
					$new_dir="user_data/".$file_name.time();
					 rename($file_tmp,$new_dir) ;
				}
				mysql_query($query);
			}else{
				print_r($errors);
			}
		}
		if(empty($error)){
	    	$delete_msg = "<div style='background-color: #ff4; width: 280px; margin: auto; text-align: center; padding:5px;'>Image(s) uploaded successfully!</div>";
		}
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

///////////////////////////////////////////////////////

    <link href="themes/1/js-image-slider.css" rel="stylesheet" type="text/css" />
    <script src="themes/1/js-image-slider.js" type="text/javascript"></script>
    <link href="generic.css" rel="stylesheet" type="text/css" />

////////////////////////////////////////////////////////

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

			<?php
				echo $delete_msg;
				echo $upload_msg;
			?>
		<div id="home_wrapper2_2_top">
			<div id="home_listings_2_top2_details">

				<div id="home_regions">
					<h3>Edit Options</h3>
					<div id="home_regions_a"><form action="edit_listing.php" method="POST"><input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>" /><input type="submit" name="edit_listing" value="Edit Listing" /></form></div>
					<div id="home_regions_a"><form action="edit_listing_gallery.php" method="POST"><input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>" /><input type="submit" name="edit_gallery" value="Edit Gallery" style="font-weight: bold" /></form></div>
					<div id="home_regions_a"><form action="delete_listing.php" method="POST"><input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>" /><input type="submit" name="delete_listing" value="Delete Listing" /></form></div>
				</div>

				<div id="home_image">
					<!--<img src="images/home_image.png" />-->

				<div id="home_regions" style="width: 600px">
					<h3>Edit Gallery</h3>
					<div id='gallery_img_container'>
					<?php
							$manage_db = new manage_db();
							$query_listing_ref = $manage_db->return_query("SELECT * FROM listings WHERE listing_id='$listing_id'");

							while($row = mysql_fetch_array($query_listing_ref)){
								$listing_ref = $row['listing_ref'];
							}

							$query_image = $manage_db->return_query("SELECT * FROM images WHERE listing_ref='$listing_ref' ORDER BY image_id");
							$num_image = mysql_num_rows($query_image);
							if($num_image > 0){
							while($row = mysql_fetch_array($query_image)){
								 $image = "user_data/".$row['image'];
								 $image_id = $row['image_id'];
								 echo "<div id='gallery_img'><img src='".$image."' alt='' /><form action='edit_listing_gallery.php' method='POST'><input type='hidden' name='image_id' value='".$image_id."' /><input type='hidden' name='listing_id' value='".$listing_id."' /><input type='submit' value='Delete' name='delete_image' style='background:#f00; color:#fff' /></form></div>";
							}
							}else{
								echo "<center><h3>No images have been uploaded yet!</h3></center>";
							}
							?>

					<br />
					<dic id="gallery_img_container">
					<form action="edit_listing_gallery.php" method="POST" enctype="multipart/form-data">
						<h4>Want to add some images?</h4>
						<input type="hidden" name="listing_id" value="<?php echo $listing_id; ?>"  />
						<input type="file" name="files[]" multiple/><br /><br />
						<div id='post_listing_btn'>
							<input type="submit" name="upload" id="upload" value="Upload" style="width:70px" />
						</div>
					</form>


					<form action="manage_posts.php" method="POST" />
						<div id='cancel_listing_btn'>
							<input type="submit" name="cancel" id="cancel" value="Cancel" />
						</div>
					</form>
					</div>
					</div><!--gallery_img_container-->

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