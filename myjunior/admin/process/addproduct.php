<?php
session_start();
include_once("../../conn.php");

// Function to validate product type
function isValidProductType($productType) {
    $validTypes = [1, 2, 3, 4, 5];
    return in_array($productType, $validTypes);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Step 1: Save post values into variables
    $productName = $_POST["productname"];
    $productType = isset($_POST["product_type"]) ? intval($_POST["product_type"]) : 0;
    $description = $_POST["description"];
    $actualPrice = isset($_POST["productprice_actual"]) ? floatval($_POST["productprice_actual"]) : 0;
    $newPrice = isset($_POST["productprice_new"]) ? floatval($_POST["productprice_new"]) : 0;

    // Step 2: Sanitize input
    $productName = htmlspecialchars($productName);
    $description = htmlspecialchars($description);

    // Step 3: Check image format and size
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $maxFileSize = 300 * 1024 * 1024; // 300 MB

    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION["error"] = "Error uploading file. Please try again.";
        header("Location: ../dashboard.php?snippet=4");
        exit();
    }

    $file_title = basename($_FILES['file']['name']);
    $fileExt = strtolower(pathinfo($file_title, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes) || $_FILES['file']['size'] > $maxFileSize) {
        $_SESSION["error"] = "Invalid file format or size. Please upload a valid image (JPG, JPEG, or PNG) with size less than 300 MB.";
        header("Location: ../dashboard.php?snippet=4");
        exit();
    }

    // Step 4: Generate seed and target directory
    $seed = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
    shuffle($seed);
    $rand = '';
    foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];

    $dir_key = "(" . $rand . ")";
    $target = "../../product/";

    // Extract file extension
    $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    // Define new file name with directory key and file extension
    $newFileName = $dir_key . '.' . $fileExt;

    //date
    $dateUpload = date("Y-m-d");

    // Step 5: Define query for insertion
    $insertQuery = "INSERT INTO product (product_name, product_type, description, actual_product_price, new_product_price, product_image,date_upload,userID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("sisddssi", $productName, $productType, $description, $actualPrice, $newPrice, $newFileName, $dateUpload, $_SESSION["userID"]);

    // Step 6: Execute insertion query
    if ($stmt->execute()) {
        $none = "None";
        $default = 1;
        $lastInsertedProductID = $conn->insert_id;
        $newinsertQuery = "INSERT INTO product_info (productID, stock_quantity, shipping_details, warranty_info, social_media) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($newinsertQuery);
        $stmt->bind_param("iisss", $lastInsertedProductID, $default, $none, $none, $none);
        if ($stmt->execute()) {
            // Step 7: Move the uploaded file
            $targetFile = $target . '/' . $newFileName; // Concatenate target directory with new file name
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                $_SESSION["success"] = "Successfully added the product.";
                header("Location: ../dashboard.php?snippet=4");
                exit();
            } else {
                $_SESSION["error"] = "Failed to upload the image.";
                header("Location: ../dashboard.php?snippet=4");
                exit();
            }
        } else {
            $_SESSION["error"] = "Failed to add the product.";
            header("Location: ../dashboard.php?snippet=4");
            exit();
            echo "fail 2";
        }
    } else {
        $_SESSION["error"] = "Failed to add the product.";
        header("Location: ../dashboard.php?snippet=4");
        exit();
        echo "fail 1";
    }
} else {
    // Handle non-POST requests or redirect if needed
    $_SESSION["info"] = "No request being made";
    header("Location: ../dashboard.php?snippet=4");
    exit();
}
?>
