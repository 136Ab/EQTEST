<?php
session_start();
require_once 'db.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    error_log("User not logged in, redirecting to login.php");
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

// Generate a unique session ID for the test if not already set
if (!isset($_SESSION['test_session_id'])) {
    $_SESSION['test_session_id'] = uniqid();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = (int)$_POST['question_id'];
    $answer = (int)$_POST['answer'];
    $session_id = $_SESSION['test_session_id'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO eq_responses (session_id, user_id, question_id, answer) VALUES (:session_id, :user_id, :question_id, :answer)");
        $stmt->execute([
            'session_id' => $session_id,
            'user_id' => $user_id,
            'question_id' => $question_id,
            'answer' => $answer
        ]);
        error_log("Response saved for question $question_id, user $user_id, session $session_id");
    } catch (PDOException $e) {
        error_log("Error saving response: " . $e->getMessage());
        die("Error saving response. Please try again.");
    }

    $next_question = $question_id + 1;
    if ($next_question > 10) {
        error_log("Redirecting to result.php for session $session_id");
        // Use absolute URL to avoid path issues
        $base_url = 'http://rsoa17.rehanschool.us/';
        echo "<script>window.location.href='{$base_url}result.php';</script>";
        exit;
    } else {
        error_log("Redirecting to quiz.php?q=$next_question");
        echo "<script>window.location.href='quiz.php?q=$next_question';</script>";
        exit;
    }
}

$question_id = isset($_GET['q']) ? (int)$_GET['q'] : 1;
if ($question_id < 1 || $question_id > 10) {
    error_log("Invalid question_id $question_id, resetting to 1");
    $question_id = 1;
}

$questions = [
    1 => ["question" => "How often do you recognize your emotions as you experience them?", "options" => ["Never", "Rarely", "Sometimes", "Often", "Always"]],
    2 => ["question" => "How well do you manage stress in high-pressure situations?", "options" => ["Very Poorly", "Poorly", "Moderately", "Well", "Very Well"]],
    3 => ["question" => "How often do you empathize with others' feelings?", "options" => ["Never", "Rarely", "Sometimes", "Often", "Always"]],
    4 => ["question" => "How effectively do you resolve conflicts with others?", "options" => ["Very Ineffectively", "Ineffectively", "Moderately", "Effectively", "Very Effectively"]],
    5 => ["question" => "How often do you adapt your behavior based on social cues?", "options" => ["Never", "Rarely", "Sometimes", "Often", "Always"]],
    6 => ["question" => "How well do you motivate yourself to achieve goals?", "options" => ["Very Poorly", "Poorly", "Moderately", "Well", "Very Well"]],
    7 => ["question" => "How often do you listen actively to others?", "options" => ["Never", "Rarely", "Sometimes", "Often", "Always"]],
    8 => ["question" => "How effectively do you express your emotions?", "options" => ["Very Ineffectively", "Ineffectively", "Moderately", "Effectively", "Very Effectively"]],
    9 => ["question" => "How often do you seek feedback to improve yourself?", "options" => ["Never", "Rarely", "Sometimes", "Often", "Always"]],
    10 => ["question" => "How well do you build positive relationships?", "options" => ["Very Poorly", "Poorly", "Moderately", "Well", "Very Well"]]
];

$current_question = $questions[$question_id];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Quiz</title>
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
        .quiz-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        .option {
            display: block;
            padding: 15px;
            margin: 10px 0;
            background: #f4f4f4;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .option:hover {
            background: #e0e0e0;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .submit-btn {
            background: #3498db;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.3s, transform 0.2s;
        }
        .submit-btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        @media (max-width: 600px) {
            .quiz-container {
                padding: 20px;
                margin: 10px;
            }
            h2 {
                font-size: 1.5em;
            }
            .submit-btn {
                padding: 10px 20px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h2>Question <?php echo $question_id; ?> of 10</h2>
        <p><?php echo $current_question['question']; ?></p>
        <form method="POST">
            <input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
            <?php foreach ($current_question['options'] as $index => $option): ?>
                <label class="option">
                    <input type="radio" name="answer" value="<?php echo $index; ?>" required>
                    <?php echo $option; ?>
                </label>
            <?php endforeach; ?>
            <button type="submit" class="submit-btn">Next</button>
        </form>
    </div>
</body>
</html>
