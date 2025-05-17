<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "foodmunch_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("
    <div style='font-family: sans-serif; padding: 20px; background-color: rgba(255, 0, 0, 0.2); border: 1px solid red; color: darkred; text-align: center;'>
        <h2>Connection Error:</h2>
        <p>Connection failed: " . $conn->connect_error . "</p>
    </div>
    ");
}

// Form Data Validation
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$phone = isset($_POST['phone']) ? preg_replace('/[^0-9]/', '', $_POST['phone']) : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$time = isset($_POST['time']) ? $_POST['time'] : '';
$guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;

$errors = [];

if (empty($name)) {
    $errors[] = "Name is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
}

if (empty($phone)) {
    $errors[] = "Phone number is required.";
}

if (empty($date)) {
    $errors[] = "Date is required.";
}

if (empty($time)) {
    $errors[] = "Time is required.";
}

if ($guests < 1) {
    $errors[] = "Number of guests must be at least 1.";
}

if (!empty($errors)) {
    echo "
    <div style='font-family: sans-serif; padding: 20px; background-color: rgba(255, 255, 0, 0.2); border: 1px solid orange; color: darkgoldenrod; text-align: center;'>
        <h2>Error:</h2>
    ";
    foreach ($errors as $error) {
        echo "<p style='margin-bottom: 10px;'>$error</p>";
    }
    echo "<p><a href='booking.html' style='color: #007bff; text-decoration: none;'>Go back</a></p>";
    echo "</div>";
    $conn->close();
    exit;
}

// Database Insertion
$sql = "INSERT INTO table_bookings (customer_name, customer_email, customer_phone, booking_date, booking_time, guests) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $name, $email, $phone, $date, $time, $guests);

if ($stmt->execute()) {
    echo "
    <div style='font-family: sans-serif; padding: 20px; background-color: rgba(0, 255, 0, 0.2); border: 1px solid green; color: darkgreen; text-align: center;'>
        <h2>Booking Successful!</h2>
        <p style='margin-bottom: 10px;'>Thank you for booking a table.</p>
        <p><a href='booking.html' style='color: #007bff; text-decoration: none;'>Book another table</a></p>
    </div>
    ";
} else {
    echo "
    <div style='font-family: sans-serif; padding: 20px; background-color: rgba(255, 0, 0, 0.2); border: 1px solid red; color: darkred; text-align: center;'>
        <h2>Error:</h2>
        <p style='margin-bottom: 10px;'>Failed to book table: " . $stmt->error . "</p>
        <p><a href='booking.html' style='color: #007bff; text-decoration: none;'>Go back</a></p>
    </div>
    ";
}

$stmt->close();
$conn->close();
?>