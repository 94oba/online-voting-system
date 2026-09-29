<?php
include 'config.php';
session_start();
?>
<a href="manage_users.php"> Back to Dashboard </a>
<?php
    if (isset($_POST['update'])) {
        $user_id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];

        $sql = "UPDATE users SET username='$username', email='$email', phone='$phone', role='$role' WHERE id=$user_id";
        if ($conn->query($sql) === TRUE) {
            echo "User updated successfully.";
        } else {
            echo "Error updating user: " . $conn->error;
        }
    }
    $conn->close();
?>
