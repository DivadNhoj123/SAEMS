<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";
$log_dbname = "event_report";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $conn_log = new PDO("mysql:host=$servername;dbname=$log_dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Redirect to login if session is not set
if (!isset($_SESSION['student_id'])) {
    $_SESSION['alert'] = [
        'title' => 'Session Expired',
        'message' => 'Please log in again.',
        'type' => 'error',
    ];
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form input
    $full_name = trim($_POST['full_name']);
    $password = trim($_POST['password']);
    $department = trim($_POST['department']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    $current_image = $_SESSION['image'];

    // Capture new student_id from the form
    $new_student_id = trim($_POST['student_id']);
    $old_student_id = $_SESSION['student_id']; // Current student_id from session

    // Handle image upload
    $imagePath = $current_image; // Default to current image if no new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $targetDir = "../uploads/";
        $targetFile = $targetDir . basename($image);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validate file type and upload image
        if (in_array($fileType, ['jpg', 'jpeg', 'png'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = basename($image);
            }
        } else {
            $_SESSION['alert'] = [
                'title' => 'Invalid File Type',
                'message' => 'Only JPG, JPEG, and PNG files are allowed.',
                'type' => 'error',
            ];
            header("Location: view-profile.php");
            exit;
        }
    }

    try {
        // Update query
        $sql = "UPDATE system_account 
                SET full_name = ?, department = ?, course = ?, year_level = ?, image = ?, student_id = ?";
        $params = [$full_name, $department, $course, $year_level, $imagePath, $new_student_id];

        // Update password if provided
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = ?";
            $params[] = $hashedPassword;
        }

        $sql .= " WHERE student_id = ?";
        $params[] = $old_student_id;

        // Execute query
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        // Log action
        $action_message = "Updated profile information, including student ID.";

        $stmt_log = $conn_log->prepare("INSERT INTO student_logs (student_id, action) VALUES (?, ?)");
        $stmt_log->execute([$new_student_id, $action_message]);

        // Update session data
        $_SESSION['student_id'] = $new_student_id;
        $_SESSION['full_name'] = $full_name;
        $_SESSION['department'] = $department;
        $_SESSION['course'] = $course;
        $_SESSION['year_level'] = $year_level;
        $_SESSION['image'] = $imagePath;

        // Set success alert
        $_SESSION['alert'] = [
            'title' => 'Profile Updated',
            'message' => 'Your profile has been successfully updated.',
            'type' => 'success',
        ];
        header("Location: view-profile.php");
        exit;
    } catch (PDOException $e) {
        // Set error alert
        $_SESSION['alert'] = [
            'title' => 'Update Failed',
            'message' => 'An error occurred: ' . $e->getMessage(),
            'type' => 'error',
        ];
        header("Location: view-profile.php");
        exit;
    }
}
