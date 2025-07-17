<?php
session_start();
require_once 'db.php';

$session_id = $_SESSION['test_session_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$score = 0;
$max_score = 40;

if ($session_id && $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT answer FROM eq_responses WHERE session_id = :session_id AND user_id = :user_id");
        $stmt->execute(['session_id' => $session_id, 'user_id' => $user_id]);
        $answers = $stmt->fetchAll();
        foreach ($answers as $answer) {
            $score += (int)$answer['answer'];
        }

        // Save result to eq_results
        $percentage = ($score / $max_score) * 100;
        $stmt = $pdo->prepare("INSERT INTO eq_results (user_id, session_id, score, percentage) VALUES (:user_id, :session_id, :score, :percentage)");
        $stmt->execute([
            'user_id' => $user_id,
            'session_id' => $session_id,
            'score' => $score,
            'percentage' => $percentage
        ]);
        error_log("Result saved for user $user_id, session $session_id: score $score, percentage $percentage");
    } catch (PDOException $e) {
        error_log("Error retrieving or saving results: " . $e->getMessage());
        die("Error processing results. Please try again.");
    }
} else {
    error_log("Missing session_id or user_id on result.php");
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$feedback = "";
if ($percentage >= 80) {
    $feedback = "Excellent! You have a high level of emotional intelligence. You excel in self-awareness, empathy, and emotional regulation.";
} elseif ($percentage >= 60) {
    $feedback = "Good job! You have a solid foundation in emotional intelligence. Focus on practicing empathy and stress management to improve further.";
} elseif ($percentage >= 40) {
    $feedback = "You're on the right track! Work on recognizing your emotions and understanding others to boost your EQ.";
} else {
    $feedback = "There’s room for growth. Consider practicing self-reflection and active listening to enhance your emotional intelligence.";
}

unset($_SESSION['test_session_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Results</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .result-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            text-align: center;
            margin: 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .score {
            font-size: 2em;
            color: #3498db;
            margin: 20px 0;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            margin: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        @media (max-width: 600px) {
            .result-container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 2em;
            }
            .btn {
                padding: 10px 20px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>Your EQ Test Results</h1>
        <div class="score">Score: <?php echo $score; ?> / <?php echo $max_score; ?> (<?php echo round($percentage); ?>%)</div>
        <p><?php echo $feedback; ?></p>
        <button class="btn" onclick="window.location.href='quiz.php?q=1'">Retake Test</button>
        <button class="btn" onclick="window.location.href='dashboard.php'">View Dashboard</button>
        <button class="btn" onclick="alert('Share your score: <?php echo $score; ?> / <?php echo $max_score; ?>')">Share Results</button>
    </div>
</body>
</html>
