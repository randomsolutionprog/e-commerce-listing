<?php
include_once("../../conn.php");

if (isset($_GET['product_type'])) {
    $productType = $_GET['product_type'];

    // Modify this query based on your database schema
    $query = "SELECT categoryID, category_name FROM category WHERE product_type = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productType);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch categories and convert to JSON
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    echo json_encode($categories);
} else {
    // Handle missing or invalid product_type parameter
    echo json_encode([]);
}
?>