<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_post'] !== 'admin') {
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

// Fetch pending meetings
$pending_meetings = $conn->query("SELECT * FROM meetings WHERE status = 'pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Meeting Room Bookings</title>
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
        h1, h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 15%;
        }
        button.reject {
            background-color: #dc3545;
        }
        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <h1>Pending Meeting Room Bookings</h1>

    <div class="container">
        <table>
            <tr>
                <th>Department</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Room</th>
                <th>Actions</th>
            </tr>
            <?php while ($meeting = $pending_meetings->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($meeting['department']); ?></td>
                <td><?php echo date('d-m-Y', strtotime($meeting['meeting_date'])); ?></td>
                <td><?php echo date('H:i', strtotime($meeting['start_time'])); ?></td>
                <td><?php echo date('H:i', strtotime($meeting['end_time'])); ?></td>
                <td><?php echo htmlspecialchars($meeting['meeting_room']); ?></td>
                <td>
                    <form action="approve_booking.php" method="POST">
                        <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                        <button type="submit">Approve</button>
                    </form>
                    <form action="reject_booking.php" method="POST">
                        <input type="hidden" name="meeting_id" value="<?php echo $meeting['id']; ?>">
                        <button type="submit" class="reject">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
