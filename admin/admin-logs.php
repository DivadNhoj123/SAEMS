<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Manage Event - Student Event Attendance Management System</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link
        rel="icon"
        href="../assets/img/kaiadmin/favicon.ico"
        type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="../assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include '../sidebar/admin-sidebar.php'; ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="../index.html" class="logo">
                            <img
                                src="../assets/img/kaiadmin/logo_light.svg"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <?php include '../header-and-nav/main-header.php'; ?>
                <!-- End Navbar -->
            </div>

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
                                <a href="#">System Logs</a>
                            </li>
                        </ul>
                    </div>
                    <?php
                    $servername2 = "localhost";
                    $username2 = "root";
                    $password2 = "";
                    $dbname2 = "event_report";

                    // Connect to the event_report database
                    $conn2 = new mysqli($servername2, $username2, $password2, $dbname2);

                    // Check connection
                    if ($conn2->connect_error) {
                        die("Connection failed: " . $conn2->connect_error);
                    }

                    // Pagination Variables
                    $records_per_page = 5;
                    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($current_page - 1) * $records_per_page;

                    // Fetch total number of logs for pagination
                    $sql_count = "SELECT COUNT(*) AS total_logs FROM admin_logs";
                    $result_count = $conn2->query($sql_count);
                    $total_logs = $result_count->fetch_assoc()['total_logs'];
                    $total_pages = ceil($total_logs / $records_per_page);

                    // Fetch logs data with LIMIT and OFFSET
                    $sql_logs = "SELECT account_name, action, date 
                    FROM admin_logs 
                    WHERE account_name = 'admin' 
                    ORDER BY date DESC 
                    LIMIT $records_per_page OFFSET $offset";

                    $result_logs = $conn2->query($sql_logs);
                    ?>

                    <!-- Card Structure to Display Logs -->
                    <div class="card">
                        <div class="card-body">
                            <?php if ($result_logs->num_rows > 0): ?>
                                <?php while ($log = $result_logs->fetch_assoc()): ?>
                                    <div class="d-flex">
                                        <div class="avatar avatar-online">
                                            <span class="avatar-title rounded-circle border border-white bg-info">
                                                <?php echo strtoupper(substr($log['account_name'], 0, 1)); ?>
                                            </span>
                                        </div>
                                        <div class="flex-1 ms-3 pt-1">
                                            <h6 class="text-uppercase fw-bold mb-1">
                                                <?php
                                                if (isset($_SESSION['account_name']) && $_SESSION['account_name'] === $log['account_name']) {
                                                    echo "You"; // Display "You" if the student_id matches the logged-in user
                                                } else {
                                                    echo htmlspecialchars($log['account_name']); // Otherwise, display the student_id
                                                }
                                                ?>
                                            </h6>
                                            <span class="text-muted text-capitalize">
                                                <?php
                                                // Retrieve and sanitize the log action
                                                $action = htmlspecialchars($log['action']);

                                                // Replace specific words with colored spans using regular expressions
                                                $action = preg_replace('/\b(updated)\b/i', '<span class="text-warning">updated</span>', $action);
                                                $action = preg_replace('/\b(added)\b/i', '<span class="text-info">added</span>', $action);
                                                $action = preg_replace('/\b(deleted)\b/i', '<span class="text-danger">deleted</span>', $action);

                                                // Add color for specific actions like login and logout
                                                $action = preg_replace('/\b(logged in)\b/i', '<span class="text-success">logged in</span>', $action);
                                                $action = preg_replace('/\b(logged out)\b/i', '<span class="text-danger">logged out</span>', $action);
                                                $action = preg_replace('/\b(registered)\b/i', '<span class="text-info">registered</span>', $action);

                                                // Output the transformed action text
                                                echo $action;
                                                ?>
                                            </span>

                                        </div>
                                        <div class="float-end pt-1">
                                            <small class="text-muted">
                                                <?php echo date("g:i A", strtotime($log['date'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="separator-dashed"></div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-muted">No logs found.</p>
                            <?php endif; ?>

                            <!-- Pagination Controls -->
                            <div class="demo float-end">
                                <ul class="pagination pg-primary">
                                    <!-- Previous Button -->
                                    <?php if ($current_page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">«</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Previous">
                                                <span aria-hidden="true">«</span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Page Number Links -->
                                    <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                                        <li class="page-item <?php echo ($page == $current_page) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Next Button -->
                                    <?php if ($current_page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">»</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    <?php else: ?>
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true">»</span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php
                    $conn2->close(); // Close the database connection
                    ?>

                </div>

            </div>
        </div>

    </div>
    <?php include 'script.php'; ?>
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
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <script>
        function deleteEvent(eventId) {
            swal({
                title: "Are you sure?",
                text: "This event will be permanently deleted!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "No, cancel!",
                        visible: true,
                        className: "btn btn-danger",
                    },
                    confirm: {
                        text: "Yes, delete it!",
                        className: "btn btn-success",
                    },
                },
            }).then((willDelete) => {
                if (willDelete) {
                    // Send AJAX request to delete the event
                    $.ajax({
                        url: 'delete_event.php',
                        type: 'POST',
                        data: {
                            event_id: eventId
                        },
                        success: function(response) {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                swal("Event deleted successfully!", {
                                    icon: "success",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-success",
                                        },
                                    },
                                }).then(() => {
                                    // Reload the page to refresh the event list
                                    location.reload();
                                });
                            } else {
                                swal("Error deleting event: " + res.message, {
                                    icon: "error",
                                    buttons: {
                                        confirm: {
                                            className: "btn btn-danger",
                                        },
                                    },
                                });
                            }
                        },
                        error: function() {
                            swal("Error connecting to server", {
                                icon: "error",
                                buttons: {
                                    confirm: {
                                        className: "btn btn-danger",
                                    },
                                },
                            });
                        },
                    });
                } else {
                    swal("Your event is safe!", {
                        buttons: {
                            confirm: {
                                className: "btn btn-success",
                            },
                        },
                    });
                }
            });
        }
    </script>
</body>

</html>