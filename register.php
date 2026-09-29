<?php
include "config.php";
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, phone, role, password) 
    VALUES ('$username', '$email', '$phone', '$role', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <title> Registration </title>
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
        <h2> Registration </h2>
        <form action="register.php" method="POST">
            <table align="center">
                <tr>
                    <td> Username: </td>
                    <td> <input type="text" name="username" required> </td>
                </tr>
                <tr>
                    <td> Email: </td>
                    <td> <input type="email" name="email" required> </td>
                </tr>
                <tr>
                    <td> Phone: </td>
                    <td> <input type="text" name="phone" required> </td>
                </tr>
                <tr>
                    <td> Role:</td>
                    <td> <input type="text"  placeholder="user" name="user" required> </td>
                </tr>
                <tr>
                    <td> Password: </td>
                    <td> <input type="password" name="password" required> </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"> 
                        <input type="submit" name="submit" value="Register"> 
                    </td>
                </tr>
            </table>
        </form>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>