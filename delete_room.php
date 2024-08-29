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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = (int)$_POST['room_id'];

    // Delete the room from the database
    $sql = "DELETE FROM meeting_rooms WHERE id = $room_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Room deleted successfully!'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
