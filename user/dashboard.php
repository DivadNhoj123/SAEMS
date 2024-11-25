<?php
session_start();

// Handle session notifications
$notify_message = $notify_type = $register_message = null;

if (isset($_SESSION['notify_message'], $_SESSION['notify_type'])) {
    $notify_message = $_SESSION['notify_message'];
    $notify_type = $_SESSION['notify_type'];

    // Clear session notification data after use
    unset($_SESSION['notify_message'], $_SESSION['notify_type']);
}

if (isset($_SESSION['register_message'])) {
    $register_message = $_SESSION['register_message'];

    // Clear session registration message after use
    unset($_SESSION['register_message']);
}

// Redirect to login if user is not authenticated
if (!isset($_SESSION['full_name'])) {
    header("Location: login.php");
    exit();
}

// Access user information
$full_name = $_SESSION['full_name'];
$role = $_SESSION['role'];
$student_id = $_SESSION['student_id'];

// Display a welcome message if available
if (isset($_SESSION['register_message'])) {
    echo '<p>' . $_SESSION['register_message'] . '</p>';
    unset($_SESSION['register_message']); // Clear the message after showing it
}

// Database connection
$host = 'localhost';
$dbname = 'event_management';
$username = 'root'; // Your DB username
$password = '';     // Your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query counts based on the status
    $ongoingCount = $pdo->query("SELECT COUNT(*) FROM event WHERE status = 'ongoing'")->fetchColumn();
    $upcomingCount = $pdo->query("SELECT COUNT(*) FROM event WHERE status = 'upcoming'")->fetchColumn();
    $endedCount = $pdo->query("SELECT COUNT(*) FROM event WHERE status = 'done'")->fetchColumn();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include '../header/user-header.php'; ?>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include '../sidebar/user-sidebar.php'; ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <!-- Main Header -->
            <?php include '../header-and-nav/main-header.php'; ?>
            <!-- End Main Header -->

            <div class="container">
                <div class="page-inner">
                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Dashboard</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Ongoing Event</p>
                                                <h4 class="card-title"><?php echo $ongoingCount; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Upcoming Event</p>
                                                <h4 class="card-title"><?php echo $upcomingCount; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                                <i class="fas fa-calendar-times"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Ended Event</p>
                                                <h4 class="card-title"><?php echo $endedCount; ?></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include 'script.php'; ?>
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <script>
        <?php if (isset($_SESSION['alert'])) : ?>
            swal({
                title: "<?= $_SESSION['alert']['title'] ?>",
                text: "<?= $_SESSION['alert']['message'] ?>",
                icon: "<?= $_SESSION['alert']['type'] ?>",
                buttons: {
                    confirm: {
                        text: "OK",
                        className: "btn btn-<?= $_SESSION['alert']['type'] === 'success' ? 'success' : 'danger' ?>"
                    }
                }
            });
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </script>
    <script>
        // Display general notifications
        <?php if (!empty($notify_message) && !empty($notify_type)) : ?>
            $.notify({
                message: '<?php echo addslashes($notify_message); ?>', // Add slashes for escaping quotes
                title: "Login Successfully",
                icon: "fa fa-check-circle"
            }, {
                type: '<?php echo addslashes($notify_type); ?>', // Escape quotes
                placement: {
                    from: "top",
                    align: "center"
                },
                time: 1000,
                delay: 2000,
            });
        <?php endif; ?>

        // Display registration success notification
        <?php if (!empty($register_message)) : ?>
            $.notify({
                message: '<?php echo addslashes($register_message); ?>',
                title: "Registration Successful",
                icon: "fa fa-check-circle"
            }, {
                type: "success", // Default type for registration
                placement: {
                    from: "top",
                    align: "center"
                },
                time: 1000,
                delay: 2000,
            });
        <?php endif; ?>
    </script>

</body>

</html>