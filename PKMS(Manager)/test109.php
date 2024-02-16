<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sample data
$project = [
    "deadline" => "2024-02-24", // Deadline in yyyy-mm-dd format
    "estimatedHours" => 20, // Estimated project hours
    "assignedHours" => 4 // Assigned task hours
];

// Calculate remaining project hours
$today = time();
$deadline = strtotime($project["deadline"]);
$daysRemaining = ceil(($deadline - $today) / (60 * 60 * 24));
$hoursPerDay = ($project["estimatedHours"] - $project["assignedHours"]) / $daysRemaining;

// Prepare data for chart
$labels = [];
$remainingHoursData = [];
$idealLineData = [];

$currentDate = $today;

while ($currentDate <= $deadline) {
    $labels[] = date("Y-m-d", $currentDate);
    $remainingHoursData[] = $project["estimatedHours"] - (($currentDate - $today) / (60 * 60 * 24)) * $hoursPerDay;
    $idealLineData[] = max(0, $project["estimatedHours"] - ($daysRemaining * $hoursPerDay)); // Calculate ideal progress line
    $currentDate += 86400; // Move to the next day (86400 seconds in a day)
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burn Down Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="burnDownChart"></canvas>
    <script>
        // Prepare data for chart
        var labels = <?php echo json_encode($labels); ?>;
        var remainingHoursData = <?php echo json_encode($remainingHoursData); ?>;
        var idealLineData = <?php echo json_encode($idealLineData); ?>;

        // Create burn down chart
        var ctx = document.getElementById('burnDownChart').getContext('2d');
        var burnDownChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Remaining Project Hours',
                    data: remainingHoursData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    fill: false
                }, {
                    label: 'Ideal Progress Line',
                    data: idealLineData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderDash: [5, 5],
                    fill: false
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Remaining Project Hours'
                        },
                        beginAtZero: true // Start y-axis from zero
                    }
                }
            }
        });
    </script>
</body>
</html>
