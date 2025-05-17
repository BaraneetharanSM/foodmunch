<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "foodmunch_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => "Connection failed: " . $conn->connect_error]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if ($input === null) {
    echo json_encode(['error' => "Invalid JSON data received."]);
    exit;
}

$name = $input['name'];
$address = $input['address'];
$phone = $input['phone'];
$delivery = $input['delivery'];
$total = $input['total'];
$items = json_encode($input['items']); // Store items as JSON

$sql = "INSERT INTO orders (customer_name, customer_address, customer_phone, delivery_pickup, order_items, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssd", $name, $address, $phone, $delivery, $items, $total);

if ($stmt->execute()) {
    echo json_encode(['message' => "Order placed successfully!"]);
} else {
    echo json_encode(['error' => "Error: " . $sql . "<br>" . $conn->error]);
}

$stmt->close();
$conn->close();
?>