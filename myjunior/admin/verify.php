<?php
session_start();
include "../conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform verification logic here
    echo $username;
    echo $password;
    // Redirect or display appropriate messages

      // Prepare the SQL statement
      $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();

      // Get the result set
      $result = $stmt->get_result();
      $databasePassword = "";
      $databaseUsername = "";
      $databaseSalt = "";
      if ($result->num_rows > 0) {
          // Loop through the rows and process the data
         
          while ($row = $result->fetch_assoc()) {
              // Access the data using column names
              $databasePassword = $row['password'];
              $databaseSalt = $row['salt'];
              $_SESSION["userID"]=$row['userID'];
          }
          // Combine the salt and password
          $saltedPassword = $databaseSalt . $password;
          if(password_verify($saltedPassword, $databasePassword)){
            //echo "success";
              header('Location: dashboard.php');
              exit();
          }
          else{
            //echo "failed";
              $_SESSION["error"]="Log in failed. Invalid Password Please try again.";
              unset($_SESSION["userID"]);
              header('Location: index.php');
              exit();
          }

      }
      else{
        //echo "none";
          //invalid or wrong password
          $_SESSION["error"]="Log in failed. Account Not Registered... Please Register.";
          header('Location: index.php');
          exit();
      }
      
}
?>