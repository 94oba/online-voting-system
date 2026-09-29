<?php
include 'config.php';
session_start();        
?>
<a href="admin_dashboard.php"> Back to manage users </a>
<?php
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id=$user_id";
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
$conn->close();
?>