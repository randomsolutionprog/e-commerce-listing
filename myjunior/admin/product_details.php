<?php
// Start session
session_start();
include("security.php");
include_once("../conn.php");

// Retrieve productID from the URL parameter
if (isset($_GET['productID']) && is_numeric($_GET['productID'])) {
    $productID = $_GET['productID'];

    $query = "SELECT * FROM product
          JOIN product_info ON product.productID = product_info.productID
          LEFT JOIN product_category ON product.productID = product_category.productID
          LEFT JOIN category ON product_category.categoryID = category.categoryID
          WHERE product.productID = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productData = $result->fetch_all(MYSQLI_ASSOC);

       
    
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
            .text-bold {
            font-weight: 800;
            }

            text-color {
                color: #0093c4;
            }

            /* Main image - left */
            .main-img img {
                width: 100%;
            }

            /* Preview images */
            .previews img {
                width: 100%;
                height: 140px;
            }

            .main-description .category {
                text-transform: uppercase;
                color: #0093c4;
            }

            .main-description .product-title {
                font-size: 2.5rem;
            }

            .old-price-discount {
                font-weight: 600;
            }

            .new-price {
                font-size: 2rem;
            }

            .details-title {
                text-transform: uppercase;
                font-weight: 600;
                font-size: 1.2rem;
                color: #757575;
            }

            .buttons .block {
                margin-right: 5px;
            }

            .quantity input {
                border-radius: 0;
                height: 40px;

            }


            .custom-btn {
                text-transform: capitalize;
                background-color: #0093c4;
                color: white;
                width: 150px;
                height: 40px;
                border-radius: 0;
            }

            .custom-btn:hover {
                background-color: #0093c4 !important;
                font-size: 18px;
                color: white !important;
            }

            .similar-product img {
                height: 400px;
            }

            .similar-product {
                text-align: left;
            }

            .similar-product .title {
                margin: 17px 0px 4px 0px;
            }

            .similar-product .price {
                font-weight: bold;
            }

            .questions .icon i {
                font-size: 2rem;
            }

            .questions-icon {
                font-size: 2rem;
                color: #0093c4;
            }
            a {
            font-size:14px;
            font-weight:700
        }

            /* Small devices (landscape phones, less than 768px) */
            @media (max-width: 767.98px) {

                /* Make preview images responsive  */
                .previews img {
                    width: 100%;
                    height: auto;
                }

            }
        </style>
    </head>
    <body>
       
        <?php
        include("navigation.php")
        ?>
       <div class="container shadow"><!--Head of conatin-->
        <div class="container mt-5">
            <h1>Product View</h1>
        </div>
        <div class="container my-5">
        <div class="row">
            <div class="col-md-5">
                <div class="main-img">
                    <img class="img-fluid shadow-sm border-sm" src="../product/<?php echo $productData[0]['product_image'];?>" alt="ProductS">
                </div>
                
            </div>
            <div class="col-md-7 mt-2">
                <div class="main-description px-2">
                    <div class="category text-bold">
                        <?php
                        $type = $productData[0]['product_type'];
                        if($type==1){
                            echo "APPAREL: ";
                        }
                        elseif($type==2){
                            echo "GOLF CLUBS: ";
                        }
                        elseif($type==3){
                            echo "USED CLUBS: ";
                        }
                        elseif($type==4){
                            echo "SHOES: ";
                        }
                        elseif($type==5){
                            echo "ACCESSORIES: ";
                        }
                        echo $productData[0]['category_name'];
                        ?>
                    </div>
                    <div class="product-title text-bold mt-3">
                        <?php echo $productData[0]['product_name'];   ?>
                    </div>
                  
                        <p class="text-mute">
                            Publish By:
                            <span class='text-danger'>My Junior Golf</span>
                        </p>
                  

                    <div class="price-area my-4">
                        <!--Final Price--->
                        <?php
                         $actual = $productData[0]['actual_product_price'];
                         $new = $productData[0]['new_product_price'];
                        if ($new > 0) {
                            $percent = ($new / $actual) * 100;
                            $percentOff = 100 - $percent;
                            ?>
                            <p class="old-price mb-1">
                                <del>
                                    RM<?php echo $actual; ?>
                                </del> 
                                <span class="old-price-discount text-danger">(<?php echo number_format($percentOff, 2); ?>% off)</span>
                            </p>
                            <?php
                            echo '<p class="new-price text-bold mb-1">RM' . $new . '</p>';
                        } else {
                            echo '<p class="new-price text-bold mb-1">RM' . $actual . '</p>';
                        }
                        ?>
                   
                    <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-boxes-stacked"></i></span> <b>Available:</b> 
                        <?php echo $productData[0]['stock_quantity'];?>
                    </p>
                        
                    </div>


                    <div class="buttons d-flex my-5">
                        <div class="block">
                            <a href="https://wa.me/+601136685033/?text=I%20want%20this%20product:<?php echo $productData[0]['product_name']?>" target="_blank" class="btn btn-success">Order via WhatsApp</a>
                        </div>
                        <div class="block">
                        <?php
                        $socialMediaLink = $productData[0]['social_media'];

                        // Check if the value is not "None" and not null
                        if ($socialMediaLink !== "None" && !is_null($socialMediaLink)) {
                            // Extract the domain from the link
                            $parsedUrl = parse_url($socialMediaLink);
                            $domain = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';

                            // Display the link text as a button
                            echo '<a href="' . $socialMediaLink . '" target="_blank" class="btn btn-primary">' . $domain . '</a>';
                        }
                        ?>


                        </div>
                    </div>
                    



                </div>

                <div class="product-details my-4">
                    <p class="details-title text-color mb-1">Product Details</p>
                    <hr>
                    <div class="col border">
                        <p class="description px-2 py-1">
                        <?php 
                        echo nl2br($productData[0]['description']);
                        ?>    
                        </p>
                    </div>
                    
                </div>
              
                <div class="row questions bg-light p-3">
                    <div class="col-md-1 icon text-center mb-2">
                        <i class="fa-solid fa-circle-info" style="color: blue;"></i>
                    </div>
                    <div class="col-md-11 text">
                    PROCEDURE: ORDER VIA WHATSAPP > BANK IN > POS
                    </div>
                </div>

               
                <div class="delivery-options my-4 row">
                    <div class="col-4 mr-0 pr-0">
                        <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-file-shield" ></i></span> <b>Warranty Information: </b>
                    </div>
                    <div class="col-6 ml-0 pl-0 border">
                        <p><?php echo nl2br($productData[0]["warranty_info"])?></p>
                    </div>
                </div>
                <div class="delivery-options my-4 row">
                    <div class="col-4 mr-0 pr-0">
                    <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-truck"></i></span> <b>Shipping Information: </b></p>
                    </div>
                    <div class="col-6 ml-0 pl-0 border">
                        <p><?php echo nl2br($productData[0]["shipping_details"])?></p>
                    </div>
                </div>
             
            </div>
        </div>
    </div>

    <div class="container similar-products my-4">
    <hr>
    <p class="display-5">Similiar Products</p>

    <div class="row">
        <?php
        // Assuming you have a database connection named $conn (included from "conn.php")

        // Get the product type and product ID from the current product
        $currentProductType = $productData[0]['product_type'];
        $currentProductID = $productData[0]['productID'];

        // Query to fetch similar products based on the same product type (excluding the current product)
        $query = "SELECT * FROM product
        JOIN product_info ON product.productID = product_info.productID
        LEFT JOIN product_category ON product.productID = product_category.productID
        LEFT JOIN category ON product_category.categoryID = category.categoryID
        WHERE product.productID <> ? AND product.product_type = ?
        LIMIT 4";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $currentProductID,  $currentProductType);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display similar product information using the fetched data
                
                echo '<div class="col-md-3">';
                echo '    <div class="card">';
                echo '      <a class="text-decoration-none" style="color: inherit; " href="product_details.php?snippet=4&productID='.$row["productID"].'">';
                echo '        <div class="d-flex justify-content-between p-3">';
                echo '              <p class="lead mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                    . substr($row['product_name'], 0, 15) . (strlen($row['product_name']) > 15 ? '...' : '')
                                    . '</p>';
                echo '            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-1-strong" style="width: 35px; height: 35px; border: 2px black solid;">';
                echo '                <i class="fa-solid fa-box-open"></i>';
                echo '            </div>';
                echo '        </div>';
                echo '<img src="../product/' . $row['product_image'] . '" class="card-img-top shadow-sm" alt="Preview" style="object-fit: cover; height: 200px; width: 100%;">';
                echo '        <div class="card-body">';
                echo '            <!-- Category and Discount Button -->';
                echo '            <div class="d-flex justify-content-between mb-2">';
                echo '                <p class="mb-0 small">';
                echo '                    '.$row['category_name'];
                echo '                </p>';
                // Check if new product price is greater than 0 before displaying it
                if ($row['new_product_price'] > 0) {
                    echo '                <p class="text-danger mb-0 small">';
                    echo '                    <s>RM' . $row['actual_product_price'] . '</s>';
                    echo '                </p>';
                }
                echo '            </div>';
                echo '            <div class="d-flex justify-content-between mb-3">';
                echo '                  <h5 class="mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                        . substr($row['product_name'], 0, 12) . (strlen($row['product_name']) > 12 ? '...' : '')
                                        . '</h5>';
                echo '                  <h5 class="text-dark mb-0">RM' . ($row['new_product_price'] > 0 ? $row['new_product_price'] : $row['actual_product_price']) . '</h5>';
                echo '            </div>';
            
                echo '            <div class="d-flex justify-content-between mb-2">';
                echo '                <p class="text-muted mb-0">Available:';
                echo '                    <span class="fw-bold">' . $row['stock_quantity'] . '</span>';
                echo '                </p>';
                echo '            </div>';
            
                echo '            <!-- Order Button -->';
                echo '            <div class="d-flex justify-content-between mb-2">';
                echo '                <p class="text-muted mb-0 text">';
                echo '                    <a href="https://wa.me/+601136685033/?text=I%20want%20this%20product" target="_blank" class="btn btn-success">Order via WhatsApp</a>';
                echo '                </p>';
                echo '            </div>';
                echo '        </div>';
                echo'</a>';
                echo '    </div>';
                echo '</div>';
            
            }
        }else {
            // If no results are found, display the "There None Sorry!!!" text in the center
            echo '<div class="alert alert-secondary text-center" role="alert" style="margin: 4px 4px; opacity: 0.6;">';
            echo '    <h3>There None Sorry!!!</h3>';
            echo '</div>';
        }
        
        ?>
    </div>
