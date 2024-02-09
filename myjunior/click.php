<?php

include("conn.php");
include("encrypt.php");
$productID = $_GET["productID"];
$productIDdec = decrypt($productID, $key);
$randomName = $_COOKIE["random_name"];

// Step 1: Select visitorID by visitor_name
$stmt = $conn->prepare("SELECT visitorID FROM trace_visitor WHERE visitor_name = ?");
$stmt->bind_param("s", $randomName);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    // If visitorID not found, redirect to display_search.php
    header("Location: display_search.php?productID=".$_GET["productID"]);
    exit;
}
else{   
    // Prepare the SQL query to join the tables, select the product_type, and filter by productID
    $stmt = $conn->prepare("SELECT c.product_type 
    FROM category c 
    INNER JOIN product_category pc ON c.categoryID = pc.categoryID
    WHERE pc.productID = ?");
    $stmt->bind_param("i", $productIDdec);
    $stmt->execute();
    $result = $stmt->get_result();
    // Fetch the result rows and output the product types
    $row = $result->fetch_assoc();
    $product_type = $row['product_type'];

    // Step 2: If visitorID found
    // Select clickID by visitorID
    $stmt = $conn->prepare("SELECT * FROM trace_click WHERE productID = ?");
    $stmt->bind_param("i", $productIDdec);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        // If clickID not found, insert a new record
        $stmt = $conn->prepare("INSERT INTO trace_click (productID, product_type) VALUES (?, ?)");
        $stmt->bind_param("ii", $productIDdec, $product_type);
        
    } else {
        // If clickID found, update existing record with new action
        $clickID = $row["clickID"];
        $pastCount = $row['click_count'] + 1;
        $stmt = $conn->prepare("UPDATE trace_click SET click_count = ? WHERE clickID = ?");
        $stmt->bind_param("si", $pastCount, $clickID);
    }
    $stmt->execute();
    // Close connection
    $stmt->close();
    $conn->close();
}

header("Location: display_search.php?productID=".$_GET["productID"]);
exit;
?>