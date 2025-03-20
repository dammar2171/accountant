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

// Check if debtor ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input to prevent SQL injection

    // SQL query to delete the debtor
    $sql = "DELETE FROM debtors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Debtor deleted successfully!');
                window.location.href = 'manage-debtor.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting debtor!');
                window.location.href = 'manage-debtor.php';
              </script>";
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Invalid request!');
            window.location.href = 'manage-debtor.php';
          </script>";
}

$conn->close();
?>
