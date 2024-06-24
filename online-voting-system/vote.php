<?php
require 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user has already voted
$stmt = $conn->prepare("SELECT * FROM votes WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "You have already voted.";
    exit();
}

// Fetch candidates
$candidates = [];
$stmt = $conn->prepare("SELECT * FROM candidates");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $candidates[] = $row;
}

// Handle voting
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_id'])) {
    $candidate_id = $_POST['candidate_id'];
    $stmt = $conn->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $candidate_id);
    if ($stmt->execute()) {
        header("Location: logout.php?message=thankyou");
        exit();
    } else {
        echo "Error casting vote.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote - Online Voting System</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        let timer;
        let timeRemaining = 300; // 5 minutes in seconds

        function startTimer() {
            timer = setInterval(function() {
                if (timeRemaining <= 0) {
                    clearInterval(timer);
                    alert("Time's up! You have been logged out.");
                    window.location.href = "logout.php";
                } else {
                    timeRemaining--;
                    document.getElementById("timer").innerHTML = "Time remaining: " + Math.floor(timeRemaining / 60) + ":" + (timeRemaining % 60);
                }
            }, 1000);
        }

        window.onload = startTimer;
    </script>
</head>
<body>
    <header class="header">
        <h1>Vote for Your Candidate</h1>
        <div id="timer"></div>
    </header>
    <main class="main-container">
        <form action="vote.php" method="post">
            <?php foreach ($candidates as $candidate): ?>
                <div class="form-group">
                    <img src="<?php echo $candidate['profile_image']; ?>" alt="<?php echo $candidate['name']; ?>" width="100" class="img-thumbnail">
                    <label class="form-check-label">
                        <input type="radio" name="candidate_id" value="<?php echo $candidate['id']; ?>" class="form-check-input" required>
                        <?php echo $candidate['name']; ?> (<?php echo $candidate['party']; ?>)
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-success">Submit Vote</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Election Commission of India</p>
    </footer>
</body>
</html>
