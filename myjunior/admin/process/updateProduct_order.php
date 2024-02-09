<?php
// Include the database connection file
include_once("../../conn.php");

// Check if the new order data is received
if (isset($_POST['newOrder'])) {
    // Sanitize the received data to prevent SQL injection
    $newOrder = $_POST['newOrder'];
    $minOrder = $_POST['minOrder'];
   
    // Find the smallest number in the array
    $minNumber = min($minOrder);

    // Prepare the SQL statement
    $sql = "UPDATE product SET product_order = ? WHERE productID = ?";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the query for each productID
    $i = $minNumber;
    foreach ($newOrder as $productId) {
        $stmt->bind_param("ii", $i, $productId);
        $stmt->execute();
        $i++;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "New order data not received";
}
?>
