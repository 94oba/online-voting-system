<?php
include 'config.php';
session_start();    
?>
<!DOCTYPE html>
<html>  
    <head><title>Manage Users</title></head>
    <style>
        body{
            background-color: white;
            background-size: cover;
        }
        header{
            text-align: center;
            color: black;
        }
        h1{
            text-align: center;
            color: black;
        }
        table{
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td{
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th{
            background-color: #f2f2f2;
        }
        button{
            background-color: white;
            color: black;
            font-size: 16px;
            font-weight: bold;
            border: 2px solid #ccc;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }
        button:hover{
            background-color: #4CAF50;
            color: white;
        }
    </style>
    <body>
        <h1 style="text-align: center; color: black;"> Manage Users </h1>
         <table>
        <thead>
            <tr>
                <th> ID </th>
                <th> Username </th>
                <th> Email </th>
                <th> Phone </th>
                <th> Role </th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . $row["role"] . "</td>";
                        echo "<td><button style='background-color: #4CAF50; color: white;' onclick='editUser(" . $row["id"] . ")'>Edit</button> 
                        <button style='background-color: #f44336; color: white;' onclick='deleteUser(" . $row["id"] . ")'>Delete</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found</td></tr>";
                }
            ?>
        </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="goBack('admin_dashboard.php')"> Go Back </button>
        </div>
    </body>
    <script>
        function goBack(url) {
            window.location.href = url;
        }
        function editUser(id) {
            window.location.href = "edit_user.php?id=" + id;
        }
        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "delete_user.php?id=" + id;
            }
        }
    </script>
</body>
</html>

</html>