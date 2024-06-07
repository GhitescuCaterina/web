<?php
include 'dbConfig.php';

header('Content-Type: application/json');


// Get the posted data
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$email = $request->email;
$last_name = $request->last_name;
$first_name = $request->first_name;
$dob = $request->dob;
$password = password_hash($request->password, PASSWORD_BCRYPT);

// Check if email already exists
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already registered.']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->close();

// Insert new user
$sql = "INSERT INTO users (email, last_name, first_name, dob, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $email, $last_name, $first_name, $dob, $password);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registration successful.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
