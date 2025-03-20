<?php
session_start();
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'school_management';

$conn = new mysqli($host, $user, $password, $database);

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
    <title>Outstanding Bills</title>
    <link rel="stylesheet" href="outstanding-bill.css">
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
        <div class="container">
            <hr><hr>
            <h2>Outstanding Bills</h2>
    
            <div class="search-section">
                <input type="text" id="searchInput" placeholder="🔍 Search by Reg No or Name">
                <button class="search-btn" onclick="searchBills()">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            
            <table class="bill-table">
                <thead>
                    <tr>
                        <th>Reg No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Paid Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="billTableBody">
                    <?php
                    // Fetch outstanding bills from fee_payments table
                    $sql = "SELECT 
                                student_reg_no AS reg_no, 
                                student_name,
                                student_class AS class, 
                                amount_paid AS outstanding_amount, 
                                payment_date, 
                                'Pending' AS status 
                            FROM fee_payments 
                            WHERE amount_paid > 0"; // Only show unpaid bills
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['reg_no']}</td>
                                    <td>{$row['student_name']}</td>
                                    <td>{$row['class']}</td>
                                    <td>{$row['outstanding_amount']}</td>
                                    <td>{$row['payment_date']}</td>
                                    <td class='pending'>{$row['status']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No outstanding bills found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    
        <script>
            function searchBills() {
                let input = document.getElementById("searchInput").value.toLowerCase();
                let rows = document.querySelectorAll("#billTableBody tr");

                rows.forEach(row => {
                    let regNo = row.cells[0].textContent.toLowerCase();
                    let className = row.cells[1].textContent.toLowerCase();

                    if (regNo.includes(input) || className.includes(input)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }
        </script>
</body>
</php>