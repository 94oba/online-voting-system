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
        <h1 style="text-align: center; color: black;"> Manage Candidates </h1>
        <button style="background-color: #4CAF50; margin-left: 10%;" 
        onclick="window.location.href='candidate.php'">Add Candidate</button>
         <table>
        <thead>
            <tr>
                <th> Candidate Name </th>
                <th> Party </th>
                <th> Position </th>
                <th> Actions </th>
            </tr>
        </thead>    
        <tbody>
            <?php
            $sql = "SELECT * FROM candidates";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["candidate_name"] . "</td>";
                    echo "<td>" . $row["party"] . "</td>";
                    echo "<td>" . $row["position"] . "</td>";
                    echo "<td>
        <button style='background-color: #4CAF50; color: white;'
        onclick='editCandidate(" . (int) $row["id"] . ")'>
            Edit
        </button>

        <button style='background-color: #f44336; color: white;'
        onclick='deleteCandidate(" . (int) $row["id"] . ")'>
            Delete
        </button>
      </td>";

                }
            } else {
                echo "<tr><td colspan='6'>No candidates found</td></tr>";
            }
            ?>
        </tbody>
        </table>
        <div style="text-align: center; margin-top: 20px;">
            <button style='background-color: #4CAF50; color: white;' onclick="goBack('admin_dashboard.php')"> Go Back </button>
        </div>
    </body>
    <script>
        function goBack(url) {
            window.location.href = url;
        }
        function editCandidate(id) {
            window.location.href = "edit_candidate.php?id=" + id;
        }   
        function deleteCandidate(id) {
            if (confirm("Are you sure you want to delete this candidate?")) {
                window.location.href = "delete_candidate.php?id=" + id;
            }
        }
    </script>
</html>