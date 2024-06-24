<?php
require 'config.php';

$stmt = $conn->prepare("SELECT candidates.name, candidates.party, COUNT(votes.id) AS vote_count FROM candidates LEFT JOIN votes ON candidates.id = votes.candidate_id GROUP BY candidates.id ORDER BY vote_count DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Results - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <header class="header">
        <h1>Election Results</h1>
    </header>
    <main class="main-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Party</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['party']; ?></td>
                    <td><?php echo $row['vote_count']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
