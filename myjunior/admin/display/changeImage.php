<?php
include("security.php");
?>
<div class="modal fade" id="change_image" tabindex="-1" aria-labelledby="change_image" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-body">
            <div class="container-fluid">
                
                <div class="card">
                    <div class="card-title text-center mt-3">
                        <h3>Change Product Image</h3>
                    </div>
                    <div class="card-body">
                        <form action="process/changeimage.php" method="post"  enctype="multipart/form-data">
                            <p>Product Image:</p>
                            <div class="custom-file">

                                <input type="file" class="form-control" id="productimage" name="newImage" required>
                                <input type="text" style="display: none;" name="productID" value="<?php echo $productID;?>">
                                <div class="invalid-feedback">File Format Not Supported</div>
                            </div>
                            <button class="btn btn-dark mt-5 mx-auto d-block" type="submit" name="submit">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>