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

$candidateId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$candidateId) {
    exit("Invalid candidate ID.");
}

$error = "";

// Update candidate
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $candidateName = trim($_POST['candidate_name'] ?? '');
    $party = trim($_POST['party'] ?? '');
    $position = trim($_POST['position'] ?? '');

    if (
        $candidateName === '' ||
        $party === '' ||
        $position === ''
    ) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE candidates
             SET candidate_name = ?, party = ?, position = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssi",
            $candidateName,
            $party,
            $position,
            $candidateId
        );

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();

            header("Location: manage_candidate.php");
            exit;
        }

        $error = "Error updating candidate.";
        $stmt->close();
    }
}

// Fetch candidate information
$stmt = $conn->prepare(
    "SELECT id, candidate_name, party, position
     FROM candidates
     WHERE id = ?"
);

$stmt->bind_param("i", $candidateId);
$stmt->execute();

$result = $stmt->get_result();
$candidate = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$candidate) {
    exit("Candidate not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Candidate</title>

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

    <h1>Edit Candidate</h1>

    <?php if ($error !== ''): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label for="candidate_name">Candidate Name</label>

        <input
            type="text"
            id="candidate_name"
            name="candidate_name"
            value="<?= e($candidate['candidate_name']) ?>"
            required
        >

        <label for="party">Party</label>

        <input
            type="text"
            id="party"
            name="party"
            value="<?= e($candidate['party']) ?>"
            required
        >

        <label for="position">Position</label>

        <input
            type="text"
            id="position"
            name="position"
            value="<?= e($candidate['position']) ?>"
            required
        >

        <button type="submit">
            Save Changes
        </button>

        <a href="manage_candidate.php">
            Cancel
        </a>

    </form>

</div>

</body>
</html>
