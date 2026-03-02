<?php
// Start the session
session_start();

// Destroy all session data
session_unset(); 

// Destroy the session
session_destroy(); 

// Redirect to the driver login page
header("Location: driver_login.php");
exit();
?>