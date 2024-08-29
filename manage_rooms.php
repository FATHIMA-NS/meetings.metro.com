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

// Fetch all meeting rooms
$meeting_rooms = $conn->query("SELECT * FROM meeting_rooms");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Meeting Rooms</title>
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
        h1, h2, h3 {
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
            width: auto;
        }
        button:hover {
            opacity: 0.9;
        }
        input[type="text"], input[type="number"] {
            padding: 8px;
            width: calc(50% - 16px);
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Manage Meeting Rooms</h1>

    <div class="container">
        <h2>Add New Room</h2>
        <form id="roomForm" action="add_room.php" method="POST">
            <input type="text" name="room_name" placeholder="Room Name" required>
            <input type="number" name="capacity" placeholder="Capacity" required>
            <br>
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
                    <form action="delete_room.php" method="POST">
                        <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                        <button type="submit" class="reject">Delete</button>
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
