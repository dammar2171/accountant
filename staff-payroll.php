<?php
session_start();
// Database Connection
$host = "localhost";
$username = "root"; // Change this if using a different user
$password = "";
$database = "school_management"; // Change this to your database name

$conn = new mysqli($host, $username, $password, $database);

// Check Connection
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


// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_name = $_POST["staff_name"];
    $staff_salary = $_POST["staff_salary"];

    $sql = "INSERT INTO staff_payroll (staff_name, salary, date) VALUES ('$staff_name', '$staff_salary', NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Staff added successfully'); window.location='staff-payroll.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM staff_payroll WHERE id = $delete_id");
    echo "<script>alert('Record deleted successfully'); window.location='staff-payroll.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Payroll</title>
    <link rel="stylesheet" href="outstanding-bill.css">
    <link rel="stylesheet" href="styles-staff-payroll.css">
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
    <!-- Header -->
    <div class="header">
        <div class="head1"><p id="svm">SVM</p></div>
        <div class="head2">
            <i class="bi bi-justify top-icon"></i>
            <div class="date"><?php echo date("d-M-Y H:i:s"); ?></div>
        </div>
    </div>

    <!-- Mid Section -->
    <div class="mid">
        <div class="mid1"><img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic1" width="100" height="100">
        <p><?php echo $user["full_name"]; ?><br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p>
            <div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Staff Payrolls</div>
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

        <!-- Content -->
        <div class="container1">
            <div class="header1">Add New Staff to Payroll</div>
            <form method="POST">
                <div class="input-group">
                    <input type="text" name="staff_name" placeholder="Enter Staff Name" required>
                    <input type="text" name="staff_salary" placeholder="Enter Staff Salary" required>
                    <button type="submit" class="save-btn">SAVE</button>
                </div>
            </form>

            <div class="table-container">
                <h3>View Staff Payroll</h3>
                <table>
                    <tr>
                        <th>S/N</th>
                        <th>STAFF NAME</th>
                        <th>SALARY (NGN)</th>
                        <th>DATE</th>
                        <th>ACTION</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT * FROM staff_payroll ORDER BY id DESC");
                    $total_salary = 0;
                    $sn = 1;
                    while ($row = $result->fetch_assoc()) {
                        $total_salary += $row["salary"];
                        echo "<tr>
                            <td>{$sn}</td>
                            <td>{$row['staff_name']}</td>
                            <td>{$row['salary']}</td>
                            <td>{$row['date']}</td>
                            <td><a href='staff-payroll.php?delete_id={$row['id']}'><button class='delete-btn'>DELETE</button></a></td>
                        </tr>";
                        $sn++;
                    }
                    ?>
                    <tr>
                        <td colspan="2" class="footer">Total Staff Salaries Per Month</td>
                        <td><strong><?php echo $total_salary; ?></strong></td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
