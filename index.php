<?php
session_start();

if (isset($_SESSION['notify_message']) && isset($_SESSION['notify_type'])) {
  $notify_message = $_SESSION['notify_message'];
  $notify_type = $_SESSION['notify_type'];

  // Clear the session notification data
  unset($_SESSION['notify_message']);
  unset($_SESSION['notify_type']);
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";
$log_dbname = "event_report"; // The database for logs

// Create connection for event_management database
$conn = new mysqli($servername, $username, $password, $dbname);

// Create connection for event_report (log) database
$conn_log = new mysqli($servername, $username, $password, $log_dbname);

if ($conn->connect_error || $conn_log->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $student_id = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (empty($student_id) || empty($password)) {
    $_SESSION['notify_message'] = 'Please Enter Student ID and Password';
    $_SESSION['notify_type'] = 'danger';
    echo "<script>
  window.location.href = 'index.php';
</script>";
    exit;
  }

  $stmt = $conn->prepare("SELECT * FROM system_account WHERE student_id = ?");
  $stmt->bind_param("s", $student_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verify the hashed password
    if (password_verify($password, $user['password'])) {
      $_SESSION['image'] = $user['image'];
      $_SESSION['student_id'] = $user['student_id'];
      $_SESSION['full_name'] = $user['full_name'];
      $_SESSION['department'] = $user['department'];
      $_SESSION['course'] = $user['course'];
      $_SESSION['year_level'] = $user['year_level'];
      $_SESSION['role'] = $user['role'];

      // Log the login action
      $action_message = "Logged in";

      $stmt_log = $conn_log->prepare("INSERT INTO student_logs (student_id, action) VALUES (?, ?)");
      $stmt_log->bind_param("ss", $_SESSION['student_id'], $action_message);
      $stmt_log->execute();

      $action_message = "Logged in";
      $current_date = date("Y-m-d H:i:s");

      $stmt_log = $conn_log->prepare("INSERT INTO admin_logs (account_name, action) VALUES (?, ?)");
      $stmt_log->execute([$student_id, $action_message]);
      $stmt_log->execute();

      if ($_SESSION['role'] == 0) {
        $_SESSION['notify_message'] = 'Welcome back Admin!';
        $_SESSION['notify_type'] = 'success';
        echo "<script>
  window.location.href = 'admin/dashboard.php';
</script>";
      } elseif ($_SESSION['role'] == 1) {
        $_SESSION['notify_message'] = 'Welcome back! ' . $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['notify_type'] = 'success';
        echo "<script>
  window.location.href = 'user/dashboard.php';
</script>";
      } else {
        $_SESSION['notify_message'] = 'Unknown role. Please contact support.';
        $_SESSION['notify_type'] = 'warning';
        echo "<script>
  window.location.href = 'index.php';
</script>";
      }
    } else {
      $_SESSION['notify_message'] = 'Invalid Password.';
      $_SESSION['notify_type'] = 'danger';
      echo "<script>
  window.location.href = 'index.php';
</script>";
    }
  } else {
    $_SESSION['notify_message'] = 'Invalid Student ID.';
    $_SESSION['notify_type'] = 'danger';
    echo "<script>
  window.location.href = 'index.php';
</script>";
  }

  $stmt->close();
  $conn->close();
}
?>


<!doctype html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/auth/assets/"
  data-template="vertical-menu-template-free"
  data-style="light">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Login Basic - Pages | Materio - Bootstrap Material Design Admin Template</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="assets/auth/assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https:/fonts.googleapis.com" />
  <link rel="preconnect" href="https:/fonts.gstatic.com" crossorigin />
  <link
    href="https:/fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="assets/auth/assets/vendor/fonts/remixicon/remixicon.css" />

  <!-- Menu waves for no-customizer fix -->
  <link rel="stylesheet" href="assets/auth/assets/vendor/libs/node-waves/node-waves.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="assets/auth/assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="assets/auth/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="assets/auth/assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="assets/auth/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="assets/auth/assets/vendor/css/pages/page-auth.css" />

  <!-- Helpers -->
  <script src="assets/auth/assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="assets/auth/assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->

  <div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-6 mx-4">
        <!-- Login -->
        <div class="card p-7">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-5">

          </div>
          <!-- /Logo -->

          <div class="card-body mt-1">
            <form id="formAuthentication" class="mb-5" action="index.php" method="POST">
              <div class="form-floating form-floating-outline mb-5">
                <input
                  type="text"
                  class="form-control"
                  name="username"
                  placeholder="Enter your email or username"
                  autofocus />
                <label for="email">Email or Username</label>
              </div>
              <div class="mb-5">
                <div class="form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input
                        type="password"
                        class="form-control"
                        name="password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="password" />
                      <label for="password">Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line ri-20px"></i></span>
                  </div>
                </div>
              </div>
              <div class="mb-5">
                <button class="btn btn-primary d-grid w-100" type="submit">login</button>
              </div>
            </form>

            <p class="text-center mb-5">
              <span>New on our platform?</span>
              <a href="register.php">
                <span>Create an account</span>
              </a>
            </p>
          </div>
        </div>
        <!-- /Login -->
      </div>
    </div>
  </div>

  <!-- / Content -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/auth/assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/auth/assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/auth/assets/vendor/js/bootstrap.js"></script>
  <script src="assets/auth/assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="assets/auth/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/auth/assets/vendor/js/menu.js"></script>
  <script src="assets/auth/assets/js/plugin/sweetalert/sweetalert.min.js"></script>


  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->
  <script src="assets/auth/assets/js/main.js"></script>
  <script>
    <?php if (isset($notify_message) && isset($notify_type)) : ?>
      // Map the notify type to SweetAlert icon and button class
      let swalIcon;
      let swalClass;

      switch ('<?php echo $notify_type; ?>') {
        case 'success':
          swalIcon = 'success';
          swalClass = 'btn btn-success';
          break;
        case 'error':
        case 'danger':
          swalIcon = 'error';
          swalClass = 'btn btn-danger';
          break;
        case 'warning':
          swalIcon = 'warning';
          swalClass = 'btn btn-warning';
          break;
        case 'info':
          swalIcon = 'info';
          swalClass = 'btn btn-info';
          break;
        default:
          swalIcon = 'info'; // Default to 'info' for unrecognized types
          swalClass = 'btn btn-primary';
      }

      // Trigger SweetAlert
      swal({
        title: swalIcon === 'success' ? "Success!" : "Notification",
        text: '<?php echo $notify_message; ?>',
        icon: swalIcon,
        buttons: false,
        timer: 2000, // Optional: Auto-close after 2 seconds
      });
    <?php endif; ?>
  </script>

  </script>


  <!-- Page JS -->
</body>

</html>