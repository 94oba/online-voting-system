<?php
include 'config.php';
session_start();
?>
<a href="admin_dashboard.php"> Back to manage users </a>
<?php
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id=$user_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h2>Edit User</h2>";
        echo "<form method='post' action='update_user.php'>";
        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
        echo "Username: <input type='text' name='username' value='" . $row['username'] . "'><br><br>";
        echo "Email: <input type='email' name='email' value='" . $row['email'] . "'><br><br>";
        echo "Phone: <input type='text' name='phone' value='" . $row['phone'] . "'><br><br>";
        echo "Role: <select name='role'>";
        echo "<option value='user'" . ($row['role'] == 'user' ? ' selected' : '') . ">User</option>";
        echo "<option value='admin'" . ($row['role'] == 'admin' ? ' selected' : '') . ">Admin</option>";
        echo "</select><br><br>";
        echo "<input type='submit' name='update' value='Update User'>";
        echo "</form>";
    } else {
        echo "User not found.";
    }
}
$conn->close();
?>