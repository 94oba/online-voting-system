<?php
require_once 'config.php';
session_start();

$message = '';
$messageType = '';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Process form submission
if (isset($_POST['add_candidate'])) {
    $candidate_name = trim($_POST['candidate_name'] ?? '');
    $party = trim($_POST['party'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $election_id = filter_input(
        INPUT_POST,
        'election_id',
        FILTER_VALIDATE_INT
    );

    if (
        $candidate_name === '' ||
        $party === '' ||
        $position === '' ||
        !$election_id
    ) {
        $message = 'Please complete all fields.';
        $messageType = 'error';
    } else {
        // Confirm that the selected election exists and is open
        $checkElection = $conn->prepare(
            "SELECT id
             FROM elections
             WHERE id = ?
             AND status = 'open'"
        );

        $checkElection->bind_param("i", $election_id);
        $checkElection->execute();

        $electionResult = $checkElection->get_result();

        if ($electionResult->num_rows === 0) {
            $message = 'Please select a valid open election.';
            $messageType = 'error';
        } else {
            // Insert candidate safely using a prepared statement
            $stmt = $conn->prepare(
                "INSERT INTO candidates
                (candidate_name, party, position, election_id)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssi",
                $candidate_name,
                $party,
                $position,
                $election_id
            );

            if ($stmt->execute()) {
                $message = 'Candidate added successfully.';
                $messageType = 'success';

                // Clear form values after successful submission
                $candidate_name = '';
                $party = '';
                $position = '';
            } else {
                $message = 'Error adding candidate: ' . $stmt->error;
                $messageType = 'error';
            }

            $stmt->close();
        }

        $checkElection->close();
    }
}

// Fetch open elections for the dropdown
$elections = [];

$result = $conn->query(
    "SELECT id, election_name
     FROM elections
     WHERE status = 'open'
     ORDER BY election_name ASC"
);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $elections[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Candidate Management</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        h1 {
            text-align: center;
            color: black;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            font-size: 15px;
        }

        input[type="submit"] {
            margin-top: 25px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #388E3C;
        }

        .back-link {
            display: block;
            max-width: 500px;
            margin: 0 auto 20px auto;
            color: #333;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            color: #155724;
            background-color: #d4edda;
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
        }

        .no-elections {
            color: #721c24;
            background-color: #f8d7da;
            padding: 12px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>

<body>

<a class="back-link" href="manage_candidate.php">
    Back to Manage Candidates
</a>

<div class="container">

    <h1>Add Candidate</h1>

    <?php if ($message !== ''): ?>
        <div class="message <?= e($messageType) ?>">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($elections)): ?>

        <div class="no-elections">
            No open elections are available. Create an election first.
        </div>

    <?php else: ?>

        <form method="POST" action="">

            <label for="candidate_name">Candidate Name:</label>
            <input
                type="text"
                id="candidate_name"
                name="candidate_name"
                value="<?= e($candidate_name ?? '') ?>"
                required
            >

            <label for="party">Party:</label>
            <input
                type="text"
                id="party"
                name="party"
                value="<?= e($party ?? '') ?>"
                required
            >

            <label for="position">Position:</label>
            <input
                type="text"
                id="position"
                name="position"
                value="<?= e($position ?? '') ?>"
                required
            >

            <label for="election_id">Election:</label>
            <select name="election_id" id="election_id" required>
                <option value="">-- Select Election --</option>

                <?php foreach ($elections as $election): ?>
                    <option
                        value="<?= (int) $election['id'] ?>"
                        <?= (
                            isset($election_id) &&
                            (int) $election_id === (int) $election['id']
                        ) ? 'selected' : '' ?>
                    >
                        <?= e($election['election_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input
                type="submit"
                name="add_candidate"
                value="Add Candidate"
            >

        </form>

    <?php endif; ?>

</div>

</body>
</html>

<?php
$conn->close();
?>
