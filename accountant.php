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
?>

<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .mid2{
            height:90px;
        }

/* Fixed Sidebar */
.sidebar {
    position: fixed;
    top: 130px;
    left: 0;
    width: 15%;
    height: calc(100vh - 130px);
    background-color: #012220;
    color: white;
    padding-top: 20px;
    overflow-y: auto;
}
.sidebar ul {
    list-style: none;
}
.sidebar ul li {
    padding: 12.5px 26px;
    cursor: pointer;
}
.sidebar ul li:hover {
    background-color: #004d40;
}
.sidebar ul li a {
    text-decoration: none;
    color: white;
    display: block;
}


 /* Sidebar Styles */
 .sidebar1 {
    width: 200px;
    height: 90vh;
    background-color: #024e49;
    color: white;
    position: fixed;
    left: 205px;
    top: 120px;
    border-radius: 20px;
    padding: 10px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
}

.sidebar1 h2 {
    text-align: center;
    font-size: 22px;
    margin-bottom: 20px;
}

.sidebar1 ul {
    list-style: none;
    padding: 0;
}

.sidebar1 ul li {
    padding: 4px;
    margin: 10px 0;
    margin-left: 10px;
    width: 150px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
}

.sidebar1 ul li:hover {
    background: rgba(255, 255, 255, 0.4);
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
        <p><?php echo $user["full_name"]; ?><br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i>/Dashboard</div></div>
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
                <li><a href="staff-payroll.php"><i class="bi bi-people-fill">Staff Payroll</i><li> <i class="bi bi-box-arrow-in-right"></i>Logout</a></li>
            </ul>
        </div>
        <div class="content" style=" margin-left: 15rem; margin-top: 40px;">
            <div class="contents"><a href="profile.php"><h3>My Profile</h3><br><img src="graduation.jpg" alt="profile"></a></div>
            <div class="contents"><a href="allocate-fee.php"><h3>Allocate Fee</h3><br><img src="money.jpg" alt="fee"></a></div>
            <div class="contents"><a href="fee-payment.php"><h3>Fee Payment</h3><br><img src="payment.webp" alt="payment"></a></div>
            <div class="contents"><a href="outstanding-bill.php"><h3>Outstanding Bills</h3><br><img src="bills.webp" alt="bill"></a></div>
            <div class="contents"><a href="manage-expenses.php"><h3>Manage Expenses</h3><br><img src="expenses.jpg" alt="expenses"></a></div>
            <div class="contents"><a href="manage-debtor.php"><h3>Manage Debtors</h3><br><img src="debtors.avif" alt="debtors"></a></div>
            <div class="contents"><a href="manage-inventory.php"><h3>Manage Inventory</h3><br><img src="inventory.webp" alt="Inventory"></a></div>
            <div class="contents"><a href="staff-payroll.php"><h3>Staff Payrols</h3><br><img src="payrolls.jpg" alt="payrolls"></a></div>
            <div class="contents"><a href="calender.php"><h3>Calendar</h3><br><img src="calender.jpg" alt="calender"></a></div>
            <div class="contents"><a href="password-reset.php"><h3>Password Reset</h3><br><img src="password.jpg" alt="password"></a></div>
        </div>
     </div>
</body>
</php>