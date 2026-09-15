<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Database credentials
$servername = "database-1.cxxxxxxx.ap-south-1.rds.amazonaws.com"; // Your RDS Endpoint
$username   = "admin";                                           // RDS Username
$password   = "Evaeid123";                               // RDS Password
$dbname     = "portfolio_db";                                    // DB Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Get POST data from JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['name']) && !empty($data['email']) && !empty($data['message'])) {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['name'], $data['email'], $data['message']);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Message stored in database!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to write record"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid form inputs"]);
}

$conn->close();
?>