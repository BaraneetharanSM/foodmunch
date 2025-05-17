<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submit Feedback</title>
</head>
<body>
    <h2>Give Your Feedback</h2>
    <form action="submit_feedback.php" method="POST">
        <label for="dish_id">Select Dish:</label>
        <select name="dish_id" required>
            <?php
            include 'db_connect.php';
            $sql = "SELECT id, dish_name FROM dishes";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='".$row['id']."'>".$row['dish_name']."</option>";
            }
            ?>
        </select>
        
        <label for="rating">Rating:</label>
        <select name="rating" required>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>

        <label for="review">Review:</label>
        <textarea name="review" required></textarea>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
