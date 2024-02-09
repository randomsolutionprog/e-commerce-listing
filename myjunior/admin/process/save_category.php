<?php
session_start();
include_once("../../conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required fields are set in the POST request
    if (isset($_POST["product_type"], $_POST["categoryID"])) {
        // Sanitize and validate input
        $product_type = filter_var($_POST["product_type"], FILTER_VALIDATE_INT);
        $categoryID = filter_var($_POST["categoryID"], FILTER_VALIDATE_INT);

        // Check if the input is valid
        if ($product_type !== false && $categoryID !== false) {
            // Update the product_type in the category table using prepared statements
            $updateQuery = "UPDATE category SET product_type = ? WHERE categoryID = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ii", $product_type, $categoryID);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Category updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update category.";
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
