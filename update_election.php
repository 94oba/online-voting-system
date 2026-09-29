<?php
include 'config.php';
session_start();
?>
<a href="manage_election.php"> Back to manage elections </a>
<?php
    if (isset($_POST['update'])) {
        $election_name = $_POST['election_name'];           
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $sql = "UPDATE elections SET election_name='$election_name', 
        start_date='$start_date', end_date='$end_date' WHERE election_name='$election_name'";
        if ($conn->query($sql) === TRUE) {
            echo "Election updated successfully.";
        } else {
            echo "Error updating election: " . $conn->error;
        }
    }
    $conn->close();
?>