</div>



    <!--On Sale-->
    <div class="container similar-products my-4">
        <hr>
        <p class="display-5">On Sale Products</p>
        <div class="row">
        <?php
        // Assuming you have a database connection named $conn (included from "conn.php")

        // Get the product type and product ID from the current product
        $currentProductType = $productData[0]['product_type'];
        $currentProductID = $productData[0]['productID'];

        // Query to fetch similar products based on the same product type (excluding the current product)
        $query = "SELECT p.*, pi.*
                FROM product p
                JOIN product_info pi ON p.productID = pi.productID
                WHERE p.product_type = ? AND p.productID <> ? AND p.new_product_price > 0
                LIMIT 4";


        $query = "SELECT * FROM product
        JOIN product_info ON product.productID = product_info.productID
        LEFT JOIN product_category ON product.productID = product_category.productID
        LEFT JOIN category ON product_category.categoryID = category.categoryID
        WHERE product.productID <> ? AND product.new_product_price > 0
        LIMIT 4";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $currentProductID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Display similar product information using the fetched data
                
                echo '<div class="col-md-3">';
                echo '    <div class="card">';
                echo '      <a class="text-decoration-none" style="color: inherit; " href="product_details.php?snippet=4&productID='.$row["productID"].'">';
                echo '        <div class="d-flex justify-content-between p-3">';
                echo '              <p class="lead mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                    . substr($row['product_name'], 0, 15) . (strlen($row['product_name']) > 15 ? '...' : '')
                                    . '</p>';
                echo '            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-1-strong" style="width: 35px; height: 35px; border: 2px black solid;">';
                echo '                <i class="fa-solid fa-tags" style="color: red;"></i>';
                echo '            </div>';
                echo '        </div>';
                echo '<img src="../product/' . $row['product_image'] . '" class="card-img-top shadow-sm" alt="Preview" style="object-fit: cover; height: 200px; width: 100%;">';
                echo '        <div class="card-body">';
                echo '            <!-- Category and Discount Button -->';
                echo '            <div class="d-flex justify-content-between mb-2">';
                echo '                <p class="mb-0 small">';
                echo '                    '.$row['category_name'];
                echo '                </p>';
                // Check if new product price is greater than 0 before displaying it
                if ($row['new_product_price'] > 0) {
                    echo '                <p class="text-danger mb-0 small">';
                    echo '                    <s>RM' . $row['actual_product_price'] . '</s>';
                    echo '                </p>';
                }
                echo '            </div>';
                echo '            <div class="d-flex justify-content-between mb-3">';
                echo '                  <h5 class="mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                        . substr($row['product_name'], 0, 12) . (strlen($row['product_name']) > 12 ? '...' : '')
                                        . '</h5>';
                echo '                  <h5 class="text-dark mb-0">RM' . ($row['new_product_price'] > 0 ? $row['new_product_price'] : $row['actual_product_price']) . '</h5>';
                echo '            </div>';
            
                echo '            <div class="d-flex justify-content-between mb-2">';
                echo '                <p class="text-muted mb-0">Available:';
                echo '                    <span class="fw-bold">' . $row['stock_quantity'] . '</span>';
                echo '                </p>';
                echo '            </div>';
            
                echo '            <!-- Order Button -->';
                echo '            <div class="d-flex justify-content-between mb-2">';
                echo '                <p class="text-muted mb-0 text">';
                echo '                    <a href="https://wa.me/+601136685033/?text=I%20want%20this%20product" target="_blank" class="btn btn-success">Order via WhatsApp</a>';
                echo '                </p>';
                echo '            </div>';
                echo '        </div>';
                echo'</a>';
                echo '    </div>';
                echo '</div>';
            
            }
        }else {
            // If no results are found, display the "There None Sorry!!!" text in the center
            echo '<div class="alert alert-secondary text-center" role="alert" style="margin: 4px 4px; opacity: 0.6;">';
            echo '    <h3>There None Sorry!!!</h3>';
            echo '</div>';
        }
        ?>
    </div>
    </div>
    <!--End Head of conatin-->
</div> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>

</html>
<?php
} else {
    echo "No data found for productID: " . $productID;
}
} else {
echo "Invalid productID.";
}
?>