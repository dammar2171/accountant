<?php
session_start();

$servername = "localhost"; // Change if needed
$username = "root"; // Change as per your database
$password = ""; // Change as per your database
$dbname = "school_management"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Query to get the user details
    $stmt = $conn->prepare("SELECT id, full_name, password FROM accountant WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify user credentials
    if ($user && password_verify($password, $user["password"])) {
        // Store user details in session
        $_SESSION["accountant_id"] = $user["id"];
        $_SESSION["accountant_name"] = $user["full_name"];
        
        echo "<script>alert('Login Successful!'); window.location.href='accountant.php';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
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
    <title>Accountant Login</title>
    <link rel="stylesheet" href="signup-login.css">
</head>
<body>
    <div class="container">
        <h2>Accountant Login</h2>
        <form action="login_accountant.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup_accountant.php">Sign up here</a></p>
    </div>
</body>
</html>
