<?php
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
    $fullName = $_POST["full-name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
    // Handle profile picture upload
    $profilePic = "";
    if (isset($_FILES["profile-pic"]) && $_FILES["profile-pic"]["error"] == 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $profilePic = $uploadDir . basename($_FILES["profile-pic"]["name"]);
        move_uploaded_file($_FILES["profile-pic"]["tmp_name"], $profilePic);
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO accountant (full_name, email, phone, password, profile_pic) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullName, $email, $phone, $password, $profilePic);

    if ($stmt->execute()) {
        echo "<script>alert('Signup Successful!'); window.location.href='login_accountant.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Accountant Signup</title>
    <link rel="stylesheet" href="signup-login.css">
</head>
<body>
    <div class="container">
        <h2>Accountant Signup</h2>
        <form action="signup_accountant.php" method="POST" enctype="multipart/form-data">
            <label for="full-name">Full Name:</label>
            <input type="text" id="full-name" name="full-name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="profile-pic">Profile Picture:</label>
            <input type="file" id="profile-pic" name="profile-pic" accept="image/*" required>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login_accountant.php">Login here</a></p>
    </div>
</body>
</html>
