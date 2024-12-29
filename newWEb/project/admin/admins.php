<?php
include '../components/connect.php';


if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_admin = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
        $delete_admin->execute([$delete_id]);
        $success_msg[] = 'Admin deleted!';
    } else {
        $warning_msg[] = 'Admin not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!-- Header Section Starts -->
<?php include '../components/admin_header.php'; ?>
<!-- Header Section Ends -->

<!-- Admins Section Starts -->
<section class="grid">

    <h1 class="heading">Admins</h1>

    <div class="box-container">

        <div class="box" style="text-align: center;">
            <p>Create a new admin</p>
            <a href="register.php" class="btn">Register now</a>
        </div>
        

        <?php
        $select_admins = $conn->prepare("SELECT * FROM `admins`");
        $select_admins->execute();
        if ($select_admins->rowCount() > 0) {
            while ($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box" <?php if ($fetch_admins['name'] == 'admin') { echo 'style="display:none;"'; } ?>>
            <p>Name: <span><?= $fetch_admins['name']; ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="delete_id" value="<?= $fetch_admins['id']; ?>">
                <input type="submit" value="Delete Admin" onclick="return confirm('Delete this admin?');" name="delete" class="btn">
            </form>
        </div>
        <?php
            }
        } else {
            echo '<p>No admins found.</p>';
        }
        ?>

    </div>

</section>
<!-- Admins Section Ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>
