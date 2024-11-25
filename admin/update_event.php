<?php
session_start(); // Start the session

// Database credentials for event management and logging
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

// Check database connections
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
    $status = $_POST['status'];
    $student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

    // Ensure student_id is set
    if (empty($student_id)) {
        echo "<script>alert('No session found. Please log in again.'); window.location.href='login.php';</script>";
        exit;
    }

    // Check if updating an existing event
    if (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
        $event_id = $_POST['event_id'];

        // Prepare the update statement
        $update_stmt = $conn1->prepare("UPDATE event SET event_name = ?, event_location = ?, event_schedule = ?, status = ? WHERE id = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("ssssi", $event_name, $event_location, $event_schedule, $status, $event_id);
            if ($update_stmt->execute()) {
                // Log the update action
                $action_message = "Updated event: " . $event_name;
                $stmt_log = $conn2->prepare("INSERT INTO admin_logs (account_name, action) VALUES (?, ?)");
                $stmt_log->bind_param("ss", $student_id, $action_message);
                $stmt_log->execute();
                $stmt_log->close();

                echo "<script>alert('Event updated successfully!'); window.location.href='manage_event.php';</script>";
            } else {
                echo "<script>alert('Error updating event: " . $update_stmt->error . "');</script>";
            }
            $update_stmt->close();
        } else {
            die("Update prepare failed: " . $conn1->error);
        }
    } else {
        // Insert new event if no event_id is provided
        $stmt_event = $conn1->prepare("INSERT INTO event (event_name, event_location, event_schedule, status) VALUES (?, ?, ?, ?)");
        if ($stmt_event) {
            $stmt_event->bind_param("ssss", $event_name, $event_location, $event_schedule, $status);
            $stmt_event->execute();
            $stmt_event->close();

            // Log the insert action
            $action_message = "Updated the event: " . $event_name;
            $stmt_log = $conn2->prepare("INSERT INTO admin_logs (account_name, action) VALUES (?, ?)");
            $stmt_log->bind_param("sss", $student_id, $action_message);
            $stmt_log->execute();
            $stmt_log->close();

            echo "<script>alert('Event added successfully!'); window.location.href='manage_event.php';</script>";
        } else {
            die("Event insert prepare failed: " . $conn1->error);
        }
    }
}

// Close connections
$conn1->close();
$conn2->close();
