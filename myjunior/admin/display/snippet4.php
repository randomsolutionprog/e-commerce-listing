<?php
include("security.php");
?>
   <div class="container mb-3">
        <div class="col">
            <h1>Products</h1>
        </div>
        <div class="col">
            <?php
            include("status.php");
            // Determine the current page (you might get it from the URL parameter)
            $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $sort = isset($_GET['table']) ? intval($_GET['table']) : 0;
            $searchTerm =  "";
            if (isset($_GET['search'])){
                $searchTerm = htmlspecialchars($_GET['search']);
                $_SESSION["search_product"] = $searchTerm;
            }
            else if(isset($_SESSION['search_product'])){
                $searchTerm = $_SESSION["search_product"]; 
            }
           
            
            ?>
        </div>
        
    </div>
    <div class="row">
        <div class="col">
            <label for="filter">Sort By:</label>
            <a href="dashboard.php?snippet=4&table=1" class="btn btn-secondary" style="margin-left: 5px;">Older <span class="product_img_bigger">Date</span></a>
            <a href="dashboard.php?snippet=4&table=2" class="btn btn-secondary" style="margin-left: 5px;">Newest <span class="product_img_bigger">Date</span></a>
            <a href="dashboard.php?snippet=4&table=3" class="btn btn-secondary" style="margin-left: 5px;">A-Z</a>
            <a href="dashboard.php?snippet=4&table=4" class="btn btn-secondary" style="margin-left: 5px;">Z-A</a>
            <a href="dashboard.php?snippet=4" class="btn btn-secondary" style="margin-left: 5px;">None</a>
        </div>
        
    </div>
    <div class="row" style="margin-top:10px; margin-bottom: 5px;">
        <div class="col">
            <form action="process/search.php" method="post">
                
                <div class="col-md-6" style="display: flex;">
                    <input type="search" class="form-control" placeholder="Search" name="search_text">    
                    <input type="text" style="display: none" name="snippet" value="<?php echo 4; ?>">
                    <input type="text" style="display: none" name="sort" value="<?php echo $sort; ?>">
                    <input type="submit" name="admin_product" value="Search">
                    <input type="submit" name="admin_product_reset" value="Reset">
                </div>
            
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
        <?php
        include_once("../conn.php");

        // Define the number of rows to display per page
        $rows_per_page = 6;

        // Calculate the offset
        $offset = ($current_page - 1) * $rows_per_page;

        if(  $offset > 2){
            $offset  =   $offset - 2;
        }
        $query = " ";

        if (!empty($searchTerm)) {
            $searchParam = "%{$searchTerm}%";
            if ($sort == 1) {
                $query = "SELECT * FROM product WHERE (product_name LIKE ? OR description LIKE ?) ORDER BY date_upload ASC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 2) {
                $query = "SELECT * FROM product WHERE (product_name LIKE ? OR description LIKE ?) ORDER BY date_upload DESC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 3) {
                $query = "SELECT * FROM product WHERE (product_name LIKE ? OR description LIKE ?) ORDER BY product_name ASC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 4) {
                $query = "SELECT * FROM product WHERE (product_name LIKE ? OR description LIKE ?) ORDER BY product_name DESC LIMIT $rows_per_page OFFSET $offset";
            } else {
                $query = "SELECT * FROM product WHERE (product_name LIKE ? OR description LIKE ?) ORDER BY product_order ASC LIMIT $rows_per_page OFFSET $offset";
            }
            // Use prepared statement to execute the query
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $searchParam, $searchParam);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            if ($sort == 1) {
                $query = "SELECT * FROM product ORDER BY date_upload ASC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 2) {
                $query = "SELECT * FROM product ORDER BY date_upload DESC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 3) {
                $query = "SELECT * FROM product ORDER BY product_name ASC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 4) {
                $query = "SELECT * FROM product ORDER BY product_name DESC LIMIT $rows_per_page OFFSET $offset";
            } else {
                $query = "SELECT * FROM product ORDER BY product_order ASC LIMIT $rows_per_page OFFSET $offset";
            }
            // Fetch rows for the current page
            $result = $conn->query($query);
        }
        $total_rows = $result->num_rows;


            // Check if there are any products
            if ($result->num_rows > 0) {
                echo '<table class="table">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th scope="col" class="d-none d-md-table-cell">#</th>'; // Hide on small screens
                echo '<th scope="col" ><span class="product_img_bigger">Product Image</span> <span class="product_img_smaller">Image</span></th>';
                echo '<th scope="col">Name</th>';
                echo '<th scope="col">Price</th>';
                echo '<th scope="col">Edit</th>';
                echo '<th scope="col">Delete</th>';
                echo '<th scope="col">Drag</th>';
                '</tr>';
                '</thead>';
                echo '<tbody>';

                $counter = 1;

                while ($row = $result->fetch_assoc()) {
                    $queryCategories = "SELECT categoryID FROM product_category WHERE productID=".$row['productID'];
                    $resultCategories = $conn->query($queryCategories);
                    if($resultCategories->num_rows < 1){
                        $flag = 1;
                    }
                    else{
                        $flag=0;
                    }

                    if($flag==1){
                        echo '<tr class="table-danger">';
                    }
                    else{
                        echo '<tr>';
                    }
                    
                    echo '<td class="d-none d-md-table-cell">' . $counter++ . '</td>'; // Hide on small screens
                    echo '<td><img src="../product/' . $row['product_image'] . '" alt="' . $row['product_name'] . '" class="img-thumbnail" id="img" width="100wv;"></td>';
                    echo '<td><a href="product_details.php?snippet=4&productID=' . $row['productID'] . '">' . $row['product_name'] . '</a></td>';

                    if($row['new_product_price']>0){
                        echo '<td>RM' . $row['new_product_price'] . '</td>';
                    }
                    else{
                        echo '<td>RM' . $row['actual_product_price'] . '</td>';
                    }
                    // Edit button
                    
                    
                    if($flag==1){
                        echo '<td><a href="edit_product.php?snippet=4&productID=' . $row['productID'] . '" class="btn btn-warning">Edit</a>';
                        echo '<br><span class="text-danger">ATTENTION</span>';
                        echo '</td>';
                    }
                    else{
                        echo '<td><a href="edit_product.php?snippet=4&productID=' . $row['productID'] . '" class="btn btn-warning">Edit</a></td>';
                    }
                    

                    
                    // Delete button
                    echo '<td><a href="process/delete_product.php?productID=' . $row['productID'] . '" onclick="return confirm(\'Do you want to delete?\');" class="btn btn-danger">Delete</a></td>';
                    if($flag==1){
                        echo '<td><span><i class="fa-solid fa-thumbs-down"></i></span></td>';
                    }
                    else{
                        echo '<td><span class="drag-handle"><i class="fa-solid fa-hand"></i></span></td>';
                        echo '<input type="hidden" class="product-id" value="' . $row['productID'] . '">';
                        echo '<input type="hidden" class="product-order" value="' . $row['product_order'] . '">';
                        
                    }
                   
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<div class="alert alert-info" role="alert" style="margin: 4px 4px;">';
                echo 'No products found.';
                echo '</div>';
            }
        ?>

        </div>
    </div>

    <?php
    $total_rows=0;
     // Fetch total rows for pagination
     if (!empty($searchTerm)) {
        $searchParam = "%{$searchTerm}%";
        $total_rows_query = "SELECT COUNT(*) AS total_rows FROM product WHERE product_name LIKE ? OR description LIKE ?";
        $stmt = $conn->prepare($total_rows_query);
        // Bind the parameters
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $stmt->execute();
        $total_rows_result = $stmt->get_result();
        $total_rows = $total_rows_result->fetch_assoc()['total_rows'];
    } else {
        $total_rows_query = "SELECT COUNT(*) AS total_rows FROM product";
        $total_rows_result = $conn->query($total_rows_query);
        $total_rows = $total_rows_result->fetch_assoc()['total_rows'];
    }
    
   
   
    
        
    // Calculate the total number of pages
    $total_pages = ceil(($total_rows+2) / $rows_per_page) ;
    // Calculate the range of pages to display
    $page_range = 5; // Adjust the number of pages to display
    $start_page = max(1, $current_page - floor($page_range / 2));
    $end_page = min($total_pages, $start_page + $page_range - 1);
    ?>

    <!-- HTML Pagination -->
    <div class="row">
        <div class="col-8 text-center">
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?snippet=4&table=<?php echo $sort;?>&page=<?php echo $current_page - 1; ?>" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?snippet=4&table=<?php echo $sort;?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?snippet=4&table=<?php echo $sort;?>&page=<?php echo $current_page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-4">
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addproduct">Add <span class="product_img_bigger">New</span> Product</button>
        </div>
    </div>
    <script>
    $(function() {
        $(".table tbody").sortable({
            handle: ".drag-handle",
            update: function(event, ui) {
                var newOrder = [];
                var minOrder = [];
                $(".table tbody tr").each(function() {
                    newOrder.push($(this).find('.product-id').val());
                    minOrder.push($(this).find('.product-order').val());
                });
                // Send newOrder to the server using AJAX
                $.ajax({
                    url: "process/updateProduct_order.php",
                    method: "POST",
                    data: { newOrder: newOrder, minOrder : minOrder},
                    success: function(response) {
                        // Handle the server response
                        console.log(response); // Log the response to the console
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        console.error(xhr.responseText);
                        alert("Error occurred while updating order.");
                    }
                });
            }
        });
    });

    </script>
    <script>
        var myModal = document.getElementById('addproduct')
        var myInput = document.getElementById('productname')

        myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus()
    })
    </script>
