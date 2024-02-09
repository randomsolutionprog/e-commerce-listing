<?php
// Start session
session_start();
include("security.php");

// Get the productID from the URL
$productID = $_GET['productID'];
include('../conn.php');
if(!empty($productID)){
    // SQL query to select all columns from both tables using LEFT JOIN
    $query = "SELECT product.*, product_info.*
            FROM product
            LEFT JOIN product_info ON product.productID = product_info.productID WHERE product.productID=$productID";

    $result = $conn->query($query);

    if ($result) {
        // Fetch the results into an associative array
        $productData = $result->fetch_all(MYSQLI_ASSOC);


    
        // SQL query to select categoryID from product_category based on productID
        $query = "SELECT categoryID FROM product_category WHERE productID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productID);
        $stmt->execute();
        $result = $stmt->get_result();
        $categoryFlag = 0;
        $selectedCategoryID = 0;
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $selectedCategoryID = $row['categoryID'];
            $categoryFlag = 1;
        } else {
            $categoryFlag = 0;
        }
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Admin</title>
            <!-- Bootstrap CSS -->
            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                rel="stylesheet"
                integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                crossorigin="anonymous">

            <script src="https://kit.fontawesome.com/56968ef8c3.js" crossorigin="anonymous"></script>
            <style>
            .product_img_bigger {
                    display: inline-block;
                }
                .product_img_smaller{
                    display: none;
                }
                /* Responsive styles for screens 900px or less */
                @media screen and (max-width: 900px) {
                    #img:active {
                        transform: translate(-50%, -50%) scale(3);
                        -ms-transform: translate(-50%, -50%) scale(3); /* IE 9 */
                        -webkit-transform: translate(-50%, -50%) scale(3); /* Safari and Chrome */
                    }
                    .product_img_bigger {
                        display: none; /* Hide text on smaller screens */
                    }
                    .product_img_smaller{
                        display: inline-block;
                    }
                }
            </style>
        </head>
        <body>
            <?php
            include("navigation.php")
            ?>
            <div class="container mt-2">
            <div class="row">
                <div class="col text-center">
                    <h3>Edit Product Information</h3>
                </div>
            </div>
            <form action="process/updateproduct.php" method="post"  enctype="multipart/form-data">
                <section style="background-color: #eee;">
                    <div class="container m-2 p-5">
                            <?php
                            include("status.php");
                            ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card mb-4 d-none d-sm-block">
                                    <div class="card-body text-center">
                                        <h5 class="my-3">Do you want to save?</h5>
                                        
                                        <div class="d-flex justify-content-center mb-2">
                                            <input type="submit" name="save" class="btn btn-primary" value="Save">
                                            <a href="dashboard.php?snippet=4" class="btn btn-outline-primary ms-1">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-4 mb-lg-0">
                                    <div class="card-body p-0 text-center">
                                        <label for="imageInput" class="img-container m-3">
                                        <p class="m-3">Product Image</p>
                                            <img src="../product/<?php echo $productData[0]['product_image'];?>" class="img-fluid mx-auto d-block shadow-ms" id="productImage" style="max-height: 200px;">
                                            <button type="button" class="btn btn-dark mt-2" data-bs-toggle="modal" data-bs-target="#change_image">Change <span class="product_img_bigger">Product</span> Image</button>
                                        </label>
                                       
                                    </div>
                                </div>
                                <div class="card mb-4 mt-4 d-none d-sm-block">
                                    <div class="card-body text-center">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-0">Category</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <div id="categoryContainer1">
                                                    <!-- Category dropdown will be inserted here -->
                                                </div>
                                                <?php
                                                    if ($categoryFlag > 0) {
                                                        // Display a message for found categories with Bootstrap styling
                                                        echo '<p class="text-success" style="font-size: 12px;">Categories found for this product.</p>';
                                                    } else {
                                                        // Display a message for no categories found with Bootstrap styling
                                                        echo '<p class="text-danger" style="font-size: 12px;">No categories found for this product.</p>';
                                                    }
                                                    ?>

                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Product Name</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="hidden" class="form-control" name="productID" value="<?php echo $productID;?>">
                                                <p class="text-muted mb-0"><input type="text" class="form-control" name="product_name" value="<?php echo $productData[0]['product_name'];?>"></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Description</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><textarea class="form-control" id="description" placeholder="Enter Product Description" name="description" rows="5"><?php echo $productData[0]['description'];?></textarea></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Actual Price (RM)</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><input type="number" step="any" class="form-control" name="actual_price" value="<?php echo $productData[0]['actual_product_price'];?>"></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">New Price (RM)</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0"><input type="number" step="any" class="form-control" name="new_price" value="<?php echo $productData[0]['new_product_price'];?>"></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Product Type</p>
                                            </div>
                                            <div class="col-sm-9">
                                            <p class="text-muted mb-0">
                                                <?php
                                                $type = (int)$productData[0]['product_type'];
                                                ?>
                                                <select class="form-select" aria-label=".form-select-lg example" name="product_type" id="product_type" onchange="updateCategoryDropdown()">
                                                    <option value="1" <?php echo ($type == 1) ? 'selected' : ''; ?>>Apparel</option>
                                                    <option value="2" <?php echo ($type == 2) ? 'selected' : ''; ?>>Golf Clubs</option>
                                                    <option value="3" <?php echo ($type == 3) ? 'selected' : ''; ?>>Used Clubs</option>
                                                    <option value="4" <?php echo ($type == 4) ? 'selected' : ''; ?>>Shoes</option>
                                                    <option value="5" <?php echo ($type == 5) ? 'selected' : ''; ?>>Accessories</option>
                                                </select>
                                            </p>
                                            </div>
                                        </div>
                                        
                                        <div class="row d-block d-sm-none">
                                        <div class="col-sm-4">
                                                <p class="mt-2 mb-0">Category</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <div id="categoryContainer2">
                                                    <!-- Category dropdown will be inserted here -->
                                                </div>
                                                <?php
                                                    if ($categoryFlag > 0) {
                                                        // Display a message for found categories with Bootstrap styling
                                                        echo '<p class="text-success" style="font-size: 12px;">Categories found for this product.</p>';
                                                    } else {
                                                        // Display a message for no categories found with Bootstrap styling
                                                        echo '<p class="text-danger" style="font-size: 12px;">No categories found for this product.</p>';
                                                    }

                                                    // Close the statement and connection
                                                    $stmt->close();
                                                    ?>

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Stock Quantity</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    <input type="number" step="any" class="form-control" name="stock_quantity" value="<?php echo isset($productData[0]['stock_quantity']) ? $productData[0]['stock_quantity'] : 0; ?>">
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Shiping Details</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    <input type="text" class="form-control" name="shipping_details" value="<?php echo isset($productData[0]['shipping_details']) ? $productData[0]['shipping_details'] : 'None'; ?>">
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Warranty Info</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    <input type="text" class="form-control" name="warranty_info" value="<?php echo isset($productData[0]['warranty_info']) ? $productData[0]['warranty_info'] : 'None'; ?>">
                                                </p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Social Media Link</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    <input type="text" class="form-control" name="social_media" value="<?php echo isset($productData[0]['social_media']) ? $productData[0]['social_media'] : 'None'; ?>">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 d-block d-sm-none">
                            <div class="card-body text-center">
                                <h5 class="my-3">Do you want to save?</h5>
                                
                                <div class="d-flex justify-content-center mb-2">
                                        <input type="submit" name="save" class="btn btn-primary" value="Save">
                                        <a href="dashboard.php?snippet=4" class="btn btn-outline-primary ms-1">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
            </div>
            <?php
            include("display/changeImage.php")
            ?>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <script>
                var myModal = document.getElementById('change_image')
                var myInput = document.getElementById('productname')

                myModal.addEventListener('shown.bs.modal', function () {
                myInput.focus()
            })
            </script>
          
            <script>
                var selectedCategoryID = <?php echo json_encode($selectedCategoryID); ?>;
                
                function updateCategoryDropdown(num) {
                    // Get the selected product type
                    var productType = document.getElementById("product_type").value;
                   

                    // Make an AJAX request to fetch categories based on product type
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Parse the JSON response
                            var categories = JSON.parse(xhr.responseText);
                            
                            // Update the category dropdown options
                            updateDropdownOptions(categories, num);
                        }
                    };

                    // Replace "fetch_categories.php" with the actual PHP script to fetch categories
                    xhr.open("GET", "process/fetch_categories.php?product_type=" + productType, true);
                    xhr.send();
                }

                function updateDropdownOptions(categories, num) {
                    // Get the category dropdown container
                    var categoryContainer = document.getElementById("categoryContainer" + num);

                    // Create a new select element
                    var categoryDropdown = document.createElement("select");
                    categoryDropdown.className = "form-select";
                    categoryDropdown.name = "category";
                    categoryDropdown.id = "category";

                    // Add options to the category dropdown based on the fetched categories
                    for (var i = 0; i < categories.length; i++) {
                        var option = document.createElement("option");
                        option.value = categories[i].categoryID;
                        option.text = categories[i].category_name;

                        // Check if the categoryID matches the selected categoryID
                        if (categories[i].categoryID === selectedCategoryID) {
                            option.selected = true;
                        }

                        categoryDropdown.appendChild(option);
                    }
                    
                    // Remove any existing category dropdown
                    while (categoryContainer.firstChild) {
                        categoryContainer.removeChild(categoryContainer.firstChild);
                    }

                    // Append the new category dropdown to the container
                    categoryContainer.appendChild(categoryDropdown);
                }

                // Function to handle the update of category dropdown
                function handleCategoryDropdown() {
                    if (window.innerWidth > 1200) {
                        // Call the updateCategoryDropdown function for larger screens
                        updateCategoryDropdown("1");
                    } else {
                        // Call the updateCategoryDropdown function for smaller screens
                        updateCategoryDropdown("2");
                    }
                }

                // Add an event listener for the "DOMContentLoaded" event
                document.addEventListener("DOMContentLoaded", handleCategoryDropdown);

                // Add an event listener for the window resize event
                window.addEventListener("resize", handleCategoryDropdown);

                // Add an event listener for the "change" event on the product type dropdown
                document.getElementById("product_type").addEventListener("change", function () {
                    // Update the dropdown based on screen width
                    handleCategoryDropdown();
                });

                
            </script>

        </body>
    </html>
<?php
    } else {
        echo "Error executing the query: " . $conn->error;
    }
}
else{
    $_SESSION["error"] = "Invalid Method";
    header("Location: ../dashboard.php?snippet=4");
    exit();
}
    // Close the database connection
    $conn->close();
?>