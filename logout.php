<?php
session_start();
session_unset();  // Clear session variables
session_destroy(); // End the session
header("Location: login.php"); // Redirect to login
exit();
?>
