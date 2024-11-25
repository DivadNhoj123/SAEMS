<?php
session_start();
// Include database connection (assuming it's already available)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";
$log_dbname = "event_report"; // The database for logs

try {
    // Establish PDO connection for the event_management database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Establish PDO connection for the event_report database (for logs)
    $conn_log = new PDO("mysql:host=$servername;dbname=$log_dbname", $username, $password);
    $conn_log->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if user is logged in
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    // Insert logout action into logs table in the event_report database
    $action_message = "Logged out";
   
    // Insert log entry
    $stmt_log = $conn_log->prepare("INSERT INTO admin_logs (account_name, action) VALUES (?, ?)");
    $stmt_log->execute([$student_id, $action_message]);

    $stmt_log = $conn_log->prepare("INSERT INTO student_logs (student_id, action) VALUES (?, ?)");
    $stmt_log->execute([$student_id, $action_message]);

    // Clear all session data and destroy the session
    session_unset();
    session_destroy();

    // Redirect to login page
    header("Location: index.php");
    exit;
} else {
    // If no session exists, redirect to login page
    header("Location: index.php");
    exit;
}
?>
