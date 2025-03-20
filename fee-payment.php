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


// Handle form submission for saving fee payment
if (isset($_POST['save_payment'])) {
    $student_reg_no = $_POST['student_reg_no'];
    $student_name = $_POST['student_name']; // Added student_name
    $student_class = $_POST['student_class'];
    $student_type = $_POST['student_type'];
    $fee_term = $_POST['fee_term'];
    $fee_session = $_POST['fee_session'];
    $amount_paid = $_POST['amount_paid'];
    $teller_no = $_POST['teller_no'];

    // Insert data into the database
    $sql = "INSERT INTO fee_payments (student_reg_no, student_name, student_class, student_type, fee_term, fee_session, amount_paid, teller_no)
            VALUES ('$student_reg_no', '$student_name', '$student_class', '$student_type', '$fee_term', '$fee_session', $amount_paid, '$teller_no')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Fee payment recorded successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Payment</title>
    <link rel="stylesheet" href="outstanding-bill.css">
    <link rel="stylesheet" href="fee-payment.css">
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
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Staff Payrolls / Fee Payments</div></div>
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
            <h2>Record Students Fees</h2>
            
            <div class="fee-form">
                <form action="fee-payment.php" method="POST">
                    <div class="row">
                        <label>Student Reg No</label>
                        <input type="text" name="student_reg_no" placeholder="Enter Student Reg Number" required>
                        
                        <label>Student Name</label>
                        <input type="text" name="student_name" placeholder="Enter Student Name" required>
                        
                        <label>Student Class</label>
                        <select name="student_class" required>
                            <option value="">--select--</option>
                            <option value="Class 1">Class 1</option>
                            <option value="Class 2">Class 2</option>
                            <option value="Class 3">Class 3</option>
                            <option value="Class 4">Class 4</option>
                            <option value="Class 5">Class 5</option>
                        </select>
                        
                        <label>Student Type</label>
                        <select name="student_type" required>
                            <option value="">--select--</option>
                            <option value="Regular">Regular</option>
                            <option value="Scholarship">Scholarship</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <label>Fee Term</label>
                        <select name="fee_term" required>
                            <option value="">--select--</option>
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Term 3">Term 3</option>
                        </select>

                        <label>Fee Session</label>
                        <select name="fee_session" required>
                            <option value="">--select--</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                        </select>

                        <label>Amount Paid</label>
                        <input type="number" name="amount_paid" placeholder="Enter Fees Amount" required>
                    </div>

                    <div class="row">
                        <label>Teller No</label>
                        <input type="text" name="teller_no" placeholder="Enter Bank Teller Number" required>
                    </div>

                    <button type="submit" name="save_payment" class="save-btn">SAVE FEE PAYMENT</button>
                </form>
            </div>

            <h3>Select the Appropriate Class, Term, and Session to View Specified School Fee Records</h3>

            <div class="filter-section">
                <form action="fee-payment.php" method="GET">
                    <label>Class</label>
                    <select name="filter_class">
                        <option value="">--select--</option>
                        <option value="Class 1">Class 1</option>
                        <option value="Class 2">Class 2</option>
                        <option value="Class 3">Class 3</option>
                        <option value="Class 4">Class 4</option>
                        <option value="Class 5">Class 5</option>
                    </select>

                    <label>Term</label>
                    <select name="filter_term">
                        <option value="">--select--</option>
                        <option value="Term 1">Term 1</option>
                        <option value="Term 2">Term 2</option>
                        <option value="Term 3">Term 3</option>
                    </select>

                    <label>Session</label>
                    <select name="filter_session">
                        <option value="">--select--</option>
                        <option value="2023-2024">2023-2024</option>
                        <option value="2024-2025">2024-2025</option>
                    </select>

                    <button type="submit" name="filter" class="view-btn">VIEW</button>
                </form>
            </div>

            <div class="no-records">
                <?php
                // Fetch filtered records
                if (isset($_GET['filter'])) {
                    $filter_class = $_GET['filter_class'];
                    $filter_term = $_GET['filter_term'];
                    $filter_session = $_GET['filter_session'];

                    $sql = "SELECT * FROM fee_payments WHERE 1=1";
                    if (!empty($filter_class)) {
                        $sql .= " AND student_class = '$filter_class'";
                    }
                    if (!empty($filter_term)) {
                        $sql .= " AND fee_term = '$filter_term'";
                    }
                    if (!empty($filter_session)) {
                        $sql .= " AND fee_session = '$filter_session'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>Reg No</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Type</th>
                                        <th>Term</th>
                                        <th>Session</th>
                                        <th>Amount Paid</th>
                                        <th>Teller No</th>
                                        <th>Payment Date</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['student_reg_no']}</td>
                                    <td>{$row['student_name']}</td>
                                    <td>{$row['student_class']}</td>
                                    <td>{$row['student_type']}</td>
                                    <td>{$row['fee_term']}</td>
                                    <td>{$row['fee_session']}</td>
                                    <td>{$row['amount_paid']}</td>
                                    <td>{$row['teller_no']}</td>
                                    <td>{$row['payment_date']}</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<p>No Records Found</p>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</php>