<?php
include("conn.php");
// Check if the form has been submitted
if(isset($_POST['filter'])) {
    // Retrieve the values of the form fields
 
    $minPrice = isset($_POST['min_price']) ? intval($_POST['min_price']) : ''; // Cast to integer
    $maxPrice = isset($_POST['max_price']) ? intval($_POST['max_price']) : ''; // Cast to integer

    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $categoryID = isset($_POST['categoryID']) ? $_POST['categoryID'] : '';
    $productType = isset($_POST['product_type']) ? $_POST['product_type'] : '';


    // Construct the URL with parameters
    $parameters = [];
    if(!empty($minPrice)) {
        $parameters['min_price'] = $minPrice;
    }
    if(!empty($maxPrice)) {
        $parameters['max_price'] = $maxPrice;
    }
    if(!empty($search)) {
        $parameters['search'] = $search;
    }
    if(!empty($categoryID)) {
        $parameters['categoryID'] = $categoryID;
    }
    if(!empty($productType)) {
        $parameters['product_type'] = $productType;
    }

    // Construct the URL with parameters
    $redirectURL = 'index.php';
    if(!empty($parameters)) {
        $redirectURL .= '?' . http_build_query($parameters);
    }

    if(isset($_COOKIE["random_name"])){
        $randomName = $_COOKIE["random_name"];

        // Step 1: Select visitorID by visitor_name
        $stmt = $conn->prepare("SELECT visitorID FROM trace_visitor WHERE visitor_name = ?");
        $stmt->bind_param("s", $randomName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            // Redirect back to index.php with the parameters
            header("Location: $redirectURL");
            exit; // Stop executing the current script after redirection
        }
        else{
            $visitorID = $row["visitorID"];
            // Calculate the range based on the number of digits in the mean price
            $meanPrice = round(($minPrice + $maxPrice) / 2);
            $numDigits = strlen((string)$meanPrice);
            $range = 20 * pow(10, $numDigits - 3);
            $before = $meanPrice - $range;
            $after = $meanPrice + $range;
           
            // Print the values
            echo "Mean Price: $meanPrice <br>";
            echo "Range: $range <br>";
            echo "Before: $before <br>";
            echo "After: $after <br>";
            // Prepare and execute the SQL query
            $stmt = $conn->prepare("SELECT * FROM trace_price WHERE mean_price >= ?  AND mean_price <= ?");
            $stmt->bind_param("ii", $before, $after);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows>0){
                $smallestSpread = PHP_INT_MAX; // Initialize with a very large number
                $smallestNumberMean = 0;
                $smallestPriceID = null;

                // Loop through each row
                while ($row = $result->fetch_assoc()) {
                    // Calculate the spread for the current row
                    $spread = abs($row["mean_price"] - $meanPrice);

                    // Check if the current spread is smaller than the smallest spread encountered so far
                    if ($spread < $smallestSpread) {
                        // Update the smallest spread, the corresponding mean price, and the priceID
                        $smallestSpread = $spread;
                        $smallestNumberMean = $row["mean_price"];
                        $smallestPriceID = $row["priceID"];
                        $smallestPriceCount = $row["price_count"];
                    }
                }

                // Output the smallest spread, the corresponding mean price, and the priceID
                echo "Smallest Spread: $smallestSpread <br>";
                echo "Smallest Number Mean: $smallestNumberMean <br>";
                echo "Smallest PriceID: $smallestPriceID <br>";
                $price_count=$smallestPriceCount+1;
                $stmt = $conn->prepare("UPDATE trace_price SET price_count = ? WHERE priceID = ?");
                $stmt->bind_param("ii", $price_count, $smallestPriceID);
                $stmt->execute();

            }
            else{
                $stmt = $conn->prepare("INSERT INTO trace_price (visitorID, mean_price, price_count) VALUES (?, ?, 1)");
                $stmt->bind_param("ii", $visitorID, $meanPrice);
                $stmt->execute();
            }
           
            
        }
    }
    // Redirect back to index.php with the parameters
    header("Location: $redirectURL");
    exit; // Stop executing the current script after redirection
}
else{
    // Redirect back to index.php with the parameters
    header("Location: index.php");
    exit; // Stop executing the current script after redirection
}
?>
