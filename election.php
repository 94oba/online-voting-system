<?php
include 'config.php';
session_start();
?>
<a href="admin_dashboard.php"> Back to Dashboard </a>
<?php
if (isset($_POST['add_election'])) {
    $election_name = trim($_POST['election_name']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if ($end_date < $start_date) {
        exit("End date cannot be earlier than the start date.");
    }

    $stmt = $conn->prepare(
        "INSERT INTO elections
        (election_name, start_date, end_date)
        VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "sss",
        $election_name,
        $start_date,
        $end_date
    );

    if ($stmt->execute()) {
        echo "Election added successfully.";
    } else {
        echo "Error adding election: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
    <head><title>add election</title></head>
    <body>
        <h1 style="text-align: center; color: black;"> Add Election </h1>
        <form method="POST" action="">
            <label for="election_name">Election Name:</label>
            <input type="text" id="election_name" name="election_name" required><br><br>
        
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br><br>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required><br><br>

            <input type="submit" name="add_election" value="Add Election">
        </form>
    </body>
</html>