<?php
	require("manage_db.php");

	class display extends manage_db{
//1.
		function categories(){
			$categories = "";
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM categories  ORDER BY category ASC");
			while($row = mysql_fetch_array($query)){
				$categories = $categories."[BRK]".$row['category'];
			}
			return $categories;
		}

		function listings($category_id){
			$listings = "";
			$manage_db = new manage_db();
			if($category_id == 0){
				$query = $manage_db->return_query("SELECT * FROM listings ORDER BY listing_id DESC LIMIT 40");
			}else{
				$query = $manage_db->return_query("SELECT * FROM listings WHERE category_id='$category_id' ORDER BY listing_id DESC LIMIT 40");
			}
			while($row = mysql_fetch_array($query)){
				$listing_id = $row['listing_id'];
				$user_id = $row['user_id'];
				$details = $row['details'];
				$time = $row['date_posted'];
				$time = date("y  M  d", $time);
				$image = $row['images'];

				if($image == ""){
					$image = "images/listing_images/listing_image.gif";
				}

				$query_user = $manage_db->return_query("SELECT * FROM users WHERE user_id='$user_id'");
				$name = mysql_result($query_user, 0, "name");
				if(strlen($details) > 0){
					$details_input = "<input type='button' onClick='show_details(".$listing_id.")' value='view details' />";
				}else{
					$details_input = "";
				}
				$listings = $listings."[BRK]"."<div id='listing'>
																	<div id='listing_info'>
																			<div id='listing_text'>
																				<div id='listing_main_text'>".$row['listing']."<br /></div>
																				<div id='".$listing_id."' style='height: 0px; font-size: 12px; overflow: hidden'>".$details."</div>
																				<div id='details_input'><div id='details_input".$listing_id."'>".$details_input."</div></div>
																			</div><!--listing_text-->
																			<div id='listing_append'>
																				<div id='lister_name'>".$name."</div>
																				<div id='listing_time'>".$time."</div>
																			</div>
																	</div><!--listing_info-->
																	<div id='listing_image'>
																			<img src='".$image."' />
																	</div><!--listing_image-->
																</div><!--listing-->";
			}

			if($listing_id < 1){
				return "[BRK]"."<div id='listing'><center>No Listings For this category just yet! :(</center></div>";
			}else{
				return $listings;
			}
		}
	}
?>