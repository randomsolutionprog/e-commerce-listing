<?php
session_start();
// Assuming you have a database connection named $conn (included from "conn.php")
include("../../conn.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $productName = filter_var($_POST["product_name"], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    $actualPrice = filter_var($_POST["actual_price"], FILTER_VALIDATE_FLOAT);
    $newPrice = filter_var($_POST["new_price"], FILTER_VALIDATE_FLOAT);
    $productType = filter_var($_POST["product_type"], FILTER_VALIDATE_INT);
    $stockQuantity = filter_var($_POST["stock_quantity"], FILTER_VALIDATE_INT);
    $shippingDetails = filter_var($_POST["shipping_details"], FILTER_SANITIZE_STRING);
    $warrantyInfo = filter_var($_POST["warranty_info"], FILTER_SANITIZE_STRING);
    $socialMedia = filter_var($_POST["social_media"], FILTER_SANITIZE_STRING);
    $productId = filter_var($_POST["productID"], FILTER_VALIDATE_INT);
    $categoryId = filter_var($_POST["category"], FILTER_VALIDATE_INT);

    // Check if new price is greater than or equal to actual price
    if ($newPrice >= $actualPrice) {
        // Set session error
        $_SESSION['error'] = 'The new price must be lower than'.$actualPrice.'.';
        // Redirect to your desired page
        header("Location: ../edit_product.php?snippet=4&productID=".$productId);
        exit();
    }

    if($stockQuantity<1){
        $_SESSION['error'] = 'The stock quantity must not lower than 0.';
        // Redirect to your desired page
        header("Location: ../edit_product.php?snippet=4&productID=".$productId);
        exit();
    }

    try {
        // Check if the product already has a category
        $checkCategoryQuery = "SELECT product_categoryID FROM product_category WHERE productID = ?";
        $stmtCheckCategory = $conn->prepare($checkCategoryQuery);
        $stmtCheckCategory->bind_param("i", $productId);
        $stmtCheckCategory->execute();
        $resultCheckCategory = $stmtCheckCategory->get_result();

        if ($resultCheckCategory->num_rows > 0) {
            $row = $resultCheckCategory->fetch_assoc();
            $productCategoryId = $row['product_categoryID'];
            // Product already has a category, update the category
            $updateCategoryQuery = "UPDATE product_category SET categoryID = ? WHERE product_categoryID = ?";
            $stmtUpdateCategory = $conn->prepare($updateCategoryQuery);
            $stmtUpdateCategory->bind_param("ii", $categoryId, $productCategoryId);
            $stmtUpdateCategory->execute();
        } else {
            // Product does not have a category, insert a new category
            $insertCategoryQuery = "INSERT INTO product_category (categoryID, productID) VALUES (?, ?)";
            $stmtInsertCategory = $conn->prepare($insertCategoryQuery);
            $stmtInsertCategory->bind_param("ii", $categoryId, $productId);
            $stmtInsertCategory->execute();
        }

        // Update the product and product_info tables
        $updateProductQuery = "UPDATE product p
                            JOIN product_info pi ON p.productID = pi.productID
                            SET 
                            p.product_name = ?, 
                            p.description = ?, 
                            p.actual_product_price = ?, 
                            p.new_product_price = ?, 
                            p.product_type = ?, 
                            pi.stock_quantity = ?, 
                            pi.shipping_details = ?, 
                            pi.warranty_info = ?, 
                            pi.social_media = ?
                            WHERE p.productID = ?";

        $stmtUpdateProduct = $conn->prepare($updateProductQuery);
        $stmtUpdateProduct->bind_param("ssddiisssi", $productName, $description, $actualPrice, $newPrice, $productType, $stockQuantity, $shippingDetails, $warrantyInfo, $socialMedia, $productId);

        $stmtUpdateProduct->execute();

        
        $_SESSION['success'] = 'Successfully Update';
        // Redirect to your desired page
        header("Location: ../edit_product.php?snippet=4&productID=".$productId);
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an exception
        // $conn->rollback();
        // $conn->autocommit(TRUE); // Turn off transaction
        echo "Error: " . $e->getMessage();
    }
    
}
?>
