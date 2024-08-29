<?php
include('connection.php'); // Database connection

if (isset($_GET['user_id']) && isset($_GET['action'])) {
    $user_id = $_GET['user_id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }

    $query = "UPDATE users SET status='$status' WHERE user_id='$user_id'";

    if (mysqli_query($conn, $query)) {
        if ($status == 'approved') {
            echo "<script>alert('User approved successfully!'); window.location.href = 'admin_dashboard.php';</script>";
        } elseif ($status == 'rejected') {
            echo "<script>alert('User rejected!'); window.location.href = 'admin_dashboard.php';</script>";
        }
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
