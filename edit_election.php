<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$electionId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$electionId) {
    exit("Invalid election ID.");
}

$error = "";

// Update election when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $electionName = trim($_POST['election_name'] ?? '');
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($electionName === '' || $startDate === '' || $endDate === '') {
        $error = "All fields are required.";
    } elseif ($endDate < $startDate) {
        $error = "End date cannot be before the start date.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE elections
             SET election_name = ?, start_date = ?, end_date = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssi",
            $electionName,
            $startDate,
            $endDate,
            $electionId
        );

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            header("Location: manage_election.php");
            exit;
        }

        $error = "Error updating election.";
        $stmt->close();
    }
}

// Fetch the selected election
$stmt = $conn->prepare(
    "SELECT id, election_name, start_date, end_date
     FROM elections
     WHERE id = ?"
);

$stmt->bind_param("i", $electionId);
$stmt->execute();

$result = $stmt->get_result();
$election = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$election) {
    exit("Election not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Election</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
        }

        button,
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 15px;
            cursor: pointer;
        }

        button {
            background-color: #4CAF50;
            color: white;
        }

        a {
            background-color: #555;
            color: white;
            margin-left: 8px;
        }

        .error {
            color: #d00000;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Election</h1>

    <?php if ($error !== ''): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="election_name">Election Name</label>

        <input
            type="text"
            id="election_name"
            name="election_name"
            value="<?= e($election['election_name']) ?>"
            required
        >

        <label for="start_date">Start Date</label>

        <input
            type="date"
            id="start_date"
            name="start_date"
            value="<?= e($election['start_date']) ?>"
            required
        >

        <label for="end_date">End Date</label>

        <input
            type="date"
            id="end_date"
            name="end_date"
            value="<?= e($election['end_date']) ?>"
            required
        >

        <button type="submit">
            Save Changes
        </button>

        <a href="manage_election.php">
            Cancel
        </a>

    </form>

</div>

</body>
</html>
