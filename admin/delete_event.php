<?php
session_start(); // Start the session at the top of the script

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];

    if (!empty($event_id)) {
        // Prepare the delete statement
        $stmt_event = $conn1->prepare("SELECT event_name FROM event WHERE id = ?");
        $stmt_event->bind_param("i", $event_id);
        $stmt_event->execute();
        $stmt_event->store_result();

        // Check if event exists
        if ($stmt_event->num_rows > 0) {
            $stmt_event->bind_result($event_name);
            $stmt_event->fetch();

            // Proceed with deletion
            $stmt_delete = $conn1->prepare("DELETE FROM event WHERE id = ?");
            $stmt_delete->bind_param("i", $event_id);

            if ($stmt_delete->execute()) {
                // Log the deletion in the logs table
                $action_message = "Deleted event: " . $event_name;
                $student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

                if ($student_id) {
                    $stmt_log = $conn2->prepare("INSERT INTO admin_logs (account_name, action) VALUES (?, ?)");
                    $stmt_log->bind_param("ss", $student_id, $action_message);
                    $stmt_log->execute();
                    $stmt_log->close();
                }

                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt_delete->error]);
            }
            $stmt_delete->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Event not found']);
        }
        $stmt_event->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Event ID is required']);
    }
}

$conn1->close();
$conn2->close();
