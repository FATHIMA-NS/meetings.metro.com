<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "metro_meetings";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available rooms based on selected date and time
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['date'])) {
    $selected_date = $_GET['date'];
    // Render the booking form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Book a Meeting Room</title>
    </head>
    <body>
        <h2>Book a Meeting for <?php echo htmlspecialchars($selected_date); ?></h2>
        <form action="book_meeting.php" method="POST">
            <input type="hidden" name="meeting_date" value="<?php echo htmlspecialchars($selected_date); ?>">
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required><br>
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required><br>
            <label for="capacity">Room Capacity:</label>
            <input type="number" id="capacity" name="capacity" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
            <label for="tea">Refreshment Required:</label>
            <input type="checkbox" id="tea" name="tea" value="1"><br>
            <button type="submit">Check Availability</button>
        </form>
    </body>
    </html>
    <?php
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meeting_date = $_POST['meeting_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $tea = isset($_POST['tea']) ? 1 : 0;

    // Query to find available rooms
    $sql = "SELECT * FROM meeting_rooms WHERE capacity >= ? AND id NOT IN (
                SELECT room_id FROM meetings WHERE meeting_date = ? 
                AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $capacity, $meeting_date, $start_time, $start_time, $end_time, $end_time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Rooms available
        echo "<h3>Available Rooms:</h3>";
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . $row['room_name'] . " (Capacity: " . $row['capacity'] . ") <a href='confirm_booking.php?room_id=" . $row['id'] . "&date=$meeting_date&start_time=$start_time&end_time=$end_time&description=$description&tea=$tea'>Book this Room</a></p>";
        }
    } else {
        // No rooms available
        echo "<p>No rooms available for the selected time slot. Please <a href='javascript:history.back()'>reschedule</a>.</p>";
    }
    $stmt->close();
}
$conn->close();
?>
