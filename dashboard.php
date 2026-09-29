<?php
include 'config.php';
session_start();    
?>
<?php

?>
<!DOCTYPE html>
<html>  
    <head>
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
            nav{
                text-align: center;
                margin-top: 20px;
            }
            nav a{
                margin-right: 20px;
                color: black;
                text-decoration: none;
            }
            nav a:hover{
                text-decoration: underline;
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
    </head>
    <body>
        <header>
            <div style="text-align: right; margin-right: 10%; margin-top: -30px; color: black; font-size: 18px; 
                 background-color: #f2f2f2; padding: 10px; border-radius: 5px; 
                 box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h1> Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                <button> <a href="logout.php"> Logout </a> </button>
            </div>
        </header>  
        <nav style="text-align: center; margin-top: 20px;">
            <button> <a href="vote.php"> Vote </a> </button>
            <button> <a href="results.php"> Results </a> </button>
        </nav>
    </body>
</html>