<?php

//isset($_POST['submit']) to verify that the acces comes from a submit and not from typing the url
if (isset($_POST['submit'])) {
	
	include_once 'dbh.inc.php';
	
	//mysqli_real_escape_string is to avoid people to enter code in these fields to the database
	//and instead they are converted to string
	$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
	$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
	
	//Error handlers
	//Check for empty fields
	if (empty($firstname) || empty($lastname) || empty($email) || empty($uid) || empty($pwd)){
		header("Location: ../signup.php?signup=empty");//the ? after the url sends what you specifies as a querystring to the url you are redirecting to.
		exit();//it closes off the script from runing, usefull if there are more code AFTER the exit() function.
	} else {
		//Check if input characters are valid.
		//preg_match php function checks if there are certain characters that we don't allow inside the inputs.
		if (!preg_match("/^[a-z A-Z]*$/", $firstname) || !preg_match("/^[a-z A-Z]*$/", $lastname)) {
			header("Location: ../signup.php?signup=invalid");
			exit();
		} else {
			//Check if email is vlid.
			//filter_ check a certain string using a specific method inside the php language
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				header("Location: ../signup.php?signup=email");
				exit();
			} else {
				//Check if the uid already exists in the db.
				$sql = "SELECT * FROM users WHERE user_uid='$uid'";
				$result = mysqli_query($conn, $sql); //run the query in the db
				$resultCheck = mysqli_num_rows($result); //check if the result has any data.
				
				if ($resultCheck > 0) {
					header("Location: ../signup.php?signup=usertaken");
					exit();
				} else {
					//Hashig the password
					$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
					//Insert the user into the db.
					$sql = "INSERT INTO users (user_firstname, user_lastname, user_email, user_uid, user_pwd) 
							VALUES ('$firstname', '$lastname', '$email', '$uid', '$hashedPwd');";
					mysqli_query($conn, $sql);//here we don't use a $result variable because we are not gonna use the result for the insert.
					header("Location: ../signup.php?signup=success");
					exit();
				}
			}
		}
	}
	
} else {
	header("Location: ../signup.php");
	exit();//it closes off the script from runing, usefull if there are more code AFTER the exit() function.
}

?>