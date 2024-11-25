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
                                <a href="#">Event Management</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                class="btn btn-primary mb-4 ms-auto"
                                data-bs-toggle="modal"
                                data-bs-target="#addRowModal">
                                <i class="fa fa-calendar me-1"></i>
                                Add Event
                            </button>
                            <a
                                href="event_attendance.php"
                                class="btn btn-primary mb-4 ms-auto">
                                <i class="fa fa-calendar me-1"></i>
                                Event Attendance
                            </a>
                        </div>
                    </div>
                    <?php include '../modals/add-event.php'; ?>

                    <div class="row">
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

                        // Number of records per page (set to 6)
                        $recordsPerPage = 6;

                        // Get the total number of records in the event table
                        $sqlCount = "SELECT COUNT(*) as total FROM event";
                        $resultCount = $conn->query($sqlCount);
                        $rowCount = $resultCount->fetch_assoc();
                        $totalRecords = $rowCount['total'];

                        // Calculate the total number of pages
                        $totalPages = ceil($totalRecords / $recordsPerPage);

                        // Get the current page from the query string, default to 1 if not set
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                        // Calculate the offset for the query (start of records for the current page)
                        $offset = ($page - 1) * $recordsPerPage;

                        // Fetch data from the `event` table with the limit and offset
                        $sql = "SELECT id, event_name, event_location, event_schedule, status FROM event LIMIT $offset, $recordsPerPage";
                        $result = $conn->query($sql);

                        // Array of Bootstrap card classes for random selection
                        $cardColors = ['card-primary', 'card-info', 'card-success', 'card-danger', 'card-warning', 'card-secondary'];

                        // Check if there are any records
                        if ($result->num_rows > 0) {
                            // Loop through the data and populate the cards
                            while ($row = $result->fetch_assoc()) {
                                // Randomly select a card color
                                $randomColor = $cardColors[array_rand($cardColors)];

                                // Format the date
                                $formattedDate = date('M d, Y', strtotime($row['event_schedule']));
                                $statusBadge = ($row['status'] === 'Ongoing') ? 'badge-info' : 'badge-secondary';
                        ?>
                                <div class="col-sm-6 col-md-4 mb-4">
                                    <div class="card <?= $randomColor ?> card-stats card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Icon and Modal Button -->
                                                <div class="col-5">
                                                    <div class="text-center">
                                                        <button class="btn p-0" data-bs-toggle="modal" data-bs-target="#update-event-modal-<?= $row['id'] ?>">
                                                            <i class="fa fa-calendar-alt fa-5x text-white mt-4"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Event Details -->
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

                                <!-- Update Event Modal -->
                                <div class="modal fade" id="update-event-modal-<?= $row['id'] ?>" tabindex="-1" aria-labelledby="updateEventLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateEventLabel">Update Event</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="update_event.php" method="POST">
                                                    <input type="hidden" name="event_id" value="<?= $row['id'] ?>">
                                                    <div class="row">
                                                        <div class="col-md-6 pe-0">
                                                            <div class="form-group form-group-default">
                                                                <label>Event Name</label>
                                                                <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($row['event_name']) ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>Status</label>
                                                                <input type="text" name="status" class="form-control" value="<?= htmlspecialchars($row['status']) ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 pe-0">
                                                            <div class="form-group form-group-default">
                                                                <label>Location</label>
                                                                <input type="text" name="event_location" class="form-control" value="<?= htmlspecialchars($row['event_location']) ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>Event Date</label>
                                                                <input type="date" name="event_schedule" class="form-control" value="<?= htmlspecialchars($row['event_schedule']) ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="submit" name="update_event" class="btn btn-primary">Update</button>
                                                        <button type="button" class="btn btn-danger" onclick="deleteEvent(<?= $row['id'] ?>)">Delete</button>
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

                        <!-- Pagination -->
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
                </div>
            </div>
        </div>

    </div>
    <?php include 'script.php'; ?>

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