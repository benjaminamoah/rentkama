<?php
	require("includes/manage_db.php");

	class manage extends manage_db{
//1.
		function categories(){
			$categories = "";
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM categories");
			while($row = mysql_fetch_array($query)){
				$categories = $categories."[BRK]".$row['category'];
			}
			return $categories;
		}

		function listings($user_id){
			$listings = "";
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM listings WHERE user_id='$user_id'");
			while($row = mysql_fetch_array($query)){
				$listings = $listings."[BRK]".$row['listing_id']."[BRK]".$row['listing'];
			}
			return $listings;
		}
	}
?>