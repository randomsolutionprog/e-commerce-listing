<?php

// Initialize the base query
$query = "SELECT * FROM product
          JOIN product_info ON product.productID = product_info.productID
          LEFT JOIN product_category ON product.productID = product_category.productID
          LEFT JOIN category ON product_category.categoryID = category.categoryID";

// Check conditions
if (isset($_GET['categoryID']) && isset($_GET['product_type'])) {
    // Condition 1: Both categoryID and product_type are set
    $categoryID = filter_var($_GET['categoryID'], FILTER_VALIDATE_INT);
    $productType = filter_var($_GET['product_type'], FILTER_VALIDATE_INT);

    if ($categoryID !== false && $categoryID > 0 && $productType !== false && $productType >= 0 && $productType <= 5) {
        // Use the base query with additional conditions
        $query .= " WHERE product_category.categoryID = $categoryID AND product.product_type = $productType";
    } else {
        // Invalid parameters, handle the error or redirect to error page
    }
} elseif (isset($_GET['product_type'])) {
    // Condition 2: Only product_type is set
    $productType = filter_var($_GET['product_type'], FILTER_VALIDATE_INT);

    if ($productType !== false && $productType >= 0 && $productType <= 5) {
        // Use the base query with additional condition
        $query .= " WHERE product.product_type = $productType";
    } else {
        // Invalid parameter, handle the error or redirect to error page
    }
} else {
    // Condition 3: Neither categoryID nor product_type is set
    $query .= " WHERE product.new_product_price > 0";
}

?>