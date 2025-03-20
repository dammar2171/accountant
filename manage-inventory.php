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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST["item_name"];
    $quantity = $_POST["quantity"];
    $purchase_date = $_POST["purchase_date"];

    $sql = "INSERT INTO inventory (item_name, quantity, purchase_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $item_name, $quantity, $purchase_date);

    if ($stmt->execute()) {
        header("Location: manage-inventory.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch inventory items
$sql = "SELECT * FROM inventory ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="outstanding-bill.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .containerINV {
            max-width: 1300px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 130px;
            margin-left: 230px;
            max-height: 550px;
            overflow-y: auto;
        }
        .profile-pic1 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-top: 10px;
            margin-bottom: 10px;
            border: 3px solid #008066;
        }

        h2, h3 { text-align: center; color: #333; }
        form { display: flex; flex-direction: column; gap: 10px; }
        label { font-weight: bold; color: #555; }
        input { padding: 8px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px; background-color: #008066; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #05977a8f; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #008066; color: white; }
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


    <!-- Sidebar & Navigation -->
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
                <li><a href="calender.php"><i class="bi bi-calendar-fill"></i>Calendar</a></li>
                <li><a href="password-reset.php"><i class="bi bi-key-fill"></i>Password Reset</a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-in-right"></i>Logout</a></li>
            </ul>
        </div>

        <!-- Manage Inventory Section -->
        <div class="containerINV">
            <hr><hr>
            <h2>Manage Inventory</h2>
            <form method="POST" action="manage-inventory.php">
                <label for="item-name">Item Name:</label>
                <input type="text" id="item-name" name="item_name" required>

                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required>

                <label for="purchase-date">Purchase Date:</label>
                <input type="date" id="purchase-date" name="purchase_date" required>

                <button type="submit">Add Item</button>
            </form>

            <h3>Inventory List</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Purchase Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["item_name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                            <td><?php echo htmlspecialchars($row["purchase_date"]); ?></td>
                            <td><a href="delete-inventory.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure?');">Remove</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php $conn->close(); ?>
