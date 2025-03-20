<?php
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

// Check if ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the expense from the database
    $sql = "DELETE FROM expenses WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Expense deleted successfully!'); window.location.href = 'manage-expenses.php';</script>";
    } else {
        echo "<script>alert('Error deleting expense: " . $conn->error . "'); window.location.href = 'manage-expenses.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'manage-expenses.php';</script>";
}

// Close the database connection
$conn->close();
?>