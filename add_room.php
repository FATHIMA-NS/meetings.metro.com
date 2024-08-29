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
    $room_name = $conn->real_escape_string($_POST['room_name']);
    $capacity = (int)$_POST['capacity'];

    // Insert new room into the database
    $sql = "INSERT INTO meeting_rooms (room_name, capacity) VALUES ('$room_name', $capacity)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New room added successfully!'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
