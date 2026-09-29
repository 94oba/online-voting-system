<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html>
    <title> Dashboard </title>
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
        <header>

            <div style="text-align: right; margin-right: 10%; margin-top: -30px; color: black; font-size: 18px; 
                 background-color: #f2f2f2; padding: 10px; border-radius: 5px; 
                 box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h1> Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                <button style="background-color: #f44336; color: white;"> <a href="logout.php"> Logout </a> </button>
            </div>
        </header>  
       <button style="background-color: #4CAF50; color: white;"> 
        <a href="manage_users.php"> Manage Users </a> </button>
       <button style="background-color: #4CAF50; color: white;"><a href="manage_election.php"> Manage Elections </a></button>
       <button style="background-color: #4CAF50; color: white;"><a href="manage_candidate.php"> Manage Candidates </a></button>
    </body>
    <script>
    function goBack() {
        window.history.back();
    }
    </script>
</html>