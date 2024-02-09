<?php
// Start session
session_start();
include("security.php");
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
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <style>
            #img:active {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(6);
                -ms-transform: translate(-50%, -50%) scale(6); /* IE 9 */
                -webkit-transform: translate(-50%, -50%) scale(6); /* Safari and Chrome */
                color: #424242;
            }
            .product_img_bigger {
                display: inline-block;
            }
            .product_img_smaller{
                display: none;
            }
            /* Responsive styles for screens 900px or less */
            @media screen and (max-width: 900px) {
                #img:active {
                    transform: translate(-50%, -50%) scale(3);
                    -ms-transform: translate(-50%, -50%) scale(3); /* IE 9 */
                    -webkit-transform: translate(-50%, -50%) scale(3); /* Safari and Chrome */
                }
                .product_img_bigger {
                    display: none; /* Hide text on smaller screens */
                }
                .product_img_smaller{
                    display: inline-block;
                }
            }
        </style>
    </head> 
    <body style="background-color: #eee;">

        
<?php
// Get the 'snippet' value from the URL

$addProduct = isset($_GET['add']) && $_GET['add'] === 'true';
?>

  

<?php
include("navigation.php")
?>
<!--Display--->


<div class="container " style="margin-top: 80px; background-color: white;" id="">
<?php
// Get the 'snippet' value from the URL
$snippetValue = isset($_GET['snippet']) ? htmlspecialchars($_GET['snippet']) : '';

// Now $snippetValue contains the sanitized value

// Use $snippetValue in your logic, for example:
    echo'<div class="row p-2" >';
   
if ($snippetValue === '1') {
    // Load content for snippet 1
    include('display/snippet1.php');
} elseif ($snippetValue === '2') {
    // Load content for snippet 2
    include('display/snippet2.php');
} elseif ($snippetValue === '3') {
    // Load content for snippet 2
    include('display/snippet3.php');
    include("display/addCategory.php");
} elseif ($snippetValue === '4') {
    // Load content for snippet 2
    include('display/snippet4.php');
    include("display/addProduct.php");
}
else {
    include('display/snippet1.php');
}
echo ' </div>';
?>
</div>






    <!-- Bootstrap Bundle with Popper -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    
    </body>
</html>