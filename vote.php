<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Create CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Fetch elections
$elections = [];

$sql = "SELECT id, election_name FROM elections ORDER BY election_name";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $elections[] = $row;
    }
}

// Fetch candidates
$candidates = [];

$sql = "SELECT id, candidate_name FROM candidates ORDER BY candidate_name";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>

    <style>
        body {
            background-color: white;
            color: black;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .welcome {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 30px;
            background-color: #f2f2f2;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-top: 30px;
        }

        label {
            display: inline-block;
            margin: 10px;
            font-weight: bold;
        }

        select,
        input[type="submit"],
        button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        select {
            min-width: 220px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #388E3C;
        }

        .back-button {
            margin-top: 25px;
            background-color: #555;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="welcome">
        <h1>
            Welcome, <?= e($_SESSION['username']) ?>!
        </h1>

        <p>You can vote for your favorite candidate here.</p>
    </div>

    <h2>Voting Page</h2>

    <?php if (empty($elections) || empty($candidates)): ?>

        <p>No elections or candidates are currently available.</p>

    <?php else: ?>

        <form method="POST" action="submit_vote.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= e($_SESSION['csrf_token']) ?>"
            >

            <label for="election">Select an election:</label>

            <select name="election_id" id="election" required>
                <option value="">-- Select Election --</option>

                <?php foreach ($elections as $election): ?>
                    <option value="<?= (int) $election['id'] ?>">
                        <?= e($election['election_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>

            <label for="candidate">Select a candidate:</label>

            <select name="candidate_id" id="candidate" required>
                <option value="">-- Select Candidate --</option>

                <?php foreach ($candidates as $candidate): ?>
                    <option value="<?= (int) $candidate['id'] ?>">
                        <?= e($candidate['candidate_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>

            <input type="submit" value="Submit Vote">
        </form>

    <?php endif; ?>

    <button class="back-button" onclick="window.location.href='dashboard.php'">
        Go Back
    </button>

</body>
</html>
