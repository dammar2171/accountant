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


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $due_date = $_POST['due-date'];

    // Insert data into the database
    $sql = "INSERT INTO expenses (category, amount, description, due_date) VALUES ('$category', '$amount', '$description', '$due_date')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Fetch expenses from the database
$sql = "SELECT * FROM expenses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Expenses</title>
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
        <p><?php echo $user["full_name"]; ?> <br>Accountant</p></div>
        <div class="mid2"><p id="p1"><i class="bi bi-mortarboard-fill cap-icon"></i>SHREE SHISHU VIDHYA MANDIR<br></p><div class="home"><i class="bi bi-house-door-fill"></i> / Dashboard / Manage Expenses</div></div>
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
                <h1>Manage Expenses</h1>
            </header>
            <main>
                <form id="expense-form" method="POST" action="">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required>
                    
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" required>
                    
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" required>
                    
                    <label for="due-date">Due Date:</label>
                    <input type="date" id="due-date" name="due-date" required>
                    
                    <div class="expenses-container">
                        <button type="submit" class="add-expense-btn">Add Expense</button>
                    </div>
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "<td>" . $row['category'] . "</td>";
        echo "<td>" . $row['amount'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>" . $row['due_date'] . "</td>";
        echo "<td>
        <button>        <a href='edit-expense.php?id=" . $row['id'] . "' class='btn'>Edit</a></button>
        <button>  <a href='delete-expense.php?id=" . $row['id'] . "' class='btn' onclick='return confirm(\"Are you sure you want to delete this expense?\");'>Delete</a>
      </button>
        </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No expenses found.</td></tr>";
}
?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</body>
</html>