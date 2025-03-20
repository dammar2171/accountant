<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Change according to your database credentials
$password = "";
$dbname = "school_management"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION["accountant_id"])) {
    header("Location: login_accountant.php");
    exit();
}

$accountant_id = $_SESSION["accountant_id"];

// Fetch user data
$sql = "SELECT full_name, email, phone, profile_pic FROM accountant WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $accountant_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}

// Update profile information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["full-name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $update_sql = "UPDATE accountant SET full_name = ?, email = ?, phone = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $fullName, $email, $phone, $accountant_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="outstanding-bill.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .containerProfile {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 160px;
            margin-left: 550px;
            text-align: center;
            overflow-y: auto;
        }
        h2 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input {
            padding: 8px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid #008066;
        }
        .profile-pic1 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-top: 10px;
            margin-bottom: 10px;
            border: 3px solid #008066;
        }
        button {
            padding: 10px;
            background-color: #008066;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #05977a8f;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="head1"><p id="svm">SVM</p></div>
        <div class="head2">
            <i class="bi bi-justify top-icon"></i>
            <div class="date"><?php echo date("d-M-Y H:i:s"); ?></div>
        </div>
    </div>
    <!-- mid div -->
    <div class="mid">
        <div class="mid1"><img src="<?php echo $user["profile_pic"] ? $user["profile_pic"] : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic1"><p><?php echo $user["full_name"]; ?><br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Outstanding Bills</div></div>
     </div>
     <!--sidebar-->
     <div class="main">
        <div class="sidebar">
            <ul>
                <li><a href="accountant.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="profile.php"><i class="bi bi-person-fill"></i>Profile</a></li>
                <li><a href="allocate-fee.php"><i class="bi bi-journal-text"></i>Allocate Fee</a></li>
                <li><a href="fee-payment.php"><i class="bi bi-box-arrow-in-down"></i>Fee Payment</a></li>
                <li><a href="outstanding-bill.php"><i class="bi bi-eye-fill"></i>Outstanding Bills</a></li>
                <li><a href="manage-expenses.php"><i class="bi bi-printer-fill"></i>Manage Expenses</a></li>
                <li><a href="manage-debtor.php"><i class="bi bi-database-add"></i>Manage Debtors</a></li>
                <li><a href="manage-inventory.php"><i class="bi bi-database-up"></i>Manage Inventory</a></li>
                <li><a href="staff-payroll.php"><i class="bi bi-people-fill"></i>Staff Payrolls</a></li>
                <li><a href="calender.php"><i class="bi bi-calendar-fill"></i>Calender</a></li>
                <li><a href="password-reset.php"><i class="bi bi-key-fill"></i>Password Reset</a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-in-right"></i>Logout</a></li>
            </ul>
        </div>


    <div class="containerProfile">
        <h2>User Profile</h2>
        <img src="<?php echo $user["profile_pic"] ? $user["profile_pic"] : 'default-profile.png'; ?>" alt="Profile Picture" class="profile-pic">
        <form action="profile.php" method="POST">
            <label for="full-name">Full Name:</label>
            <input type="text" id="full-name" name="full-name" value="<?php echo $user["full_name"]; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user["email"]; ?>" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $user["phone"]; ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
