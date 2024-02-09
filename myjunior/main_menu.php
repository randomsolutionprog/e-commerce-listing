<div class="superNav border-bottom py-2 bg-light">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 centerOnMobile">
            
              <span class="d-none d-lg-inline-block d-md-inline-block d-sm-inline-block d-xs-none me-3"><strong>myjrgolf@gmail.com</strong></span>
              <span class="me-3"><i class="fa-solid fa-phone me-1 text-danger"></i> <strong>+60 11-3668 5033</strong></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-none d-lg-block d-md-block-d-sm-block d-xs-none text-end">
              
              <span class="me-3"><i class="fa-solid fa-file  text-muted me-2"></i><a class="text-muted" href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Policy</a></span>
            </div>
          </div>
        </div>
      </div>
      <nav class="navbar navbar-expand-lg bg-white sticky-top navbar-light p-3 shadow-sm">
        <div class="container">
          <a class="navbar-brand" href="index.php"><i class="fa-solid fa-shop me-2"></i> <strong>MyGolfBooth</strong></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
      
          <div class="mx-auto my-3 d-lg-none d-sm-block d-xs-block">
              <form action="search_product.php" method="post" class="input-group">
                  <span class="border-danger input-group-text bg-danger text-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                  <input type="text" name="search_query" class="form-control border-danger" style="color:#7a7a7a">
                  <button type="submit" class="btn btn-danger text-white" name="search">Search</button>
              </form>
          </div>

          <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <div class="ms-auto d-none d-lg-block">
                  <form action="search_product.php" method="post" class="input-group">
                      <span class="border-danger input-group-text bg-danger text-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                      <input type="text" name="search_query" class="form-control border-danger" style="color:#7a7a7a">
                      <button type="submit" class="btn btn-danger text-white" name="search">Search</button>
                  </form>
              </div>
       
            <ul class="navbar-nav ms-auto ">
              <?php
                $productTypes = array(
                    1 => "Apparel",
                    2 => "Golf Clubs",
                    3 => "Used Clubs",
                    4 => "Golf Shoes",
                    5 => "Accessories"
                );

                // Fetch all categories
                $query = "SELECT * FROM category ORDER BY category_order";
                $result = $conn->query($query);

                // Organize categories by product type
                $categoriesByType = array();

                while ($row = $result->fetch_assoc()) {
                    $productType = $row['product_type'];
                    $categoriesByType[$productType][] = $row;
                }

                if(!isset($_GET["product_type"])){
                  $product_type = 0;
                }
                else{
                  $product_type=$_GET["product_type"];
                }

                echo '<li class="nav-item">';
                echo '<a class="nav-link mx-2 text-uppercase'; 
                if($product_type == 0){
                  echo ' active';
                }
                echo '" aria-current="page"';
                echo 'href="index.php">Offers</a>';
                echo ' </li>';

                // Display navigation
                foreach ($categoriesByType as $type => $categories) {
          
                  echo '<li class="nav-item dropdown">';
                  echo ' <a class="nav-link mx-2 text-uppercase dropdown-toggle';
                  if($product_type == $type){
                    echo ' active';
                  }
                  echo'" href="index.php?product_type=' . $type . '" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  echo $productTypes[$type];
                  echo '</a>';
                  echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';

                  foreach ($categories as $category) {
                      echo '<a class="dropdown-item" href="index.php?product_type='.$type.'&categoryID=' . $category["categoryID"] . '">' . $category["category_name"] . '</a>';
                  }

                  echo '<div class="dropdown-divider"></div>';
                  echo '<a class="dropdown-item" href="index.php?product_type=' . $type .'">All ' . $productTypes[$type] . '</a>';
                  echo '</div>';
                  echo ' </li>';
                
                   
                }
                ?>
            </ul>
           
          </div>
        </div>
      </nav>