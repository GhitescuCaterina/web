<?php
include 'dbConfig.php';

$year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : 'all';


if ($year !== 'all') {
    $sql = "SELECT JUDET, ANUL_STATISTICII, SUM(TOTAL_VEHICULE) as TOTAL_JUDET FROM top_100_car_brands";
    $sql .= " WHERE ANUL_STATISTICII = '" . $year . "'";
    $sql .= " GROUP BY JUDET, ANUL_STATISTICII ";
}
else{
    $sql = "SELECT JUDET, SUM(TOTAL_VEHICULE) as TOTAL_JUDET FROM top_100_car_brands";
    $sql .= " WHERE ANUL_STATISTICII = 2023 GROUP BY JUDET ";
}


$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
