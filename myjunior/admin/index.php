<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Log In Admin</title>
        <!-- Bootstrap CSS -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
            crossorigin="anonymous">
        
        <style>
            .btn-color{
            background-color: #0e1c36;
            color: #fff;
            
            }

            .profile-image-pic{
            height: 200px;
            width: 200px;
            object-fit: cover;
            }



            .cardbody-color{
            background-color: #ebf2fa;
            }

            a{
            text-decoration: none;
            }
        </style>
    </head>
    <body>
        
        <div class="container">
            <div class="row">
                
                <div class="col-md-6 offset-md-3">
                    <h2 class="text-center text-dark mt-5">Login Form</h2>
                    <div class="text-center mb-5 text-dark">Please Fill The Credential</div>
                    <?php 
                    include("status.php");
                    ?>
                    
                    <div class="card my-5">

                        <form class="card-body cardbody-color p-lg-5" action="verify.php" method="POST">

                            <div class="text-center">
                                <img
                                    src="../img/logo.jpg"
                                    class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                                    width="200px"
                                    alt="profile">
                            </div>

                            <div class="mb-3">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="Username"
                                    aria-describedby="emailHelp"
                                    placeholder="User Name"
                                    name="username">
                            </div>
                            <div class="mb-3">
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    placeholder="password"
                                    name="password">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-color px-5 mb-5 w-100">Login</button>
                            </div>
                            <!--<div id="emailHelp" class="form-text text-center mb-5 text-dark">Not Registered?
                                <a href="#" class="text-dark fw-bold">
                                    Create an Account</a>
                            </div>-->
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>