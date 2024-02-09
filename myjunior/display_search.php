<?php
session_start();
include_once("conn.php");
include("encrypt.php");
function getCurrentDomain() {
    // Get the current URL
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
    // Parse the URL
    $parsed_url = parse_url($url);
    
    // Extract and return the domain
    return $parsed_url['scheme'] . '://' . $parsed_url['host'];
}

function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocol . "://" . $host . $uri;
}

// Usage
$currentUrl = getCurrentUrl();
// Usage
$domain = getCurrentDomain();

// Retrieve productID from the URL parameter
if (isset($_GET['productID'])) {
    $productID = decrypt($_GET['productID'], $key);

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
        <title>My Golf Booth: <?php echo htmlspecialchars($productData[0]['product_name']); ?></title>
        <meta name="description" content="Listing new and used golf equipment for sale">
        <meta name="url" content="<?php echo getCurrentUrl(); ?>">
        <meta name="image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">
        <meta name="type" content="website">
        <meta name="author" content="My Junior Golf">

        <!-- Google / Search Engine Tags -->
        <meta itemprop="name" content="My Golf Booth Product Listing">
        <meta itemprop="description" content="Listing new and used golf equipment for sale">
        <meta itemprop="image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">

        <!-- Facebook Meta Tags -->
        <meta property="og:url" content="<?php echo getCurrentUrl(); ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo htmlspecialchars($productData[0]['product_name']); ?>">
        <meta property="og:description" content="Listing new and used golf equipment for sale">
        <meta property="og:image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo htmlspecialchars($productData[0]['product_name']); ?>">
        <meta name="twitter:description" content="Listing new and used golf equipment for sale">
        <meta name="twitter:image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">

        
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
        include("main_menu.php")
        ?>
        <section class="mb-10" style="background-color: #eee;" id="">
       <div class="container shadow bg-white" ><!--Head of conatin-->
        <div class="container py-5">
        <div class="row">
            <div class="col-md-5">
                <div class="main-img">
                    <img class="img-fluid shadow-sm border-sm" src="product/<?php echo $productData[0]['product_image'];?>" alt="ProductS">
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


                    <div class="buttons d-flex flex-column flex-md-row my-5">
                        <div class="block mb-3 mb-md-0">
                            <?php
                                echo '<a href="https://wa.me/601136685033/?text=I%20want%20to%20order%20this%20product:%0A' . urlencode($productData[0]['product_name']) . '%20(RM' . ($productData[0]['new_product_price'] > 0 ? $productData[0]['new_product_price']  : $productData[0]['actual_product_price'] ) . ')" target="_blank" class="btn btn-success">Order via WhatsApp</a>';
                            ?>
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
        <p class="display-5 text-danger" >Similiar Products</p>

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
            LIMIT 10";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $currentProductID,  $currentProductType);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display similar product information using the fetched data
                    if ($row['categoryID'] !== null) {
                        echo '<div class="col-md-3 mb-3">';
                        echo '    <div class="card">';
                        echo '      <a class="text-decoration-none" style="color: inherit; " href="click.php?productID='.encrypt($row["productID"], $key).'">';
                        echo '        <div class="d-flex justify-content-between p-3">';
                        echo '              <p class="lead mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                            . substr($row['product_name'], 0, 15) . (strlen($row['product_name']) > 15 ? '...' : '')
                                            . '</p>';
                        echo '            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-1-strong" style="width: 35px; height: 35px; border: 2px black solid;">';
                        echo '                <i class="fa-solid fa-box-open"></i>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '<img src="product/' . $row['product_image'] . '" class="card-img-top shadow-sm" alt="Preview" style="object-fit: cover; height: 200px; width: 100%;">';
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
                        echo '                  <a href="https://wa.me/601136685033/?text=I%20want%20to%20order%20this%20product:%0A' . urlencode($row['product_name']) . '%20(RM' . ($row['new_product_price'] > 0 ? $row['new_product_price'] : $row['actual_product_price']) . ')" target="_blank" class="btn btn-success">Order via WhatsApp</a>';
                        echo '                </p>';
                        echo '            </div>';
                        echo '        </div>';
                        echo'</a>';
                        echo '    </div>';
                        echo '</div>';
                    }
                }
            }else {
                // If no results are found, display the "There None Sorry!!!" text in the center
                echo '<div class="alert alert-secondary text-center" role="alert" style="margin: 4px 4px; opacity: 0.6;">';
                echo '    <h3>There Are None Sorry!!!</h3>';
                echo '</div>';
            }
            
            ?>
        </div>
    </div>



    <!--On Sale-->
    <div class="container similar-products my-4">
        <hr>
        <p class="display-5 text-danger">On Sale Products</p>
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
            LIMIT 10";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $currentProductID);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display similar product information using the fetched data
                    if ($row['categoryID'] !== null) {
                        echo '<div class="col-md-3 mb-3">';
                        echo '    <div class="card">';
                        echo '      <a class="text-decoration-none" style="color: inherit; " href="click.php?productID='.encrypt($row["productID"], $key).'">';
                        echo '        <div class="d-flex justify-content-between p-3">';
                        echo '              <p class="lead mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                            . substr($row['product_name'], 0, 15) . (strlen($row['product_name']) > 15 ? '...' : '')
                                            . '</p>';
                        echo '            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-1-strong" style="width: 35px; height: 35px; border: 2px black solid;">';
                        echo '                <i class="fa-solid fa-tags" style="color: red;"></i>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '<img src="product/' . $row['product_image'] . '" class="card-img-top shadow-sm" alt="Preview" style="object-fit: cover; height: 200px; width: 100%;">';
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
                        echo '                  <a href="https://wa.me/601136685033/?text=I%20want%20to%20order%20this%20product:%0A' . urlencode($row['product_name']) . '%20(RM' . ($row['new_product_price'] > 0 ? $row['new_product_price'] : $row['actual_product_price']) . ')" target="_blank" class="btn btn-success">Order via WhatsApp</a>';
                        echo '                </p>';
                        echo '            </div>';
                        echo '        </div>';
                        echo'</a>';
                        echo '    </div>';
                        echo '</div>';
                    }
                }
            }else {
                // If no results are found, display the "There None Sorry!!!" text in the center
                echo '<div class="alert alert-secondary text-center" role="alert" style="margin: 4px 4px; opacity: 0.6;">';
                echo '    <h3>There Are None Sorry!!!</h3>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <!--End Head of container-->
    <!--POLICY-->

    <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasRightLabel">Our Policy</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body " style="text-align: justify;">
                <p>We adhere to a policy of conducting all transactions exclusively through WhatsApp. This page is dedicated to 
                showcasing our extensive range of golf products that are currently available. It is important to clarify that while we proudly offer these 
                products, we do not engage in their manufacturing nor do we assert ownership over their designs. Our role is solely that of a retailer, committed 
                to providing customers with access to high-quality golf merchandise. Furthermore, we maintain the flexibility to negotiate prices on select items, 
                ensuring a tailored and personalized shopping experience for our valued customer.</p>
                
                <!--Accordian-->
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Are our Item Legitimate?
                        </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Ensuring the legitimacy of our items</strong> 
                                is at the core of our commitment to you. We understand the importance of trust in every transaction, 
                                and that's why we guarantee the authenticity of all our products. With rigorous quality checks and a dedication to sourcing from reputable suppliers, 
                                we aim to provide you with genuine, high-quality golf merchandise. Your confidence in us drives our mission to deliver nothing but the best
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Selling Your Golf Items Hassle-Free
                        </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong> Yes, you can!</strong> We offer a hassle-free solution for individuals looking to 
                                sell their golf items while maximizing their benefits. Simply reach out to us for further discussion on how we can assist you in selling your 
                                products with the best possible outcome. Feel free to contact us via the phone number provided on our website. We look forward to hearing from you!"
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Is shipping available, and what are the associated costs?
                        </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>Our shipping policy is tailored to the individual product,</strong>with costs varying based on factors such as size, weight, and destination. We strive to
                                provide transparent and competitive shipping rates to our customers. To get an accurate estimate of the shipping cost for your desired product, please 
                                proceed to checkout where the shipping options and associated costs will be displayed. If you have any specific shipping inquiries or require assistance, 
                                please don't hesitate to contact our customer service team for personalized support.
                            </div>
                        </div>
                    </div>
                <!--End Accordian-->
            </div>
        </div>
    </div> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <?php
    include("footer.php");
    ?>
    </section>
    
</body>
    
</html>
<?php
    
} else {
    header("Location: fail/error404.php");
    exit(); // Stop script execution
}
} else {
    header("Location: fail/error404.php");
    exit(); 
}
?>