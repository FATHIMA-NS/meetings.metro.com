<?php
// Database connection setup
$servername = "localhost";
$username = "root"; // Change according to your database setup
$password = ""; // Change according to your database setup
$dbname = "metro_meetings"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $user_post = $_POST['user_post'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        header("Location: registerform.php?error=Passwords do not match!");
        exit();
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user details into the database (pending admin approval)
    $sql = "INSERT INTO users (user_id, full_name, user_post, password, status) 
            VALUES ('$user_id', '$full_name', '$user_post', '$hashed_password', 'pending')";

    if ($conn->query($sql) === TRUE) {
        // Notify admin for approval (simulated here)
        echo "<script>
            if (confirm('Admin: Do you approve this registration?')) {
                window.location.href = 'approve.php?user_id=$user_id';
            } else {
                window.location.href = 'registerform.php?error=Registration denied by admin.';
            }
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
