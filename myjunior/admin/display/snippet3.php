<?php
include("security.php");
?>
<div class="container mb-3">
        <div class="col">
            <h1>Category</h1>
        </div>
        <div class="col">
            <?php
            include("status.php");

            $flag = 0;
            // Determine the current page (you might get it from the URL parameter)
            $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $sort = isset($_GET['table']) ? intval($_GET['table']) : 0;
            $product_type =  0;
            if (isset($_GET['product_type'])){
                $product_type = (int)$_GET['product_type'];
                $flag = 1;
            }
            else if(isset($_SESSION['product_type'])){
                $product_type = (int)$_SESSION["product_type"]; 
                $flag = 1;
            }
            else{
                $flag = 0;
            }
            
            
            ?>
        </div>
        
    </div>
    <div class="row">
        <div class="col">
            <label for="filter">Sort By:</label>
            <a href="dashboard.php?snippet=3&table=3" class="btn btn-secondary" style="margin-left: 5px;">A-Z</a>
            <a href="dashboard.php?snippet=3&table=4" class="btn btn-secondary" style="margin-left: 5px;">Z-A</a>
            <a href="dashboard.php?snippet=3" class="btn btn-secondary" style="margin-left: 5px;">None</a>
        </div>
        
    </div>
    <div class="row" style="margin-top: 10px; margin-bottom: 5px;">
        <div class="col">
            <form action="process/search.php" method="post">
                <div class="col-md-3 d-inline-block">
                    <label for="product_type">Primary Category:</label>
                    <select name="product_type" id="product_type" class="form-control">
                        <option value="1" <?php echo ($product_type == 1 ? "selected" : ''); ?> >APPAREL</option>
                        <option value="2" <?php echo ($product_type == 2 ? "selected" : ''); ?> >GOLF CLUBS</option>
                        <option value="3" <?php echo ($product_type == 3 ? "selected" : ''); ?> >USED CLUBS</option>
                        <option value="4" <?php echo ($product_type == 4 ? "selected" : ''); ?> >GOLF SHOES</option>
                        <option value="5" <?php echo ($product_type == 5 ? "selected" : ''); ?> >ACCESSORIES</option>
                    </select>

                </div>
                <input type="text" style="display: none" name="sort" value="<?php echo $sort; ?>">
                <input type="submit" name="admin_category" value="Select" class="btn btn-primary d-inline-block">
                <input type="submit" name="admin_category_reset" value="Display All" class="btn btn-primary d-inline-block">
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col">
        <?php
        include_once("../conn.php");

        // Define the number of rows to display per page
        $rows_per_page = 5;

        // Calculate the offset
        $offset = ($current_page - 1) * $rows_per_page;
        $query = " ";
        if (!empty($product_type)) {
            if ($sort == 3) {
                $query = "SELECT * FROM category WHERE product_type = ? ORDER BY category_name ASC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 4) {
                $query = "SELECT * FROM category WHERE product_type = ? ORDER BY category_name DESC LIMIT $rows_per_page OFFSET $offset";
            } else {
                $query = "SELECT * FROM category WHERE product_type = ? ORDER BY category_order LIMIT $rows_per_page OFFSET $offset";
            }
            // Use prepared statement to execute the query
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $product_type);
            $stmt->execute();
            $result = $stmt->get_result();

        } else {
            
            if ($sort == 3) {
                $query = "SELECT * FROM category ORDER BY category_name ASC LIMIT $rows_per_page OFFSET $offset";
            } elseif ($sort == 4) {
                $query = "SELECT * FROM category ORDER BY category_name DESC LIMIT $rows_per_page OFFSET $offset";
            } else {
                $query = "SELECT * FROM category ORDER BY category_order LIMIT $rows_per_page OFFSET $offset";
            }
            // Fetch rows for the current page
            $result = $conn->query($query);
        }
        
       // Check if there are any products
        if ($result->num_rows > 0) {
            echo '<table class="table">';
            echo '<thead class="thead-dark">';
            echo '<tr>';
            echo '<th scope="col" class="d-none d-md-table-cell">#</th>'; // Hide on small screens
            echo '<th scope="col">Name</th>';
            echo '<th scope="col">Product Type</th>';
            echo '<th scope="col">Save</th>';
            echo '<th scope="col">Delete</th>';
            if($flag==1){
                echo '<th scope="col">Drag</th>';
            }
            '</tr>';
            '</thead>';
            echo '<tbody>';

            $counter = 1;

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<form id="row'.$counter.'" action="process/save_category.php" method="post">';
                echo '<td>' . $counter++ . '</td>';
                echo '<td>' . $row['category_name'] . '</td>';
                echo '<td>';
                echo '<select class="form-select" aria-label=".form-select-lg example" name="product_type" id="product_type">';
                echo '<option value="1" ' . ($row['product_type'] == 1 ? "selected" : '') . '>Apparel</option>';
                echo '<option value="2" ' . ($row['product_type'] == 2 ? "selected" : '') . '>Golf Clubs</option>';
                echo '<option value="3" ' . ($row['product_type'] == 3 ? "selected" : '') . '>Used Clubs</option>';
                echo '<option value="4" ' . ($row['product_type'] == 4 ? "selected" : '') . '>Golf Shoes</option>';
                echo '<option value="5" ' . ($row['product_type'] == 5 ? "selected" : '') . '>Accessories</option>';
                echo '</select>';
                echo '</td>';
                // Hidden input for categoryID
                echo '<input type="hidden" name="categoryID" value="' . $row['categoryID'] . '">';

                // Edit button
                echo '<td><input class="btn btn-success" type="submit" value="Save"></td>';

                // Delete button
                echo '<td><a href="process/delete_category.php?categoryID=' . $row['categoryID'] . '" onclick="return confirm(\'Do you want to delete?\');" class="btn btn-danger">Delete</a></td>';
                if($flag==1){
                    echo '<td><span class="drag-handle"><i class="fa-solid fa-hand"></i></span></td>';
                    echo '<input type="hidden" class="category-id" value="' . $row['categoryID'] . '">';
                    echo '<input type="hidden" class="category-order" value="' . $row['category_order'] . '">';
                }
               
                echo '</form>';
                echo '</tr>';

            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<div class="alert alert-info" role="alert" style="margin: 4px 4px;">';
            echo 'No category found.';
            echo '</div>';
        }
        ?>

        </div>
    </div>

    <?php
    // Fetch total rows for pagination
    if (!empty($product_type)) {
        $total_rows_query = "SELECT COUNT(*) AS total_rows FROM category WHERE product_type = ?";
        $stmt = $conn->prepare($total_rows_query);
        // Bind the parameters
        $stmt->bind_param("i", $product_type);
        $stmt->execute();
        $total_rows_result = $stmt->get_result();
        $total_rows = $total_rows_result->fetch_assoc()['total_rows'];
    } else {
        $total_rows_query = "SELECT COUNT(*) AS total_rows FROM category";
        $total_rows_result = $conn->query($total_rows_query);
        $total_rows = $total_rows_result->fetch_assoc()['total_rows'];
    }
    
    
    // Calculate the total number of pages
    $total_pages = ceil($total_rows / $rows_per_page);
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
                        <a class="page-link" href="?snippet=3&table=<?php echo $sort;?>&page=<?php echo $current_page - 1; ?>" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?snippet=3&table=<?php echo $sort;?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?snippet=3&table=<?php echo $sort;?>&page=<?php echo $current_page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-4">
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addcategory">Add <span class="product_img_bigger">New</span> Category</button>
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
                    newOrder.push($(this).find('.category-id').val());
                    minOrder.push($(this).find('.category-order').val());
                });
                // Send newOrder to the server using AJAX
                $.ajax({
                    url: "process/updateCategory_order.php",
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
        var myModal = document.getElementById('addcategory')
        var myInput = document.getElementById('productname')

        myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus()
    })
    </script>
</div>


