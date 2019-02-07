<?php
	require("manage_db.php");

	class login extends manage_db{
//1.
		function check_admin_user($user, $pass, $access_level){
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM admin_users");
			$i = 0;
			$k = 0;
			while($i<mysql_num_rows($query)){
				$result_user = mysql_result($query, $i, 'username') or die(mysql_error());
				if($result_user==$user){
					$i = mysql_num_rows($query) + 2;
				}else{
					$i++;
					$k++;
				}
			}

			if($i == mysql_num_rows($query) + 2){
				$result_pass = mysql_result($query, $k, 'password') or die(mysql_error());
				$result_level = mysql_result($query, $k, 'access_level') or die(mysql_error());

				if($result_pass==$pass && $result_level==$access_level){
					$fdata = true;
				}else{
					$fdata = false;
				}
			}else{
				$fdata = false;
			}

			return $fdata;
		}

//2.
		function check_student_user($user, $pass){
			$manage_db = new manage_db();
			$query = $manage_db->return_query("SELECT * FROM students");
			$i = 0;
			$k = 0;
			while($i<mysql_num_rows($query)){
				$result_user = mysql_result($query, $i, 'username') or die(mysql_error());
				if($result_user==$user){
					$i = mysql_num_rows($query) + 2;
				}else{
					$i++;
					$k++;
				}
			}

			if($i == mysql_num_rows($query) + 2){
				$result_pass = mysql_result($query, $k, 'password') or die(mysql_error());
				if($result_pass==$pass){
					$fdata = true;
				}else{
					$fdata = false;
				}
			}else{
				$fdata = false;
			}

			return $fdata;
		}

	}

?>