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

// Fetch election results
$results = [];

$sql = "
    SELECT
        e.id AS election_id,
        e.election_name,
        c.id AS candidate_id,
        c.candidate_name,
        COUNT(v.id) AS vote_count
    FROM elections e
    CROSS JOIN candidates c
    LEFT JOIN votes v
        ON v.election_id = e.id
        AND v.candidate_id = c.id
    GROUP BY
        e.id,
        e.election_name,
        c.id,
        c.candidate_name
    ORDER BY
        e.election_name ASC,
        vote_count DESC,
        c.candidate_name ASC
";

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $electionId = $row['election_id'];

        $results[$electionId]['election_name'] =
            $row['election_name'];

        $results[$electionId]['candidates'][] = [
            'candidate_name' => $row['candidate_name'],
            'vote_count' => (int) $row['vote_count']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results</title>

    <style>
        body {
            background-color: white;
            color: black;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .election {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 8px;
            background-color: #f2f2f2;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .election h2 {
            margin-top: 0;
            color: #333;
        }

        .winner {
            padding: 12px;
            background-color: #fff3cd;
            border: 1px solid #ffe69c;
            border-radius: 5px;
            color: #664d03;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-results {
            text-align: center;
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 5px;
        }

        .back-button {
            display: block;
            margin: 25px auto 0;
            padding: 10px 18px;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #333;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Election Results</h1>

    <?php if (empty($results)): ?>

        <p class="no-results">
            No elections or results are currently available.
        </p>

    <?php else: ?>

        <?php foreach ($results as $election): ?>

            <?php
            $highestVotes = 0;
            $winners = [];

            foreach ($election['candidates'] as $candidate) {
                if ($candidate['vote_count'] > $highestVotes) {
                    $highestVotes = $candidate['vote_count'];
                    $winners = [$candidate['candidate_name']];
                } elseif (
                    $candidate['vote_count'] === $highestVotes &&
                    $highestVotes > 0
                ) {
                    $winners[] = $candidate['candidate_name'];
                }
            }
            ?>

            <div class="election">

                <h2>
                    <?= e($election['election_name']) ?>
                </h2>

                <?php if ($highestVotes === 0): ?>

                    <p class="winner">
                        No votes have been submitted yet.
                    </p>

                <?php elseif (count($winners) === 1): ?>

                    <p class="winner">
                        Winner:
                        <strong><?= e($winners[0]) ?></strong>
                        with
                        <?= $highestVotes ?>
                        vote<?= $highestVotes === 1 ? '' : 's' ?>.
                    </p>

                <?php else: ?>

                    <p class="winner">
                        Tie between:
                        <strong>
                            <?= e(implode(', ', $winners)) ?>
                        </strong>
                        with <?= $highestVotes ?> votes each.
                    </p>

                <?php endif; ?>

                <table>
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Votes</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($election['candidates'] as $candidate): ?>
                            <tr>
                                <td>
                                    <?= e($candidate['candidate_name']) ?>
                                </td>
                                <td>
                                    <?= $candidate['vote_count'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

    <button
        class="back-button"
        onclick="window.location.href='dashboard.php'"
    >
        Return to Dashboard
    </button>

</div>

</body>
</html>
