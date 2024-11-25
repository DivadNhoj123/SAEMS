<?php
session_start();

// Database connection for `event_management` database
$servername1 = "localhost";
$username1 = "root";
$password1 = "";
$dbname1 = "event_management";

$conn1 = new mysqli($servername1, $username1, $password1, $dbname1);
if ($conn1->connect_error) {
    die("Connection failed: " . $conn1->connect_error);
}

// Get the event ID from the URL (e.g., view_attendance.php?event_id=1)
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if ($event_id > 0) {
    // Corrected query to fetch data based on event_id
    $sql = "
    SELECT 
        e.id AS event_id,
        e.event_name,
        e.event_location,
        e.event_schedule,
        sa.student_id,
        sa.full_name,
        sa.course,
        sa.department,
        ra.registered_date,
        tr.time_in,            -- Add relevant columns from time_records
        tr.time_out            -- No comma here
    FROM event_management.event AS e
    LEFT JOIN event_student_attendance.registered_attendance AS ra 
        ON ra.event_id = e.id
    LEFT JOIN event_management.system_account AS sa 
        ON sa.student_id = ra.student_id
    LEFT JOIN event_student_attendance.time_records AS tr 
        ON tr.ra_id = ra.id   
    WHERE e.id = ?
";


    // Prepare and execute the query
    $stmt = $conn1->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Query preparation failed: " . $conn1->error);
    }
} else {
    echo "<p class='text-center'>Invalid event ID.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include '../header/user-header.php'; ?>

<body>
    <div class="wrapper">
        <?php include '../sidebar/admin-sidebar.php'; ?>
        <div class="main-panel">
            <?php include '../header-and-nav/main-header.php'; ?>
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">SEAMS</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home"><a href="dashboard.php"><i class="icon-home"></i></a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="manage_event.php">Event Management</a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="#">Event Attendance</a></li>
                        </ul>
                    </div>

                    <!-- Attendance Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="attendance-table" class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Full Name</th>
                                            <th>Course</th>
                                            <th>Department</th>
                                            <th>Registration Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($result) && $result->num_rows > 0): ?>
                                            <!-- If records are found, display them -->
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['student_id']) ?></td>
                                                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                                                    <td><?= htmlspecialchars($row['course']) ?></td>
                                                    <td><?= htmlspecialchars($row['department']) ?></td>
                                                    <td>
                                                        <?php if (!empty($row['registered_date'])): ?>
                                                            <?= date('M d, Y', strtotime($row['registered_date'])) ?>
                                                        <?php endif; ?>
                                                    </td>
                                                   <td class="fw-bold text-success"><?= htmlspecialchars(date('F j, Y, h:i A', strtotime($row['time_in']))) ?></td>
                                                    <td class="fw-bold text-danger"><?= htmlspecialchars(date('F j, Y, h:i A', strtotime($row['time_out']))) ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <!-- Display a row with "No events found" if there are no records -->
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    No attendance records found for this event.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php
                            // Close the database connections
                            $stmt->close();
                            $conn1->close();
                            ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#attendance-table').DataTable({
                pageLength: 5,
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Display _MENU_ records per page",
                    zeroRecords: "No attendance records found",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "No attendance available",
                    infoFiltered: "(filtered from _MAX_ total records)"
                }
            });
        });
    </script>


</body>

</html>