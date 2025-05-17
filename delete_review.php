<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

if (isset($_GET['id'])) {
    $reviewId = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);

        header("Location: admin_dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting review: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
