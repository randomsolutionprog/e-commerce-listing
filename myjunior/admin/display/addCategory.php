<?php
include("security.php");
?>
<div class="modal fade" id="addcategory" tabindex="-1" aria-labelledby="addcategory" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                
                <div class="card">
                    <div class="card-title text-center mt-3">
                        <h3>Fill The Category Credential</h3>
                    </div>
                    <div class="card-body">
                        <form action="process/addcategory.php" method="post">
                            <div class="form-group mb-2">
                                <label for="categoryname">Category Name:</label>
                                <input type="text" class="form-control" id="categoryname" name="categoryname"
                                    placeholder="Enter Product Name" required>
                                <div class="invalid-feedback">Product Name Can't Be Empty</div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="product_type">Product Type:</label>
                                <select class="form-select" aria-label=".form-select-lg example" name="product_type" id="product_type" required>
                                    <option value="1">Apparel</option>
                                    <option value="2">Golf Clubs</option>
                                    <option value="3">Used Clubs</option>
                                    <option value="4">Golf Shoes</option>
                                    <option value="5">Accessories</option>
                                </select>
                            </div>
                            
                            <button class="btn btn-dark mt-5 mx-auto d-block" type="submit" name="submit">Add
                                Category</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
     