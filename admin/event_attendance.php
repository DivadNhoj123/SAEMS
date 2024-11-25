<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">

<?php include '../header/user-header.php'; ?>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include '../sidebar/admin-sidebar.php'; ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <?php include '../header-and-nav/main-header.php'; ?>
            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">SEAMS</h3>
                        <ul class="breadcrumbs mb-3">
                            <li class="nav-home">
                                <a href="dashboard.php">
                                    <i class="icon-home"></i>
                                </a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="manage_event.php">Event Management</a>
                            </li>
                            <li class="separator">
                                <i class="icon-arrow-right"></i>
                            </li>
                            <li class="nav-item">
                                <a href="#">Event Attendance</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <?php
                                            // Database connection
                                            $servername = "localhost";
                                            $username = "root";
                                            $password = "";
                                            $dbname = "event_management";

                                            // Create a new connection
                                            $conn = new mysqli($servername, $username, $password, $dbname);

                                            if ($conn->connect_error) {
                                                die("Connection failed: " . $conn->connect_error);
                                            }

                                            // Fetch data from the `event` table
                                            $sql = "SELECT id, event_name, event_location, event_schedule, status FROM event";
                                            $result = $conn->query($sql);
                                            ?>

                                            <!-- Render the table only once -->
                                            <table id="add-row" class="display table table-striped table-hover">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Event Name</th>
                                                        <th>Venue</th>
                                                        <th>Date</th>
                                                        <th style="width: 10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    // Check if there are any records
                                                    if ($result->num_rows > 0) {
                                                        // Loop through the data and populate the table rows
                                                        while ($row = $result->fetch_assoc()) {
                                                            // Format the date
                                                            $formattedDate = date('M d, Y', strtotime($row['event_schedule']));
                                                            $statusBadge = ($row['status'] === 'Ongoing') ? 'badge-info' : 'badge-secondary';
                                                    ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($row['event_name']) ?></td>
                                                                <td><?= htmlspecialchars($row['event_location']) ?></td>
                                                                <td><?= $formattedDate ?></td>
                                                                <td>
                                                                    <div class="form-button-action">
                                                                        <a href="view-attendance.php?event_id=<?= $row['id'] ?>" class="btn btn-info">
                                                                            View Attendance
                                                                        </a>
                                                                    </div>
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

                                            <?php $conn->close(); ?>
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
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../assets/js/setting-demo2.js"></script>
    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-select"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on("change", function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });

            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $("#addRowButton").click(function() {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        $("#addPosition").val(),
                        $("#addOffice").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
        });
    </script>
</body>

</html>