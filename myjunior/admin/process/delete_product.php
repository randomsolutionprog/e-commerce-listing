<?php
session_start();
include_once("../../conn.php");

if (isset($_GET['productID']) && is_numeric($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Step 1: Select product image from product table based on productID
    $selectImageQuery = "SELECT product_image FROM product WHERE productID = ?";
    $stmtSelectImage = $conn->prepare($selectImageQuery);
    $stmtSelectImage->bind_param("i", $productID);
    $stmtSelectImage->execute();
    $stmtSelectImage->bind_result($productImage);
    $stmtSelectImage->fetch();
    
    // Close the result set before preparing the next query
    $stmtSelectImage->close();

    // Step 2: Delete image based on directory ../../product/ and the image name
    $imagePath = "../../product/" . $productImage;
    if (file_exists($imagePath)) {
        unlink($imagePath); // Delete the image file
    } else {
        $_SESSION['error'] = "Failed to delete because image not found!!!";
        // Redirect back to dashboard.php?snippet=4
        header("Location: ../dashboard.php?snippet=4");
        exit();
    }

    // Step 3: Delete product based on productID
    $deleteProductQuery = "DELETE FROM product WHERE productID = ?";
    $stmtDeleteProduct = $conn->prepare($deleteProductQuery);
    $stmtDeleteProduct->bind_param("i", $productID);

    // Execute deletion query
    if ($stmtDeleteProduct->execute()) {
        // Delete corresponding entry in product_info table
        $deleteProductInfoQuery = "DELETE FROM product_info WHERE productID = ?";
        $stmtDeleteProductInfo = $conn->prepare($deleteProductInfoQuery);
        $stmtDeleteProductInfo->bind_param("i", $productID);
        $stmtDeleteProductInfo->execute();
        $stmtDeleteProductInfo->close(); // Close the result set

        // Set success message in session
        $_SESSION['success'] = "Product deleted successfully.";

        // Reset auto-increment for product table
        $resetAutoIncrementQueryProduct = "ALTER TABLE product AUTO_INCREMENT = 1";
        $conn->query($resetAutoIncrementQueryProduct);

        // Reset auto-increment for product_info table
        $resetAutoIncrementQueryProductInfo = "ALTER TABLE product_info AUTO_INCREMENT = 1";
        $conn->query($resetAutoIncrementQueryProductInfo);

        // Redirect back to dashboard.php?snippet=4
        header("Location: ../dashboard.php?snippet=4");
        exit();
    } else {
        // Set error message in session
        $_SESSION['error'] = "Failed to delete the product.";
        // Redirect back to dashboard.php?snippet=4
        header("Location: ../dashboard.php?snippet=4");
        exit();
    }

} else {
    // Handle invalid productID or missing parameter
    $_SESSION['error'] = "Invalid productID.";
    // Redirect back to dashboard.php?snippet=4
    header("Location: ../dashboard.php?snippet=4");
    exit();
}
?>
