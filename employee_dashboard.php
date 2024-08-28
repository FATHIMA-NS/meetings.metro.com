<?php
session_start();
// Check if employee is logged in
if (!isset($_SESSION['employee_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "metro_meetings";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

    <h1>Employee Dashboard</h1>

    <div class="container">
        <h2>Calendar View</h2>
        <div id="calendar"></div>
    </div>

    <div class="container">
        <h2>Book a Meeting Room</h2>
        <form id="bookingForm" action="book_meeting.php" method="POST">
            <label for="meeting_date">Date:</label>
            <input type="date" name="meeting_date" required><br>
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" required><br>
            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" required><br>
            <label for="room_id">Room:</label>
            <select name="room_id" id="room_id" required>
                <option value="" disabled selected>Select Room</option>
                <?php
                // Fetch available rooms from the database
                $sql = "SELECT id, room_name FROM meeting_rooms";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['room_name']) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No rooms available</option>";
                }
                ?>
            </select><br>
            <label for="description">Description:</label>
            <textarea name="description" placeholder="Meeting Description" required></textarea><br>
            <input type="checkbox" name="refreshment_needed" value="1"> Refreshment Needed<br>
            <button type="submit">Book Room</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'fetch_events.php' // Fetch booked meetings from the database
            });
            calendar.render();
        });
    </script>

</body>
</html>

<?php
$conn->close();
?>
