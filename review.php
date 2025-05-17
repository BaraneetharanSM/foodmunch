<?php
require_once 'db_connect.php';

$successMessage = $errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["customer_name"]);
    $rating = trim($_POST["rating"]);
    $feedback = trim($_POST["feedback"]);

    if (!empty($name) && !empty($rating) && !empty($feedback)) {
        try {
            // Correct SQL Query - Ensure the column names match your database
            $stmt = $conn->prepare("INSERT INTO reviews (customer_name, rating, feedback, review_timestamp) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $rating, $feedback]);
            $successMessage = "Thank you for your review!";
        } catch (PDOException $e) {
            $errorMessage = "Error submitting review: " . $e->getMessage();
        }
    } else {
        $errorMessage = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Your Review - FoodMunch</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: url("https://i.ibb.co/4wR6RH3v/customer-experience-creative-collage.jpg") no-repeat center center fixed;
    background-size: cover;
    color: white;
    text-align: center;
}

.review-container {
    position: relative;
    z-index: 1;
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.2); /* Light blur effect */
    border-radius: 10px;
    text-align: center;
}

/* Form Input Styles */
input, select, textarea {
    width: 90%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: none;
    display: block;
}

textarea {
    height: 100px;
}

/* Button Styles */
button, .back-button {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    transition: 0.3s;
}

button:hover, .back-button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>

<div class="review-container">
    <h1>Give Your Review</h1>

    <?php if ($successMessage): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="customer_name" placeholder="Your Name" required>
        <select name="rating" required>
            <option value="">Select Rating</option>
            <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
            <option value="4">⭐⭐⭐⭐ - Good</option>
            <option value="3">⭐⭐⭐ - Average</option>
            <option value="2">⭐⭐ - Below Average</option>
            <option value="1">⭐ - Poor</option>
        </select>
        <textarea name="feedback" placeholder="Your Feedback" required></textarea>
        <button type="submit">Submit Review</button>
        <a href="index.html" class="back-button">Back to Home</a>
    </form>
</div>

</body>
</html>
