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
    <link rel="stylesheet" href="calender.css">
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
            <div class="date">18-NOV-2024  11:47:10</div>
        </div>
    </div>
    <!-- mid div -->
     <div class="mid">
        <div class="mid1"><img src="<?php echo $profile_pic; ?>" alt="Profile Picture" class="profile-pic1" width="100" height="100">
        <p><?php echo $user["full_name"]; ?><br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Calendar</div></div>
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
        <div class="calender-main">
            <div class="sidebar1">
                <h2>School Calendar</h2>
                <ul>
                    <li onclick="showMonth('january')">January</li>
                    <li onclick="showMonth('february')">February</li>
                    <li onclick="showMonth('march')">March</li>
                    <li onclick="showMonth('april')">April</li>
                    <li onclick="showMonth('may')">May</li>
                    <li onclick="showMonth('june')">June</li>
                    <li onclick="showMonth('july')">July</li>
                    <li onclick="showMonth('august')">August</li>
                    <li onclick="showMonth('september')">September</li>
                    <li onclick="showMonth('october')">October</li>
                    <li onclick="showMonth('november')">November</li>
                    <li onclick="showMonth('december')">December</li>
                </ul>
            </div>
            <div class="main-content">
                <h1>School Year Calendar</h1>
                <div class="calendar">
                    <div id="january" class="month">
                        <h2>January</h2>
                        <ul class="event-list">
                            <li>1st - New Year's Day (Holiday)</li>
                            <li>10th - School Reopens</li>
                            <li>25th - Science Exhibition</li>
                        </ul>
                    </div>
                    <div id="february" class="month">
                        <h2>February</h2>
                        <ul class="event-list">
                            <li>14th - Valentine's Day Celebration</li>
                            <li>20th - Parent-Teacher Meeting</li>
                        </ul>
                    </div>
                    <div id="march" class="month">
                        <h2>March</h2>
                        <ul class="event-list">
                            <li>5th - Annual Sports Day</li>
                            <li>20th - Final Exams Begin</li>
                        </ul>
                    </div>
                    <div id="april" class="month"><h2>April</h2></div>
                    <div id="may" class="month"><h2>May</h2></div>
                    <div id="june" class="month"><h2>June</h2></div>
                    <div id="july" class="month"><h2>July</h2></div>
                    <div id="august" class="month"><h2>August</h2></div>
                    <div id="september" class="month"><h2>September</h2></div>
                    <div id="october" class="month"><h2>October</h2></div>
                    <div id="november" class="month"><h2>November</h2></div>
                    <div id="december" class="month"><h2>December</h2></div>
                </div>
            </div>
        
            <script>
                function showMonth(monthId) {
                    document.querySelectorAll('.month').forEach(month => {
                        month.style.display = 'none';
                    });
                    document.getElementById(monthId).style.display = 'block';
                }
            </script>
        
        </div>
</body>
</php>