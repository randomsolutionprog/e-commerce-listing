<?php
session_start();
include_once("../../conn.php");

if (isset($_GET['categoryID']) && is_numeric($_GET['categoryID'])) {
    $categoryID = $_GET['categoryID'];

// Assuming you have a database connection named $conn (included from "conn.php")
include("../../conn.php");

// Prepare and execute the SQL query
$query = "SELECT productID FROM product_category WHERE categoryID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$result = $stmt->get_result();

// Check if any rows are returned
if ($result->num_rows > 0) {
    // Fetch the productIDs from the result set
    $number=0;
    while ($row = $result->fetch_assoc()) {
        $number++;
    }
    $_SESSION['error'] = "Failed to delete the category because there are ".$number." product related.";
    // Redirect back to dashboard.php?snippet=4
    header("Location: ../dashboard.php?snippet=3");
    exit();
}
else {
   // Prepare and execute the SQL query to delete the category
    $query = "DELETE FROM category WHERE categoryID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $categoryID);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        // Reset auto-increment for product table
        $resetAutoIncrementQueryProduct = "ALTER TABLE category AUTO_INCREMENT = 1";
        $conn->query($resetAutoIncrementQueryProduct);


        $_SESSION['success']="The category has been deleted successfully.";
        // Redirect back to dashboard.php?snippet=4
        header("Location: ../dashboard.php?snippet=3");
        exit();
    } 
    else {
        $_SESSION['error'] = "Failed to delete the category";
        header("Location: ../dashboard.php?snippet=3");
        exit();
    }

}

// Close the database connection
$stmt->close();
}
?>