<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html>  
    <head><title>Manage Elections</title></head>
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
        <h1 style="text-align: center; color: black;"> Manage Elections </h1>
        <button style="background-color: #4CAF50; margin-left: 10%;" 
        onclick="window.location.href='election.php'">Add Election</button>
         <table>
        <thead>
            <tr>
                <th> Election Name </th>
                <th> Start Date </th>
                <th> End Date </th>
                <th> Status </th>
                <th> Actions </th>
            </tr>
        </thead>    
        <tbody>
            <?php
            $sql = "SELECT * FROM elections";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["election_name"] . "</td>";
                    echo "<td>" . $row["start_date"] . "</td>";
                    echo "<td>" . $row["end_date"] . "</td>";
                    $current_date = date("Y-m-d");
                    if ($current_date < $row["start_date"]) {
                        echo "<td> Upcoming </td>";
                    } elseif ($current_date >= $row["start_date"] && $current_date <= $row["end_date"]) {
                        echo "<td> Ongoing </td>";
                    } else {
                        echo "<td> Ended </td>";
                    }
                   echo "<td>
        <button style='background-color: #4CAF50;'
        onclick='editElection(" . (int) $row["id"] . ")'>
            Edit
        </button>

        <button style='background-color: #f44336;'
        onclick='deleteElection(" . (int) $row["id"] . ")'>
            Delete
        </button>
      </td>";

                }
            } else {
                echo "<tr><td colspan='6'>No elections found</td></tr>";
            }
            ?>
        </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <button style="background-color: #4CAF50; color: white; padding: 10px 20px;
             border: none; border-radius: 5px; cursor: pointer;" onclick="goBack('admin_dashboard.php')"> Go Back </button>
        </div>
    </body>
   <script>
    function goBack(url) {
        window.location.href = url;
    }

    function editElection(id) {
        window.location.href = "edit_election.php?id=" + id;
    }

    function deleteElection(id) {
        if (confirm("Are you sure you want to delete this election?")) {
            window.location.href = "delete_election.php?id=" + id;
        }
    }
</script>
</html>