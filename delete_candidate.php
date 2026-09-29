<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$candidateId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$candidateId) {
    exit("Invalid candidate ID.");
}

// Check whether the candidate exists
$stmt = $conn->prepare(
    "SELECT id FROM candidates WHERE id = ?"
);

$stmt->bind_param("i", $candidateId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();

    exit("Candidate not found.");
}

$stmt->close();

// Delete votes belonging to this candidate first
$stmt = $conn->prepare(
    "DELETE FROM votes WHERE candidate_id = ?"
);

$stmt->bind_param("i", $candidateId);
$stmt->execute();
$stmt->close();

// Delete the candidate
$stmt = $conn->prepare(
    "DELETE FROM candidates WHERE id = ?"
);

$stmt->bind_param("i", $candidateId);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    header("Location: manage_candidate.php");
    exit;
}

$stmt->close();
$conn->close();

echo "Error deleting candidate.";
?>
