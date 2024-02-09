<?php
session_start();
include_once("../../conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set in the POST request
    if (isset($_POST["categoryname"]) && isset($_POST["product_type"])) {
        // Sanitize and validate input
        $categoryname = filter_var($_POST["categoryname"], FILTER_SANITIZE_STRING);
        $product_type = filter_var($_POST["product_type"], FILTER_VALIDATE_INT);

        // Check if the inputs are valid
        if ($categoryname && $product_type !== false) {
            // Insert data into the category table using prepared statements
            $insertQuery = "INSERT INTO category (category_name, product_type) VALUES (?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("si", $categoryname, $product_type);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Category added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add category.";
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = "Invalid input data.";
        }
    } else {
        $_SESSION['error'] = "Missing required fields.";
    }

    // Redirect back to the page where the form was submitted
    header("Location: ../dashboard.php?snippet=3");
    exit();
} else {
    // Handle non-POST requests
    $_SESSION['error'] = "Invalid request method.";
    header("Location: ../dashboard.php?snippet=3");
    exit();
}
?>
