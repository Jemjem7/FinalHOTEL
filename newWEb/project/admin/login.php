<?php
// Database credentials
include '../components/connect.php';

// Start the session
session_start();

// Establish database connection
$conn = new mysqli($db_host, $db_user_name, $db_user_pass, $db_name);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize input data
    $name = trim($_POST['name']);
    $pass = trim($_POST['pass']);

    // Basic validation
    if (empty($name) || empty($pass)) {
        echo "<p style='color: red;'>Please fill out all fields!</p>";
    } else {
        // Query to check if user exists
        $query = "SELECT * FROM `admins` WHERE name = ? LIMIT 1";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if user exists
            if ($row = $result->fetch_assoc()) {
                // Verify password
                if (password_verify($pass, $row['Password'])) {
                    // Set secure cookie for admin ID
                    setcookie('admin_id', $row['id'], [
                        'expires' => time() + 2592000, // 30 days
                        'path' => '/',
                        'httponly' => true, // Prevent JavaScript access
                        'secure' => isset($_SERVER['HTTPS']), // Only over HTTPS
                        'samesite' => 'Strict', // Prevent cross-site attacks
                    ]);

                    // Redirect to dashboard
                    header('Location: dashboard.php');
                    exit;
                } else {
                    // Set error message in session
                    $_SESSION['error'] = 'Incorrect password!';
                    header('Location: http://localhost/newWEb/index.html'); // Redirect to login page
                    exit;
                }
            } else {
                echo "<p style='color: red;'>Username not found!</p>";
            }

            $stmt->close();
        }
    }
}

// Close the database connection
$conn->close();
?>