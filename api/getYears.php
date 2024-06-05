<?php
include 'dbConfig.php';

$sql = "SELECT DISTINCT ANUL_STATISTICII FROM top_100_car_brands";
$result = $conn->query($sql);

$years = array();
while ($row = $result->fetch_assoc()) {
    $years[] = $row['ANUL_STATISTICII'];
}

header('Content-Type: application/json');
echo json_encode($years);

$conn->close();
?>
