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

// Confirm booking and send notification to admin
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $meeting_date = $_GET['date'];
    $start_time = $_GET['start_time'];
    $end_time = $_GET['end_time'];
    $description = $_GET['description'];
    $tea = $_GET['tea'];
    $user_id = 1; // For demonstration purposes, we are using a static user_id. Replace with dynamic value.

    // Insert meeting into database
    $sql = "INSERT INTO meetings (user_id, room_id, meeting_date, start_time, end_time, description, tea) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssi", $user_id, $room_id, $meeting_date, $start_time, $end_time, $description, $tea);
    $stmt->execute();

    // Get the last inserted meeting id
    $meeting_id = $stmt->insert_id;

    // Insert notification for admin approval
    $notification_message = "A new meeting is scheduled on $meeting_date from $start_time to $end_time. Please approve.";
    $sql = "INSERT INTO notifications (meeting_id, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $meeting_id, $notification_message);
    $stmt->execute();

    echo "<p>Meeting booked successfully! Waiting for admin approval.</p>";
    $stmt->close();
}
$conn->close();
?>
