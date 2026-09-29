<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: vote.php");
    exit;
}

// Check CSRF token
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    exit("Invalid request.");
}

$electionId = filter_input(
    INPUT_POST,
    'election_id',
    FILTER_VALIDATE_INT
);

$candidateId = filter_input(
    INPUT_POST,
    'candidate_id',
    FILTER_VALIDATE_INT
);

$username = $_SESSION['username'];

if (!$electionId || !$candidateId) {
    exit("Please select a valid election and candidate.");
}

// Check that the selected candidate exists
$stmt = $conn->prepare(
    "SELECT id FROM candidates WHERE id = ?"
);

$stmt->bind_param("i", $candidateId);
$stmt->execute();
$candidateResult = $stmt->get_result();

if ($candidateResult->num_rows === 0) {
    exit("Invalid candidate.");
}

$stmt->close();

// Check whether the user already voted in this election
$stmt = $conn->prepare(
    "SELECT id FROM votes
     WHERE username = ? AND election_id = ?"
);

$stmt->bind_param("si", $username, $electionId);
$stmt->execute();
$existingVote = $stmt->get_result();

if ($existingVote->num_rows > 0) {
    $stmt->close();
    exit("You have already voted in this election.");
}

$stmt->close();

// Insert vote
$stmt = $conn->prepare(
    "INSERT INTO votes
     (username, election_id, candidate_id)
     VALUES (?, ?, ?)"
);

$stmt->bind_param(
    "sii",
    $username,
    $electionId,
    $candidateId
);

if ($stmt->execute()) {
    echo "Your vote was submitted successfully.";
    echo "<br><a href='dashboard.php'>Return to dashboard</a>";
} else {
    echo "Error submitting vote.";
}

$stmt->close();
$conn->close();
?>
