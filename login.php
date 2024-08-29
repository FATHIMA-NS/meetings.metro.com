<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "metro_meetings";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = htmlspecialchars($_POST['user_id']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, user_post, password FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $user_post, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_post'] = $user_post;

            if ($user_post == 'admin') {
                $_SESSION['admin_logged_in'] = true;
                header("Location: new_admin_dashboard.php");
                exit();
            } else if ($user_post == 'employee') {
                $_SESSION['employee_logged_in'] = true; // This is crucial for the employee dashboard
                header("Location: new_employee_dashboard.php");
                exit();
            } else {
                header("Location: login.php?error=Unknown user role");
                exit();
            }
        } else {
            header("Location: login.php?error=Invalid credentials");
            exit();
        }
    } 
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .container input {
            width: 93%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .container button {
            width: 100%;
            padding: 10px;
            background-color: #37B7C3;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #0D7C66;
        }
        .error {
            color: red;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-link a {
            color: blue;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="user_id" placeholder="User ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p class="error">
                <?php
                if (isset($_GET['error'])) {
                    echo htmlspecialchars($_GET['error']);
                }
                ?>
            </p>
        </form>
        <div class="register-link">
            <p>Not registered? <a href="registerform.php">Create an account</a></p>
        </div>
    </div>
</body>
</html>
