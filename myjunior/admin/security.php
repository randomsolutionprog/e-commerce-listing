<?php
// Check if user is not logged in
if (!isset($_SESSION["userID"])) {
    // Redirect to login page or display an error message
    header("Location: ../fail/error401.php");
    exit();
}
?>