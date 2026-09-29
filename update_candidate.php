<?php
include 'config.php';
session_start();
?>
<a href="manage_candidate.php"> Back to manage candidates </a>
<?php
    if (isset($_POST['update'])) {
        $candidate_name = $_POST['candidate_name'];
        $party = $_POST['party'];
        $position = $_POST['position'];

        $sql = "UPDATE candidates SET candidate_name='$candidate_name', party='$party', 
        position='$position' WHERE candidate_name='$candidate_name';";
        if ($conn->query($sql) === TRUE) {
            echo "Candidate updated successfully.";
        } else {
            echo "Error updating candidate: " . $conn->error;
        }
    }
    $conn->close();
?>