<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION["accountant_id"])) {
    header("Location: login_accountant.php");
    exit();
}

// Fetch accountant profile picture from database
$accountant_id = $_SESSION["accountant_id"];
$sql = "SELECT profile_pic,full_name FROM accountant WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $accountant_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Set profile picture path
$profile_pic = isset($user["profile_pic"]) && !empty($user["profile_pic"]) ? $user["profile_pic"] : "default-profile.png";


$accountant_id = $_SESSION['accountant_id'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    // Fetch current password from database
    $sql = "SELECT password FROM accountant WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accountant_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $db_password)) {
        $message = "Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password in database
        $update_sql = "UPDATE accountant SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $accountant_id);
        if ($update_stmt->execute()) {
            $message = "Password successfully updated!";
        } else {
            $message = "Error updating password. Try again.";
        }
        $update_stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="outstanding-bill.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .containerReset {
            max-width: 700px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 200px;
            margin-left: 480px;
            text-align: center;
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
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
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

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
        <!-- Header -->
        <div class="header">
        <div class="head1"><p id="svm">SVM</p></div>
        <div class="head2">
            <i class="bi bi-justify top-icon"></i>
            <div class="date">18-NOV-2024  11:47:10</div>
        </div>
    </div>

    <!-- Mid Section -->
    <div class="mid">
        <div class="mid1">
        <img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic1" width="100" height="100">

            <p><?php echo $user["full_name"]; ?>
            <br>Accountant</p>
        </div>
        <div class="mid2">
            <p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR</p>
            <div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Password Reset</div>
        </div>
    </div>

    <!-- Sidebar -->
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

    <div class="containerReset">
        <h2>Password Reset</h2>
        <?php if ($message): ?>
            <p class="error"> <?php echo $message; ?> </p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="current-password">Current Password:</label>
            <input type="password" id="current-password" name="current-password" required>

            <label for="new-password">New Password:</label>
            <input type="password" id="new-password" name="new-password" required>

            <label for="confirm-password">Confirm New Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
