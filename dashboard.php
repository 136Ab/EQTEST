<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    error_log("User not logged in, redirecting to login.php from dashboard");
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
try {
    $stmt = $pdo->prepare("SELECT score, percentage, created_at FROM eq_results WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $user_id]);
    $results = $stmt->fetchAll();
    error_log("Loaded dashboard for user $user_id, found " . count($results) . " results");
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    die("Error loading dashboard. Please try again.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Dashboard</title>
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
        .dashboard-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #3498db;
            color: white;
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
            .dashboard-container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 1.8em;
            }
            .btn {
                padding: 10px 20px;
                font-size: 1em;
            }
            table {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Your Test History</h1>
        <?php if (empty($results)): ?>
            <p>No test results yet. Take the test to see your scores!</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($results as $result): ?>
                    <tr>
                        <td><?php echo $result['score']; ?> / 40</td>
                        <td><?php echo round($result['percentage']); ?>%</td>
                        <td><?php echo $result['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <button class="btn" onclick="window.location.href='quiz.php?q=1'">Take Test</button>
        <button class="btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>
</body>
</html>
