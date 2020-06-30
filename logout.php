<?php
	// Logging out
	session_start();
	session_destroy();
	header('location: ./');
?>