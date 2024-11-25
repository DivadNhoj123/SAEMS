<?php
include '../db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_event'])) {
    // Fetch form data
    $eventId = (int)$_POST['event_id']; // Ensure integer for IDs
    $studentId = trim($_POST['student_id']);
    $eventName = trim($_POST['event_name']);

    // Database connections
    $connAttendance = connectEventStudentAttendanceDB();
    $connLog = connectLogs();

    // Start transaction for event attendance DB
    $connAttendance->begin_transaction();

    try {
        // Insert into `registered_attendance`
        $stmt1 = $connAttendance->prepare(
            "INSERT INTO registered_attendance (event_id, student_id, registered_date) VALUES (?, ?, NOW())"
        );
        $stmt1->bind_param("is", $eventId, $studentId);

        if (!$stmt1->execute()) {
            throw new Exception("Failed to insert into registered_attendance: " . $stmt1->error);
        }

        // Get the inserted `id` for `time_records`
        $raId = $connAttendance->insert_id;

        // Insert into `time_records`
        $stmt2 = $connAttendance->prepare(
            "INSERT INTO time_records (ra_id) VALUES (?)"
        );
        $stmt2->bind_param("i", $raId);

        if (!$stmt2->execute()) {
            throw new Exception("Failed to insert into time_records: " . $stmt2->error);
        }

        // Log the action in `student_logs`
        $actionMessage = "Have Registered for the event: " . $eventName;
        $stmtLog = $connLog->prepare(
            "INSERT INTO student_logs (student_id, action) VALUES (?, ?)"
        );
        $stmtLog->bind_param("ss", $studentId, $actionMessage);

        if (!$stmtLog->execute()) {
            throw new Exception("Failed to insert into student_logs: " . $stmtLog->error);
        }

        // Commit transaction
        $connAttendance->commit();

        // Set success alert
        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Event registration successful!'
        ];
    } catch (Exception $e) {
        // Rollback transaction on error
        $connAttendance->rollback();
        error_log("Error during event registration: " . $e->getMessage());

        // Set error alert
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'An unexpected error occurred during event registration.'
        ];
    } finally {
        // Close statements and connections
        $stmt1->close();
        $stmt2->close();
        $stmtLog->close();
        $connAttendance->close();
        $connLog->close();
    }

    header('Location: event.php');
    exit();
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
                    <div class="page-header">
                        <h3 class="fw-bold">SEAMS</h3>
                        <ul class="breadcrumbs">
                            <li class="nav-home">
                                <a href="dashboard.php">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Event List</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <?php
                                $recordsPerPage = 6;
                                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                                $offset = ($page - 1) * $recordsPerPage;

                                $conn = new mysqli("localhost", "root", "", "event_management");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                $totalRecordsResult = $conn->query("SELECT COUNT(*) AS total FROM event");
                                $totalRecords = $totalRecordsResult->fetch_assoc()['total'];
                                $totalPages = ceil($totalRecords / $recordsPerPage);

                                $sql = "SELECT id, event_name, event_location, event_schedule, status FROM event LIMIT $offset, $recordsPerPage";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $formattedDate = date('M d, Y', strtotime($row['event_schedule']));
                                        $statusBadge = ($row['status'] === 'Ongoing') ? 'badge-info' : 'badge-secondary';
                                ?>
                                        <div class="col-sm-6 col-md-4">
                                            <div class="card card-stats card-primary card-round">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <div class="text-center">
                                                                <button class="btn p-0" data-bs-toggle="modal" data-bs-target="#update-event-modal-<?= $row['id'] ?>">
                                                                    <i class="fa fa-calendar-alt fa-5x text-white mt-4"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col-7 col-stats">
                                                            <div class="numbers">
                                                                <p class="card-category"><?= htmlspecialchars($row['event_name']) ?></p>
                                                                <small><?= $formattedDate ?></small>
                                                                <br>
                                                                <span class="badge <?= $statusBadge ?>"><?= htmlspecialchars($row['status']) ?></span> |
                                                                <span class="badge badge-success"><?= htmlspecialchars($row['event_location']) ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal code remains the same -->
                                        <div class="modal fade" id="update-event-modal-<?= $row['id'] ?>" tabindex="-1" aria-labelledby="updateEventLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateEventLabel">Register Event</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="POST">
                                                            <div class="modal-body">
                                                                <p class="small">Register your desired event by simply clicking the register button!</p>
                                                                <input type="hidden" name="event_id" value="<?= $row['id'] ?>" />
                                                                <input type="hidden" name="student_id" value="<?= isset($_SESSION['student_id']) ? $_SESSION['student_id'] : '' ?>" />

                                                                <div class="row">
                                                                    <div class="col-sm-12 mb-3">
                                                                        <div class="form-group form-group-default">
                                                                            <label>Event Name</label>
                                                                            <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($row['event_name']) ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3 pe-0">
                                                                        <div class="form-group form-group-default">
                                                                            <label>Venue</label>
                                                                            <input type="text" class="form-control" value="<?= htmlspecialchars($row['event_location']) ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <div class="form-group form-group-default">
                                                                            <label>Date</label>
                                                                            <input type="date" class="form-control" value="<?= date('Y-m-d', strtotime($row['event_schedule'])) ?>" readonly />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer border-0">
                                                                <button type="submit" name="register_event" class="btn btn-primary">Register</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo '<p class="text-center">No events found.</p>';
                                }

                                $conn->close();
                                ?>
                             <div class="demo float-start">
                                <ul class="pagination pg-primary">
                                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true">«</span>
                                        </a>
                                    </li>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true">»</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                           
                            <?php
                            if (!isset($_SESSION['student_id'])) {
                                die("Student ID is not available in the session.");
                            }

                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname1 = "event_student_attendance";
                            $dbname2 = "event_management";


                            $conn1 = new mysqli($servername, $username, $password, $dbname1);
                            if ($conn1->connect_error) {
                                die("Connection to `event_student_attendance` failed: " . $conn1->connect_error);
                            }

                            $conn2 = new mysqli($servername, $username, $password, $dbname2);
                            if ($conn2->connect_error) {
                                die("Connection to `event_management` failed: " . $conn2->connect_error);
                            }

                            $student_id = trim($_SESSION['student_id']);

                            $sql = "
                            SELECT 
                                tr.time_in,
                                tr.time_out,
                                e.event_name,
                                e.event_location,
                                e.event_schedule,
                                e.event_type,
                                tr.ra_id,
                                ra.student_id
                            FROM 
                                time_records AS tr
                            INNER JOIN 
                                registered_attendance AS ra
                            ON 
                                tr.ra_id = ra.id
                            INNER JOIN 
                                event_management.event AS e
                            ON 
                                ra.event_id = e.id
                            WHERE 
                                ra.student_id = ? AND tr.time_out IS NULL
                        ";


                            if ($stmt = $conn1->prepare($sql)) {
                                // Bind the student_id parameter
                                $stmt->bind_param("s", $student_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                            } else {
                                die("Query preparation failed: " . $conn1->error);
                            }
                            ?>
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Venue</th>
                                        <th>Event Schedule</th>
                                        <th>Event Type</th>
                                        <th style="width: 15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Check if there are any records
                                    if ($result->num_rows > 0) {
                                        // Loop through the data and populate the table rows
                                        while ($row = $result->fetch_assoc()) {
                                            // Format the date
                                    ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['event_name']) ?></td>
                                                <td><?= htmlspecialchars($row['event_location']) ?></td>
                                                <td><?= htmlspecialchars($row['event_schedule']) ?></td>
                                                <td><?= htmlspecialchars($row['event_type']) ?></td>
                                                <td>
                                                    <form action="time-in.php" method="POST">
                                                        <input type="hidden" name="ra_id" value="<?= htmlspecialchars($row['ra_id']) ?>">
                                                        <input type="hidden" name="student_id" value="<?= htmlspecialchars($row['student_id']) ?>">
                                                        <input type="hidden" name="event_name" value="<?= htmlspecialchars($row['event_name']) ?>">
                                                        <?php if (empty($row['time_in'])) { ?>
                                                            <button type="submit" name="action" value="time_in" class="btn btn-success btn-sm">Time In</button>
                                                        <?php } elseif (empty($row['time_out'])) { ?>
                                                            <button type="submit" name="action" value="time_out" class="btn btn-danger btn-sm">Time Out</button>
                                                        <?php } ?>
                                                    </form>

                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">No events found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'script.php'; ?>
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
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

</body>

</html>