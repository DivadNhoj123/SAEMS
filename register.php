<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Contact Us -Event School Attendance Monitoring System</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/auth/img/favicon.png" rel="icon">
    <link href="assets/auth/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/auth/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/auth/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/auth/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/auth/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/auth/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/auth/css/main.css" rel="stylesheet">

</head>

<body class="index-page about">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="index.html" class="logo d-flex align-items-center">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/auth/img/logo.png" alt=""> -->
                <h1 class="sitename">Event School Attendance Monitoring System</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <!-- <li><a href="contact.php">Contact Us</a></li> -->
                    <li><a href="login.php" class="active">Login</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>
    </header>
    <main class="main">

        <!-- Login Section -->
        <section id="contact" class="contact section">

            <div class="container">
                <div class="row align-items-xl-center gy-2">
                    <div class="col-xl-12">
                        <div class="row gy-4 icon-boxes">
                            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200"></div>
                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row p-2">
                                            <h3>Register</h3>
                                            <div class="col-md-12">
                                                <form action="registration_process.php" method="post">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label>Full name:</label>
                                                            <input type="text" class="form-control p-2" name="full_name" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="mt-2">Student ID:</label>
                                                            <input type="text" class="form-control p-2" name="student_id" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="mt-2">Department:</label>
                                                            <input type="text" class="form-control p-2" name="department" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="mt-2">Course:</label>
                                                                <input type="text" class="form-control p-2" name="course" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="mt-2">Year Level:</label>
                                                                <input type="text" class="form-control p-2" name="year_level" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="mt-2">Password:</label>
                                                            <input type="text" class="form-control p-2" name="password" />
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mt-3">Register</button>
                                                    </div>
                                                </form>
                                                <br>
                                                <p>
                                                    <a href="forgot_password.php">Forgot your password?</a>
                                                    <span class="float-end">
                                                        Don't have an account? <a href="login.php">Sign In</a>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- End Icon Box -->
                            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /Login Section -->

    </main>

    <footer id="footer" class="footer dark-background">

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Event School Attendance Monitoring System</strong> <span>All Rights Reserved</span></p>
            <div class="credits">
                Designed by <a href="#">Lykzelle Mae Padasas</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/auth/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/auth/vendor/php-email-form/validate.js"></script>
    <script src="assets/auth/vendor/aos/aos.js"></script>
    <script src="assets/auth/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/auth/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/auth/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/auth/js/main.js"></script>
    <script src="assets/auth/js/sweetalert/sweetalert.min.js"></script>
</body>

</html>