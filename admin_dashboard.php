<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

require_once 'db_connect.php';

// Fetch orders
try {
    $ordersStmt = $conn->query("SELECT * FROM orders ORDER BY order_timestamp DESC");
    $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ordersError = "Error fetching orders: " . $e->getMessage();
    $orders = [];
}

// Fetch table bookings
try {
    $bookingsStmt = $conn->query("SELECT * FROM table_bookings ORDER BY booking_timestamp DESC");
    $bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $bookingsError = "Error fetching bookings: " . $e->getMessage();
    $bookings = [];
}

// Fetch reviews (Replace `review` with correct column name like `feedback`)
try {
    $reviewsStmt = $conn->query("SELECT id, feedback AS review, rating FROM reviews ORDER BY id DESC");
    $reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $reviewsError = "Error fetching reviews: " . $e->getMessage();
    $reviews = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #1c1c1c;
    color: #fff;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

h1, h2 {
    text-align: center;
    color: #00eeff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #fff;
}

th {
    background: #007bff;
}

a.logout-link {
    display: block;
    text-align: center;
    color: #00eeff;
    text-decoration: none;
    font-weight: bold;
    margin-bottom: 20px;
}

button.delete-button {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

button.delete-button:hover {
    background: #c82333;
}

    </style>
    <script>
        function confirmDelete(id, type) {
            if (confirm("Are you sure you want to delete this " + type + "?")) {
                if (type === 'order') {
                    window.location.href = 'delete_order.php?id=' + id;
                } else if (type === 'booking') {
                    window.location.href = 'delete_booking.php?id=' + id;
                } else if (type === 'review') {
                    window.location.href = 'delete_review.php?id=' + id;
                }
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="logout-link">Logout</a>

        <h2>Food Orders</h2>
        <?php if (isset($ordersError)): ?>
            <p class="error-message"><?php echo $ordersError; ?></p>
        <?php elseif (empty($orders)): ?>
            <p class="no-data">No orders found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Amount</th>
                    <th>Order Items</th>
                    <th>Timestamp</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td>₹<?php echo htmlspecialchars($order['total_amount']); ?></td>
                        <td>
                            <?php
                            $items = json_decode($order['order_items'], true);
                            if ($items) {
                                foreach ($items as $item) {
                                    echo htmlspecialchars($item['name']) . " - ₹" . htmlspecialchars($item['price']) . "<br>";
                                }
                            } else {
                                echo "Invalid order items.";
                            }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['order_timestamp']); ?></td>
                        <td><button class="delete-button" onclick="confirmDelete(<?php echo $order['order_id']; ?>, 'order')">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <h2>Table Bookings</h2>
        <?php if (isset($bookingsError)): ?>
            <p class="error-message"><?php echo $bookingsError; ?></p>
        <?php elseif (empty($bookings)): ?>
            <p class="no-data">No bookings found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th>Guests</th>
                    <th>Booking Date</th>
                    <th>Timestamp</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['guests']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['booking_timestamp']); ?></td>
                        <td><button class="delete-button" onclick="confirmDelete(<?php echo $booking['booking_id']; ?>, 'booking')">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <h2>Customer Reviews</h2>
        <?php if (isset($reviewsError)): ?>
            <p class="error-message"><?php echo $reviewsError; ?></p>
        <?php elseif (empty($reviews)): ?>
            <p class="no-data">No reviews found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>S.No</th>
                    <th>Review</th>
                    <th>Rating</th>
                    <th>Actions</th>
                </tr>
                <?php $sno = 1; foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo $sno++; ?></td>
                        <td><?php echo htmlspecialchars($review['review']); ?></td>
                        <td><?php echo htmlspecialchars($review['rating']); ?> / 5</td>
                        <td><button class="delete-button" onclick="confirmDelete(<?php echo $review['id']; ?>, 'review')">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
