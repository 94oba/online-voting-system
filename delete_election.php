<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$electionId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$electionId) {
    exit("Invalid election ID.");
}

// Check whether the election exists
$stmt = $conn->prepare(
    "SELECT id FROM elections WHERE id = ?"
);

$stmt->bind_param("i", $electionId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();

    exit("Election not found.");
}

$stmt->close();

// Delete related votes first
$stmt = $conn->prepare(
    "DELETE FROM votes WHERE election_id = ?"
);

$stmt->bind_param("i", $electionId);
$stmt->execute();
$stmt->close();

// Delete the election
$stmt = $conn->prepare(
    "DELETE FROM elections WHERE id = ?"
);

$stmt->bind_param("i", $electionId);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    header("Location: manage_election.php");
    exit;
}

$stmt->close();
$conn->close();

echo "Error deleting election.";
?>
