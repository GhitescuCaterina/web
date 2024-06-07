<?php
session_start();
include 'dbConfig.php';

// Get the posted data
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$email = $request->email;
$password = $request->password;

// Check if user exists
$sql = "SELECT id, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Email not registered.']);
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->bind_result($id, $hashed_password);
$stmt->fetch();

if (password_verify($password, $hashed_password)) {
    $_SESSION['user_id'] = $id;
    echo json_encode(['success' => true, 'message' => 'Login successful.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
}

$stmt->close();
$conn->close();
?>
