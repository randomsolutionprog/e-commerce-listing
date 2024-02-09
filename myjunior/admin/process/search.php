<?php
session_start();
if (isset($_POST["admin_product"])){
    // Sanitize and validate search input
    $search = filter_var(trim($_POST["search_text"]), FILTER_SANITIZE_STRING);
    $sort = filter_var($_POST["sort"], FILTER_VALIDATE_INT);

    // Validate sort input
    if ($sort === false || $sort < 1 || $sort > 4) {
        // Handle invalid sort value, perhaps set a default value
        $sort = 1;
    }

    // Unset session variable for search_product
    unset($_SESSION["search_product"]);

    // Check if search is not empty before setting the session variable
    if (!empty($search)) {
        $_SESSION["search_product"] = $search;
    }

    // Redirect with sanitized values
    header("Location: ../dashboard.php?snippet=4&table=$sort&page=1&search=" . urlencode($search));
    exit();
}
else if (isset($_POST["admin_category"])){
    // Sanitize and validate search input
    $product_type = filter_var(trim($_POST["product_type"]), FILTER_VALIDATE_INT);
    $sort = filter_var($_POST["sort"], FILTER_VALIDATE_INT);

    // Unset session variable for product_type
    unset($_SESSION["product_type"]);

    // Check if search is not empty before setting the session variable
    if (!empty($search)) {
        $_SESSION["product_type"] = $search;
    }

    // Redirect with sanitized values
    header("Location: ../dashboard.php?snippet=3&table=$sort&page=1&product_type=" . $product_type);
    exit();
}
else if(isset($_POST["admin_product_reset"])){
    $sort = $_POST["sort"];
    unset($_SESSION["search_product"]);
    header("Location: ../dashboard.php?snippet=4&table=$sort&page=1");
    exit();
}
else if(isset($_POST["admin_category_reset"])){
    $sort = $_POST["sort"];
    unset($_SESSION["product_type"]);
    header("Location: ../dashboard.php?snippet=3&table=$sort&page=1");
    exit();
}

?>