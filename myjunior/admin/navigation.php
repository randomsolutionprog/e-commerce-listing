<?php
include("security.php");
?>
<?php
$snippetValue = isset($_GET['snippet']) ? htmlspecialchars($_GET['snippet']) : '';
?>
<div class="bg-danger" style="position: fixed; left: 0; top: 10px; z-index: 1000;">
    <a class="btn btn-dark" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
        <i class="fa-solid fa-bars"></i> Open Menu
    </a>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header bg-danger">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel"><strong>Main Menu</strong></h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
        <ul class="nav nav-pills flex-column mb-auto" id="sidebar">
            <li class="nav-item">
                <a href="dashboard.php?snippet=1" class="<?php if($snippetValue==='1'){echo"nav-link active";}else{echo"nav-link text-white";}?>" data-target="home">
                    <i class="fa-solid fa-house" style="padding-right: 3px;"></i>
                    Home
                </a>
            </li>
            <li>
                <a href="dashboard.php?snippet=2" class="<?php if($snippetValue==='2'){echo"nav-link active";}else{echo"nav-link text-white";}?>" data-target="dashboard">
                    <i class="fa-solid fa-gauge" style="padding-right: 3px;"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="dashboard.php?snippet=3" class="<?php if($snippetValue==='3'){echo"nav-link active";}else{echo"nav-link text-white";}?>" data-target="orders">
                    <i class="fa-solid fa-list" style="padding-right: 3px;"></i>
                    Category
                </a>
            </li>
            <li>
                <a href="dashboard.php?snippet=4" class="<?php if($snippetValue==='4'){echo"nav-link active";}else{echo"nav-link text-white";}?>" data-target="products">
                    <i class="fa-solid fa-dolly" style="padding-right: 1px;"></i>
                    Products
                </a>
            </li>
        </ul>
        <hr>




        <!--Drop Down--->
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="../img/logo.jpg" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>My Junior Golf</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
  </div>
</div>
