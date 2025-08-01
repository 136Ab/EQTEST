<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute(['username' => $username, 'password' => $password]);
        error_log("User $username registered successfully");
        echo "<script>alert('Registration successful! Please login.'); window.location.href='login.php';</script>";
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        echo "<script>alert('Error: Username already exists or invalid input.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Sign Up</title>
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
        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            margin: 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        .link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3498db;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .signup-container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 1.8em;
            }
            .btn {
                padding: 10px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <a href="login.php" class="link">Already have an account? Login</a>
    </div>
</body>
</html>
