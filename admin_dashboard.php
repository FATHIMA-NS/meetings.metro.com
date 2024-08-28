<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
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

// Fetch pending registrations
$pending_users = $conn->query("SELECT * FROM users WHERE status = 'pending'");

// Fetch all meeting rooms
$meeting_rooms = $conn->query("SELECT * FROM meeting_rooms");

// Fetch pending meetings
$pending_meetings = $conn->query("SELECT * FROM meetings WHERE status = 'pending'");

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

    <h1>Admin Dashboard</h1>

    <div class="container">
        <h2>Pending Registrations</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Post</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = $pending_users->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                <td><?php echo htmlspecialchars($user['user_post']); ?></td>
                <td>
                    <button onclick="handleApproval(<?php echo $user['id']; ?>, 'approve')">Approve</button>
                    <button class="reject" onclick="handleApproval(<?php echo $user['id']; ?>, 'reject')">Reject</button>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <div class="container">
        <h2>Manage Meeting Rooms</h2>
        <form id="roomForm" action="manage_rooms.php" method="POST">
            <input type="text" name="room_name" placeholder="Room Name" required>
            <input type="number" name="capacity" placeholder="Capacity" required>
            <button type="submit">Add Room</button>
        </form>
        <h3>Existing Rooms</h3>
        <table>
            <tr>
                <th>Room Name</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
            <?php while ($room = $meeting_rooms->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($room['room_name']); ?></td>
                <td><?php echo htmlspecialchars($room['capacity']); ?></td>
                <td>
                    <button class="reject" onclick="handleRoomDeletion(<?php echo $room['id']; ?>)">Delete</button>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <div class="container">
        <h2>Pending Meeting Room Bookings</h2>
        <table>
            <tr>
                <th>Meeting Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Room</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php while ($meeting = $pending_meetings->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($meeting['meeting_date']); ?></td>
                <td><?php echo htmlspecialchars($meeting['start_time']); ?></td>
                <td><?php echo htmlspecialchars($meeting['end_time']); ?></td>
                <td><?php echo htmlspecialchars($meeting['room_id']); ?></td>
                <td><?php echo htmlspecialchars($meeting['description']); ?></td>
                <td>
                    <button onclick="handleMeetingApproval(<?php echo $meeting['id']; ?>, 'approve')">Approve</button>
                    <button class="reject" onclick="handleMeetingApproval(<?php echo $meeting['id']; ?>, 'reject')">Reject</button>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    