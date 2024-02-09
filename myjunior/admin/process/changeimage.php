<?php
session_start();
include('../../conn.php'); // Include your database connection

// Assuming you have the productID from a form submission
$productID = $_POST['productID']; // replace with the actual productID from your form
$newImage = $_FILES['newImage']['name']; // replace with the actual form field name for the new image

// Step 1: Retrieve the existing image filename from the database
$selectQuery = "SELECT product_image FROM product WHERE productID = ?";
$stmtSelect = $conn->prepare($selectQuery);
$stmtSelect->bind_param("i", $productID);
$stmtSelect->execute();
$stmtSelect->bind_result($existingImage);
$stmtSelect->fetch();
$stmtSelect->close();

// Step 2: Unlink the existing image from the server
if (!empty($existingImage)) {
    $existingImagePath = "../../product/" . $existingImage; // Update the path accordingly
    if (file_exists($existingImagePath)) {
        unlink($existingImagePath);
    }
}

//Step 4: Generate seed and target directory
    $seed = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
    shuffle($seed);
    $rand = '';
    foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];

    $dir_key = "(" . $rand . ")";
    $target = "../../product/";

    // Extract file extension
    $fileExt = pathinfo($_FILES['newImage']['name'], PATHINFO_EXTENSION);

    // Define new file name with directory key and file extension
    $newFileName = $dir_key . '.' . $fileExt;

// Step 4: Update the product_image in the database with the new image filename
$updateQuery = "UPDATE product SET product_image = ? WHERE productID = ?";
$stmtUpdate = $conn->prepare($updateQuery);
$stmtUpdate->bind_param("si", $newFileName, $productID);

// Step 5: Move the uploaded file and execute the update query
$targetPath = "../../product/" . $newFileName; // Update the path accordingly
if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetPath) && $stmtUpdate->execute()) {
    $_SESSION["success"] = "Product image updated successfully.";
    header("Location: ../edit_product.php?productID=$productID");
    exit();
} else {
    echo "Failed to update product image: " . $stmtUpdate->error;
    $_SESSION["error"] = "Failed to upload the image.";
    header("Location: ../edit_product.php?productID=$productID");
    exit();
}

// Close the statements
$stmtUpdate->close();
$conn->close();
?>
