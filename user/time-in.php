<?php
session_start();

// Database connection credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_student_attendance";
$logDbName = "event_report"; // The database where student_logs are stored

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ra_id'], $_POST['action'], $_POST['student_id'])) {
    $raId = (int)$_POST['ra_id'];
    $studentId = $_POST['student_id'];
    $eventName = $_POST['event_name'];
    $action = $_POST['action'];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Connect to the logging database
    $connLog = new mysqli($servername, $username, $password, $logDbName);
    if ($connLog->connect_error) {
        die("Logging database connection failed: " . $connLog->connect_error);
    }

    // Prepare SQL based on the action
    if ($action === 'time_in') {
        $sql = "UPDATE time_records SET time_in = NOW() WHERE ra_id = ? AND time_in IS NULL";
        $actionMessage = "Have Time In this event " . $eventName . " on "; // Will concatenate time_in later
    } elseif ($action === 'time_out') {
        $sql = "UPDATE time_records SET time_out = NOW() WHERE ra_id = ? AND time_in IS NOT NULL AND time_out IS NULL";
        $actionMessage = "Have Time Out this event " . $eventName . " on "; // Will concatenate time_out later
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'Invalid action.'
        ];
        header('Location: dashboard.php');
        exit();
    }

    // Prepare and execute the query for time records update
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("i", $raId);

    // Check execution and provide feedback
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Retrieve the updated time_in or time_out value
        $selectSql = "SELECT time_in, time_out FROM time_records WHERE ra_id = ?";
        $selectStmt = $conn->prepare($selectSql);
        $selectStmt->bind_param("i", $raId);
        $selectStmt->execute();
        $result = $selectStmt->get_result();
        $row = $result->fetch_assoc();

        // Concatenate the correct timestamp into the actionMessage
        if ($action === 'time_in' && isset($row['time_in'])) {
            $actionMessage .= $row['time_in']; // Add the time_in value
        } elseif ($action === 'time_out' && isset($row['time_out'])) {
            $actionMessage .= $row['time_out']; // Add the time_out value
        }

        // Log the action in `student_logs`
        $stmtLog = $connLog->prepare(
            "INSERT INTO student_logs (student_id, action) VALUES (?, ?)"
        );
        $stmtLog->bind_param("ss", $studentId, $actionMessage);

        if (!$stmtLog->execute()) {
            throw new Exception("Failed to insert into student_logs: " . $stmtLog->error);
        }

        // Set success alert
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Success!',
            'message' => $action === 'time_in' ? 'Time In recorded successfully!' : 'Time Out recorded successfully!'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => $action === 'time_in' ? 'Failed to record Time In.' : 'Failed to record Time Out.'
        ];
    }

    // Close the statements and connections
    $stmt->close();
    $selectStmt->close();
    $stmtLog->close();
    $conn->close();
    $connLog->close();

    // Redirect back to the dashboard
    header('Location: event.php');
    exit();
} else {
    // If the form was not submitted correctly, redirect back
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Error!',
        'message' => 'Invalid request.'
    ];
    header('Location: dashboard.php');
    exit();
}
?>
