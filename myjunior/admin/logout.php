<?php
session_start();
session_destroy();

// Redirect to the login page or any other page
header("Location: index.php");
exit(); // Stop script execution

?>