<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_post'] !== 'admin') {
    header("Location: login.php");
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

// Fetch today's meetings
$date = date('Y-m-d');
$sql = "SELECT * FROM meetings WHERE meeting_date = '$date'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #37B7C3;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .nav {
            background-color: #088395;
            overflow: hidden;
        }
        .nav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .nav a:hover {
            background-color: #ddd;
            color: black;
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #37B7C3;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
    </div>

    <div class="nav">
        <a href="#">Today's Meetings</a>
        <a href="book_meeting_room.php">Book Meeting Room</a>
        <a href="reschedule_meeting.php">Reschedule Meeting</a>
        <a href="manage_rooms.php">Manage Rooms</a>
        <a href="pending_booking.php">Pending Bookings</a>

    </div>

    <div class="content">
        <h2>Today's Meetings</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Department</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Meeting Room</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . date('d-m-Y', strtotime($row['meeting_date'])) . "</td>
                        <td>" . htmlspecialchars($row['department']) . "</td>
                        <td>" . date('H:i', strtotime($row['start_time'])) . "</td>
                        <td>" . date('H:i', strtotime($row['end_time'])) . "</td>
                        <td>" . htmlspecialchars($row['meeting_room']) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No meetings scheduled for today.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
