<?php
include 'config.php';
session_start();
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
        }
    if ($row['role'] === 'admin') {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: dashboard.php");
            exit();
        } 
    } else {
        echo "<script>alert('User not found.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <title> Login </title>
    <style>
        body{
            background-color: white;
            background-size: cover;
        }
        h2{
            text-align: center;
            color: black;
        }
    </style>
    <body>
        <h2> Login </h2>
        <form action="login.php" method="POST">
            <table align="center">
                <tr>
                    <td> Username: </td>
                    <td> <input type="text" name="username" required> </td>
                </tr>
                <tr>
                    <td> Password: </td>
                    <td> <input type="password" name="password" required> </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"> 
                        <input type="submit" name="submit" value="Login"> 
                    </td>
                </tr>
            </table>
        </form>
        <a href="register.php" style="display: block; text-align: center; margin-top: 20px; color: black;"> Don't have an account? Register here. </a>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>