<?php
session_start();

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
    ra_id
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
    ra.student_id = ?
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
<!DOCTYPE html>
<html lang="en">

<?php include '../header/user-header.php'; ?>

<body>
    <div class="wrapper">
        <?php include '../sidebar/user-sidebar.php'; ?>
        <div class="main-panel">
            <?php include '../header-and-nav/main-header.php'; ?>
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">SEAMS</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home"><a href="dashboard.php"><i class="icon-home"></i></a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="#">My Event</a></li>
                        </ul>
                    </div>

                    <!-- Attendance Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="add-row" class="display table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Event Name</th>
                                            <th>Venue</th>
                                            <th>Event Schedule</th>
                                            <th>Event Type</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
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
                                                    <td class="fw-bold text-success"><?= htmlspecialchars(date('F j, Y, h:i A', strtotime($row['time_in']))) ?></td>
                                                    <td class="fw-bold text-danger"><?= htmlspecialchars(date('F j, Y, h:i A', strtotime($row['time_out']))) ?></td>
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

                            <?php
                            // Close the database connections
                            if (isset($stmt)) {
                                $stmt->close();
                            }
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
            $('#add-row').DataTable({
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