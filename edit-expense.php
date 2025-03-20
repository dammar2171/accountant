<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "school_management"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
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

// Fetch expense data if ID is provided
$expense = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM expenses WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $expense = $result->fetch_assoc();
    } else {
        echo "<script>alert('Expense not found.'); window.location.href = 'manage-expenses.php';</script>";
        exit();
    }
}

// Handle form submission for updating expense
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $due_date = $_POST['due-date'];

    // Update data in the database
    $sql = "UPDATE expenses SET category = '$category', amount = '$amount', description = '$description', due_date = '$due_date' WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense updated successfully!'); window.location.href = 'manage-expenses.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="manage-expenses.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
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
            <div class="date"><?php echo date("d-M-Y  H:i:s"); ?></div>
        </div>
    </div>
    <!-- mid div -->
    <div class="mid">
        <div class="mid1"><img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic1" width="100" height="100">
        <p><?php echo $user["full_name"]; ?>
        <br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Edit Expense</div></div>
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
        <div class="container">
            <header>
                <h1>Edit Expense</h1>
            </header>
            <main>
                <form id="expense-form" method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $expense['id']; ?>">
                    
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" value="<?php echo $expense['category']; ?>" required>
                    
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" value="<?php echo $expense['amount']; ?>" required>
                    
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" value="<?php echo $expense['description']; ?>" required>
                    
                    <label for="due-date">Due Date:</label>
                    <input type="date" id="due-date" name="due-date" value="<?php echo $expense['due_date']; ?>" required>
                    
                    <div class="expenses-container">
                        <button type="submit" class="add-expense-btn">Update Expense</button>
                    </div>
                </form>
            </main>
        </div>
    </div>
</body>
</html>