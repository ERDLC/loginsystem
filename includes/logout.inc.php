<?php

if (isset($_POST['submit'])) {
	session_start();
	//We could unset specific sessions using de unset() function like:
	/* <?php unset($_SESSION['sessionname']); ?> */
	session_unset();
	session_destroy();
	header("Location: ../index.php");
	exit();
}

?>