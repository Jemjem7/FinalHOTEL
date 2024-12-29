<?php
// Database credentials
$db_host = 'localhost'; // Assuming localhost for local development
$db_name = 'hotel1_db';
$db_user_name = 'root';
$db_user_pass = '';

// Create a new PDO connection
try {
  $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user_name, $db_user_pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set the error mode to exceptions
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit; // If connection fails, stop execution
}

// Initialize message arrays
$warning_msg = [];
$success_msg = [];

if (isset($_POST['submit'])) {
  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING); // Sanitize input
  $pass = $_POST['pass'];
  $c_pass = $_POST['c_pass'];

  // Check for duplicate usernames
  $stmt = $conn->prepare("SELECT * FROM admins WHERE name = ?");
  $stmt->execute([$name]);

  if ($stmt->rowCount() > 0) {
    $warning_msg[] = 'Username already taken!';
  } elseif ($pass !== $c_pass) {
    $warning_msg[] = 'Passwords do not match!';
  } else {
    // Hash the password using PASSWORD_BCRYPT
    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    // Call the stored procedure to insert new admin
    $stmt = $conn->prepare("CALL sp_insert_admin(?, ?)");
    $stmt->execute([$name, $hashed_pass]);

    if ($stmt->rowCount() > 0) { // Assuming rowCount() works for stored procedures in your setup
      $success_msg[] = 'Registered successfully!';
      header('Location: http://localhost/newWEb/project/admin/register.php');
      exit; // Ensure no further output after redirect
    } else {
      $warning_msg[] = 'Registration failed. Please try again.';
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Additional styles for the login link */
        .login-link {
            display: block; /* Make it a block element to take full width */
            margin-top: 10px; /* Space above the link */
            font-size: 14px; /* Font size */
            color: #007BFF; /* Link color */
            text-decoration: none; /* Remove underline */
            text-align: center; /* Center the link */
        }

        .login-link:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>

<!-- header section starts -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- register section starts -->
<section class="form-container">
    <form action="" method="POST">
        <h3>Register New</h3>
        <input type="text" name="name" placeholder="Enter Username" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" placeholder="Enter Password" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="c_pass" placeholder="Confirm Password" maxlength="20" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Register Now" name="submit" class="btn">
        <!-- Login Link -->
        <a href="http://localhost/newWEb/index.html" class="login-link">Already have an account? Login here</a>
    </form>
</section>
<!-- register section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link -->
<script src="../js/admin_script.js"></script>

<?php
// Display any success or warning messages
if (!empty($warning_msg)) {
    foreach ($warning_msg as $msg) {
        echo "<script>swal('Warning', '$msg', 'warning');</script>";
    }
}
if (!empty($success_msg)) {
    foreach ($success_msg as $msg) {
        echo "<script>swal('Success', '$msg', 'success');</script>";
    }
}
?>

</body>
</html>
