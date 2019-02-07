<?php
	require("display.php");

	if(isset($_POST['category_id'])){
		$category_id = $_POST['category_id'];
		$display = new display();
		$listings = $display->listings($category_id);
		echo $listings;
	}

?>