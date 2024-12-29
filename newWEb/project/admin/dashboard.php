<?php
include '../components/connect.php';

// Check if admin is logged in using the cookie
if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    // Redirect to login page if not logged in
    header('location: login.php');
    exit; // Ensure no further code execution after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!-- Header Section Starts -->
<?php include '../components/admin_header.php'; ?>
<!-- Header Section Ends -->

<!-- Dashboard Section Starts -->
<section class="dashboard">
    <h1 class="heading">Dashboard</h1>

    <div class="box-container">
        <!-- Admin Profile Box -->
        <div class="box">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <h3>Welcome!</h3>
            <p><?= htmlspecialchars($fetch_profile['name']); ?></p>
            <a href="update.php" class="btn">Update Profile</a>
        </div>

        <!-- Total Bookings Box -->
        <div class="box">
            <?php
            $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
            $select_bookings->execute();
            $count_bookings = $select_bookings->rowCount();
            ?>
            <h3><?= $count_bookings; ?></h3>
            <p>Total Bookings</p>
            <a href="bookings.php" class="btn">View Bookings</a>
        </div>

        <!-- Total Admins Box -->
        <div class="box">
            <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $count_admins = $select_admins->rowCount();
            ?>
            <h3><?= $count_admins; ?></h3>
            <p>Total Admins</p>
            <a href="admins.php" class="btn">View Admins</a>
        </div>

        <!-- Total Messages Box -->
        <div class="box">
            <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $count_messages = $select_messages->rowCount();
            ?>
            <h3><?= $count_messages; ?></h3>
            <p>Total Messages</p>
            <a href="messages.php" class="btn">View Messages</a>
        </div>

        <!-- Quick Select Box -->
        

    </div>

</section>
<!-- Dashboard Section Ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>
