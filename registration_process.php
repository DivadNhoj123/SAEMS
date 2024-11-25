<?php
session_start(); // Start the session

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";
$event_dbname = "event_report";

// Establish connections to both databases
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$event_conn = new mysqli($servername, $username, $password, $event_dbname);
if ($event_conn->connect_error) {
    die("Event DB connection failed: " . $event_conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $student_id = $_POST['student_id'];
    $department = $_POST['department'];
    $course = $_POST['course'];
    $year_level = $_POST['year_level'];
    $password = $_POST['password'];
    $role = 1; // Assuming role 1
    $image = '1.jpg';

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Store all data in session
    $_SESSION['full_name'] = $full_name;
    $_SESSION['student_id'] = $student_id;
    $_SESSION['department'] = $department;
    $_SESSION['course'] = $course;
    $_SESSION['year_level'] = $year_level;
    $_SESSION['password'] = $hashed_password; // Storing the hashed password
    $_SESSION['role'] = $role;
    $_SESSION['image'] = $image;

    // Prepare SQL to insert into the `system_account` table
    $stmt = $conn->prepare("INSERT INTO system_account (role, image, full_name, student_id, department, course, year_level, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("isssssss", $role, $image, $full_name, $student_id, $department, $course, $year_level, $hashed_password);

    // Execute and handle success or failure
    if ($stmt->execute()) {
        // Log the event in the event report database
        $action_message = $full_name . " Registered to the System";
        $current_date = date("Y-m-d H:i:s");
        
        $stmt_log = $event_conn->prepare("INSERT INTO student_logs (student_id, action, date) VALUES (?, ?, ?)");
        $stmt_log->bind_param("sss", $student_id, $action_message, $current_date);
        $stmt_log->execute();
        $stmt_log->close();

        // Redirect to dashboard or appropriate page
        $_SESSION['register_message'] = 'Welcome, ' . $full_name . '!';
        $_SESSION['register'] = 'success';
        echo "<script>window.location.href = 'user/dashboard.php';</script>";
    } else {
        echo "<script>alert('Registration failed: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
    $event_conn->close();
}
?>
