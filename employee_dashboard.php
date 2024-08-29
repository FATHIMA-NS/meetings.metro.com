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

// Check connection
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.4/main.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color:#37B7C3 ;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 500px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 28px;
            margin: 0;
        }
        h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #37B7C3;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input[type="date"], input[type="time"], input[type="text"], select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="checkbox"] {
            margin-right: 10px;
        }
        button {
            background-color: #37B7C3;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: auto;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .calendar-container {
            margin-top: 40px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>EMPLOYEE DASHBOARD</h1>
    </div>

    <div class="container">
        <h2>Book a Meeting Room</h2>
        <form id="bookingForm" action="book_meeting.php" method="POST">
            <label for="meeting_date">Date:</label>
            <input type="date" name="meeting_date" required>

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" required>

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" required>

            <label for="room_id">Room:</label>
            <select name="room_id" id="room_id" required>
                <option value="" disabled selected>Select Room</option>
                <?php
                // Fetch available rooms from the database
                $sql = "SELECT id, room_name FROM meeting_rooms";
                $result = $conn->query($sql);

                if ($result === FALSE) {
                    echo "<option value='' disabled>Error fetching rooms</option>";
                    echo "Error: " . $conn->error;
                } else if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['room_name']) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No rooms available</option>";
                }
                ?>
            </select>

            <label for="department">Department:</label>
            <input type="text" name="department" id="department" required>

            <label>
                <input type="checkbox" name="refreshment_needed" value="1"> Refreshment Needed
            </label>

            <button type="submit">BOOK ROOM</button>
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
