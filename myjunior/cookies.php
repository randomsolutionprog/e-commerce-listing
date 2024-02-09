<?php

$random_name = $_GET["random_name"];
// Store in the database
include("conn.php"); // Assuming you have a file for database connection
$insertQuery = "INSERT INTO trace_visitor (visitor_name) VALUES (?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("s", $random_name);
$stmt->execute();
?>
 