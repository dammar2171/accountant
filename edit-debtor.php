<?php
session_start();
// Database connection
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
$sql = "SELECT profile_pic, full_name FROM accountant WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $accountant_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$profile_pic = isset($user["profile_pic"]) && !empty($user["profile_pic"]) ? $user["profile_pic"] : "default-profile.png";

// Get debtor details
if (isset($_GET['id'])) {
    $debtor_id = $_GET['id'];
    $sql = "SELECT * FROM debtors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $debtor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $debtor = $result->fetch_assoc();
} else {
    header("Location: manage-debtor.php");
    exit();
}

// Update debtor details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $debtor_name = $_POST['debtor-name'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due-date'];

    $sql = "UPDATE debtors SET debtor_name = ?, amount = ?, due_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsi", $debtor_name, $amount, $due_date, $debtor_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Debtor updated successfully!'); window.location='manage-debtor.php';</script>";
    } else {
        echo "<script>alert('Error updating debtor.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Debtor</title>
    <link rel="stylesheet" href="outstanding-bill.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .containerDEB {
            max-width: 1250px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 150px;
            margin-left:250px;
        }
        /* Form Styles */
form {
    margin-top: 20px;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

input, select, button {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

button {
    background-color: #00796b;
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease-in-out;
}

button:hover {
    background-color: #004d40;
}
h2{
    color:black;
}
.profile-pic1 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-top: 10px;
            margin-bottom: 10px;
            border: 3px solid #008066;
        }
    </style>
</head>
<body>
     <!-- header -->
     <div class="header">
        <div class="head1"><p id="svm">SVM</p></div>
        <div class="head2">
            <i class="bi bi-justify top-icon"></i>
            <div class="date">18-NOV-2024  11:47:10</div>
        </div>
    </div>
    <!-- mid div -->
     <div class="mid">
        <div class="mid1"><img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic1" width="100" height="100">
        <p><?php echo $user["full_name"]; ?> <br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Fee Allocate</div></div>
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

    <div class="containerDEB">
        <h2>Edit Debtor</h2>
        <form method="POST" action="">
            <label for="debtor-name">Debtor Name:</label>
            <input type="text" id="debtor-name" name="debtor-name" value="<?php echo $debtor['debtor_name']; ?>" required>
            
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" value="<?php echo $debtor['amount']; ?>" required>
            
            <label for="due-date">Due Date:</label>
            <input type="date" id="due-date" name="due-date" value="<?php echo $debtor['due_date']; ?>" required>
            
            <button type="submit">Update Debtor</button>
        </form>
    </div>
</body>
</html>
