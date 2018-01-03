<?php

session_start();

//isset($_POST['submit']) to verify that the acces comes from a submit and not from typing the url
if (isset($_POST['submit'])) {
	
	include_once 'dbh.inc.php';
	
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
	
	//Error handlers
	//Check if these inputs are empty
	if (empty($uid) || empty($pwd)) {
		$_SESSION['errMsg'] = "Invalid username or password";//Sets the error message for the session variable $_SESSION['errMsg']
		header("Location: ../index.php?login=empty");
		exit();
	} else {
		$sql = "SELECT * FROM users WHERE user_uid = '$uid' OR user_email = '$uid'";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			$_SESSION['errMsg'] = "Invalid username or password";
			header("Location: ../index.php?login=error");
			exit();
		} else {
			//Check if the user tiped the correct password that matches the user in the username.
			//mysqli_fetch_assoc inserts the selected user from the query into the variable we specify.
			//that way we can work with the variable $row as if it is a datatable from c#
			//in the php scenario would be like echo $row['user_uid'];
			if ($row = mysqli_fetch_assoc($result)) {
				//De-hashing the password
				$hashedPwdCheck = password_verify($pwd, $row['user_pwd']);
				if ($hashedPwdCheck == false) {
					$_SESSION['errMsg'] = "Invalid username or password";
					header("Location: ../index.php?login=error");
					exit();
				} elseif ($hashedPwdCheck == true) {//Not sure what the value of password_verify return datatype could be, so we doble check with an elseif for the specific true value.
					//Log in the user here.
					$_SESSION['u_id'] = $row['user_id'];//Assign the user id to a system session variable we called 'u_id'
					$_SESSION['u_firstname'] = $row['user_firstname'];
					$_SESSION['u_lastname'] = $row['user_lastname'];
					$_SESSION['u_email'] = $row['user_email'];
					$_SESSION['u_uid'] = $row['user_uid'];
					
					
					
					header("Location: ../index.php?login=success");
					exit();
				}
			}
		}
	}
}
else {
	header("Location: ../index.php?login=error");
	exit();
}

?>