
<?php
include("security.php");
?>
    <div class="modal fade" id="addproduct" tabindex="-1" aria-labelledby="addproduct" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                
                <div class="card">
                    <div class="card-title text-center mt-3">
                        <h3>Fill The Product Credential</h3>
                    </div>
                    <div class="card-body">
                        <form action="process/addproduct.php" method="post"  enctype="multipart/form-data">
                            <div class="form-group mb-2">
                                <label for="productname">Product Name:</label>
                                <input type="text" class="form-control" id="productname" name="productname"
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
                            <div class="form-group mb-2">
                                <label for="description">Product Description:</label>
                                <textarea class="form-control" id="description" placeholder="Enter Product Description" name="description" required></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label for="productprice">Actual Product Price:</label>
                                <input type="number" step="any" class="form-control" id="productprice_actual"
                                    placeholder="Enter Actual Product Price" name="productprice_actual" required>
                                <div class="invalid-feedback">Product Price Can't Be Empty</div>

                            </div>
                            <div class="form-group mb-2">
                                <label for="productprice">New Product Price:</label>
                                <input type="number" step="any"class="form-control" id="productprice_new"
                                    placeholder="Optional" name="productprice_new">
                            </div>
                            <p>Product Image:</p>
                            <div class="custom-file">

                                <input type="file" class="form-control" id="productimage" name="file" required>
                                
                                <div class="invalid-feedback">File Format Not Supported</div>
                            </div>
                            <button class="btn btn-dark mt-5 mx-auto d-block" type="submit" name="submit">Add
                                Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
     