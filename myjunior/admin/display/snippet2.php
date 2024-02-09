<?php
include("security.php");
?>
<div class="container mb-3">
    <div class="col">
        <h1>Dashboard Trends</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6 my-2 border shadow-sm">
        <div class="row">
            <h6>Most Searched Keyword</h6>
        </div>
        <div class="row-md">
            <canvas id="pieChart"></canvas> <!-- Pie chart canvas -->
        </div>
       
    </div>
    <div class="col-md-6 my-2 border shadow-sm">
        <div class="row">
            <h6>Most Click Product</h6>
        </div>
        <div class="row">
            <canvas id="histogramChart"></canvas> <!-- First Histogram chart canvas for products -->
        </div>
        
    </div>
    <div class="container my-2 ">
    <div class="row justify-content-center ">
        <div class="col-md-6 border shadow-sm">
            <div class="row">
                <h6>Prices Distribution</h6>
            </div>
            <div class="row">
                <canvas id="priceHistogramChart"></canvas> <!-- Second Histogram chart canvas for prices -->
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sample data for the pie chart (5 variables)
    var pieChartData = {
        <?php
        include("../conn.php");
        $stmt = $conn->prepare("SELECT * FROM trace_search ORDER BY search_count DESC LIMIT 5");
        $stmt->execute();
        $result = $stmt->get_result();
        $labels = []; // Array to store labels (word)
        $data = []; // Array to store data (search_count)
        while ($row = $result->fetch_assoc()) {
            $labels[] = $row["word"];
            $data[] = $row["search_count"];
        }
        ?>
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($data); ?>, // Sample data values
            backgroundColor: [
                'red',
                'blue',
                'green',
                'orange',
                'purple'
            ]
        }]
    };


    <?php
       // Prepare the SQL query to join the tables and select the product name and search count
        $stmt = $conn->prepare("SELECT tc.click_count, p.product_name, p.product_type 
        FROM trace_click tc 
        INNER JOIN product p ON tc.productID = p.productID");
        $stmt->execute();
        $result = $stmt->get_result();

        $labels = []; // Array to store product names
        $data = []; // Array to store search counts

        // Initialize count variables for each predefined product type
        $apparel_count = 0;
        $golfClubs_count = 0;
        $usedClubs_count = 0;
        $golfShoes_count = 0;
        $accessories_count = 0;

        // Fetch the result rows and populate the count variables
        while ($row = $result->fetch_assoc()) {
            // Determine the product type name based on the product_type value
            switch ($row["product_type"]) {
                case 1:
                    $apparel_count += $row["click_count"];
                    break;
                case 2:
                    $golfClubs_count += $row["click_count"];
                    break;
                case 3:
                    $usedClubs_count += $row["click_count"];
                    break;
                case 4:
                    $golfShoes_count += $row["click_count"];
                    break;
                case 5:
                    $accessories_count += $row["click_count"];
                    break;
                default:
                    // Handle other cases if needed
                    break;
            }
        }

        // Define the labels array with the predefined product type names
        $labels = ['APPAREL', 'GOLF CLUBS', 'USED CLUBS', 'GOLF SHOES', 'ACCESSORIES'];

        // Define the data array with the count variables for each product type
        $data = [$apparel_count, $golfClubs_count, $usedClubs_count, $golfShoes_count, $accessories_count];
        $stmt->close();
        ?>
    // Sample data for the histogram chart (6 variables)
    var histogramChartData = {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Main Category',
            data: <?php echo json_encode($data); ?>, // Sample data values
            backgroundColor: 'blue'
        }]
    };

    // Sample data for the second histogram chart (prices)
    <?php
     $stmt = $conn->prepare("SELECT * 
     FROM trace_price 
     ORDER BY price_count DESC 
     LIMIT 6");
     $stmt->execute();
     $result = $stmt->get_result();
     $labels = []; // Array to store labels (word)
     $data = []; // Array to store data (search_count)
     while ($row = $result->fetch_assoc()) {
         $labels[] = $row["mean_price"];
         $data[] = $row["price_count"];
     }
    
    ?>
    var priceHistogramChartData = {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Price Distribution',
            data: <?php echo json_encode($data); ?>, // Sample data values for prices
            backgroundColor: 'green'
        }]
    };

    // Function to create and render the pie chart
    function renderPieChart() {
        var ctx = document.getElementById('pieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: pieChartData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }

    // Function to create and render the first histogram chart (products)
    function renderHistogramChart() {
        var ctx = document.getElementById('histogramChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: histogramChartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

    // Function to create and render the second histogram chart (prices)
    function renderPriceHistogramChart() {
        var ctx = document.getElementById('priceHistogramChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: priceHistogramChartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }

    // Call the functions to render the charts
    renderPieChart();
    renderHistogramChart();
    renderPriceHistogramChart();
</script>
