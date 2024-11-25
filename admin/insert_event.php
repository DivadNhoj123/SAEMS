<?php
session_start(); // Start the session at the top of the script

// Database credentials
$servername1 = "localhost";
$username1 = "root";
$password1 = "";
$dbname1 = "event_management";

$servername2 = "localhost";
$username2 = "root";
$password2 = "";
$dbname2 = "event_report";

// Establish database connections
$conn1 = new mysqli($servername1, $username1, $password1, $dbname1);
$conn2 = new mysqli($servername2, $username2, $password2, $dbname2);

// Check connection
if ($conn1->connect_error) {
    die("Connection to event_management failed: " . $conn1->connect_error);
}
if ($conn2->connect_error) {
    die("Connection to event_report failed: " . $conn2->connect_error);
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $event_name = $_POST['event_name'];
    $event_location = $_POST['event_location'];
    $event_schedule = $_POST['event_schedule'];
    $event_type = $_POST['event_type'];
    $status = $_POST['status'];
    $student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

    // Ensure student_id is set
    if (empty($student_id)) {
        $_SESSION['alert'] = [
            'title' => 'Error!',
            'message' => 'No session found. Please log in again.',
            'type' => 'error'
        ];
        header('Location: login.php'); // Redirect to login page
        exit;
    }

    // Insert into event table
    $stmt_event = $conn1->prepare("INSERT INTO event (event_name, event_location, event_schedule, status, event_type) VALUES (?, ?, ?, ?, ?)");
    if ($stmt_event) {
        $stmt_event->bind_param("sssss", $event_name, $event_location, $event_schedule, $status, $event_type);
        $stmt_event->execute();
        $stmt_event->close();
    } else {
        $_SESSION['alert'] = [
            'title' => 'Error!',
            'message' => 'Failed to insert event. Please try again.',
            'type' => 'error'
        ];
        header('Location: manage_event.php'); // Redirect to manage events page
        exit;
    }

    // Insert into logs table with current date
    $action_message = "Added new event: " . $event_name;

    $stmt_log = $conn2->prepare("INSERT INTO admin_logs (account_name, action) VALUES (?, ?)");
    if ($stmt_log) {
        $stmt_log->bind_param("ss", $student_id, $action_message); // Only two placeholders needed
        $stmt_log->execute();
        $stmt_log->close();
    } else {
        $_SESSION['alert'] = [
            'title' => 'Error!',
            'message' => 'Failed to log action. Please try again.',
            'type' => 'error'
        ];
        header('Location: manage_event.php'); // Redirect to manage events page
        exit;
    }

    // Set session alert for success
    $_SESSION['alert'] = [
        'title' => 'Success!',
        'message' => 'Event added successfully!',
        'type' => 'success'
    ];

    // Redirect with success message
    header('Location: manage_event.php');
    exit;
}

// Close connections
$conn1->close();
$conn2->close();
