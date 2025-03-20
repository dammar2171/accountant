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


// Handle form submission
if (isset($_POST['allocate'])) {
    $class_name = $_POST['class_name'];
    $tuition_fee = $_POST['tuition_fee'];
    $transport_fee = $_POST['transport_fee'];
    $lab_fee = $_POST['lab_fee'];
    $sports_fee = $_POST['sports_fee'];
    $other_charges = $_POST['other_charges'];

    // Insert data into the database
    $sql = "INSERT INTO fee_structure (class_name, tuition_fee, transport_fee, lab_fee, sports_fee, other_charges)
            VALUES ('$class_name', $tuition_fee, $transport_fee, $lab_fee, $sports_fee, $other_charges)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Fee structure allocated successfully!');</script>";
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
    <title>Allocate Fee</title>
    <link rel="stylesheet" href="allocate-fee.css">
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
        <div class="container">
            <h2>Allocate Fee Structure</h2>
    
            <div class="fee-form">
                <form action="allocate-fee.php" method="POST">
                    <div class="row">
                        <label>Select Class</label>
                        <select name="class_name" required>
                            <option value="">--select--</option>
                            <option value="Class 1">Class 1</option>
                            <option value="Class 2">Class 2</option>
                            <option value="Class 3">Class 3</option>
                            <option value="Class 4">Class 4</option>
                            <option value="Class 5">Class 5</option>
                        </select>
                    </div>

                    <div class="row">
                        <label>Tuition Fee</label>
                        <input type="number" name="tuition_fee" placeholder="Enter Tuition Fee" required>
                        
                        <label>Transport Fee</label>
                        <input type="number" name="transport_fee" placeholder="Enter Transport Fee" required>
                    </div>

                    <div class="row">
                        <label>Lab Fee</label>
                        <input type="number" name="lab_fee" placeholder="Enter Lab Fee" required>
                        
                        <label>Sports Fee</label>
                        <input type="number" name="sports_fee" placeholder="Enter Sports Fee" required>
                    </div>

                    <div class="row">
                        <label>Other Charges</label>
                        <input type="number" name="other_charges" placeholder="Enter Other Charges" required>
                    </div>

                    <button type="submit" name="allocate" class="save-btn">ALLOCATE</button>
                </form>
            </div>
    
            <h3>Allocated Fee Structure</h3>
            <table>
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Tuition Fee</th>
                        <th>Transport Fee</th>
                        <th>Lab Fee</th>
                        <th>Sports Fee</th>
                        <th>Other Charges</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch data from the database
                    $sql = "SELECT * FROM fee_structure";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['class_name']}</td>
                                    <td>{$row['tuition_fee']}</td>
                                    <td>{$row['transport_fee']}</td>
                                    <td>{$row['lab_fee']}</td>
                                    <td>{$row['sports_fee']}</td>
                                    <td>{$row['other_charges']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No fee structure allocated yet.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</php>