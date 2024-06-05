<?php
include 'dbConfig.php';

$sql = "SELECT DISTINCT MARCA FROM top_100_car_brands";
$result = $conn->query($sql);

$brands = array();
while ($row = $result->fetch_assoc()) {
    $brands[] = $row['MARCA'];
}

header('Content-Type: application/json');
echo json_encode($brands);

$conn->close();
?>
