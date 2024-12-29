<?php



include '../components/connect.php';

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
           function getAdminProfile($conn, $admin_id) {
            $stmt = $conn->prepare("CALL sp_get_admin_by_id(?)");
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $fetch_profile = $result->fetch_assoc();
            if ($fetch_profile) {
                return "<h3>Welcome!</h3><p>" . htmlspecialchars($fetch_profile['name']) . "</p><a href='update.php' class='btn'>Update Profile</a>";
            } else {
                return "<h3>No Profile Found</h3>"; // Handle case where no profile is found
            }
        }
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
        <div class="box">
            <h3>Quick Select</h3>
            <p>Login or Register</p>
            <a href="login.php" class="btn" style="margin-right: 1rem;">Login</a>
            <a href="register.php" class="btn" style="margin-left: 1rem;">Register</a>
        </div>

    </div>

</section>
<!-- Dashboard Section Ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>



</body>
</html>
