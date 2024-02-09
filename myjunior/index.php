<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- HTML Meta Tags -->
    <title>My Golf Booth Product Listing</title>
    <meta name="description" content="Golf gear galore: New & used equipment for sale!">
    <meta name="author" content="My Junior Golf">
    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="My Golf Booth Product Listing">
    <meta itemprop="description" content="Golf gear galore: New & used equipment for sale!">
    <meta itemprop="image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="http://mygolfbooth.wuaze.com/index.php">
    <meta property="og:type" content="website">
    <meta property="og:title" content="My Golf Booth Product Listing">
    <meta property="og:description" content="Golf gear galore: New & used equipment for sale!">
    <meta property="og:image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="My Golf Booth Product Listing">
    <meta name="twitter:description" content="Golf gear galore: New & used equipment for sale!">
    <meta name="twitter:image" content="https://mygolfbooth.wuaze.com/img/logo.jpg">

   
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/56968ef8c3.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
    <style>
        a {
            font-size:14px;
            font-weight:700
        }
        .superNav {
            font-size:13px;
        }
        .form-control {
            outline:none !important;
            box-shadow: none !important;
        }
        @media screen and (max-width:540px){
            .centerOnMobile {
                text-align:center
            }
        }
    </style>
   
</head>
<body>

    <!-- Navbar -->
    <?php
    function shortenWord($word) {
        $length = strlen($word);
    
        // If word has less than or equal to 3 characters, no need to shorten
        if ($length <= 3) {
            return $word;
        }
        // If word has more than 5 characters, shorten by 1
        elseif ($length == 5 || $length==4) {
            return substr($word, 0, -1);
        }
        // If word has 6 characters, shorten by 2
        elseif ($length == 6) {
            return substr($word, 0, -2);
        }
        // If word has 7 characters, stay missing 3 only on the ending character
        elseif ($length >= 7) {
            return substr($word, 0, -3) . '...';
        }
        // For other cases, return the original word
        else {
            return $word;
        }
    }
    include("encrypt.php");
    include("conn.php");
    include("main_menu.php")
    ?>
    <!-- Modal -->
    <div class="modal fade" id="cookieModal" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="cookieModalLabel">Cookie Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>We use cookies to enhance your experience. By clicking "Accept", you consent to the use of all cookies.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="declineCookies" data-bs-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary" id="acceptCookies" data-bs-dismiss="modal">Accept</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Main Content -->
        <section style="background-color: #eee;" id="">
        <div class="container-fluid py-5">
            <div class="row">
            <aside class="col-md-2 mb-3">
                <div class="card">
                     <!-- filter-group .// -->
                    <article class="filter-group">
                        <header class="card-header">
                            <h6 class="title">Price Range
                            </h6>
                        </header>
                        <div class="filter-content collapse show mb-3" id="collapse_3" style="">
                            <form id="filterForm" action="filter.php" method="POST">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="minPriceInput">Min</label>
                                            <input id="minPriceInput" name="min_price" class="form-control" placeholder="RM0" type="number" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
                                        </div>
                                        <div class="form-group text-right col-md-12">
                                            <label for="maxPriceInput">Max</label>
                                            <input id="maxPriceInput" name="max_price" class="form-control" placeholder="RM1,0000" type="number" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
                                        </div>
                                    </div>
                                    <!-- form-row.// -->
                                    <input type="hidden" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                    <input type="hidden" name="categoryID" value="<?php echo isset($_GET['categoryID']) ? $_GET['categoryID'] : ''; ?>">
                                    <input type="hidden" name="product_type" value="<?php echo isset($_GET['product_type']) ? $_GET['product_type'] : ''; ?>">
                                    <button type="submit" name="filter" class="btn btn-block btn-primary mt-2">Apply</button>
                                </div>
                            </form>
                           
                        </div>
                    </article>
                </div>
                <!-- card -->
            </aside>
            <main class="col-md-10">
                

                <div class="row">
                <?php
                    // Pagination configuration
                    $itemsPerPage = 12; // Number of items per page
                    
                    if(isset($_GET["search"])){
                        $search="%".$_GET["search"]."%";
                        $tempSearch = "%".shortenWord($_GET["search"])."%";
                        $query = "SELECT * FROM product
                        JOIN product_info ON product.productID = product_info.productID
                        LEFT JOIN product_category ON product.productID = product_category.productID
                        LEFT JOIN category ON product_category.categoryID = category.categoryID 
                        WHERE ( product.product_name LIKE ? OR product.description LIKE ? OR product.product_name LIKE ? OR product.description LIKE ? )";

                        if(isset($_GET["min_price"])&&isset($_GET["max_price"])){
                            $min = $_GET["min_price"];
                            $max = $_GET["max_price"];
                            $query .= " AND ( ( product.actual_product_price >=  ? AND product.actual_product_price <= ? )
                                OR (product.new_product_price > 0 AND product.new_product_price >=  ? AND product.new_product_price <= ? ) )";
                            $query .= " ORDER BY product.product_order";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssssdddd",  $tempSearch,  $tempSearch, $search, $search, $min, $max, $min, $max);
                            
                        }else{
                            $query .= " ORDER BY product.product_order";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssss",  $tempSearch,  $tempSearch, $search, $search);
                        }
                    }
                    else{
                        include("query.php");
                        if(isset($_GET["min_price"])&&isset($_GET["max_price"])){
                            $min = $_GET["min_price"];
                            $max = $_GET["max_price"];
                            $query .= " AND ( ( product.actual_product_price >=  ? AND product.actual_product_price <= ? )
                                OR (product.new_product_price > 0 AND product.new_product_price >=  ? AND product.new_product_price <= ? ) )";
                            $query .= " ORDER BY product.product_order";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("dddd", $min, $max, $min, $max);
                            
                        }else{
                            $query .= " ORDER BY product.product_order";
                            $stmt = $conn->prepare($query);
                        }
                    }
                   
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $totalItems = $result->num_rows;
                    
                    $totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages
                    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get the current page number or default to 1

                    // Calculate the starting index for fetching items
                    $startIndex = ($currentPage - 1) * $itemsPerPage;



                    // add limit and offset
                    if(isset($_GET["search"])){
                        $search="%".$_GET["search"]."%";
                        $query = "SELECT * FROM product
                        JOIN product_info ON product.productID = product_info.productID
                        LEFT JOIN product_category ON product.productID = product_category.productID
                        LEFT JOIN category ON product_category.categoryID = category.categoryID 
                        WHERE ( product.product_name LIKE ? OR product.description LIKE ? OR product.product_name LIKE ? OR product.description LIKE ? )";

                        
                        if(isset($_GET["min_price"])&&isset($_GET["max_price"])){
                            $min = $_GET["min_price"];
                            $max = $_GET["max_price"];
                            $query .= " AND ( ( product.actual_product_price >=  ? AND product.actual_product_price <= ? )
                                OR (product.new_product_price > 0 AND product.new_product_price >=  ? AND product.new_product_price <= ? ) )";
                            $query .= " LIMIT $itemsPerPage OFFSET $startIndex";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssssdddd",  $tempSearch,  $tempSearch, $search, $search, $min, $max, $min, $max);
                            
                        }else{
                            $query .= " LIMIT $itemsPerPage OFFSET $startIndex";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("ssss",  $tempSearch,  $tempSearch, $search, $search);
                        } 
                    }
                    else{
                        include("query.php");
                        if(isset($_GET["min_price"])&&isset($_GET["max_price"])){
                            $min = $_GET["min_price"];
                            $max = $_GET["max_price"];
                            $query .= " AND ( ( product.actual_product_price >=  ? AND product.actual_product_price <= ? )
                                OR (product.new_product_price > 0 AND product.new_product_price >=  ? AND product.new_product_price <= ? ) )";
                            $query .= " ORDER BY product.product_order";
                            $query .= " LIMIT $itemsPerPage OFFSET $startIndex";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("dddd", $min, $max, $min, $max);
                            
                        }else{
                            $query .= " ORDER BY product.product_order";
                            $query .= " LIMIT $itemsPerPage OFFSET $startIndex";
                            $stmt = $conn->prepare($query);
                        }
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        echo '<header class="border-bottom mb-4 pb-3">';
                        echo '    <div class="form-inline">';
                        echo '        <span class="mr-md-auto" style="margin-left:5px; font-size: 30px;">';
                        echo '           '.$result->num_rows.' Items found';
                        echo '        </span>';
                        echo '    </div>';
                        echo '</header>';

                        while ($row = $result->fetch_assoc()) {
                            if ($row['categoryID'] !== null) {
                                // Display similar product information using the fetched data
                                echo '<div class="col-md-3 mb-3">';
                                echo '    <div class="card">';
                                echo '      <a class="text-decoration-none" style="color: inherit; " href="click.php?productID='.encrypt($row["productID"], $key).'">';
                                echo '        <div class="d-flex justify-content-between p-3">';
                                echo '              <p class="lead mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="' . htmlspecialchars($row['product_name']) . '">'
                                                    . substr($row['product_name'], 0, 15) . (strlen($row['product_name']) > 15 ? '...' : '')
                                                    . '</p>';
                                echo '            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-1-strong" style="width: 35px; height: 35px; border: 2px black solid;">';
                                if (isset($_GET['categoryID']) || isset($_GET['product_type'])){
                                    echo '             <i class="fa-solid fa-box-open"></i>';
                                } 
                                else{
                                    echo '             <i class="fa-solid fa-tags" style="color: red;"></i>';
                                }
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
                                echo'       </a>';
                                echo '    </div>';
                                echo '</div>';
                            }
                            else{
                                continue;
                            }
                        }
                    }else {
                        // If no results are found, display the "There None Sorry!!!" text in the center
                        echo '<header class="border-bottom mb-4 pb-3">';
                        echo '    <div class="form-inline">';
                        echo '        <span class="mr-md-auto">';
                        echo '           '.$result->num_rows.' Items found';
                        echo '        </span>';
                        echo '    </div>';
                        echo '</header>';
                        echo '<div class="container">';
                        echo '  <div class="alert alert-secondary text-center d-flex justify-content-center align-items-center" role="alert" style="margin: 4px 4px; opacity: 0.6; height: 60vh;">';
                        echo '      <h3>There Are None Sorry!!!</h3>';
                        echo '  </div>';
                        echo '</div>';

                    }

                    ?>
                    <!-- col.// -->
                </div>
                <!-- row end.// -->

                <?php
                    // Function to build query string with existing parameters
                    function buildQueryString($paramsToExclude = []) {
                        $query = $_GET;
                        foreach ($paramsToExclude as $param) {
                            unset($query[$param]);
                        }
                        return http_build_query($query);
                    }

                    // Build the pagination links
                    echo '<nav class="mt-4" aria-label="Page navigation sample">';
                    echo '    <ul class="pagination">';
                    echo '        <li class="page-item ' . ($currentPage == 1 ? 'disabled' : '') . '">';
                    echo '            <a class="page-link" href="?page=' . ($currentPage - 1) . '&' . buildQueryString(['page']) . '">Previous</a>';
                    echo '        </li>';
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo '        <li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
                        echo '            <a class="page-link" href="?page=' . $i . '&' . buildQueryString(['page']) . '">' . $i . '</a>';
                        echo '        </li>';
                    }
                    echo '        <li class="page-item ' . ($currentPage == $totalPages ? 'disabled' : '') . '">';
                    echo '            <a class="page-link" href="?page=' . ($currentPage + 1) . '&' . buildQueryString(['page']) . '">Next</a>';
                    echo '        </li>';
                    echo '    </ul>';
                    echo '</nav>';
                    ?>

            </main>
            
        </div>
        </div>
        </section>
            
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
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            International Orders and Shipping Policies:
                        </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                            <div class="accordion-body" style="max-height: 200px; overflow-y: auto;">
                                <strong>While our operations are based in Malaysia,</strong>  
                                we extend a warm invitation to golf enthusiasts worldwide to explore our comprehensive range of products. We pride ourselves on facilitating international orders, 
                                ensuring that customers from across the globe can experience the quality and variety of our offerings. Whether you're located locally or in a distant corner of the world,
                                we are committed to providing exceptional service and delivering top-quality products to your doorstep. It's important to note that international orders may be subject to 
                                additional shipping charges and import duties. Feel free to reach out to us for personalized assistance and guidance throughout the ordering process."
                            </div>
                        </div>
                    </div>
                </div>
                <!--End Accordian-->
            </div>
        </div>


    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
    // Function to check if a cookie with a given name exists
    function getCookie(name) {
        var nameEQ = name + '=';
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1, cookie.length);
            }
            if (cookie.indexOf(nameEQ) === 0) {
                return cookie.substring(nameEQ.length, cookie.length);
            }
        }
        return null;
    }

    // Check if the 'random_name' cookie exists
    var randomNameCookie = getCookie('random_name');
    if (randomNameCookie !== null) {
        console.log('Cookie already exists:', randomNameCookie);
    } else {
        // Check if the 'rejected' session exists
        var rejectedSession = sessionStorage.getItem('rejected');
        if (rejectedSession !== null && rejectedSession === 'true') {
            console.log('User rejected cookies. No modal will be shown.');
        } else {
            // Show the modal when the page loads
            window.onload = function() {
                var cookieModal = new bootstrap.Modal(document.getElementById('cookieModal'));
                cookieModal.show();
            };
        }
    }

    function generateRandomString(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    // Add event listener to the "Accept" button
    document.getElementById('acceptCookies').addEventListener('click', function() {
         // Generate random string
        var randomString = generateRandomString(12); // Generates a random string of length 12

        // Set cookie or perform any other action here
        console.log('Cookies accepted');
        // Set the cookie with the random string as the value
        setCookie('random_name', randomString, 30); // Cookie valid for 30 days

        // Send the random name to cookies.php
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'cookies.php?random_name=' + encodeURIComponent(randomString), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log('Data sent to cookies.php');
            } else {
                console.error('Failed to send data to cookies.php');
            }
        };
        xhr.send();

        // Close the modal
        var cookieModal = new bootstrap.Modal(document.getElementById('cookieModal'));
        cookieModal.hide();
    });

    // Add event listener to the "Decline" button
    document.getElementById('declineCookies').addEventListener('click', function() {
         // Set session indicating user declined cookies
        sessionStorage.setItem('rejected', 'true');
        console.log('Cookies declined. No modal will be shown.');
    });

    // Function to set cookie
    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/';
    }
</script>


<script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
        
        
        // Get the values of min and max inputs
        var minPrice = parseFloat(document.getElementById('minPriceInput').value);
        var maxPrice = parseFloat(document.getElementById('maxPriceInput').value);
        
        // Check if max price is less than min price
        if(maxPrice < minPrice || maxPrice == minPrice) {
            alert('Maximum price cannot be less or equal to  minimum price');
            // Prevent the form from submitting
            event.preventDefault();
        } 
    });
</script>

</body>

<?php
include("footer.php")
?>
</html>
