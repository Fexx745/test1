<?php
	ob_start();
	session_start();
	
	if(isset($_GET["Line"]))
	{
		$Line = $_GET["Line"];
		$_SESSION["strProductID"][$Line] = "";
		$_SESSION["strQty"][$Line] = "";
		// $_SESSION['dePro'];
		// unset($_SESSION['inPro']);
	}
	header("location:cart.php");
?>