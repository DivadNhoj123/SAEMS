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
        <?php include '../sidebar/user-sidebar.php'; ?>
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
                                <a href="#">Profile Settings</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card">
                        <form action="update-profile.php" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Left Side Form -->
                                    <div class="col-md-8">
                                        <div class="card">
                                            <!-- Account Information Form -->
                                            <div class="card-body pt-0">
                                                <div class="row mt-1 g-3">
                                                    <!-- Full Name -->
                                                    <div class="col-md-12">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" class="form-control" name="full_name"
                                                                value="<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>"
                                                                placeholder="Enter your full name" autofocus />
                                                            <label for="full_name">Full Name</label>
                                                        </div>
                                                    </div>

                                                    <!-- Department -->
                                                    <div class="col-md-12">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" class="form-control" name="department"
                                                                value="<?php echo isset($_SESSION['department']) ? $_SESSION['department'] : ''; ?>"
                                                                placeholder="Enter your department" />
                                                            <label for="department">Department</label>
                                                        </div>
                                                    </div>

                                                    <!-- Course -->
                                                    <div class="col-md-6">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" class="form-control" name="course"
                                                                value="<?php echo isset($_SESSION['course']) ? $_SESSION['course'] : ''; ?>"
                                                                placeholder="Enter your course" />
                                                            <label for="course">Course</label>
                                                        </div>
                                                    </div>

                                                    <!-- Year Level -->
                                                    <div class="col-md-6">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" class="form-control" name="year_level"
                                                                value="<?php echo isset($_SESSION['year_level']) ? $_SESSION['year_level'] : ''; ?>"
                                                                placeholder="Enter your year level" />
                                                            <label for="year_level">Year Level</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" class="form-control" name="account_name"
                                                                value="<?php echo isset($_SESSION['student_id']) ? $_SESSION['student_id'] : ''; ?>"
                                                                placeholder="Enter account name" />
                                                            <label for="account_name">Account Name</label>
                                                        </div>
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="col-md-12">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="password" class="form-control" name="password"
                                                                placeholder="Enter password" />
                                                            <label for="email">Password</label>
                                                        </div>
                                                    </div>

                                                    <!-- Save Button -->
                                                    <div class="col-md-12">
                                                        <div class="mt-3 d-flex align-items-start align-items-sm-center">
                                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <img src="../uploads/<?php echo $_SESSION['image']; ?>" alt="Profile Image" class="avatar-img rounded" style="width: 250px; height: 250px; object-fit: cover;">
                                                </div>
                                                <div class="button-wrapper mt-2">
                                                    <label for="upload" class="btn btn-sm btn-primary">
                                                        <span class="text-white">Upload new photo</span>
                                                        <input type="file" id="upload" name="image" accept="image/png, image/jpeg" hidden>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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