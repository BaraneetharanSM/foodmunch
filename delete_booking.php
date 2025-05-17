<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM table_bookings WHERE booking_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header("Location: admin_dashboard.php");
    } catch (PDOException $e) {
        echo "Error deleting booking: " . $e->getMessage();
    }
} else {
    header("Location: admin_dashboard.php");
}
?>