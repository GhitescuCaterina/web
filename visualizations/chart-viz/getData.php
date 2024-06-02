<?php
include 'dbConfig.php';

// Get the 'brand' and 'year' parameters from the URL, if they are set
$brand = isset($_GET['brand']) ? $conn->real_escape_string($_GET['brand']) : '';
$year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

// Initialize an array to hold SQL WHERE clauses
$whereClauses = [];

// Add a WHERE clause for the brand if a brand was provided
if ($brand) {
    $whereClauses[] = "MARCA = '$brand'";
}

// Add a WHERE clause for the year if a year was provided
if ($year) {
    $whereClauses[] = "ANUL_STATISTICII = '$year'";
}

// Combine the WHERE clauses into a single string
$whereSql = '';
if (count($whereClauses) > 0) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
}

// Construct the SQL query
$sql = "SELECT MARCA, ANUL_STATISTICII, SUM(TOTAL_VEHICULE) as TOTAL_VEHICULE FROM top_100_car_brands $whereSql GROUP BY MARCA, ANUL_STATISTICII";

$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "Query failed: " . $conn->error]));
}

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Set the content type of the response to JSON
header('Content-Type: application/json');

// Encode the data array as a JSON string and output it
echo json_encode($data);

$conn->close();
?>
